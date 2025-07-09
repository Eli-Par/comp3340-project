<?php

require 'dbConnection.php';

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE username = ?";
$preparedStatement = $conn->prepare($query);
$preparedStatement->bind_param("s", $username);
$preparedStatement->execute();

$result = $preparedStatement->get_result();

if( $result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if(!password_verify($password, $user["password"])) {
        header('Location: /comp3340-project/login.php?error=invalid');
        exit();
    }

    $_SESSION["userId"] = $user["userId"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["bio"] = $user["bio"];
    $_SESSION["isAdmin"] = $user["isAdmin"];

    header("Location: /comp3340-project/index.php");
    exit();
}
else {
    header('Location: /comp3340-project/login.php?error=invalid');
    exit();
}

?>