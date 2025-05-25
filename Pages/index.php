<?php
  // Include the session and header/footer functions
  require_once('../Templates/common_template.php');
  require_once(__DIR__ . '/../Utils/Session.php');
  require_once(__DIR__ . '/../database/connection.php');
  $session = new Session(); // Initialize session
  $db = getDatabaseConnection(); // Replace with your actual function if different

  $stmt = $db->query("SELECT name FROM Category ORDER BY name");
  $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<?php
// Render header
drawHeader($session);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SkillFlow</title>
  <link rel="stylesheet" href="../css/index.css">
  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="stylesheet" href="../css/footer.css">
</head>
<body>
  <div class="container">
    <div class="image-section">
      <img src="../assets/images/team.jpg" alt="Team Image">
    </div>
    <div class="text-section">
      <h1>We connect people to bring projects to life </h1>
      <p>Find high-quality talent or open jobs with the help of AI tools that keep you in control.</p> <br><br>
      <a href="jobs.php">
        <button>Find Work Now</button>
      </a>
    </div>
  </div>

  <section class="categories-section">
  <h2>Categories</h2> <br>
  <div class="categories-grid">
    <?php foreach ($categories as $category): 
      $name = htmlspecialchars($category['name']);
    ?>
      <form action="jobs_by_category.php" method="get" class="category-card">
        <input type="hidden" name="category" value="<?= $name ?>">
        <button type="submit" style="width: 100%; height: 100%; border:none; background:none;">
          <span><?= $name ?></span>
        </button>
      </form>
    <?php endforeach; ?>
  </div>

    </div>
  </div>
</section>


</body>
<script>
  window.onload = function() {
    // Get carousel elements
    const carousel = document.getElementById('carousel');
    const topPerformersCarousel = document.getElementById('top-performers-carousel');
    const leftButton = document.querySelector('.scroll-button-left');
    const rightButton = document.querySelector('.scroll-button-right');
    const leftButtonTop = document.querySelector('.scroll-button-left-top');
    const rightButtonTop = document.querySelector('.scroll-button-right-top');

    // Function to update the visibility of the scroll buttons
    function updateButtonsVisibility() {
      const maxScrollLeft = carousel.scrollWidth - carousel.clientWidth;
      const maxScrollLeftTop = topPerformersCarousel.scrollWidth - topPerformersCarousel.clientWidth;

      // Categories section buttons visibility
      if (carousel.scrollLeft === 0) {
        leftButton.style.display = 'none'; // Hide left button at the leftmost
      } else {
        leftButton.style.display = 'flex'; // Show left button if not at the leftmost
      }

      if (carousel.scrollLeft === maxScrollLeft) {
        rightButton.style.display = 'none'; // Hide right button at the rightmost
      } else {
        rightButton.style.display = 'flex'; // Show right button if not at the rightmost
      }

      // Top Performers section buttons visibility
      if (topPerformersCarousel.scrollLeft === 0) {
        leftButtonTop.style.display = 'none'; // Hide left button at the leftmost
      } else {
        leftButtonTop.style.display = 'flex'; // Show left button if not at the leftmost
      }

      if (topPerformersCarousel.scrollLeft === maxScrollLeftTop) {
        rightButtonTop.style.display = 'none'; // Hide right button at the rightmost
      } else {
        rightButtonTop.style.display = 'flex'; // Show right button if not at the rightmost
      }
    }

    // Function to scroll the carousel
    function scrollCarousel(direction) {
      const scrollAmount = 200;
      if (direction === 'left') {
        carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
      } else {
        carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
      }
      updateButtonsVisibility();
    }

    // Function to scroll the top performers section
    function scrollTopPerformers(direction) {
      const scrollAmount = 200;
      if (direction === 'left') {
        topPerformersCarousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
      } else {
        topPerformersCarousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
      }
      updateButtonsVisibility();
    }

    // Attach event listeners to the buttons
    leftButton.addEventListener('click', function() {
      scrollCarousel('left');
    });

    rightButton.addEventListener('click', function() {
      scrollCarousel('right');
    });

    leftButtonTop.addEventListener('click', function() {
      scrollTopPerformers('left');
    });

    rightButtonTop.addEventListener('click', function() {
      scrollTopPerformers('right');
    });

    // Initial call to set button visibility based on the starting positions
    updateButtonsVisibility();
  };
</script>
</html>

<?php
// Render header
drawFooter($session);
?>