<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/userClass.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    $session->addMessage('error', 'You must be logged in to view your profile.');
    header('Location: login.php');
    exit();
}

$db = getDatabaseConnection();
$user = User::getUser($db, (int) $_SESSION['id']);
?>