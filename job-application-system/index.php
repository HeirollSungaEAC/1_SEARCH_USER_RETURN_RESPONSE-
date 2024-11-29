<?php
require_once 'models.php';
session_start();  // Start the session to track user actions

$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $response = searchApplicants($searchQuery);
    
    // Log the search query if the user is logged in
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $description = "User searched for: " . htmlspecialchars($searchQuery);
        logActivity($userId, 'SEARCH', $description);
    }
} else {
    $response = readApplicants();
}

$applicants = $response['querySet'] ?? [];

if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
    deleteApplicant($_POST['id']);
    
    // Log the deletion action if the user is logged in
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $description = "User deleted applicant with ID: " . $_POST['id'];
        logActivity($userId, 'DELETE', $description);
    }

    // Redirect to index.php after deletion
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Job Application System</h1>
    
    <!-- Check if the user is logged in -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>! <a href="logout.php">Logout</a></p>
    <?php else: ?>
        <p><a href="login.php">Login</a> | <a href="register.php">Register</a></p>
    <?php endif; ?>

    <!-- Show the "Add New Applicant" link if the user is logged in -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="templates/create.php">Add New Applicant</a>
    <?php endif; ?>

    <!-- Search Form -->
    <form method="get" action="index.php">
        <input type="text" name="search" placeholder="Search applicants..." value="<?= htmlspecialchars($searchQuery) ?>">
        <button type="submit">Search</button>
    </form>

    <?php if ($response['statusCode'] !== 200): ?>
        <p style="color: red;">Error: <?= htmlspecialchars($response['message']) ?></p>
    <?php elseif (empty($applicants)): ?>
        <p>No applicants found.</p>
    <?php else: ?>
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
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="templates/edit.php?id=<?= $applicant['id'] ?>">Edit</a>
                                <form method="post" action="index.php" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $applicant['id'] ?>">
                                    <button type="submit">Delete</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
