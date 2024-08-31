<?php
// Set headers to force download
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=newsletter.csv');

include_once('database.php');

// Query to fetch data
$sql = "SELECT n_name, n_email FROM newsletter";
$result = $conn->query($sql);

// Open output stream
$output = fopen('php://output', 'w');

// Output CSV headers
fputcsv($output, ['Name', 'Email']); // Change as per your columns

// Output data rows
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

// Close the output stream
fclose($output);
$conn->close();
exit();
?>
