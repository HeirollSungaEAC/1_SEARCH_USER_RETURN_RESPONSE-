<?php
session_start();
require_once '../models.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

// Fetch applicants
$applicants = readApplicants()['querySet'];

// Handle delete request
if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = $_POST['id'];
    
    // Delete the applicant from the database
    deleteApplicant($id);
    
    // Log the deletion action
    $userId = $_SESSION['user_id'];
    $description = "Deleted applicant with ID $id";
    logActivity($userId, 'DELETE', $description);

    // Redirect to avoid resubmission on page refresh
    header("Location: list.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants List</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h1>Applicants List</h1>
    <a href="create.php">Add New Applicant</a>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Qualifications</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applicants as $applicant): ?>
                <tr>
                    <td><?= htmlspecialchars($applicant['first_name'] . ' ' . $applicant['last_name']) ?></td>
                    <td><?= htmlspecialchars($applicant['email']) ?></td>
                    <td><?= htmlspecialchars($applicant['phone']) ?></td>
                    <td><?= htmlspecialchars($applicant['address']) ?></td>
                    <td><?= htmlspecialchars($applicant['qualifications']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $applicant['id'] ?>">Edit</a>
                        <form method="post" action="list.php" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $applicant['id'] ?>">
                            <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
