
<?php
require '../backend/auth_check.php';
require '../backend/config.php';


$email = $_SESSION['email'];

if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = 'Masrafi';
}
$username = $_SESSION['username'];


// Fetch user data from the database
$stmt = $conn->prepare("SELECT name, email FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $name = $user['name'];
    $userEmail = $user['email'];
} else {
    // Handle error or redirect if user is not found
    $name = "Guest";
    $userEmail = "N/A";
}



?>    





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Expense Tracker - Dashboard</title>
    <link rel="stylesheet" href="../css/main_frame.css" />
    <link rel="stylesheet" href="../css/profile.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <nav class="navbar visible">
        <div class="nav-left">
        <a href="../frontend/main_home.php" class="logo-link">
        <img src="../assets/main_logo3.png" alt="Financy Logo" class="site-logo">
        </a>
        </div>

        <div class="nav-center">
            <a href="../frontend/main_home.php" class="nav-link">Home</a>
            <a href="../frontend/about_us.php" class="nav-link">About</a>
            <a href="../frontend/services.php" class="nav-link">Services</a>
            <a href="../frontend/contact.php" class="nav-link">Contact</a>
        </div>

                <div class="nav-right">
            <button class="dark-toggle" onclick="toggleDarkMode()" title="Toggle Dark Mode">
                <span class="icon-moon"></span>
            </button>

            <div class="profile-area relative">
                <button id="profile-button" class="profile-button-custom" onclick="toggleProfileDropdown()">
                    <img id="profileImage" class="profile-avatar-img" src="" alt="Profile" style="display: none;">
                    <span id="profileIconFallback" class="profile-icon-fallback-custom">P</span>
                    <span class="greeting-text-custom">Hi, <?php echo htmlspecialchars($username); ?>!</span>
                    <svg class="dropdown-arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="dropdown-content">
                    <a href="profile.php" class="dropdown-item">
                        <span class="emoji-icon">👤</span> Profile
                    </a>
                    <a href="#" class="dropdown-item">
                        <span class="emoji-icon">🗓️</span> Calendar
                    </a>
                    <a href="about_us.php" class="dropdown-item">
                        <span class="emoji-icon">ℹ️</span> About Us
                    </a>
                    <a href="../backend/logout.php" class="dropdown-item logout-item">
                        <span class="emoji-icon">🚪</span> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>




    <main class="profile-main">
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-avatar-large">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h1><?php echo htmlspecialchars($name); ?></h1>
                <p class="user-email"><?php echo htmlspecialchars($userEmail); ?></p>
            </div>

            <div class="profile-details">
                <div class="detail-card">
                    <h3 class="card-title">User Information</h3>
                    <form id="profile-form" action="../backend/update_profile.php" method="POST">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>" readonly>
                        </div>
                        <button type="submit" class="submit-button">Update Profile</button>
                    </form>
                </div>

                <div class="detail-card">
                    <h3 class="card-title">Change Password</h3>
                    <form id="password-form" action="../backend/update_profile.php" method="POST">
                        <div class="form-group">
                            <label for="current-password">Current Password</label>
                            <input type="password" id="current-password" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new-password">New Password</label>
                            <input type="password" id="new-password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">Confirm New Password</label>
                            <input type="password" id="confirm-password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="submit-button">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </main>


   
  <footer class="footer">
    <p>© 2025 ExpenseTracker_Team. All rights reserved.</p>
    <p>Designed by Masrafi & Anne</p>
  </footer>

<script>
function toggleProfileDropdown() {
    const dropdown = document.querySelector(".dropdown-content");
    dropdown.classList.toggle("show"); 
}

// Optional: close dropdown when clicking outside
window.addEventListener("click", function(e) {
    const profileBtn = document.getElementById("profile-button");
    const dropdown = document.querySelector(".dropdown-content");

    if (!profileBtn.contains(e.target)) {
        dropdown.classList.remove("show");
    }
});

let lastScrollY = window.scrollY;
const navbar = document.querySelector(".navbar");

window.addEventListener("scroll", () => {
  if (window.scrollY > lastScrollY) {
    // scrolling down
    navbar.classList.remove("visible");
    navbar.classList.add("hidden");
  } else {
    // scrolling up
    navbar.classList.remove("hidden");
    navbar.classList.add("visible");
  }
  lastScrollY = window.scrollY;
});

</script>



</body>
</html>