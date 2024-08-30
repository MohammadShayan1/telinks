<?php
session_start(); // Start session to store success message  

$itm_name = $_POST['nname'];
$itm_email = $_POST['nemail'];

// Database connection details
// $servername = "localhost";
// $username = "mshayan_telinksimg_db";
// $password = "@Shayan@786";
// $dbname = "mshayan_telinksimg_db";
$server = "localhost";
$username = "root";
$password = "";
$db = "telinksimg_db";

// Create a connection
$con = new mysqli($server, $username, $password, $db);



// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} else {
    // Prepare and bind
    $stmt = $con->prepare("INSERT INTO newsletter (n_name, n_email) VALUES (?, ?)");
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ss", $itm_name, $itm_email);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success_message'] = '<div class="alert alert-success" role="alert">
Your message has been sent successfully!
</div>';
            header("Location: ../../index.php?#newsletter");
            exit(); // Ensure script stops execution after redirect
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: " . $con->error;
    }
}

// Close the connection
$con->close();

?>
