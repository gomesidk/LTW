<?php
require_once('../Templates/common_template.php');
require_once(__DIR__ . '/../Utils/Session.php');
require_once(__DIR__ . '/../database/connection.php');
require_once(__DIR__ . '/../database/serviceClass.php');
require_once(__DIR__ . '/../database/userClass.php');

$session = new Session();
$db = getDatabaseConnection();

$user = User::getUser($db, $session->getId());

$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';
if (!empty($searchQuery)) {
    $services = Service::searchServices($db, $searchQuery);
} else {
    $services = Service::getServices($db);
}



drawHeader($session);  // imprime <html>, <head>, <body>, header

?>

<div class="job-page-layout">
  <div class="filter-sidebar">
    <?php drawServiceFilterSidebar($db); ?>
  </div>
  <div class="services-container">
    <?php
      foreach ($services as $service) {
        draw_service($service, $user);
      }
    ?>
  </div>
</div>

<?php

drawFooter(); // imprime footer, fecha body e html
?>
 