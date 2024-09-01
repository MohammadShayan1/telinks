<?php
include ('../../header.php');
include ('unsubscribe.php');
if (isset($_SESSION['success_message'])) {
    echo $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}
include ('../../footer.php');
?>
