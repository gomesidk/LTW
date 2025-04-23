<?php
  // Include the session and header/footer functions
  require_once('../Templates/common_template.php');
  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session(); // Initialize session
?>

<?php
// Render header
drawHeader($session);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SkillFlow</title>
  <link rel="stylesheet" href="../css/jobs.css">
  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="stylesheet" href="../css/footer.css">
</head>
<body>

<?php
  // Render header
  drawFooter($session);
?>
