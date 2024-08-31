<?php
// Fetch newsletter data
// Database connection details
include 'header.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT * FROM newsletter";
$result = $conn->query($sql);
?>

                    <h3 class="mt-5">Newsletter Submissions</h3>
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
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
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No submissions found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <a href="newslettercsv.php" class="btn btn-primary">Download CSV</a>
<?php include 'footer.php'; ?>



