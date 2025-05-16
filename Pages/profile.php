<?php
declare(strict_types=1);

// This file handles session and retrieves $user from the database
require_once('../Templates/common_template.php');
require_once(__DIR__ . '/../actions/Action_Retrieve_Profile.php');
?>

<?php
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
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillFlow</title>
    <link rel="stylesheet" href="index.css">
  </head>
  <body>
    <section class="profile">
      <div class="profile-header">
        <img src="../assets/icons/user.png" class="avatar" alt="Profile picture" />
        <div class="profile-info">
          <h1><?= htmlspecialchars($user->name) ?></h1>
          <p>Created at <?= htmlspecialchars($user->created_at) ?></p>
        </div>
        <button class="public-view">See private view</button>
        <button class="profile-settings" onclick="window.location.href='edit_profile.php';">Edit Profile</button>
      </div>

      <div class="profile-body">
        <div class="offer-card">
          <strong>⭐ FREELANCER PLUS OFFER</strong>
          <p>Get Freelancer Plus for 50% off one month and keep your profile visible during breaks. Limited time only.</p>
        </div>

        <div class="service-card">
        <div class="service-header">
          <?php if (!is_null($user->type_of_service) && $user->type_of_service != ''): ?>
            <h2><?= htmlspecialchars($user->type_of_service) ?></h2>
          <?php else: ?>
            <h2>Complete your profile</h2>
          <?php endif; ?>
          <?php if (!is_null($user->rate)): ?>
            <span class="rate">$<?= number_format($user->rate, 2) ?>/hr</span>
          <?php else: ?>
            <span class="rate">No rate set</span>
          <?php endif; ?>
        </div>
          <!-- <div class="service-header">
            <h2>Data Entry & Transcription Services</h2>
            <span class="rate">$5.40/hr</span>
          </div> -->
          <p>
            <?php if (!empty($user->description)): ?>
              <?= nl2br(htmlspecialchars($user->description)) ?>
            <?php else: ?>
              <em>Adding a service description increases your chances of getting hired — let clients know what you can do!</em>
            <?php endif; ?>
          </p>

        </div>

        <div class="ads-card">
          <h3>Promote with ads</h3>
          <p><strong>Availability badge:</strong> Off</p>
          <p><strong>Boost your profile</strong></p>
        </div>
      </div>
    </section>
  </div>

</body>
</html>

<?php
// Render header
drawFooter($session);
?>