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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../css/edit_profile.css" />
</head>
<body>

  <div class="profile-edit-container">

    <aside class="profile-sidebar">
        <div class="profile-picture">
            <!-- Default avatar image -->
            <img src="../assets/icons/default-avatar.png" alt="Profile Picture" />
        </div>
        <h2><?= htmlspecialchars($user->name) ?></h2>
        <p>Member since <?= htmlspecialchars(date('F j, Y', strtotime($user->created_at))) ?></p>
    </aside>

    <section class="profile-edit-form">
        <h1>Edit Your Profile</h1>
        <form method="POST" action="../Actions/Action_Edit_Profile.php">

            <label for="name">Name *</label>
            <input type="text" id="name" name="name" required value="<?= htmlspecialchars($user->name) ?>" />

            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required value="<?= htmlspecialchars($user->email) ?>" />

            <label for="birth_date">Birth Date *</label>
            <input type="date" id="birth_date" name="birth_date" required value="<?= htmlspecialchars($user->birth_date) ?>" />

            <label for="phone">Phone *</label>
            <input type="tel" id="phone" name="phone" required value="<?= htmlspecialchars($user->phone) ?>" />

            <label for="nr_bank_account">Bank Account Number *</label>
            <input type="text" id="nr_bank_account" name="nr_bank_account" required value="<?= htmlspecialchars($user->nr_bank_account) ?>" />

            <label for="address">Address *</label>
            <input type="text" id="address" name="address" required value="<?= htmlspecialchars($user->address) ?>" />

            <label for="type_of_service">Type of Service</label>
            <input type="text" id="type_of_service" name="type_of_service" value="<?= htmlspecialchars($user->type_of_service ?? '') ?>" />

            <label for="rate">Rate (Hourly)</label>
            <input type="number" id="rate" name="rate" step="0.01" min="0" value="<?= htmlspecialchars($user->rate !== null ? (string)$user->rate : '') ?>" />

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5"><?= htmlspecialchars($user->description ?? '') ?></textarea>

            <button type="submit" class="save-btn">Save Changes</button>
        </form>
    </section>

  </div>

</body>
</html>
