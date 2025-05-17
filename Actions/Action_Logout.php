<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../Utils/Session.php');
$session = new Session();
$session->logout();

header('Location: ../Pages/login.php'); // Redirect after logout
exit();