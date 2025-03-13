<?php 
$conn = new mysqli('localhost', 'root', '', 'event_db') or die("Could not connect to mysql" . mysqli_error($con));

// Set the character set to utf8mb4 for proper encoding
$conn->set_charset('utf8mb4');
?>
