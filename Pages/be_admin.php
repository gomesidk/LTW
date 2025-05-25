<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../Utils/Session.php');
$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Become Admin</title>
  <style>
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
      box-sizing: border-box;
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
  </style>
</head>
<body>

<div class="container">
  <form method="post" action="../Actions/Action_Be_Admin.php" id="admin-form">
    <h2>Enter Admin Password</h2>
    <input
      type="password"
      name="admin_password"
      placeholder="Admin Password"
      required
      autocomplete="off"
    >
    <button type="submit">Become Admin</button>
  </form>
</div>

</body>
</html>
