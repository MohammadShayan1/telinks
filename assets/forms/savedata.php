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
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 293.93 293.93"><defs><style>.cls-1{fill:#1d478f;}.cls-2{fill:#f4e908;}</style></defs><g id="Layer_2" data-name="Layer 2"><path class="cls-1" d="M136.18,278.4C124.38,290,105.07,289.78,93,278a30.3,30.3,0,0,1-8.48-15.29,29.4,29.4,0,0,1-.14-11.67c.07-.28.12-.58.18-.85-2.79-7.15-8.39-15.33-16.07-22.84-7.43-7.28-15.25-12.91-21.92-16.15a31.19,31.19,0,0,1-30.81-7.62l-.21-.21c-.14-.11-.25-.25-.39-.37-.32-.32-.62-.66-.94-1a26.33,26.33,0,0,1-3.35-4.66c-.32-.55-.59-1.08-.87-1.63l0,0s0,0,0-.05c-.19-.37-.35-.71-.51-1.08L9,193.41c-.25-.62-.48-1.26-.67-1.9-.11-.35-.2-.67-.29-1-.16-.55-.28-1.08-.42-1.63a31,31,0,0,1,3.86-22.4c.22-.37.45-.74.71-1.13s.59-.87.91-1.3a27.27,27.27,0,0,1,1.84-2.21c.3-.32.59-.64.89-.94a1.81,1.81,0,0,1,.32-.3l.05-.05.39-.36a27.72,27.72,0,0,1,8.53-5.45c.39-.16.75-.3,1.14-.43.65-.23,1.31-.46,1.95-.62.35-.12.67-.19,1-.28.55-.14,1.1-.25,1.65-.37a33,33,0,0,1,14.26.55c6.65-2.43,14.17-7.16,21.19-13.69.39-.37.8-.73,1.19-1.12a76.61,76.61,0,0,0,9.56-11.44,49.87,49.87,0,0,0,5.78-11.19,4,4,0,0,0-.16-.57c-.23-.9-.37-1.68-.48-2.28a33.25,33.25,0,0,1,.25-9.83,27.72,27.72,0,0,1,8.1-15.73c10.52-10,30.38-12.11,43.15.41,12,11.76,12.2,30.69.41,42.24a29.83,29.83,0,0,1-15.45,8,31.81,31.81,0,0,1-12-.07c-.27-.07-.57-.14-.87-.19-7.25,2.6-15.48,7.91-23,15.28-7.2,7.05-12.73,14.47-15.91,20.88.25.94.48,1.86.64,2.76a35.51,35.51,0,0,1,.6,7.58,2.58,2.58,0,0,1,0,.48,3.24,3.24,0,0,1,0,.46c0,.16,0,.3,0,.41s0,.12,0,.19v.16s0,0,0,.09a.47.47,0,0,1,0,.16v.09s0,0,0,0v.11c0,.35-.11.71-.18,1.06a1.43,1.43,0,0,1-.07.37c-.09.52-.2,1-.34,1.56,2.54,6.73,7.61,14.4,14.6,21.61l1.2,1.2a82.28,82.28,0,0,0,11.87,9.58,54.36,54.36,0,0,0,11.51,5.88c.21-.07.42-.09.6-.14.92-.23,1.7-.34,2.32-.44a35.39,35.39,0,0,1,10,.42,29.86,29.86,0,0,1,16.23,8.24C146.13,246.66,148.73,266.14,136.18,278.4Z"/><path class="cls-2" d="M210.84,130.57c-11.51,11.83-30.81,12.1-43.1.59a30.21,30.21,0,0,1-8.85-15.09,29.08,29.08,0,0,1-.44-11.67c.05-.27.09-.57.16-.85-3-7.07-8.76-15.11-16.62-22.46-7.5-7-15.29-12.39-22-15.49-1,.26-1.9.49-2.82.65a37.58,37.58,0,0,1-7.7.62H109c-.19,0-.35,0-.49-.05a2.55,2.55,0,0,1-.41,0h-.18a.32.32,0,0,0-.16,0h-.09l-.19,0s-.07,0-.09,0h-.16c-.37,0-.73-.09-1.1-.16l-.37-.07c-.55-.09-1.1-.21-1.63-.35-6.74,2.53-14.37,7.52-21.43,14.43-.39.37-.78.76-1.15,1.15l0,0c-7.32,7.61-12.45,15.85-14.77,23a4.18,4.18,0,0,1,.16.58c.25.87.44,1.65.55,2.27a33.37,33.37,0,0,1,0,9.83,27.87,27.87,0,0,1-7.7,15.92c-10.27,10.2-30.06,12.82-43.13.62-12.31-11.51-13-30.41-1.45-42.24a29.79,29.79,0,0,1,15.25-8.39,31.23,31.23,0,0,1,11.92-.18c.28.05.58.09.88.16C50.41,80.51,58.51,75,65.87,67.44c7.1-7.3,12.52-14.95,15.54-21.5a29,29,0,0,1,6.47-30.23l.2-.21a3.15,3.15,0,0,1,.37-.36c.39-.39.8-.76,1.22-1.11l.41-.34.41-.35c.32-.27.67-.52,1-.78l.14-.11c.11-.09.25-.16.37-.25.48-.37,1-.69,1.51-1l1.14-.67c.72-.37,1.43-.71,2.16-1L98,9c.64-.23,1.28-.46,2-.66l1-.28c.55-.16,1.1-.3,1.63-.41A33.25,33.25,0,0,1,131,15.09h0c.2.18.41.39.62.62a11,11,0,0,1,.91.9,28.19,28.19,0,0,1,5.62,8c.18.37.34.74.5,1.13.26.62.51,1.26.72,1.9.11.32.22.65.32,1,.16.55.32,1.08.43,1.61a30.58,30.58,0,0,1,.07,14C143,50.7,148.13,58,155.08,64.87L156.3,66a80.11,80.11,0,0,0,12.1,9.3,56.66,56.66,0,0,0,11.65,5.61c.21-.07.39-.12.6-.17a22.17,22.17,0,0,1,2.29-.48,35,35,0,0,1,10,.19,30,30,0,0,1,16.42,7.85C220,98.57,223.09,118,210.84,130.57Z"/><path class="cls-2" d="M83.09,163.37c11.51-11.83,30.81-12.11,43.1-.6A30.14,30.14,0,0,1,135,177.86a29.08,29.08,0,0,1,.44,11.67c0,.28-.09.57-.16.85,3,7.08,8.76,15.12,16.62,22.47,7.5,7,15.3,12.38,22,15.48,1-.25,1.9-.48,2.82-.64a36.2,36.2,0,0,1,7.7-.62h.51l.48,0a2.49,2.49,0,0,1,.41,0H186a.34.34,0,0,0,.17,0h.09l.18,0c.05,0,.07,0,.09,0h.16c.37.05.74.09,1.1.16l.37.07c.55.09,1.1.21,1.63.35,6.74-2.53,14.37-7.51,21.44-14.43.39-.37.78-.76,1.14-1.15l0,0c7.31-7.6,12.45-15.85,14.76-23a5.5,5.5,0,0,1-.16-.58c-.25-.87-.43-1.65-.55-2.27a33.35,33.35,0,0,1,0-9.83,27.87,27.87,0,0,1,7.7-15.92c10.28-10.2,30.06-12.82,43.13-.62,12.32,11.51,13,30.41,1.45,42.24a29.79,29.79,0,0,1-15.25,8.39,31.23,31.23,0,0,1-11.92.18c-.28,0-.58-.09-.87-.16-7.18,2.78-15.27,8.29-22.63,15.85-7.11,7.31-12.52,14.95-15.55,21.5a29,29,0,0,1-6.47,30.23l-.2.21a3.23,3.23,0,0,1-.37.37c-.39.39-.8.75-1.21,1.1l-.42.34-.41.35c-.32.27-.66.53-1,.78l-.14.11c-.11.1-.25.16-.36.26-.48.36-1,.69-1.52,1l-1.14.66c-.71.37-1.42.71-2.16,1l-1.14.48c-.65.23-1.29.46-1.95.67l-1,.27c-.55.16-1.1.3-1.63.41A33.25,33.25,0,0,1,163,278.84h0a6,6,0,0,1-.62-.62,11,11,0,0,1-.92-.89,28.37,28.37,0,0,1-5.62-8c-.18-.37-.34-.73-.5-1.12-.25-.62-.51-1.27-.71-1.91-.12-.32-.23-.64-.32-1-.16-.55-.33-1.07-.44-1.6a30.76,30.76,0,0,1-.07-14c-2.75-6.5-7.93-13.83-14.88-20.68l-1.21-1.14a80.76,80.76,0,0,0-12.11-9.31,56.08,56.08,0,0,0-11.65-5.6c-.2.07-.39.11-.59.16a22.85,22.85,0,0,1-2.3.48,34.8,34.8,0,0,1-10-.18,30.14,30.14,0,0,1-16.42-7.86C73.92,195.37,70.84,176,83.09,163.37Z"/><path class="cls-1" d="M157.56,15.52c11.81-11.56,31.11-11.38,43.15.39a30.3,30.3,0,0,1,8.48,15.29,29.4,29.4,0,0,1,.14,11.67c-.07.28-.11.58-.18.85,2.79,7.15,8.39,15.33,16.07,22.84,7.43,7.28,15.25,12.91,21.92,16.15A31.21,31.21,0,0,1,278,90.33l.2.21c.14.11.25.25.39.37.32.32.62.66.94,1a25.49,25.49,0,0,1,3.35,4.66c.32.55.6,1.08.87,1.63l0,0s0,0,0,0c.18.37.34.71.5,1.08l.48,1.15c.25.62.48,1.26.67,1.9.11.35.2.67.3,1,.16.55.27,1.08.41,1.63a31.06,31.06,0,0,1-3.85,22.4l-.71,1.13c-.3.43-.6.87-.92,1.3a25.35,25.35,0,0,1-1.84,2.21c-.29.32-.59.64-.89.94a1.81,1.81,0,0,1-.32.3l0,.05c-.13.13-.27.25-.39.36a27.72,27.72,0,0,1-8.53,5.45c-.39.16-.75.3-1.14.43-.64.23-1.31.46-2,.62-.35.12-.67.19-1,.28-.55.14-1.1.25-1.65.37a33,33,0,0,1-14.26-.55c-6.65,2.43-14.17,7.16-21.19,13.69-.39.36-.8.73-1.19,1.12a76.61,76.61,0,0,0-9.56,11.44,50.29,50.29,0,0,0-5.78,11.19c.05.18.09.39.16.57.23.9.37,1.68.48,2.28a32.86,32.86,0,0,1-.25,9.83,27.76,27.76,0,0,1-8.09,15.73c-10.53,10-30.38,12.11-43.16-.41-12-11.76-12.19-30.69-.41-42.24a29.82,29.82,0,0,1,15.46-8,31.76,31.76,0,0,1,11.94.07c.28.07.57.14.87.19,7.25-2.6,15.48-7.91,23-15.28,7.2-7.05,12.73-14.47,15.92-20.88-.26-.94-.49-1.86-.65-2.76a35.38,35.38,0,0,1-.59-7.58,3.85,3.85,0,0,1,0-.48,3.24,3.24,0,0,1,0-.46c0-.16,0-.3,0-.41s0-.12,0-.19v-.16s0,0,0-.09a.47.47,0,0,1,0-.16v-.09s0,0,0,0v-.11c0-.35.11-.71.18-1.06a2,2,0,0,1,.07-.37c.09-.52.21-1.05.34-1.56-2.54-6.73-7.61-14.4-14.6-21.61l-1.19-1.2a82.34,82.34,0,0,0-11.88-9.58,54.36,54.36,0,0,0-11.51-5.88c-.21.07-.41.09-.6.14-.91.23-1.69.34-2.31.44A35.47,35.47,0,0,1,174.2,66,29.89,29.89,0,0,1,158,57.74C147.61,47.26,145,27.78,157.56,15.52Z"/></g></svg>
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
