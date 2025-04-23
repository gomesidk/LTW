<?php
  declare(strict_types = 1);

  // Include necessary files for session and header/footer functions
  require_once(__DIR__ . '/../utils/session.php');
  require_once('../Templates/common_template.php');
  $session = new Session(); // Initialize session
?>

<div class="container">
  <form action="../actions/Action_Login.php" method="POST">
    <h2>Login</h2>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
    <div class="switch">
      Don't have an account? <a href="register.php">Register</a>
    </div>
  </form>
</div>

<section id="messages">
  <?php foreach ($session->getMessages() as $message) { ?>
    <article class="<?=$message['type']?>">
      <?=$message['text']?>
    </article>
  <?php } ?>
</section>

