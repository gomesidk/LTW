<?php
require_once(__DIR__ . '/../database/connection.php');

class MessageService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        // Enable foreign key constraints for SQLite
        $this->pdo->exec('PRAGMA foreign_keys = ON;');
    }

    public function sendMessage($senderId, $receiverId, $messageText) {
        if (empty(trim($messageText))) {
            return false; // Don't allow empty messages
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO Message (sender_id, receiver_id, content, timestamp) 
            VALUES (:sender_id, :receiver_id, :content, datetime('now'))
        ");

        return $stmt->execute([
            ':sender_id' => $senderId,
            ':receiver_id' => $receiverId,
            ':content' => $messageText
        ]);
    }

    public function getConversation($user1Id, $user2Id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM Message
            WHERE (sender_id = :user1 AND receiver_id = :user2)
               OR (sender_id = :user2 AND receiver_id = :user1)
            ORDER BY timestamp ASC
        ");

        $stmt->execute([
            ':user1' => $user1Id,
            ':user2' => $user2Id
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMessagesByUser($userId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM Message
            WHERE sender_id = :id OR receiver_id = :id
            ORDER BY timestamp DESC
        ");

        $stmt->execute([':id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMessagesBetweenUsers(int $user1Id, int $user2Id): array {
        $stmt = $this->pdo->prepare("
            SELECT * FROM Message 
            WHERE (sender_id = ? AND receiver_id = ?) 
               OR (sender_id = ? AND receiver_id = ?)
            ORDER BY timestamp ASC
        ");
        $stmt->execute([$user1Id, $user2Id, $user2Id, $user1Id]);

        $messages = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = [
                'id' => $row['id'],
                'sender_id' => $row['sender_id'],
                'receiver_id' => $row['receiver_id'],
                'content' => $row['content'],
                'timestamp' => $row['timestamp']
            ];
        }

        return $messages;
    }

    public function getAllConversationsForUser($userId) {
        $stmt = $this->pdo->prepare("
            SELECT m.*
            FROM Message m
            INNER JOIN (
                SELECT 
                    CASE 
                        WHEN sender_id < receiver_id THEN sender_id || '-' || receiver_id
                        ELSE receiver_id || '-' || sender_id
                    END AS convo_key,
                    MAX(timestamp) AS latest_timestamp
                FROM Message
                WHERE sender_id = :userId OR receiver_id = :userId
                GROUP BY convo_key
            ) latest_messages
            ON (
                (CASE 
                    WHEN m.sender_id < m.receiver_id THEN m.sender_id || '-' || m.receiver_id
                    ELSE m.receiver_id || '-' || m.sender_id
                END) = latest_messages.convo_key
                AND m.timestamp = latest_messages.latest_timestamp
            )
            WHERE m.sender_id = :userId OR m.receiver_id = :userId
            ORDER BY m.timestamp DESC, m.id DESC
        ");
        $stmt->execute([':userId' => $userId]);

        $messages = $stmt->fetchAll(PDO::FETCH_OBJ);

        // If multiple messages share the same timestamp per conversation, filter to one per convo_key
        $conversations = [];
        $seen = [];

        foreach ($messages as $msg) {
            $key = ($msg->sender_id < $msg->receiver_id)
                ? $msg->sender_id . '-' . $msg->receiver_id
                : $msg->receiver_id . '-' . $msg->sender_id;

            if (!isset($seen[$key])) {
                $conversations[] = $msg;
                $seen[$key] = true;
            }
        }

        return $conversations;
    }

}
?>
