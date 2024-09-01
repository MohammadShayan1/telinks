<?php
// Start session for displaying messages
session_start();

include 'config.php'; // Include your database connection

// Get the email from the URL
$email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);

if ($email) {
    // Create a connection
    $con = new mysqli($server, $username, $password, $db);

    // Check connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Prepare and bind the statement
    $stmt = $con->prepare("DELETE FROM newsletter WHERE n_email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success_message'] = '<div class="alert alert-success" role="alert">
            You have successfully unsubscribed from our newsletter.
            </div>';
        } else {
            $_SESSION['error_message'] = '<div class="alert alert-danger" role="alert">
            There was an error processing your request. Please try again later.
            </div>';
        }

        // Close the statement
        $stmt->close();
    } else {
        $_SESSION['error_message'] = '<div class="alert alert-danger" role="alert">
        There was an error processing your request. Please try again later.
        </div>';
    }

    // Close the connection
    $con->close();
} else {
    $_SESSION['error_message'] = '<div class="alert alert-danger" role="alert">
    Invalid request.
    </div>';
}

// Redirect back to the homepage or a confirmation page
header("Location: ../../assets/forms/unsubscribepage.php");
exit();
?>
