<?php
require_once("../Utils/Session.php");
require_once("../database/connection.php");
require_once("../database/messageClass.php");

$session = new Session();
if (!$session->isLoggedIn()) {
    header("Location: ../Pages/login.php");
    exit();
}

$senderId = $session->getId();
$receiverId = $_POST['receiver_id'] ?? null;
$content = trim($_POST['content'] ?? '');

if (!$receiverId || $senderId == $receiverId || empty($content)) {
    header("Location: ../Pages/chat.php?with=$receiverId&error=1");
    exit();
}

$db = getDatabaseConnection();
$messageService = new MessageService($db);
$messageService->sendMessage($senderId, $receiverId, $content);

header("Location: ../Pages/chat.php?with=$receiverId");
exit();
