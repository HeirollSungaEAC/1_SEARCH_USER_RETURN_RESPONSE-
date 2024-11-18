<?php
$host = '127.0.0.1'; // Change this if you're using a different host
$db = 'job_application_system'; // Database name
$user = 'root'; // Default XAMPP user
$pass = ''; // Default XAMPP password is blank
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Error: Could not connect to the database. " . $e->getMessage());
}