<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../Utils/Session.php');
$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /');
    exit;
}

require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/userClass.php');

$db = getDatabaseConnection();

$user = User::getUser($db, $session->getId());

if ($user) {
    // Get the admin password from POST
    $adminPassword = $_POST['admin_password'] ?? '';

    // Define your secret admin password here
    $SECRET_ADMIN_PASS = '12345';

    if ($adminPassword === $SECRET_ADMIN_PASS) {
        // Update user level or user_type to admin
        $user->user_type = 'admin';

        // Save changes to DB
        $user->save($db);

        // Update session name (optional)
        $session->setName($user->name());

    } else {
        // Optionally handle wrong password: redirect back with error or just ignore
        // header('Location: ../Pages/profile.php?error=wrong_password');
        // exit;
    }
}

header('Location: ../Pages/profile.php');
exit;
?>
