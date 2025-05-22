<?php
require_once('../Templates/common_template.php');
require_once(__DIR__ . '/../Utils/Session.php');
require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/serviceClass.php');
require_once(__DIR__ . '/../database/userClass.php');

$session = new Session();
$db = getDatabaseConnection();

$user = User::getUser($db, $session->getId());

$category = $_GET['category'] ?? null;

$services = Service::getServicesByCategory($db, $category);

drawHeader($session);  // imprime <html>, <head>, <body>, header


?>

<div class="services-container">
  <?php foreach ($services as $service): ?>
    <?php draw_service($service, $user); ?>
  <?php endforeach; ?>
</div>

<?php

drawFooter(); // imprime footer, fecha body e html
?>
