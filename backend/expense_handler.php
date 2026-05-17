<?php
session_start();
require 'config.php';

if (!isset($_SESSION['email'])) {
    die("Unauthorized access");
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    // 🔹 CREATE: Insert new expense with manual date
    case 'create':
        $title = trim($_POST['title']);
        $amount = floatval($_POST['amount']);
        $category = $_POST['category'];
        $date = $_POST['date']; // 🔹 Accept date from form (YYYY-MM-DD)
        $email = $_SESSION['email'];

        $stmt = $conn->prepare("INSERT INTO expenses (user_email, title, amount, category, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $email, $title, $amount, $category, $date);

        echo $stmt->execute() ? "success" : "error";
        $stmt->close();
        break;

    // 🔹 READ: Fetch all user expenses
    case 'read':
        $email = $_SESSION['email'];
        $stmt = $conn->prepare("SELECT * FROM expenses WHERE user_email = ? ORDER BY created_at DESC");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
        $stmt->close();
        break;

    // 🔹 UPDATE: Update specific expense
    case 'update':
        $id = intval($_POST['id']);
        $title = trim($_POST['title']);
        $amount = floatval($_POST['amount']);
        $category = $_POST['category'];
        $email = $_SESSION['email'];

        $stmt = $conn->prepare("UPDATE expenses SET title = ?, amount = ?, category = ? WHERE id = ? AND user_email = ?");
        $stmt->bind_param("sdsis", $title, $amount, $category, $id, $email);

        echo $stmt->execute() ? "updated" : "error";
        $stmt->close();
        break;

    // 🔹 DELETE: Remove specific expense
    case 'delete':
        $id = intval($_POST['id']);
        $email = $_SESSION['email'];

        $stmt = $conn->prepare("DELETE FROM expenses WHERE id = ? AND user_email = ?");
        $stmt->bind_param("is", $id, $email);

        echo $stmt->execute() ? "deleted" : "error";
        $stmt->close();
        break;

    default:
        echo "Invalid action";
}

$conn->close();
?>
