<?php
require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/serviceClass.php');
require_once(__DIR__ . '/../database/userClass.php'); // Assuming you have this for User class
require_once(__DIR__ . '/../Utils/Session.php');
require_once(__DIR__ . '/../Templates/common_template.php'); // Adjust path where draw_service() is defined

header('Content-Type: text/html; charset=utf-8');

$db = getDatabaseConnection();

$category = $_POST['category'] ?? '';
$min_price = $_POST['min_price'] ?? '';

// Sanitize and validate inputs
$category = trim($category);
$min_price = is_numeric($min_price) ? floatval($min_price) : 0;

// Build query dynamically with filters
$query = 'SELECT * FROM Service WHERE 1=1';
$params = [];

if ($category !== '') {
    $query .= ' AND category = ?';
    $params[] = $category;
}

if ($min_price > 0) {
    $query .= ' AND price >= ?';
    $params[] = $min_price;
}

$stmt = $db->prepare($query);
$stmt->execute($params);

$serviceRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// For each result row, create a Service object and get the User object of the buyer
foreach ($serviceRows as $row) {
    $service = new Service(
        (int)$row['id'],
        $row['name'],
        $row['description'],
        (float)$row['price'],
        $row['created_at'],
        (int)$row['number_applications'],
        $row['category'],
        (int)$row['buyer_id'],
        isset($row['worker_id']) ? (int)$row['worker_id'] : null,
        $row['state']
    );

    // Get buyer User object by buyer_id - you need to implement this or adjust accordingly
    $buyer = User::getUser($db, $service->buyer_id);

    // Call your draw_service() function to render each service
    draw_service($service, $buyer);
}
?>
