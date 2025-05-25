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
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 2rem;
      background: #f9f9f9;
      color: #333;
    }

    h1, h2 {
      color: #222;
    }

    .message {
      color: green;
      font-weight: bold;
      margin-bottom: 1rem;
    }

    button {
      padding: 8px 15px;
      border: none;
      border-radius: 4px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn { color: white; }

    .promote-btn { background-color: #4CAF50; }
    .promote-btn:hover { background-color: #45a049; }

    .delete-btn { background-color: #f44336; }
    .delete-btn:hover { background-color: #d32f2f; }

    .edit-btn { background-color: #ff9800; }
    .edit-btn:hover { background-color: #e68900; }

    table {
      border-collapse: collapse;
      width: 100%;
      background: white;
      margin-bottom: 2rem;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #4CAF50;
      color: white;
    }

    .form-section {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
    }

    .form-box, .category-box {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      flex: 1 1 400px;
    }

    input[type="text"], textarea {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .category-actions form {
      display: inline;
    }
  </style>
</head>
<body>

<h1>Admin Management</h1>

<button onclick="window.location.href='profile.php'" style="background-color:#007BFF; color:white; margin-bottom:20px;">Go to Profile</button>

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
          <?php if ($user['user_type'] !== 'admin'): ?>
            <form method="POST" action="../Actions/Action_Admin_Manage.php">
              <input type="hidden" name="promote_user_id" value="<?= (int)$user['id'] ?>" />
              <button type="submit" class="btn promote-btn">Promote to Admin</button>
            </form>
          <?php endif; ?>
          <form method="POST" action="../Actions/Action_Admin_Manage.php" onsubmit="return confirm('Delete this user?');">
            <input type="hidden" name="delete_user_id" value="<?= (int)$user['id'] ?>" />
            <button type="submit" class="btn delete-btn">Delete User</button>
          </form>
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
