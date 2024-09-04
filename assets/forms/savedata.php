<?php
session_start(); // Start session to store messages

// Input validation
$itm_name = filter_input(INPUT_POST, 'nname', FILTER_SANITIZE_STRING);
$itm_email = filter_input(INPUT_POST, 'nemail', FILTER_SANITIZE_EMAIL);

include 'config.php';

// Create a connection
$con = new mysqli($server, $username, $password, $db);

// Check connection
if ($con->connect_error) {
    $_SESSION['error_message'] = 'Connection failed: ' . $con->connect_error;
    header("Location: ../../index.php?#newsletter");
    exit();
}

// Check if the email already exists in the newsletter table
$stmt = $con->prepare("SELECT n_email FROM newsletter WHERE n_email = ?");
if ($stmt) {
    $stmt->bind_param("s", $itm_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email already exists, do not add again
        $_SESSION['error_message'] = 'This email is already subscribed to the newsletter.';
        header("Location: ../../index.php?#newsletter");
        exit();
    } else {
        $stmt->close();

        // Prepare and bind for insertion
        $stmt = $con->prepare("INSERT INTO newsletter (n_name, n_email) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("ss", $itm_name, $itm_email);

            if ($stmt->execute()) {
                // Prepare email details
                $subject = "Thank you for subscribing to our newsletter!";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: TE LINKS <no-reply@telinks.live>' . "\r\n"; // Update as needed

                // HTML email template
                $message = <<<HTML
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Thank You for Subscribing</title>
                    <style>
                        body {background-color: #f4f4f4; margin: 0; padding: 0; color: #333;}
                        .email-container {max-width: 600px; margin: 50px auto; background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);}
                        .header {text-align: center; padding-bottom: 20px;}
                        .header img {width: 80px;}
                        .header h1 {font-size: 24px; margin: 10px 0 5px; color: #0056A1;}
                        .header p {font-size: 16px; margin: 0; color: #666;}
                        .content {text-align: center; padding: 20px 0;}
                        .content p {font-size: 18px; margin: 20px 0; line-height: 1.6;}
                        .button {display: inline-block; margin-top: 20px; padding: 10px 25px; font-size: 18px; color: #ffffff; background-color: #FFCC00; text-decoration: none; border-radius: 5px;}
                        .footer {text-align: center; padding-top: 20px; border-top: 1px solid #e0e0e0; margin-top: 20px;}
                        .footer p {font-size: 14px; color: #999;}
                        .social-icons {margin-top: 10px;}
                        .social-icons a {margin: 0 10px; text-decoration: none;}
                        .social-icons img {width: 24px; height: 24px;}
                    </style>
                </head>
                <body>
                    <div class="email-container">
                        <div class="header">
                            <img src="https://telinks.live/telinks/assets/imgs/telinkslogo.png" alt="logo">
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
                    </div>
                </body>
                </html>
                HTML;

                // Send the email
                if (mail($itm_email, $subject, $message, $headers)) {
                    $_SESSION['success_message'] = 'Your message has been sent successfully!';
                } else {
                    $_SESSION['error_message'] = 'There was an error sending your email, but your subscription was successful.';
                }

                header("Location: ../../index.php?#newsletter");
                exit();
            } else {
                error_log("Database execution error: " . $stmt->error);
                $_SESSION['error_message'] = 'There was an error processing your request. Please try again later.';
                header("Location: ../../index.php?#newsletter");
                exit();
            }

            $stmt->close();
        } else {
            error_log("Database prepare error: " . $con->error);
            $_SESSION['error_message'] = 'There was an error processing your request. Please try again later.';
            header("Location: ../../index.php?#newsletter");
            exit();
        }
    }
} else {
    error_log("Database prepare error: " . $con->error);
    $_SESSION['error_message'] = 'There was an error processing your request. Please try again later.';
    header("Location: ../../index.php?#newsletter");
    exit();
}

$con->close();
?>
