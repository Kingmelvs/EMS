<?php
session_start();
require_once 'db_connect.php'; // Connect to the database

// Validate and get user inputs
$name = $_POST['name'];
$username = $_POST['username'];
$password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

// Handle profile picture upload
$profilePicturePath = isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : ''; // Default to existing picture
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    $fileType = mime_content_type($_FILES['profile_picture']['tmp_name']);
    $fileSize = $_FILES['profile_picture']['size'];

    if (in_array($fileType, $allowedTypes) && $fileSize <= $maxSize) {
        $uploadDir = 'uploads/profile_pictures/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Create directory if it doesn't exist
        }

        $fileName = uniqid() . '-' . basename($_FILES['profile_picture']['name']);
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
            $profilePicturePath = $uploadPath; // Update path with the new picture
        } else {
            $_SESSION['error'] = "Failed to upload the profile picture.";
            header("Location: manage_user.php?id=" . $_SESSION['login_id'] . "&mtype=own");
            exit;
        }
    } else {
        $_SESSION['error'] = "Invalid file type or size exceeded 2MB.";
        header("Location: manage_user.php?id=" . $_SESSION['login_id'] . "&mtype=own");
        exit;
    }
}

// Update user details in the database
$userId = $_SESSION['login_id'];
$updateQuery = "UPDATE users SET name = ?, username = ?, profile_picture = ?";
$params = [$name, $username, $profilePicturePath];

if ($password) {
    $updateQuery .= ", password = ?";
    $params[] = $password;
}
$updateQuery .= " WHERE id = ?";
$params[] = $userId;

// Execute the update query
$stmt = $conn->prepare($updateQuery);
if ($stmt->execute($params)) {
    // Update session variables
    $_SESSION['login_name'] = $name;
    $_SESSION['login_username'] = $username;
    $_SESSION['profile_picture'] = $profilePicturePath;
    $_SESSION['success'] = "Account updated successfully.";
} else {
    $_SESSION['error'] = "Failed to update account.";
}

// Redirect to the manage user page
header("Location: manage_user.php?id=$userId&mtype=own");
exit;
?>
