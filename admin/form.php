<?php include 'header.php'; 
// Database connection details
include_once('database.php');
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_image'])) {
    $category = $_POST['category'];
    $alt_text = $_POST['alt_text'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type
        $allowed_types = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowed_types)) {
            $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Success
                $stmt = $conn->prepare("INSERT INTO images (url, category, alt_text) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $target_file, $category, $alt_text);
                if ($stmt->execute()) {
                    $message = "Image uploaded and added successfully!";
                } else {
                    $message = "Database Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = "Error moving uploaded file: " . error_get_last()['message'];
            }
        }
    } else {
        $message = "Upload Error: " . $_FILES['image']['error'];
    }
}?>

<h3 class="mt-4">Add New Image</h3>
<?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
<form method="post" action="" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="image" class="form-label">Upload Image:</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
    </div>
    <div class="mb-3">
        <label for="category" class="form-label">Category:</label>
        <select class="form-select" id="category" name="category" required>
            <option value="" disabled selected>Select a category</option>
            <option value="ch">Counsel Hour</option>
            <option value="sih">Self Investment Hour</option>
            <option value="other">Others</option>
            <!-- Add more categories as needed -->
        </select>
    </div>
    <div class="mb-3">
        <label for="alt_text" class="form-label">Alt Text:</label>
        <input type="text" class="form-control" id="alt_text" name="alt_text" required>
    </div>
    <button type="submit" name="add_image" class="btn btn-primary w-100">Add Image</button>
</form>
<?php 
include 'footer.php';
 ?>
