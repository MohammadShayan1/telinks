<?php
session_start(); // Start session to store success message  

// Input validation (simple example)
$itm_name = filter_input(INPUT_POST, 'nname', FILTER_SANITIZE_STRING);
$itm_email = filter_input(INPUT_POST, 'nemail', FILTER_SANITIZE_EMAIL);

// Database connection details
$server = "localhost";
$username = "mshayan_telinksimg_db";
$password = "Q^G05I9i}H_W";
$db = "mshayan_telinksimg_db";

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
            // Email details
            $subject = "Thank you for subscribing to our newsletter!";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: TE LINKS <no-reply@telinks.live>' . "\r\n"; // Sender name and email

            // HTML email template
            $message = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Thank You for Subscribing</title>
                <style>
                    body {
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                        color: #333;
                    }
                    .email-container {
                        max-width: 600px;
                        margin: 50px auto;
                        background-color: #ffffff;
                        padding: 20px;
                        border-radius: 10px;
                        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
                    }
                    .header {
                        text-align: center;
                        padding-bottom: 20px;
                    }
                    .header img {
                        width: 80px;
                    }
                    .header h1 {
                        font-size: 24px;
                        margin: 10px 0 5px;
                        color: #0056A1; /* Blue color */
                    }
                    .header p {
                        font-size: 16px;
                        margin: 0;
                        color: #666;
                    }
                    .content {
                        text-align: center;
                        padding: 20px 0;
                    }
                    .content p {
                        font-size: 18px;
                        margin: 20px 0;
                        line-height: 1.6;
                    }
                    .button {
                        display: inline-block;
                        margin-top: 20px;
                        padding: 10px 25px;
                        font-size: 18px;
                        color: #ffffff;
                        background-color: #FFCC00; /* Yellow color */
                        text-decoration: none;
                        border-radius: 5px;
                    }
                    .footer {
                        text-align: center;
                        padding-top: 20px;
                        border-top: 1px solid #e0e0e0;
                        margin-top: 20px;
                    }
                    .footer p {
                        font-size: 14px;
                        color: #999;
                    }
                    .social-icons {
                        margin-top: 10px;
                    }
                    .social-icons a {
                        color: #0056A1; /* Blue color */
                        margin: 0 10px;
                        font-size: 24px;
                        text-decoration: none;
                    }
                </style>
            </head>
            <body>
                <div class="email-container">
                    <div class="header">
                        <img src="https://telinks.live/telinks/assets/imgs/telinkslogo.png" alt="TE LINKS Logo">
                        <h1>Thank You!</h1>
                        <p>You have successfully subscribed to our newsletter.</p>
                    </div>
                    <div class="content">
                        <p>We are thrilled to have you on board. Stay tuned for the latest updates, exclusive offers, and much more straight to your inbox.</p>
                        <a href="https://telinks.live/" class="button">Visit Our Website</a>
                    </div>
                    <div class="footer">
                        <p>Follow us on social media for more updates!</p>
                        <div class="social-icons">
                            <a href="https://www.facebook.com/te.links1" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://www.linkedin.com/company/telinksned" target="_blank"><i class="fab fa-linkedin"></i></a>
                            <a href="https://www.instagram.com/te.links" target="_blank"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </body>
            </html>';

            // Send the email
            if (mail($itm_email, $subject, $message, $headers)) {
                $_SESSION['success_message'] = '<div class="alert alert-success" role="alert">
                Your message has been sent successfully!
                </div>';
            } else {
                $_SESSION['error_message'] = '<div class="alert alert-danger" role="alert">
                There was an error sending your email, but your subscription was successful.
                </div>';
            }

            header("Location: ../../index.php?#newsletter");
            exit(); // Ensure script stops execution after redirect
        } else {
            // Log error and show user-friendly message
            error_log("Database execution error: " . $stmt->error);
            $_SESSION['error_message'] = '<div class="alert alert-danger" role="alert">
            There was an error processing your request. Please try again later.
            </div>';
            header("Location: ../../index.php?#newsletter");
            exit();
        }

        // Close the statement
        $stmt->close();
    } else {
        // Log error and show user-friendly message
        error_log("Database prepare error: " . $con->error);
        $_SESSION['error_message'] = '<div class="alert alert-danger" role="alert">
        There was an error processing your request. Please try again later.
        </div>';
        header("Location: ../../index.php?#newsletter");
        exit();
    }
}

// Close the connection
$con->close();
?>
