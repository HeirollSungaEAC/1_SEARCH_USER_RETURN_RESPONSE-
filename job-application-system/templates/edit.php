<?php
require_once '../models.php';
session_start(); // Make sure to start the session to track user information

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: ../index.php');
    exit;
}

$applicant = readApplicants()['querySet'];

$data = null;
foreach ($applicant as $item) {
    if ($item['id'] == $id) {
        $data = $item;
        break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming updateApplicant returns a response with a statusCode
    $response = updateApplicant($id, $_POST);

    // If update is successful, log the activity
    if ($response['statusCode'] === 200) {
        // Log activity after successful update
        $userId = $_SESSION['user_id']; // Assuming user_id is stored in session after login
        $description = "Updated applicant with ID $id: " . $_POST['first_name'] . " " . $_POST['last_name'];
        logActivity($userId, 'UPDATE', $description); // Log the update action

        header('Location: ../index.php');
        exit;
    } else {
        $error = $response['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Applicant</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h1>Edit Applicant</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($data['first_name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($data['last_name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($data['email']) ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($data['phone']) ?>" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <textarea name="address" id="address" required><?= htmlspecialchars($data['address']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="qualifications">Qualifications:</label>
            <textarea name="qualifications" id="qualifications" required><?= htmlspecialchars($data['qualifications']) ?></textarea>
        </div>

        <input type="hidden" name="action" value="update">

        <button type="submit">Update</button>

        <a href="../index.php" class="cancel-btn">Cancel</a>
    </form>
</body>
</html>
