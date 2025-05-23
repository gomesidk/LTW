<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/../Utils/Session.php');
  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/userClass.php');
  
  $session = new Session();
  $db = getDatabaseConnection();
  
  if (!$session->getId()) {
      header('Location: login.php');
      exit;
  }
  
  // Load current user info
  $user = User::getUser($db, (int)$session->getId());

  
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Upload Example</title>
    <meta charset="utf-8">
  </head>
  <body>
    <header>
      <h1><a href="index.php">Images</a></h1>
    </header>
    <nav>
      <form action="../Actions/Action_Upload_Image.php" method="post" enctype="multipart/form-data">
        <label>Title:
          <input type="text" name="title">
        </label>
        <input type="file" name="image">
        <input type="submit" value="Upload">
      </form>
    </nav>
  </body>
</html>