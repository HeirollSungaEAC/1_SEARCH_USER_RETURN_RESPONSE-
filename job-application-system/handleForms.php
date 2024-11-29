<?php
session_start();
require_once 'models.php';
include('dbConfig.php');

$response = []; // Initialize response array to prevent undefined variable error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    
    // Check if the user is logged in for actions requiring authentication
    if (!isset($_SESSION['user_id']) && $action !== 'login' && $action !== 'register') {
        echo json_encode(['error' => 'User not logged in']);
        exit;
    }

    // Handle login and registration actions
    switch ($action) {
        case 'register':
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Check if the username already exists
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username]);
            $existingUser = $stmt->fetch();

            if ($existingUser) {
                // Username already exists
                $response = ['error' => 'Username already exists'];
            } else {
                // Hash the password before storing
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert the user into the database
                $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$username, $hashedPassword]);

                // Redirect to index.php after successful registration
                header("Location: index.php");
                exit;
            }
            break;

        case 'login':
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Verify the credentials
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Redirect to the index.php (or homepage/dashboard) after successful login
                header("Location: index.php");
                exit;
            } else {
                $response = ['error' => 'Invalid credentials'];
            }
            break;

        case 'logout':
            session_destroy();
            $response = ['success' => 'Logged out successfully'];
            break;

        // Applicant actions: create, update, delete
        case 'create':
            $response = createApplicant($_POST);
            logActivity('INSERT', 'Inserted new applicant: ' . $_POST['name']);
            break;

        case 'update':
            $response = updateApplicant($_POST['id'], $_POST);
            logActivity('UPDATE', 'Updated applicant ID ' . $_POST['id']);
            break;

        case 'delete':
            $response = deleteApplicant($_POST['id']);
            logActivity('DELETE', 'Deleted applicant ID ' . $_POST['id']);
            break;

        default:
            $response = ['error' => 'Invalid action'];
            break;
    }

    // Set the header to indicate JSON response and return the response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Function to log activities (insertion, update, delete)
?>
