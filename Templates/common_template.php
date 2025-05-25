<?php
// header_footer_functions.php

function drawHeader(Session $session) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SkillFlow</title>
        <link rel="stylesheet" href="../css/navbar.css">
        <link rel="stylesheet" href="../css/jobs.css">
        <link rel="stylesheet" href="../css/footer.css">
        <link rel="stylesheet" href="../css/service.css">
    </head>
    <script>
  function toggleDropdown() {
    const dropdown = document.getElementById("profileDropdown");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
  }

  // Optional: hide dropdown if user clicks outside
  window.addEventListener('click', function (e) {
    const profile = document.getElementById("profileCircle");
    const dropdown = document.getElementById("profileDropdown");
    if (!profile.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.style.display = "none";
    }
  });

  function handleSearch() {
    const query = document.getElementById("searchInput").value.trim();
    if (query === "") return;

    const currentPage = window.location.pathname.split("/").pop();

    if (currentPage === "jobs.php") {
        // Reload current page with search param
        window.location.href = `jobs.php?query=${encodeURIComponent(query)}`;
    } else {
        // Redirect to jobs.php with query
        window.location.href = `jobs.php?query=${encodeURIComponent(query)}`;
    }

}
  function handleSearchKeyPress(event) {
    if (event.key === 'Enter') {
      handleSearch();
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById("searchInput");
    searchInput.addEventListener('keypress', handleSearchKeyPress);
  });


  
</script>
    <body>
        <header class="navbar">
            <a href="index.html" class="logo">SkillFlow</a>
            <div class="bar">
                <nav class="menu">
                    <a href="index.php">Home</a>
                    <a href="jobs.php">Jobs</a>
                    <a href="newjobs.php">New</a>
                    <a href="about.php">About</a>
                    <div class="search-bar">
                      <input type="text" placeholder="Search..." class="search-input" id="searchInput">
                      <button type="button" class="search-button" onclick="handleSearch()">
                          <img src="../assets/icons/search.png" alt="Search">
                      </button>
                  </div>
                </nav>
                <div class="auth-buttons" id="authButtons">
                    <?php if ($session->isLoggedIn()): ?>
                    <div class="profile-dropdown-container">
                        <div id="profileCircle" class="profile-circle" onclick="toggleDropdown()">
                                <?php
                                    require_once(__DIR__ . '/../database/connection.php');
                                    require_once(__DIR__ . '/../database/userClass.php');
                                    $db = getDatabaseConnection();
                                    $user = User::getUser($db, $session->getId());
                                    if ($user->profile_picture_id):
                                ?>
                                    <img src="../Actions/images/originals/<?= htmlspecialchars((string)$user->profile_picture_id) ?>.jpg" alt="Profile Picture" />
                                <?php else: ?>
                                    <img src="../assets/icons/user.png" alt="Profile" />
                                <?php endif; ?>
                        </div>
                        <div id="profileDropdown" class="dropdown-menu" style="display: none;">
                        <a href="profile.php">Go to Profile</a>
                        <a href="../Actions/Action_Logout.php">Logout</a>
                        </div>
                    </div>
                    <?php else: ?>
                        <button class="login" id="loginButton" onclick="window.location.href='login.php'">Log in</button>
                        <button class="signup" id="signupButton" onclick="window.location.href='register.php'">Sign up</button>
                    <?php endif; ?>
                </div>
            </div>
        </header>
    <?php
}

function drawFooter() {
    ?>
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>We are passionate about creating the best products and providing top-notch services for our users.</p>
            </div>
            <div class="footer-section">
                <h3>Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="https://www.facebook.com/seu-usuario" class="social-icon" target="_blank">
                        <img src="../assets/icons/facebook-icon.png" alt="Facebook">
                    </a>
                    <a href="https://twitter.com/seu-usuario" class="social-icon" target="_blank">
                        <img src="../assets/icons/twitter-logo.png" alt="Twitter">
                    </a>
                    <a href="https://www.instagram.com/seu-usuario" class="social-icon" target="_blank">
                        <img src="../assets/icons/instagram-logo.png" alt="Instagram">
                    </a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 SkillFlow. All rights reserved.</p>
        </div>
    </footer>
    </body>
    </html>
    <?php
}



function draw_service(Service $service, User $user) {
    // Prepare URL with service ID or other needed params
    if ($user->id === $service->buyer_id) {
        $url =  "view_my_job.php?jobId=" . urlencode($service->id) . 
                "&jobTitle=" . urlencode($service->name) .
                "&jobDescription=" . urlencode($service->description) .
                "&category=" . urlencode($service->category) .
                "&budget=" . urlencode($service->price);
    } else {
        $url = "apply_to_job.php?jobId=" . urlencode($service->id) .
               "&jobTitle=" . urlencode($service->name) .
               "&jobDescription=" . urlencode($service->description) .
               "&category=" . urlencode($service->category) .
               "&budget=" . urlencode($service->price);
    }

    ?>
    <a href="<?= $url ?>" class="service-card2">
      <div class="service-card2__header">
        <h2 class="service-card2__title"><?= htmlspecialchars($service->name) ?></h2>
        <span class="service-card2__price">$<?= number_format($service->price, 2) ?></span>
      </div>
      <div class="service-card2__meta">
        <span class="service-card2__author">Created by <?= htmlspecialchars($user->name) ?></span>
        <span class="service-card2__date">Posted on <?= htmlspecialchars($service->created_at) ?></span>
      </div>
      <p class="service-card2__desc">
        <?= nl2br(htmlspecialchars($service->description)) ?>
      </p>
      <div class="service-card2__footer">
        <span class="service-card2__category"><?= htmlspecialchars($service->category) ?></span>
        <span class="service-card2__apps"><?= $service->number_applications ?> applications</span>
      </div>
    </a>
    <?php
}

function draw_user(User $user, Service $service) {
    ?>
    <div class="user-card-container" style="margin-bottom: 20px; border: 2px solid #ccc; padding: 20px; width: 300px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="user-card">
            <?php if (!is_null($user->profile_picture_id)): ?>
                <img src="../Actions/images/originals/<?= htmlspecialchars((string)$user->profile_picture_id) ?>.jpg" alt="User Avatar" class="user-avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-bottom: 15px;">
            <?php else: ?>
                <img src="../assets/icons/user.png" alt="User Avatar" class="user-avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-bottom: 15px;">
            <?php endif; ?>
            <h3><?= htmlspecialchars($user->email) ?></h3>
            <p><strong>Level:</strong> <?= number_format($user->level) ?></p>
            <p><strong>Rate: </strong><?= number_format($user->rate / $user->level, 2) ?></p>
            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($user->description)) ?></p>
        </div>
        <!-- Green Select Worker Button -->
         <?php if ($service->worker_id && $service->worker_id == $user->id): ?>
            <p style="color: green; font-weight: bold;">Worker Hired</p>
            <div class="button-container" style="margin-top: 15px;">
                <form action="../Actions/Action_Rate_User.php" method="POST">
                    <!-- Pass the user's ID to the Action_Select_Worker.php script -->
                    <input type="hidden" name="userId" value="<?= htmlspecialchars($user->id) ?>" />
                    <input type="hidden" name="jobId" value="<?= htmlspecialchars($service->id) ?>" />
                    <label for="rating">Rating (1 to 5):</label>
                    <input type="number" name="rating" id="rating" min="1" max="5" step="1" required style="margin-left: 10px; padding: 5px 10px; border-radius: 5px;">
                    <button class="select-worker-btn" type="submit" style="background-color: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">
                        Rate User
                    </button>
                </form>
            </div>
        <?php else: ?>
            <div class="button-container" style="margin-top: 15px;">
                <form action="../Actions/Action_Select_Worker.php" method="POST">
                    <!-- Pass the user's ID to the Action_Select_Worker.php script -->
                    <input type="hidden" name="userId" value="<?= htmlspecialchars($user->id) ?>" />
                    <input type="hidden" name="jobId" value="<?= htmlspecialchars($service->id) ?>" />
                    <button class="select-worker-btn" type="submit" style="background-color: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">
                        Select Worker
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    <?php
}


function drawServiceFilterSidebar(PDO $db) {
  // Fetch categories dynamically from DB if you want, otherwise hardcode as below
  $categories = $db->query('SELECT name FROM Category')->fetchAll(PDO::FETCH_COLUMN);
  ?>
  <div>
    <h3>Filter Services</h3>

    <div style="margin-bottom: 15px;">
      <label for="filter-category" style="display: block; font-weight: bold;">Category:</label>
      <select id="filter-category" style="width: 100%; padding: 5px;">
        <option value="">All</option>
        <?php foreach ($categories as $category): ?>
          <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div style="margin-bottom: 15px;">
      <label for="filter-min-price" style="display: block; font-weight: bold;">Minimum Price:</label>
      <input type="number" id="filter-min-price" style="width: 100%; padding: 5px;" placeholder="e.g. 20" min="0" />
    </div>

    <button id="apply-filters" style="width: 100%; padding: 10px; background-color: #14a800; color: white; border: none; border-radius: 5px;">
      Apply Filters
    </button>
  </div>

  <script>
    document.getElementById('apply-filters').addEventListener('click', function() {
      const category = document.getElementById('filter-category').value;
      const minPrice = document.getElementById('filter-min-price').value;

      const xhr = new XMLHttpRequest();
      xhr.open('POST', '../Actions/Action_Filter_Service.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      xhr.onload = function () {
        if (xhr.status === 200) {
          document.querySelector('.services-container').innerHTML = xhr.responseText;
        } else {
          alert('Error loading filtered services.');
        }
      };

      xhr.send(`category=${encodeURIComponent(category)}&min_price=${encodeURIComponent(minPrice)}`);
    });
  </script>
  <?php
}
?>

?>
