<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Set timeout period in seconds (15 minutes)
$timeout_duration = 900;

// Check if the last activity timestamp is set
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    // Last request was more than 15 minutes ago, so log out the user
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

// Update last activity timestamp
$_SESSION['last_activity'] = time();

// Database connection details
include_once('database.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="shortcut icon" href="../assets/imgs/telinkslogo.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #daddd8; /* Light Yellow */
            --secondary-color: #1c1c1c; /* Deep Blue */
            --text-color: #1c1c1c; /* Dark Gray text color for better readability */
            --z-distance: 8.519vw;
            --from-left: 1;
            --mobile-bkp: 650px;
        }

        body {
            background-color: var(--primary-color);
            color: var(--text-color);
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2, h3 {
            text-align: center;
            color: var(--secondary-color);
        }

        .container img {
            display: block;
            margin: 0 auto 20px;
            width: 100px; /* Adjust this size as needed */
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border: none;
        }

        .btn-primary:hover {
            background-color: #333333; /* Slightly darker for hover effect */
        }

        .form-label {
            font-weight: bold;
            color: var(--text-color);
        }

        .message {
            text-align: center;
            color: #28a745;
            font-weight: bold;
        }

        .logout-link {
            display: block;
            text-align: center;
            margin-bottom: 20px;
            color: #dc3545;
        }

        .sidebar {
            background-color: #f8f9fa;
            padding: 10px;
            border-right: 1px solid #ddd;
        }

        .sidebar .nav-link {
            color: var(--secondary-color);
        }

        .sidebar .nav-link.active {
            font-weight: bold;
            color: #333333;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="admin_dashboard.php">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="newsletter.php">
                                Newsletter
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="form.php">
                                Gallery (Add Image)
                            </a>
                        </li>
                        <!-- Add more links as needed -->
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container mt-4">
                    <img src="../assets/imgs/telinkslogo.png" alt="Logo">
                    <h2>Admin Dashboard</h2>
                    <a href="admin_logout.php" class="logout-link">Logout</a>
                    <!-- Your additional content here -->

