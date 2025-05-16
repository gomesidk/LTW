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
                        <div id="profileCircle" class="profile-circle" style="display:block;" onclick="window.location.href='profile.php'">
                            <img src="../assets/icons/user.png" alt="Profile">
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



function draw_service(Service $service) {
    ?>
    <div style="
        border: 1px solid #ccc; 
        padding: 15px; 
        margin: 10px 0; 
        border-radius: 8px; 
        box-shadow: 2px 2px 8px rgba(0,0,0,0.1);
        max-width: 600px;
    ">
        <h2 style="margin: 0 0 10px 0;"><?= htmlspecialchars($service->name) ?></h2>
        <p style="margin: 0 0 8px 0;"><?= nl2br(htmlspecialchars($service->description)) ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($service->category) ?></p>
        <p><strong>Price:</strong> $<?= number_format($service->price, 2) ?></p>
        <p><strong>Applications:</strong> <?= $service->number_applications ?></p>
        <p><small>Posted on: <?= htmlspecialchars($service->created_at) ?></small></p>
    </div>
    <?php
}

?>
