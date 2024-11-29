<?php
session_start(); // Start the session to check if the user is logged in
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../models.php';

    // Check if user is logged in before proceeding
    if (!isset($_SESSION['user_id'])) {
        $error = "You must be logged in to add an applicant.";
    } else {
        // Proceed with creating the applicant if logged in
        $response = createApplicant($_POST);
        
        // Log the activity if the applicant is created successfully
        if ($response['statusCode'] === 200) {
            // Log the action in the activity log
            $userId = $_SESSION['user_id']; // Assuming user ID is stored in the session
            $description = "Inserted a new applicant: " . htmlspecialchars($_POST['first_name']) . " " . htmlspecialchars($_POST['last_name']);
            logActivity($userId, 'INSERT', $description);
            
            // Redirect to the index page after insertion
            header('Location: ../index.php');
            exit;
        } else {
            // Show error if creation fails
            $error = $response['message'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Applicant</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h1>Add New Applicant</h1>

    <!-- Display error if any -->
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Form to create a new applicant -->
    <form action="create.php" method="post">
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>
        
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" required></textarea>
        </div>

        <div class="form-group">
            <label for="qualifications">Qualifications</label>
            <textarea id="qualifications" name="qualifications" required></textarea>
        </div>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
