<?php
session_start(); // Start session to store success message  

// Input validation (simple example)
$itm_name = filter_input(INPUT_POST, 'nname', FILTER_SANITIZE_STRING);
$itm_email = filter_input(INPUT_POST, 'nemail', FILTER_SANITIZE_EMAIL);

include 'config.php';
// Create a connection
$con = new mysqli($server, $username, $password, $db);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} else {
    // Check if the email already exists in the newsletter table
    $stmt = $con->prepare("SELECT n_email FROM newsletter WHERE n_email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $itm_email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            // Email already exists, do not add again
            $_SESSION['error_message'] = '<div class="alert alert-danger" role="alert">
            This email is already subscribed to the newsletter.
            </div>';
            header("Location: ../../index.php?#newsletter");
            exit();
        } else {
            // Prepare and bind for insertion
            $stmt->close();
            $stmt = $con->prepare("INSERT INTO newsletter (n_name, n_email) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param("ss", $itm_name, $itm_email);

                // Execute the statement
                if ($stmt->execute()) {
                    // Email details
                    $subject = "Thank you for subscribing to our newsletter!";
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'From: TE LINKS <no-reply@telinks.live>' . "\r\n"; // Change this to your desired sender

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
                                color: #0056A1; /* Blue color from logo */
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
                                background-color: #FFCC00; /* Yellow color from logo */
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
                                margin: 0 10px;
                                text-decoration: none;
                            }
                            .social-icons img {
                                width: 24px;
                                height: 24px;
                            }
                            .unsubscribe-link {
                                color: #999;
                                text-decoration: none;
                                font-size: 12px;
                            }
                            .unsubscribe-link:hover {
                                text-decoration: underline;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="email-container">
                            <div class="header">
                                <h1>Thank You!</h1>
                                <p>You have successfully subscribed to our newsletter.</p>
                            </div>
                            <div class="content">
                                <p>We are thrilled to have you on board. Stay tuned for the latest updates, exclusive offers, and much more straight to your inbox.</p>
                                <a href="https://telinks.live/" class="button">Visit Our Website</a>
                                <p>Follow us on social media for more updates!</p>
                                <div class="social-icons">
                                    <a href="https://www.facebook.com/te.links1" target="_blank">
                                        <img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" alt="Facebook">
                                    </a>
                                    <a href="https://www.linkedin.com/company/telinksned" target="_blank">
                                        <img src="https://cdn-icons-png.flaticon.com/512/124/124011.png" alt="LinkedIn">
                                    </a>
                                    <a href="https://www.instagram.com/te.links" target="_blank">
                                        <img src="https://cdn-icons-png.flaticon.com/512/1409/1409946.png" alt="Instagram">
                                    </a>
                                </div>
                            </div>
                            <div class="footer">
                                <p>If you no longer wish to receive our emails, you can <a href="https://telinks.live/telinks/assets/forms/unsubscribe.php?email=' . urlencode($itm_email) . '" class="unsubscribe-link">unsubscribe</a> at any time.</p>
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
