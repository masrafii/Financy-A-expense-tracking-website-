<?php
require '../backend/auth_check.php';

if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = 'Masrafi';
}
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Expense Tracker - Dashboard</title>
    <link rel="stylesheet" href="../css/main_frame.css" />
    <link rel="stylesheet" href="../css/services.css" />
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
            <a href="../frontend/services.php" class="nav-link active">Services</a>
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




    <main>
        <div class="services-container">
            <div class="services-header">
                <h2>Our Services</h2>
                <p>We provide a wide range of services to help you manage your finances effectively.</p>
            </div>
            <div class="service-grid">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Expense Tracking</h3>
                    <p>Track and categorize your daily expenses to see where your money goes.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-piggy-bank"></i></div>
                    <h3>Budget Planning</h3>
                    <p>Create and manage budgets to stay on track with your financial goals.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-wallet"></i></div>
                    <h3>Income Management</h3>
                    <p>Log and monitor your income from multiple sources for a complete financial overview.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-cogs"></i></div>
                    <h3>Customizable Categories</h3>
                    <p>Personalize your expense and income categories to fit your lifestyle.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <h3>Reporting & Analytics</h3>
                    <p>Generate detailed reports and visualize your spending habits with intuitive charts.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-shield-alt"></i></div>
                    <h3>Data Security</h3>
                    <p>Your financial data is protected with state-of-the-art encryption and security measures.</p>
                </div>
            </div>
        </div>
    </main>




        <footer class="footer">
        <p>© 2024 DevTeam. All rights reserved.</p>
        <p>Designed by Masrafi & Anne</p>
    </footer>

    <script>
        function toggleDarkMode() {
            document.body.classList.toggle("dark-mode");
        }

        function toggleProfileDropdown() {
            const dropdown = document.querySelector(".profile-area .dropdown-content");
            dropdown.classList.toggle("show");
        }

        window.onclick = function(event) {
            const profileBtn = document.getElementById("profile-button");
            const dropdown = document.querySelector(".profile-area .dropdown-content");

            if (!profileBtn.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove("show");
            }
        }

        function setProfileDisplay(imageUrl = null) {
            const profileImage = document.getElementById('profileImage');
            const profileIconFallback = document.getElementById('profileIconFallback');

            if (imageUrl && imageUrl !== '') {
                profileImage.src = imageUrl;
                profileImage.style.display = 'block';
                profileIconFallback.style.display = 'none';
            } else {
                profileImage.style.display = 'none';
                profileIconFallback.style.display = 'block';
            }
        }

        setProfileDisplay();

        let lastScrollY = window.scrollY;
        const navbar = document.querySelector('.navbar');
        let ticking = false;

        function updateNavbarVisibility() {
            const currentScrollY = window.scrollY;

            if (Math.abs(currentScrollY - lastScrollY) < 10) {
                ticking = false;
                return;
            }

            if (currentScrollY > lastScrollY && currentScrollY > 100) {
                navbar.classList.remove("visible");
                navbar.classList.add("hidden");
            } else {
                navbar.classList.remove("hidden");
                navbar.classList.add("visible");
            }

            lastScrollY = currentScrollY;
            ticking = false;
        }

        window.addEventListener("scroll", () => {
            if (!ticking) {
                window.requestAnimationFrame(updateNavbarVisibility);
                ticking = true;
            }
        });
    </script>
</body>
</html>