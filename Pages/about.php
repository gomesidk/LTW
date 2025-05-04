<?php
  // Include the session and header/footer functions
  require_once('../Templates/common_template.php');
  require_once(__DIR__ . '/../Utils/Session.php');
  $session = new Session(); // Initialize session
?>

<?php
// Render header
drawHeader($session);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>About Us | YourSite</title>
  <link rel="stylesheet" href="../css/about.css">
  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="stylesheet" href="../css/footer.css">
</head>
<body>
  <!-- About Section -->
  <section class="about-section">
    <div class="container">
      <h2>About Us</h2>
      <p class="about-intro">
        At <strong>YourSite</strong>, we're passionate about empowering tech enthusiasts, developers, and innovators. 
        Our platform is dedicated to showcasing top performers, emerging trends, and the most exciting domains in technology.
      </p>
      <div class="about-columns">
        <div class="about-card">
          <h3>Our Mission</h3>
          <p>
            To foster a vibrant community that celebrates creativity, innovation, and collaboration across all tech domains — from AI to Web, IoT, and beyond.
          </p>
        </div>
        <div class="about-card">
          <h3>What We Offer</h3>
          <p>
            Weekly highlights of top developers, curated learning resources, project showcases, and a supportive space to connect and grow.
          </p>
        </div>
        <div class="about-card">
          <h3>Our Vision</h3>
          <p>
            To be the go-to platform where rising talent meets opportunity, and where every skill level — from beginner to pro — finds value.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Team Section (optional) -->
  <section class="team-section">
    <div class="container">
      <h2>Meet the Team</h2>
      <div class="team-members">
        <div class="team-card">
          <img src="assets/team/avatar1.png" alt="Team Member 1">
          <h4>Luís Silva</h4>
          <p>Founder & Developer</p>
        </div>
        <div class="team-card">
          <img src="assets/team/avatar2.png" alt="Team Member 2">
          <h4>Ana Costa</h4>
          <p>Design & UX</p>
        </div>
        <!-- Add more team members as needed -->
      </div>
    </div>
  </section>
</body>
</html>
<?php
// Render header
drawFooter($session);
?>
