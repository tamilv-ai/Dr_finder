<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "medpulse_tn";

$db_connected = false;
$conn = @new mysqli($host, $username, $password, $database);

if (!$conn->connect_error) {
    $conn->set_charset("utf8mb4");
    $db_connected = true;
} else {
    // Graceful fallback mode if MySQL database is not currently running
    $db_connected = false;
}
?>
