<?php
// Database connection details
include 'database.php';

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM newsletter WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Record deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting record: " . $conn->error . "</div>";
    }

    $stmt->close();
}

// Fetch newsletter data
$sql = "SELECT * FROM newsletter";
$result = $conn->query($sql);

include_once('header.php');
?>

<h3 class="mt-5">Newsletter Submissions</h3>
<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            $i = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $i++ . "</td>";
                echo "<td>" . $row['n_name'] . "</td>";
                echo "<td>" . $row['n_email'] . "</td>";
                echo "<td><a href='?delete_id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No submissions found.</td></tr>";
        }
        ?>
    </tbody>
</table>

<a href="newslettercsv.php" class="btn btn-primary">Download CSV</a>

<?php
include 'footer.php';
$conn->close();
?>