<?php

$itm_name = $_POST['nname'];
$itm_email = $_POST['nemail'];

// $server = "localhost";
// $username = "mshayan_mscontact_db";
// $password = "@Shayan@786";
// $db = "mshayan_mscontact_db";
$server = "localhost";
$username = "root";
$password = "";
$db = "telinks";

$con = mysqli_connect($server, $username, $password, $db);

    $query = "INSERT INTO newsletter(name,email) VALUES ('{$itm_name}','{$itm_email}')";
    $result = mysqli_query($con, $query);
    $done = header("Location: ../../index.php");

    mysqli_close($con);
?>