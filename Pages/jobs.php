<?php
require_once('../Templates/common_template.php');
require_once(__DIR__ . '/../Utils/Session.php');
require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/serviceClass.php');

$session = new Session();
$db = getDatabaseConnection();

$services = Service::getServicesByCategory($db, 'Software Engineering');

drawHeader($session);  // imprime <html>, <head>, <body>, header

foreach ($services as $service) {
    draw_service($service); // imprime os cards
}

drawFooter(); // imprime footer, fecha body e html
?>
