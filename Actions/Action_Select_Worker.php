<?php
declare(strict_types = 1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once(__DIR__ . '/../Utils/Session.php');
$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /');
    exit;
}

if (!isset($_POST['userId'], $_POST['jobId'])) {
    die('Missing userId or jobId!');
}

require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/userClass.php');
require_once(__DIR__ . '/../database/serviceClass.php');

$db = getDatabaseConnection();

$user = User::getUser($db, $_POST['userId']);
$service = Service::getService($db, $_POST['jobId']);

if ($service && $user) {
    Service::setworker($db, $service->id, $user->id);
    $service->save($db);
    echo "Worker (User ID: {$user->id}) successfully assigned to Service (Job ID: {$service->id}).";
    $session->addMessage('error', 'User or Service not found!');
} else {
    die('User or Service not found!');
    $session->addMessage('error', 'User or Service not found!');
}

header('Location: ../Pages/profile.php');
exit;
?>
