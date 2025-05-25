<?php
declare(strict_types=1);

require_once(__DIR__ . '/../Utils/Session.php');
require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/userClass.php');

$session = new Session();
$db = getDatabaseConnection();

// Check if user is logged in and is admin
if (!$session->isLoggedIn()) {
    http_response_code(403);
    echo "Forbidden: Not logged in";
    exit;
}

$user = User::getUser($db, $session->getId());
if ($user->user_type !== 'admin') {
    http_response_code(403);
    echo "Forbidden: Admins only";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method Not Allowed";
    exit;
}

// Promote user
if (isset($_POST['promote_user_id'])) {
    $promoteUserId = (int)$_POST['promote_user_id'];
    if ($promoteUserId === $user->id) {
        // Prevent promoting self (optional)
        http_response_code(400);
        echo "You cannot promote yourself.";
        exit;
    }
    if (User::promoteToAdmin($db, $promoteUserId)) {
        header('Location: ../Pages/admin_manage.php?msg=User promoted');
        exit;
    }
}

// Delete user
if (isset($_POST['delete_user_id'])) {
    $deleteUserId = (int)$_POST['delete_user_id'];
    if ($deleteUserId === $user->id) {
        // Prevent deleting self
        http_response_code(400);
        echo "You cannot delete yourself.";
        exit;
    }
    if (User::deleteUser($db, $deleteUserId)) {
        header('Location: ../Pages/admin_manage.php?msg=User deleted');
        exit;
    }
}

// Create new category
if (isset($_POST['new_category_name']) && isset($_POST['new_category_description'])) {
    $name = trim($_POST['new_category_name']);
    $description = trim($_POST['new_category_description']);
    if ($name === '' || $description === '') {
        http_response_code(400);
        echo "Category name and description required.";
        exit;
    }
    $stmt = $db->prepare('INSERT INTO Category (name, description) VALUES (?, ?)');
    try {
        $stmt->execute([$name, $description]);
        header('Location: ../Pages/admin_manage.php?msg=Category created');
        exit;
    } catch (PDOException $e) {
        http_response_code(400);
        echo "Error creating category: " . $e->getMessage();
        exit;
    }
}

// Delete Category
if (isset($_POST['delete_category_id'])) {
    $id = (int) $_POST['delete_category_id'];
    $stmt = $db->prepare('DELETE FROM Category WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: ../pages/admin_manage.php?msg=Category+deleted');
    exit;
}

// Edit Category (redirect to an edit page or handle inline update)
if (isset($_POST['edit_category_id'])) {
    $id = (int) $_POST['edit_category_id'];
    $name = $_POST['edit_category_name'];
    $description = $_POST['edit_category_description'];

    // For inline editing, replace this with a form to modify and resubmit
    $stmt = $db->prepare('UPDATE Category SET name = ?, description = ? WHERE id = ?');
    $stmt->execute([$name, $description, $id]);
    header('Location: ../pages/admin_manage.php?msg=Category+updated');
    exit;
}


http_response_code(400);
echo "Invalid request";
exit;
