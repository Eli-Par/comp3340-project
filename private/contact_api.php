<?php
session_start();

//Check post used
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit();
}

require '../private/dbConnection.php';

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

//Check valid name
if ($name == '') {
    $errors[] = 'Name is required.';
} elseif (mb_strlen($name) > 100) {
    $errors[] = 'Name must be 100 characters or fewer.';
}

//Check valid email, set null if not
if ($email != '') {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    } elseif (mb_strlen($email) > 255) {
        $errors[] = 'Email must be 255 characters or fewer.';
    }
}
else {
    $email = null;
}

//Check valid subject
if ($subject == '') {
    $errors[] = 'Subject is required.';
} elseif (mb_strlen($subject) > 255) {
    $errors[] = 'Subject must be 255 characters or fewer.';
}

//Check valid message
if ($message == '') {
    $errors[] = 'Message is required.';
}

//Exit if any errors above
if (!empty($errors)) {
    http_response_code(400);
    echo implode("<br>", $errors);
    exit();
}

//Insert contact message and redirect
$statement = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
$statement->bind_param("ssss", $name, $email, $subject, $message);

$statement->execute();

header("Location: /comp3340-project/public_html/contact_confirmation.html");
exit();
