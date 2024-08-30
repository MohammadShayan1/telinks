<?php

include 'header.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

    <div class="row">
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        </main>
    </div>
</div>

<?php include 'footer.php'; ?>

<?php $conn->close(); ?>
