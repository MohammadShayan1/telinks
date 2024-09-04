<?php
// Database connection details
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_member'])) {
    $name = htmlspecialchars($_POST['name']);
    $position = htmlspecialchars($_POST['position']);
    $tenure = htmlspecialchars($_POST['tenure']);
    $linkedin = htmlspecialchars($_POST['linkedin']);

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/members/";
        $imageFileType = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
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

            // Attempt to move the uploaded file
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                // Success
                $stmt = $conn->prepare("INSERT INTO members (name, position, tenure, linkedin, profile_picture) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $name, $position, $tenure, $linkedin, $target_file);
                if ($stmt->execute()) {
                    $message = "Member added successfully!";
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
    } else {
        // Handle file upload errors
        switch ($_FILES['profile_picture']['error']) {
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

<h3 class="mt-4">Add New Member</h3>
<?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
<form method="post" action="" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="position" class="form-label">Position:</label>
        <input type="text" class="form-control" id="position" name="position" required>
    </div>
    <div class="mb-3">
        <label for="tenure" class="form-label">Tenure:</label>
        <input type="text" class="form-control" id="tenure" name="tenure" required>
    </div>
    <div class="mb-3">
        <label for="linkedin" class="form-label">LinkedIn URL:</label>
        <input type="url" class="form-control" id="linkedin" name="linkedin" required>
    </div>
    <div class="mb-3">
        <label for="profile_picture" class="form-label">Profile Picture:</label>
        <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*" required>
    </div>
    <button type="submit" name="add_member" class="btn btn-primary w-100">Add Member</button>
</form>

<?php 
include 'footer.php';
$conn->close();
?>
