<?php

session_start();

$userId = $_SESSION["userId"];
$isAdmin = $_SESSION["isAdmin"];

//If no admin or logged in fail
if (!$isAdmin || $userId == 0) {
    echo json_encode(['success' => false, 'error' => 'Not admin or not logged in']);
    exit();
}

require_once 'dbConnection.php';

header('Content-Type: application/json');

//Query discussion posts per day
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

//Create arrays to display based on result
$dates = [];
$counts = [];
while ($row = $result->fetch_assoc()) {
    $dates[] = $row['postDate'];
    $counts[] = (int) $row['postCount'];
}

//Return stat data
echo json_encode([
    'success' => true,
    'data' => [
        'x' => $dates,
        'y' => $counts
    ]
]);

?>