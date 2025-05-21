<?php
  require_once('../Templates/common_template.php');
  require_once(__DIR__ . '/../Utils/Session.php');
  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/serviceClass.php');
  require_once(__DIR__ . '/../database/userClass.php');
  $session = new Session();
  $db = getDatabaseConnection();

  // Get job data to display (example: from GET params or DB)
  // For demo, I assume data comes from GET query parameters (you can change it)
  $jobTitle = $_GET['jobTitle'] ?? 'Unknown Job';
  $jobDescription = $_GET['jobDescription'] ?? 'No description available.';
  $category = $_GET['category'] ?? 'Uncategorized';
  $budget = $_GET['budget'] ?? 'N/A';
  $jobId = $_GET['jobId'] ?? null;
  
  $service = Service::getService($db, $jobId);
  $applied_users = User::get_Users_By_Service($db, $jobId);

  // You could also fetch the job info by ID from DB here instead of GET params
?>

<?php drawHeader($session); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Apply to Job - SkillFlow</title>
  <link rel="stylesheet" href="../css/newjobs.css" />
  <link rel="stylesheet" href="../css/navbar.css" />
  <link rel="stylesheet" href="../css/footer.css" />
</head>
<body>
  <main class="new-job-container">
    <div class="form-container">
      <h1>My Jobs</h1>
      <div class="job-form">
        <!-- Show job info as readonly inputs or text -->

        <label for="jobTitle">Job Title</label>
        <input type="text" id="jobTitle" name="jobTitle" value="<?= htmlspecialchars($jobTitle) ?>" readonly />

        <label for="jobDescription">Job Description</label>
        <textarea id="jobDescription" name="jobDescription" rows="5" readonly><?= htmlspecialchars($jobDescription) ?></textarea>

        <label for="category">Job Category</label>
        <input type="text" id="category" name="category" value="<?= htmlspecialchars($category) ?>" readonly />

        <label for="budget">Budget</label>
        <input type="text" id="budget" name="budget" value="<?= htmlspecialchars($budget) ?>" readonly />

        <input type="hidden" name="jobId" value="<?= htmlspecialchars($_GET['jobId'] ?? '') ?>">

        <!-- Hidden input to identify job id, if needed -->
        <!-- <input type="hidden" name="jobId" value="<?= $jobId ?>" /> -->

        <section class="applied-users-container">
            <?php if (!$service->worker_id): ?>
              <h2 style="margin-bottom: 20px">Users Applied to This Job</h2>
              <?php if (!empty($applied_users)): ?>
                  <?php foreach ($applied_users as $user): ?>
                      <?php draw_user($user, $service); ?>
                  <?php endforeach; ?>
              <?php else: ?>
                  <p>No users have applied to this job yet.</p>
              <?php endif; ?>
            <?php else: ?>
              <h2 style="margin-bottom: 20px">Worker:</h2>
              <?php draw_user(User::getUser($db, $service->worker_id), $service); ?>
            <?php endif; ?>
        </section>
        <form action="../Actions/Action_Delete_Service.php" method="POST" class="job-form">
          <input type="hidden" name="jobId" value="<?= htmlspecialchars($jobId) ?>" />
          <div class="submit-btn-container">
            <button type="submit" class="submit-btn">Delete job</button>
          </div>
        </form>
      </div>
    </div>
  </main>
<?php drawFooter($session); ?>
</body>
</html>
