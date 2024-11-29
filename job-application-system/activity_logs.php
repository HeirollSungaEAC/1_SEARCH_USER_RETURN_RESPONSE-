<?php
session_start();
include('dbConfig.php');

// Ensure the user is logged in and has admin privileges
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch activity logs
$sql = "SELECT a.id, u.username, a.action, a.description, a.timestamp FROM activity_logs a JOIN users u ON a.user_id = u.id ORDER BY a.timestamp DESC";
$stmt = $pdo->query($sql);
$logs = $stmt->fetchAll();

foreach ($logs as $log) {
    echo "{$log['timestamp']} - {$log['username']} performed {$log['action']} ({$log['description']})<br>";
}
?>
