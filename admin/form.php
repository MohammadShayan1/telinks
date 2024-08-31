<?php
// Database connection details
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_image'])) {
    $category = htmlspecialchars($_POST['category']);
    $alt_text = htmlspecialchars($_POST['alt_text']);

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "jpeg", "png", "gif");

        // Validate file type
        if (!in_array($imageFileType, $allowed_types)) {
            $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } else {
            // Ensure the uploads directory exists
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            // Generate a unique name for the file
            $target_file = $target_dir . uniqid() . "." . $imageFileType;

            // File size validation (optional)
            if ($_FILES["image"]["size"] > 5000000) { // 5MB max
                $message = "Sorry, your file is too large.";
            } else {
                // Attempt to move the uploaded file
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
                    // Detailed error message
                    $message = "Error moving uploaded file: " . error_get_last()['message'];
                    // Additional debug information
                    $message .= "<br>Target directory: " . $target_dir;
                    $message .= "<br>Target file: " . $target_file;
                    $message .= "<br>File permissions: " . substr(sprintf('%o', fileperms($target_dir)), -4);
                    $message .= "<br>Directory exists: " . (is_dir($target_dir) ? 'Yes' : 'No');
                }
            }
        }
    } else {
        // Handle file upload errors
        switch ($_FILES['image']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the maximum size.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded.";
                break;
            default:
                $message = "Unknown error occurred during file upload.";
                break;
        }
    }
}
include 'header.php';
?>

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
            <option value="climatech">Climatech</option>
            <option value="ch">Counsel Hour</option>
            <option value="sih">Self Investment Hour</option>
            <option value="olymtwo">Sports Team</option>
            <option value="iftar">Iftar Drive</option>
            <option value="plant">Plantation Drive</option>
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
$conn->close();
?>
