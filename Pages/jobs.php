<?php
require_once('../Templates/common_template.php');
require_once(__DIR__ . '/../Utils/Session.php');
require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/serviceClass.php');
require_once(__DIR__ . '/../database/userClass.php');

$session = new Session();
$db = getDatabaseConnection();

$user = User::getUser($db, $session->getId());

$services = Service::getServices($db);

drawHeader($session);  // imprime <html>, <head>, <body>, header

foreach ($services as $service) {
    draw_service($service, $user); // imprime os cards
}

drawFooter(); // imprime footer, fecha body e html
?>
