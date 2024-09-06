<?php
include '../admin/database.php';

$message = ""; // Message for success or error
$edit_mode = false; // Track if we are in "edit mode"

// Handle adding a new member
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
            $message = "<div class='alert alert-danger'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
        } else {
            // Validate file size (2MB max)
            if ($_FILES["profile_picture"]["size"] > 2000000) {
                $message = "<div class='alert alert-danger'>File is too large. Maximum allowed size is 2MB.</div>";
            } else {
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }
                $target_file = $target_dir . uniqid() . "." . $imageFileType;
                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                    $stmt = $conn->prepare("INSERT INTO members (name, position, tenure, linkedin, profile_picture) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $name, $position, $tenure, $linkedin, $target_file);
                    if ($stmt->execute()) {
                        $message = "<div class='alert alert-success'>Member added successfully!</div>";
                        $last_inserted_id = $conn->insert_id; // Get ID of the newly added member
                        $edit_mode = true; // Enable edit mode
                    } else {
                        $message = "<div class='alert alert-danger'>Database Error: " . $stmt->error . "</div>";
                    }
                    $stmt->close();
                } else {
                    $message = "<div class='alert alert-danger'>Error moving uploaded file.</div>";
                }
            }
        }
    }
}

// Handle updating the member
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_member'])) {
    $id = htmlspecialchars($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $position = htmlspecialchars($_POST['position']);
    $tenure = htmlspecialchars($_POST['tenure']);
    $linkedin = htmlspecialchars($_POST['linkedin']);
    $profile_picture = $_POST['existing_profile_picture']; // Use existing profile picture

    // Handle profile picture update if a new file is uploaded
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/members/";
        $imageFileType = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "jpeg", "png", "gif");

        // Validate file type
        if (!in_array($imageFileType, $allowed_types)) {
            $message = "<div class='alert alert-danger'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
        } else {
            // Validate file size (2MB max)
            if ($_FILES["profile_picture"]["size"] > 2000000) {
                $message = "<div class='alert alert-danger'>File is too large. Maximum allowed size is 2MB.</div>";
            } else {
                $target_file = $target_dir . uniqid() . "." . $imageFileType;
                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                    $profile_picture = $target_file; // Update profile picture path
                } else {
                    $message = "<div class='alert alert-danger'>Error moving uploaded file.</div>";
                }
            }
        }
    }

    // Update the member data
    $stmt = $conn->prepare("UPDATE members SET name = ?, position = ?, tenure = ?, linkedin = ?, profile_picture = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $name, $position, $tenure, $linkedin, $profile_picture, $id);
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Member updated successfully!</div>";
        $edit_mode = true;
    } else {
        $message = "<div class='alert alert-danger'>Error updating member: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Fetch the member's data if we are in edit mode
if ($edit_mode) {
    $result = $conn->query("SELECT * FROM members WHERE id = $last_inserted_id");
    $member = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Edit Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Logo centered at the top -->
<div class="text-center my-4">
    <img src="../assets/imgs/telinkslogo.png" alt="Logo" width="150">
</div>

<div class="container">
    <?php if (!$edit_mode): ?>
        <h3 class="text-center">Add Alumni</h3>
    <?php else: ?>
        <h3 class="text-center">Edit Details</h3>
    <?php endif; ?>

    <?php if (isset($message)) echo $message; ?>

    <!-- Add/Edit Member Form -->
    <form method="post" action="" enctype="multipart/form-data">
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $edit_mode ? $member['name'] : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="position" class="form-label">Position:</label>
            <input type="text" class="form-control" id="position" name="position" value="<?php echo $edit_mode ? $member['position'] : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="tenure" class="form-label">Tenure:</label>
            <input type="text" class="form-control" id="tenure" name="tenure" value="<?php echo $edit_mode ? $member['tenure'] : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="linkedin" class="form-label">LinkedIn URL:</label>
            <input type="url" class="form-control" id="linkedin" name="linkedin" value="<?php echo $edit_mode ? $member['linkedin'] : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="profile_picture" class="form-label">Profile Picture:</label>
            <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="existing_profile_picture" value="<?php echo $member['profile_picture']; ?>">
                <img src="<?php echo $member['profile_picture']; ?>" width="50" height="50" class="mt-2">
            <?php endif; ?>
        </div>
        <?php if ($edit_mode): ?>
            <button type="submit" name="edit_member" class="btn btn-primary w-100">Update Member</button>
        <?php else: ?>
            <button type="submit" name="add_member" class="btn btn-primary w-100">Add Member</button>
        <?php endif; ?>
    </form>
</div>
<?php
include_once ('../admin/footer.php');
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
