<?php
session_start();
require 'config.php';

if (!isset($_SESSION['email'])) {
    die("Unauthorized access");
}

$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the request is for updating the name
    if (isset($_POST['name'])) {
        $newName = trim($_POST['name']);
        
        $stmt = $conn->prepare("UPDATE users SET name = ? WHERE email = ?");
        $stmt->bind_param("ss", $newName, $email);

        if ($stmt->execute()) {
            echo "<script>alert('Name updated successfully!'); window.location.href='../frontend/profile.php';</script>";
        } else {
            echo "<script>alert('Error updating name.'); window.location.href='../frontend/profile.php';</script>";
        }
        $stmt->close();

    // Check if the request is for updating the password
    } elseif (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $currentPassword = trim($_POST['current_password']);
        $newPassword = trim($_POST['new_password']);
        $confirmPassword = trim($_POST['confirm_password']);

        if ($newPassword !== $confirmPassword) {
            echo "<script>alert('New passwords do not match.'); window.location.href='../frontend/profile.php';</script>";
            exit;
        }

        // Verify current password
        $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && $currentPassword === $user['password']) {
            // Update the password
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $updateStmt->bind_param("ss", $newPassword, $email);
            
            if ($updateStmt->execute()) {
                echo "<script>alert('Password updated successfully!'); window.location.href='../frontend/profile.php';</script>";
            } else {
                echo "<script>alert('Error updating password.'); window.location.href='../frontend/profile.php';</script>";
            }
            $updateStmt->close();
        } else {
            echo "<script>alert('Incorrect current password.'); window.location.href='../frontend/profile.php';</script>";
        }
    }
}
$conn->close();
?>