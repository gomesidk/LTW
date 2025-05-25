<?php
declare(strict_types=1);

require_once(__DIR__ . '/../Utils/Session.php');
require_once(__DIR__ . '/../database/connection.php');

$session = new Session();
$db = getDatabaseConnection();

header('Content-Type: application/json');

if (!$session->isLoggedIn()) {
    http_response_code(401);
    echo json_encode([]);
    exit;
}

$currentUserId = $session->getId();

// Get the search query parameter (sanitize input)
$query = trim($_GET['q'] ?? '');
if ($query === '') {
    echo json_encode([]);
    exit;
}

// Prepare SQL search statement to find users by id, name, or email (excluding current admin)
$sql = "
    SELECT id, name, email, user_type 
    FROM User 
    WHERE id != :currentUserId AND (
        id = :idExact
        OR name LIKE :likeQuery
        OR email LIKE :likeQuery
    )
    ORDER BY name
    LIMIT 50
";

$stmt = $db->prepare($sql);

// If query is numeric, treat as id exact match
$idExact = is_numeric($query) ? (int)$query : 0;
$likeQuery = '%' . $query . '%';

$stmt->execute([
    ':currentUserId' => $currentUserId,
    ':idExact' => $idExact,
    ':likeQuery' => $likeQuery,
]);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON response
echo json_encode($results);
exit;
