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
    // Sanitize and update fields (you can add more validation here)
    $user->name = trim($_POST['name'] ?? $user->name);
    $user->email = trim($_POST['email'] ?? $user->email);
    $user->birth_date = trim($_POST['birth_date'] ?? $user->birth_date);
    $user->phone = trim($_POST['phone'] ?? $user->phone);
    $user->nr_bank_account = trim($_POST['nr_bank_account'] ?? $user->nr_bank_account);
    $user->address = trim($_POST['address'] ?? $user->address);
    if (isset($_POST['rate']) && $_POST['rate'] !== '') {
      $user->rate = floatval($_POST['rate']);
    } else {
        $user->rate = null;
    }
    $user->description = trim($_POST['description'] ?? $user->description);

    $user->save($db);
    $session->setName($user->name());
}

header('Location: ../Pages/profile.php');
exit;
?>
