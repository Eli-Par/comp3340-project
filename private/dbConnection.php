<?php
//DB connection details
$db_host = 'localhost'; 
$db_username = 'root'; 
$db_password = 'mysql'; 
$db_name = 'travel_tipia'; 

//Connect to the database with the connection info above
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

//Check for errors
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>