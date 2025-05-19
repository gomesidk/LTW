<?php
require_once('../Utils/Session.php');
require_once('../database/connection.php');
require_once('../database/serviceClass.php'); // Assuming this contains Service::incrementApplications()
$db = getDatabaseConnection();
$session = new Session();
if (!$session->isLoggedIn()) {
    header('Location: ../Pages/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobId = $_POST['jobId'] ?? null;

    if (!is_numeric($jobId)) {
        die('Invalid job ID.');
    }

    try {
        Service::deleteService($db, (int)$jobId);
        header('Location: ../Pages/profile.php?message=deleted');
        exit;

    } catch (PDOException $e) {
        die('Database error: ' . $e->getMessage());
    }
} else {
    die('Invalid request method.');
}
