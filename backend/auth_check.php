<?php
session_start();

if (!isset($_SESSION['email'])) {
    // User not logged in
    header("Location: ../frontend/login.html");
    exit();
}
