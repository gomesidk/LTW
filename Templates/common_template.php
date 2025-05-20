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
                        <input type="text" placeholder="Search..." class="search-input">
                        <button type="submit" class="search-button">
                            <img src="../assets/icons/search.png" alt="Search">
                        </button>
                    </div>
                </nav>
                <div class="auth-buttons" id="authButtons">
                    <?php if ($session->isLoggedIn()): ?>
                    <div class="profile-dropdown-container">
                        <div id="profileCircle" class="profile-circle" onclick="toggleDropdown()">
                        <img src="../assets/icons/user.png" alt="Profile">
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
    <a href="<?= $url ?>" style="
        text-decoration: none; 
        color: inherit; 
        display: block;
        max-width: 600px;
        margin: 10px 0;
        border-radius: 8px;
        box-shadow: 2px 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #ccc;
        padding: 15px;
        ">
        <h2 style="margin: 0 0 10px 0;"><?= htmlspecialchars($service->name) ?></h2>
        <p style="margin: 0 0 8px 0;"><?= nl2br(htmlspecialchars($service->description)) ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($service->category) ?></p>
        <p><strong>Price:</strong> $<?= number_format($service->price, 2) ?></p>
        <p><strong>Applications:</strong> <?= $service->number_applications ?></p>
        <p><small>Posted on: <?= htmlspecialchars($service->created_at) ?></small></p>
    </a>
    <?php
}

function draw_user(User $user, Service $service) {
    ?>
    <div class="user-card-container" style="margin-bottom: 20px; border: 2px solid #ccc; padding: 20px; width: 300px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="user-card">
            <img src="../assets/icons/user.png" alt="User Avatar" class="user-avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-bottom: 15px;">
            <h3><?= htmlspecialchars($user->email) ?></h3>
            <p><strong>Level:</strong> <?= number_format($user->level) ?></p>
            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($user->description)) ?></p>
        </div>
        <!-- Green Select Worker Button -->
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
    </div>
    <?php
}



?>
