<?php 
session_start();

header('Content-Type: application/json');

$isAdmin = $_SESSION['isAdmin'] ?? 0;

// Check that user is admin
if (!$isAdmin) {
    http_response_code(403);
    echo json_encode(['error'=> 'Unauthorized']);
    exit;
}

// Validate that POST input is valid
if ($_SERVER['REQUEST_METHOD'] !='POST' || !isset($_POST['theme'])) {
    http_response_code(400);
    echo json_encode(['error'=> 'Invalid request']);
    exit;
}

$themeName = trim($_POST['theme']);

require_once 'dbConnection.php';

try {
    // Check if a theme record already exists
    $result =$conn->query("SELECT id FROM themes LIMIT 1");

    if ($result->num_rows > 0) {
        // Update existing theme
        $row =$result->fetch_assoc();
        $stmt =$conn->prepare("UPDATE themes SET theme = ? WHERE id = ?");
        $stmt->bind_param('si', $themeName, $row['id']);
        $stmt->execute();
    }
    else {
        // Insert new theme
        $stmt =$conn->prepare("INSERT INTO themes (theme) VALUES (?)");
        $stmt->bind_param('s', $themeName);
        $stmt->execute();
    }

    echo json_encode(['success'=> true, 'theme'=> $themeName]);

}
catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error'=> 'Database error']);
    exit;
}
