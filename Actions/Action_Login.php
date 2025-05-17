<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../Utils/Session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/userClass.php');

  $db = getDatabaseConnection();

  $customer = User::getUserWithPassword($db, $_POST['email'], $_POST['password']);

  if ($customer) {
    $session->setId($customer->id);
    $session->setName($customer->name());
    $session->addMessage('success', 'Login successful!');
    header('Location: ../Pages/index.php');
    exit();
  } else {
    $session->addMessage('error', 'Wrong password!');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }

?>