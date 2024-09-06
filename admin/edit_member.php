<?php
include 'database.php';

// Check if the member ID is provided in the URL
if (isset($_GET['id'])) {
    $member_id = $_GET['id'];

    // Fetch the member's data
    $stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();
    $stmt->close();

    // If the member does not exist, redirect back
    if (!$member) {
        echo "<div class='alert alert-danger'>Member not found.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>No member ID provided.</div>";
    exit;
}

// Handle the form submission for updating the member
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_member'])) {
    $name = htmlspecialchars($_POST['name']);
    $position = htmlspecialchars($_POST['position']);
    $tenure = htmlspecialchars($_POST['tenure']);
    $linkedin = htmlspecialchars($_POST['linkedin']);
    $profile_picture = $member['profile_picture']; // Keep the old picture by default

    // Handle the profile picture update if a new file is uploaded
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/members/";
        $imageFileType = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "jpeg", "png", "gif");

        // Validate file type
        if (in_array($imageFileType, $allowed_types)) {
            if ($_FILES["profile_picture"]["size"] <= 2000000) { // 2MB max size
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }

                // Generate a unique name for the new file
                $target_file = $target_dir . uniqid() . "." . $imageFileType;

                // Attempt to move the uploaded file
                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                    // Delete the old profile picture if a new one is uploaded
                    if (file_exists($profile_picture)) {
                        unlink($profile_picture);
                    }
                    $profile_picture = $target_file;
                } else {
                    $message = "<div class='alert alert-danger'>Error uploading new profile picture.</div>";
                }
            } else {
                $message = "<div class='alert alert-danger'>File is too large. Maximum allowed size is 2MB.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.</div>";
        }
    }

    // Update the member details in the database
    $stmt = $conn->prepare("UPDATE members SET name = ?, position = ?, tenure = ?, linkedin = ?, profile_picture = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $name, $position, $tenure, $linkedin, $profile_picture, $member_id);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Member updated successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Database Error: " . $stmt->error . "</div>";
    }
    $stmt->close();

    // Fetch the updated member data
    $stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();
    $stmt->close();
}

include 'header.php';
?>

<h3 class="mt-4">Edit Member</h3>

<?php if (isset($message)) echo $message; ?>

<form method="post" action="" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $member['name']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="position" class="form-label">Position:</label>
        <input type="text" class="form-control" id="position" name="position" value="<?php echo $member['position']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="tenure" class="form-label">Tenure:</label>
        <input type="text" class="form-control" id="tenure" name="tenure" value="<?php echo $member['tenure']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="linkedin" class="form-label">LinkedIn URL:</label>
        <input type="url" class="form-control" id="linkedin" name="linkedin" value="<?php echo $member['linkedin']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="profile_picture" class="form-label">Profile Picture:</label><br>
        <img src="<?php echo $member['profile_picture']; ?>" width="100" height="100" alt="Profile Picture"><br>
        <input type="file" class="form-control mt-2" id="profile_picture" name="profile_picture" accept="image/*">
    </div>
    <button type="submit" name="edit_member" class="btn btn-primary w-100">Update Member</button>
</form>

<?php 
include 'footer.php';
$conn->close();
?>