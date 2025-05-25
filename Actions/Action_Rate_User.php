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

if (!isset($_POST['userId'], $_POST['jobId'], $_POST['rating'])) {
    die('Missing userId, jobId, or rating!');
}

require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/userClass.php');
require_once(__DIR__ . '/../database/serviceClass.php');

$db = getDatabaseConnection();

// Get the current user ID from the session
$userid = (int)$_POST['userId'];

// Get the service ID from the form submission (POST)
$serviceID = (int)$_POST['jobId'];

$rating = (int)$_POST['rating']; // Make sure rating is properly cast

// Debugging: Check if the user and service IDs are correct
echo "User ID: " . $userid . "<br>";
echo "S25 22:48:36 2025] 127.0.0.1:52784 Closing
ervice ID: " . $serviceID . "<br>";
echo "Rating: " . $rating . "<br>";

$user = User::getUser($db, $userid);
$service = Service::getService($db, $serviceID);

// Debugging: Check if user and service are fetched correctly
if ($user) {
    echo "User found: " . $user->name() . "<br>";
} else {
    echo "User not found.<br>";
}

if ($service) {
    echo "Service found: " . $service->name . "<br>";
} else {
    echo "Service not found.<br>";
}

if ($service && $user) {
    // Call the rateUser method to update the rate
    User::rateUser($db, $user->id, $rating); 
    Service::updateState($db, $serviceID, "Worker rated");

    echo "User rated successfully.<br>";

    // Add a success message and redirect
    $session->addMessage('success', 'Worker rated');
} else {
    echo "Error: User or Service not found.<br>";
    $session->addMessage('error', 'User or Service not found!');
}

header('Location: ../Pages/profile.php');
exit;
?>
