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

$userId = $session->getId();
$messageService = new MessageService($db);
$conversations = $messageService->getAllConversationsForUser($userId);
?>

<?php drawHeader($session); ?>

<link rel="stylesheet" href="../css/conversations.css">

<main class="conversations-wrapper">
    <h1>Your Conversations</h1>

    <?php if (empty($conversations)): ?>
        <p>You don't have any conversations yet.</p>
    <?php else: ?>
        <ul class="conversation-list">
            <?php foreach ($conversations as $message):
                $otherUserId = ($message->sender_id == $userId) ? $message->receiver_id : $message->sender_id;
                $otherUser = User::getUser($db, $otherUserId);
            ?>
                <li class="conversation-card" onclick="window.location.href='chat.php?with=<?= $otherUserId ?>'">
                    <div class="conversation-profile">
                        <?php if ($otherUser->profile_picture_id): ?>
                            <img src="../Actions/images/originals/<?= htmlspecialchars((string)$otherUser->profile_picture_id) ?>.jpg" alt="Profile">
                        <?php else: ?>
                            <img src="../assets/icons/user.png" alt="Profile">
                        <?php endif; ?>
                    </div>
                    <div class="conversation-content">
                        <h3><?= htmlspecialchars($otherUser->name ?? $otherUser->email) ?></h3>
                        <p><?= htmlspecialchars(substr($message->content, 0, 60)) ?><?= strlen($message->content) > 60 ? '...' : '' ?></p>
                        <span class="timestamp"><?= date('d M Y H:i', strtotime($message->timestamp)) ?></span>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>

<?php drawFooter(); ?>
