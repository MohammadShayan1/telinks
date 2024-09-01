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
                    .header svg {
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
                        font-size: 24px;
                        text-decoration: none;
                    }
                    .social-icons svg {
                        width: 24px;
                        height: 24px;
                        fill: #0056A1; /* Blue color from logo */
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
                    </div>
                    <div class="footer">
                        <p>Follow us on social media for more updates!</p>
                        <div class="social-icons">
                            <a href="https://www.facebook.com/te.links1" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91V141.34c0-25.35 12.42-50.06 52.24-50.06H293V6.26S268.91 0 243.78 0c-73.51 0-121.06 44.38-121.06 124.72v70.62H73.06v92.66h49.66v224h96.09V288z"/></svg>
                            </a>
                            <a href="https://www.linkedin.com/company/telinksned" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M100.28 448H7.4V148.9h92.88zm-46.14-330.7c-29.93 0-54.16-24.23-54.16-54.16S24.21 9.9 54.14 9.9 108.3 34.13 108.3 64.16s-24.24 54.14-54.16 54.14zM447.8 448h-92.71V302.4c0-34.73-12.41-58.48-43.4-58.48-23.63 0-37.64 15.89-43.84 31.24-2.26 5.5-2.82 13.21-2.82 20.95v151.88H172.21V148.9h88.94v40.86h1.28c12.41-20.6 35.89-49.94 87.55-49.94 63.99 0 111.81 41.75 111.81 131.61V448z"/></svg>
                            </a>
                            <a href="https://www.instagram.com/te.links" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9S160.5 370.9 224.1 370.9c63.6 0 114.9-51.3 114.9-114.9S287.7 141 224.1 141zm0 186.7c-39.6 0-71.8-32.2-71.8-71.8 0-39.6 32.2-71.8 71.8-71.8s71.8 32.2 71.8 71.8c0 39.6-32.2 71.8-71.8 71.8zm146.4-194.3c0 14.9-12 27-27 27-14.9 0-27-12-27-27 0-14.9 12-27 27-27 14.9 0 27 12 27 27zm76.1 27.2c-.2-54.9-4.9-97.8-28.5-121.4C396.5 7.4 353.7 2.7 298.8 2.5c-54.8-.2-73.2-.2-218.6-.2S57.1 2.5 2.2 2.7C-52.7 2.9-95.5 7.6-119.1 31.2-142.7 54.8-147.4 97.7-147.6 152.6c-.2 54.8-.2 73.2-.2 218.6s0 163.8.2 218.6c.2 54.9 4.9 97.8 28.5 121.4 23.6 23.6 66.4 28.3 121.4 28.5 54.8.2 73.2.2 218.6.2s163.8 0 218.6-.2c54.9-.2 97.8-4.9 121.4-28.5 23.6-23.6 28.3-66.4 28.5-121.4.2-54.8.2-73.2.2-218.6s0-163.8-.2-218.6zm-48.9 0c-.1 49.9-4.1 77.4-23.6 96.9-19.5 19.5-46.9 23.5-96.9 23.6-49.8.1-65.1.2-192 .2s-142.2 0-192-.2c-49.9-.1-77.4-4.1-96.9-23.6-19.5-19.5-23.5-46.9-23.6-96.9-.1-49.8-.2-65.1-.2-192s0-142.2.2-192c.1-49.9 4.1-77.4 23.6-96.9 19.5-19.5 46.9-23.5 96.9-23.6 49.8-.1 65.1-.2 192-.2s142.2 0 192 .2c49.9.1 77.4 4.1 96.9 23.6 19.5 19.5 23.5 46.9 23.6 96.9.1 49.8.2 65.1.2 192s0 142.2-.2 192z"/></svg>
                            </a>
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
