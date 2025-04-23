<?php
  declare(strict_types = 1);

  // Include necessary files for session and header/footer functions
  require_once(__DIR__ . '/../utils/session.php');
  require_once('../Templates/common_template.php');
  $session = new Session(); // Initialize session
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <style>
    /* Add the same styles here as in your original code */
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .container {
      background: white;
      padding: 20px 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 300px;
    }
    h2 {
      text-align: center;
    }
    input {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      width: 100%;
      padding: 10px;
      background: #007BFF;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .switch {
      text-align: center;
      margin-top: 10px;
      font-size: 14px;
    }
    .switch a {
      cursor: pointer;
      color: #007BFF;
      text-decoration: none;
    }
  </style>
</head>
<body>

<div class="container">
    <form id="login-form" method="post" action="../Actions/Action_Register.php">
    <h2>Register</h2>
    <input type="text" placeholder="Username" required>
    <input type="text" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="text" placeholder="Birth Date" required>2
    <input type="text" placeholder="Phone Number" required>
    <input type="text" placeholder="Bank Account" required>
    <input type="text" placeholder="Address" required>
    <button type="submit">Register</button>
    <div class="switch">
      Already have an account? <a href="login.php">Login</a>
    </div>
  </form>
</div>

</body>
</html>

<section id="messages">
  <?php foreach ($session->getMessages() as $message) { ?>
    <article class="<?=$message['type']?>">
      <?=$message['text']?>
    </article>
  <?php } ?>
</section>