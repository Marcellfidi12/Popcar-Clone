<?php
// Database connection
$dsn = 'mysql:host=localhost;dbname=mcodi3286_clickdb;charset=utf8mb4';
$username = 'mcodi3286_clickdb';
$password = 'Marcellfd321';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Fetch leaderboard data
$stmt = $pdo->query('SELECT country, SUM(score) as score FROM leaderboard GROUP BY country ORDER BY score DESC LIMIT 3');
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);