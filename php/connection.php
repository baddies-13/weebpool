<?php
// connection.php
$servername = "db";  // Le nom du service MySQL défini dans le docker-compose.yml
$username = "user";
$password = "userpassword";
$dbname = "resume_db";

// Crée une connexion
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Vérifie la connexion
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
