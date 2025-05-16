<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../Utils/Session.php');
$session = new Session(); // Initialize the session

require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/userClass.php');

$db = getDatabaseConnection(); // Get the database connection

// Check if the necessary form data is provided via POST
if (isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['birth_date'], $_POST['phone'], $_POST['nr_bank_account'], $_POST['address'])) {
    
    // Sanitize input (basic example, more sanitization may be required)
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $birth_date = trim($_POST['birth_date']);
    // $profile_picture = trim($_POST['profile_picture']);  // In a real app, you may want to handle file uploads
    $phone = trim($_POST['phone']);
    $nr_bank_account = trim($_POST['nr_bank_account']);
    $address = trim($_POST['address']);

    // Check if the email is already registered
    $stmt = $db->prepare("SELECT * FROM User WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $session->addMessage('error', 'Email is already registered!');
        header('Location: ' . $_SERVER['HTTP_REFERER']);  // Redirect back to the registration form
        exit;
    }

    // Use the User class to register the new user
    $isRegistered = User::register($db, $name, $email, $password, $birth_date, $phone, $nr_bank_account, $address);

    if ($isRegistered) {
        // User successfully registered
        $session->addMessage('success', 'Registration successful!');
        // Optionally log the user in immediately after registration
        $user = User::getUserWithPassword($db, $email, $password);  // Log the user in
        $session->setId($user->id);
        $session->setName($user->name());
        
        header('Location: ../Pages/index.php');
    } else {
        // If registration fails
        $session->addMessage('error', 'There was an error during registration.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);  // Redirect back to the registration form
    }
} else {
    // If required fields are missing
    $session->addMessage('error', 'Please fill all required fields!');
}


?>
