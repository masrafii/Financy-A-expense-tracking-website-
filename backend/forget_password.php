<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $newPassword = trim($_POST["newPassword"]);
    $confirmPassword = trim($_POST["confirmPassword"]);

    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Email not found.'); window.history.back();</script>";
    } else {
        $update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $update->bind_param("ss", $newPassword, $email);

        if ($update->execute()) {
            echo "<script>alert('Password reset successful! Redirecting to login...'); window.location.href='../frontend/login.html';</script>";
            exit;
        } else {
            echo "<script>alert('Error updating password.'); window.history.back();</script>";
        }

        $update->close();
    }

    $stmt->close();
    $conn->close();
}
?>
