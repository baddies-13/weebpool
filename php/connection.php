<?php

$servername = "db";  
$username = "user";
$password = "userpassword";
$dbname = "resume_db";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
