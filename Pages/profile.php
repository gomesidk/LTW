<?php
declare(strict_types=1);

require_once('../Templates/common_template.php');
require_once(__DIR__ . '/../Actions/Action_Retrieve_Profile.php');
require_once(__DIR__ . '/../Utils/Session.php');
require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/serviceClass.php');
require_once(__DIR__ . '/../database/userClass.php');

$session = new Session();
$db = getDatabaseConnection();
$user = User::getUser($db, $session->getId());
$user_services = Service::getServicesByBuyer($db, $user->id);

// Render header
drawHeader($session);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Upwork Profile Mockup</title>
  <link rel="stylesheet" href="../css/profile.css" />
  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="stylesheet" href="../css/footer.css">
</head>
<body>

  <section class="profile">
    <div class="profile-header">
      <?php $imageId = $user->profile_picture_id ?? null; 
            $imageIdString = (string)$imageId; ?>
      <div class="profile-picture">
        <?php if ($imageId): ?>
          <img src="../Actions/images/originals/<?= htmlspecialchars($imageIdString) ?>.jpg" alt="Profile Picture" />
        <?php else: ?>
          <img src="../assets/icons/default-avatar.png" alt="Default Profile Picture" />
        <?php endif; ?>
      </div>
      <div class="profile-info">
        <h1><?= htmlspecialchars($user->name) ?></h1>
        <p>Created at <?= htmlspecialchars($user->created_at) ?></p>
      </div>
      <button class="public-view">See private view</button>
      <button class="profile-settings" onclick="window.location.href='edit_profile.php';">Edit Profile</button>
    </div>

    <div class="profile-body">
      <div class="service-card">
        <div class="service-header">
          <?php if (!is_null($user->rate)): ?>
            <span class="rate">$<?= number_format($user->rate, 2) ?>/hr</span>
          <?php else: ?>
            <span class="rate">No rate set</span>
          <?php endif; ?>
        </div>
        <p>
          <?php if (!empty($user->description)): ?>
            <?= nl2br(htmlspecialchars($user->description)) ?>
          <?php else: ?>
            <em>Adding a service description increases your chances of getting hired — let clients know what you can do!</em>
          <?php endif; ?>
        </p>
      </div>

      <?php
      // Loop over the user's services here and draw them:
      foreach ($user_services as $user_service) {
          draw_service($user_service, $user);
      }
      ?>
    </div>
  </section>

<?php
// Render footer
drawFooter($session);
?>

</body>
</html>
