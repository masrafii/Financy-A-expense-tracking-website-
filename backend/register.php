<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check that all required fields are sent
    if (
        isset($_POST["name"]) &&
        isset($_POST["email"]) &&
        isset($_POST["password"])
    ) {
        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);


        $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "exists";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $password);
            if ($stmt->execute()) {
                echo "<script>alert('Registration successful! Redirecting to login...'); window.location.href='../frontend/login.html';</script>";
                exit;

            } else {
                echo "error";
            }
        }

        $check->close();
    } else {
        echo "Missing POST fields!";
    }

    $conn->close();
}
?>
