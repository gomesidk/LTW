<?php
declare(strict_types = 1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

var_dump($_POST); // debug: vê o que tá chegando

require_once(__DIR__ . '/../Utils/Session.php');
$session = new Session(); // Initialize the session

require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/serviceClass.php');

$db = getDatabaseConnection(); // Get the database connection

// Check if the necessary form data is provided via POST
if (isset($_POST['jobTitle'], $_POST['jobDescription'], $_POST['budget'], $_POST['category'])) {
    
    // Sanitize input (basic example, more sanitization may be required)
    $name = trim($_POST['jobTitle']);
    $description = trim($_POST['jobDescription']);
    $price = floatval(trim($_POST['budget']));
    $category = trim($_POST['category']);

    $buyer_id = $session->getId(); 
    if (!$buyer_id) {
        $session->addMessage('error', 'You must be logged in to create a job.');
        header('Location: ../pages/newService.php');
        exit;
    }

    $service = new Service(
        0,              // id = 0 porque ainda não existe no BD
        $name,
        $description,
        $price,
        '',             // created_at vazio, será definido no save()
        0,              // number_applications inicial
        $category,
        $buyer_id,
        null            // worker_id vazio
    );

    try {
        $service->save($db);
        $session->addMessage('success', 'Job created successfully!');
        header('Location: ../Pages/jobs.php'); // redireciona pra página de serviços do user (cria essa página depois)
        exit;
    } catch (Exception $e) {
        $session->addMessage('error', 'Failed to create job: ' . $e->getMessage());
        echo "Failed to create job: " . $e->getMessage();
        exit;
    }
    
} else {
    // If required fields are missing
    echo "Faltam campos no POST!";
    exit;
}

?>
