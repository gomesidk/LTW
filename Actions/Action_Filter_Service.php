<?php
require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/serviceClass.php');
require_once(__DIR__ . '/../Utils/Session.php');

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

$services = $stmt->fetchAll();

function renderServiceCard(array $service): string {
    // You can adapt this function to your draw_service() HTML structure
    return '
    <div class="service-card2">
      <div class="service-card2__header">
        <h3 class="service-card2__title">' . htmlspecialchars($service['name']) . '</h3>
        <div class="service-card2__price">$' . number_format($service['price'], 2) . '</div>
      </div>
      <div class="service-card2__meta">
        <span class="service-card2__category">' . htmlspecialchars($service['category']) . '</span>
      </div>
      <p class="service-card2__desc">' . htmlspecialchars($service['description']) . '</p>
    </div>';
}

foreach ($services as $service) {
    echo renderServiceCard($service);
}
