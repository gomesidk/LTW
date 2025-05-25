<?php
require_once("../Templates/common_template.php");
require_once("../database/connection.php");
require_once("../Utils/Session.php");
require_once("../database/messageClass.php");
require_once("../database/userClass.php");

$session = new Session();
$db = getDatabaseConnection();

if (!$session->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$currentUserId = $session->getId();
$otherUserId = isset($_GET['with']) ? intval($_GET['with']) : null;

if (!$otherUserId || $otherUserId == $currentUserId) {
    echo "Invalid user.";
    exit();
}

$otherUser = User::getUser($db, $otherUserId);
if (!$otherUser) {
    echo "User not found.";
    exit();
}

$messageService = new MessageService($db);
$messages = $messageService->getMessagesBetweenUsers($currentUserId, $otherUserId);
?>

<?php drawHeader($session); ?>
<link rel="stylesheet" href="../css/chat.css">

<main class="chat-wrapper">
    <h2>Chat with <?= htmlspecialchars($otherUser->name ?? $otherUser->email) ?></h2>

    <div class="chat-box">
        <?php foreach ($messages as $message): ?>
            <div class="chat-message <?= $message['sender_id'] == $currentUserId ? 'sent' : 'received' ?>">
                <p><?= htmlspecialchars($message['content']) ?></p>
                <span class="timestamp"><?= htmlspecialchars($message['timestamp']) ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <form class="chat-form" action="../Actions/Action_Send_Message.php" method="POST">
        <input type="hidden" name="receiver_id" value="<?= $otherUserId ?>">
        <textarea name="content" placeholder="Type your message..." required></textarea>
        <button type="submit">Send</button>
    </form>
</main>

<?php drawFooter(); ?>
