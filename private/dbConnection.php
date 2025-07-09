<?php
session_start();
$db_host = 'localhost'; 
$db_username = 'root'; 
$db_password = 'mysql'; 
$db_name = 'travel_tipia'; 


$conn = new mysqli($db_host, $db_username, $db_password, $db_name);


if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>