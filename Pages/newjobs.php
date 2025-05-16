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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SkillFlow</title>
  <link rel="stylesheet" href="../css/newjobs.css">
  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="stylesheet" href="../css/footer.css">
</head>
<body>
    <main class="new-job-container">
        <div class="form-container">
            <h1>Create a New Job</h1>
            <form action="#" method="POST" class="job-form">
                <label for="jobTitle">Job Title</label>
                <input type="text" id="jobTitle" name="jobTitle" placeholder="Enter job title" required>

                <label for="jobDescription">Job Description</label>
                <textarea id="jobDescription" name="jobDescription" rows="5" placeholder="Describe the job requirements" required></textarea>

                <label for="category">Job Category</label>
                <select id="category" name="category" required>
                    <option value="Software Engineering">Software Engineering</option>
                    <option value="Hardware">Hardware</option>
                    <option value="Artificial Inteligence">Artificial Inteligence</option>
                    <option value="Data Science">Data Science</option>
                    <option value="Cybersecurity">Cybersecurity</option>
                </select>

                <label for="budget">Budget</label>
                <input type="number" id="budget" name="budget" placeholder="Enter your budget" required>

                <div class="submit-btn-container">
                    <button type="submit" class="submit-btn">Post Job</button>
                </div>
            </form>
        </div>
    </main>
<?php
// Render header
drawFooter($session);
?>