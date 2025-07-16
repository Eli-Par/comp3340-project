<?php

session_start();

$userId = $_SESSION["userId"];
$isAdmin = $_SESSION["isAdmin"];

if (!$isAdmin || $userId == 0) {
    echo json_encode(['success' => false, 'error' => 'Not admin or not logged in']);
    exit();
}

require 'dbConnection.php';

header('Content-Type: application/json');

$query = "SELECT 
        DATE(dateCreated) AS postDate,
        COUNT(*) AS postCount
    FROM discussion
    GROUP BY DATE(dateCreated)
    ORDER BY postDate ASC
";
$result = $conn->query($query);

if (!$result) {
    echo json_encode(['success' => false, 'error' => $conn->error]);
    exit();
}

$dates = [];
$counts = [];

while ($row = $result->fetch_assoc()) {
    $dates[] = $row['postDate'];
    $counts[] = (int) $row['postCount'];
}

echo json_encode([
    'success' => true,
    'data' => [
        'x' => $dates,
        'y' => $counts
    ]
]);

?>