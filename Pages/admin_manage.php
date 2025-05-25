<?php
declare(strict_types=1);

require_once(__DIR__ . '/../Utils/Session.php');
require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/userClass.php');

$session = new Session();
$db = getDatabaseConnection();

if (!$session->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$currentUser = User::getUser($db, $session->getId());
if ($currentUser->user_type !== 'admin') {
    http_response_code(403);
    echo "Access denied. Admins only.";
    exit;
}

$stmt = $db->prepare('SELECT id, name, email, user_type FROM User WHERE id != ? ORDER BY name');
$stmt->execute([$currentUser->id]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->query('SELECT id, name, description FROM Category ORDER BY name');
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = $_GET['msg'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Management</title>
  <link rel="stylesheet" href="../css/admin_manage.css">
</head>
<body>

<h1>Admin Management</h1>

<button onclick="window.location.href='profile.php'" class="go-profile-btn">Go to Profile</button>

<?php if ($message): ?>
  <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<h2>Users</h2>
<table>
  <thead>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Type</th><th>Actions</th></tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
      <tr>
        <td><?= $user['id'] ?></td>
        <td><?= htmlspecialchars($user['name']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['user_type']) ?></td>
        <td>
          <div class="admin-user-buttons">

            <?php if ($user['user_type'] !== 'admin'): ?>
              <form method="POST" action="../Actions/Action_Admin_Manage.php">
                <input type="hidden" name="promote_user_id" value="<?= (int)$user['id'] ?>" />
                <button type="submit" class="btn promote-btn">Promote to Admin</button>
              </form>
              <form method="POST" action="../Actions/Action_Admin_Manage.php" onsubmit="return confirm('Delete this user?');">
                <input type="hidden" name="delete_user_id" value="<?= (int)$user['id'] ?>" />
                <button type="submit" class="btn delete-btn">Delete User</button>
                <?php endif; ?>
          </form>
        </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<h2>Category Management</h2>
<div class="form-section">
  <div class="form-box">
    <h3>Create New Category</h3>
    <form method="POST" action="../Actions/Action_Admin_Manage.php">
      <label>Category Name:
        <input type="text" name="new_category_name" required />
      </label>
      <label>Description:
        <textarea name="new_category_description" rows="3" required></textarea>
      </label>
      <button type="submit">Create Category</button>
    </form>
  </div>

  <div class="category-box">
    <h3>Existing Categories</h3>
    <table>
      <thead>
        <tr><th>Name</th><th>Description</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php foreach ($categories as $cat): ?>
          <tr>
            <td><?= htmlspecialchars($cat['name']) ?></td>
            <td><?= htmlspecialchars($cat['description']) ?></td>
            <td class="category-actions">
              <form method="POST" action="../Actions/Action_Admin_Manage.php" style="display:inline;" onsubmit="return confirm('Delete this category?');">
                <input type="hidden" name="delete_category_id" value="<?= (int)$cat['id'] ?>" />
                <button type="submit" class="btn delete-btn">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
