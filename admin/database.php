<?php
$servername = "localhost";
$username = "mshayan_telinksimg_db";
$password = "@Shayan@786";
$dbname = "mshayan_telinksimg_db";
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "telinksimg_db";
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
?>