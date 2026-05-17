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
  <link rel="stylesheet" href="../css/main_home_content.css" />
</head>
<body>


  <nav class="navbar visible">
    <div class="nav-left">
        <a href="../frontend/main_home.php" class="logo-link">
        <img src="../assets/main_logo3.png" alt="Financy Logo" class="site-logo">
        </a>
        </div>

    <div class="nav-center">
        <a href="../frontend/main_home.php" class="nav-link active">Home</a>
        <a href="../frontend/about_us.php" class="nav-link">About</a>
        <a href="services.php" class="nav-link">Services</a>
        <a href="contact.php" class="nav-link">Contact</a>
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

  <div class="root">

    <div class="summary">
      <h1>Total Balance: <span id="updateBalance">৳0</span></h1>
      <div class="total">
        <div>Income: <span id="updateIncome">৳0</span></div>
        <div class="verticle"></div>
        <div>Expense: <span id="updateExpense">৳0</span></div>
      </div>
    </div>

    <div id="items">
      <h2>Transaction History</h2>
      <form class="search-box">
        <span class="search-icon">🔍</span>
        <input type="text" placeholder="Search" name="search" />
        <button type="submit">Submit</button>
      </form>
      <div id="transaction-list" class="transaction-list"></div>
    </div>

    <div id="new">
      <h2>Add Transaction</h2>
      <form id="expenseForm">
        <div class="inputs">
          <div class="inputitem">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required />
          </div>
          <div class="inputitem">
            <label for="amount">Amount</label>
            <input type="number" id="amount" name="amount" step="0.01" required />
          </div>
          <div class="inputitem">
            <label for="category">Type</label>
            <select id="category" name="category" required>
              <option value="Income">Income</option>
              <option value="Expense">Expense</option>
            </select>
          </div>
          <div class="inputitem">
            <label for="date">Date</label>
            <input type="date" id="date" name="date" required />
          </div>
        </div>
        <button type="submit" class="add-transaction-btn">Add</button>
      </form>
    </div>
  </div>

  <footer class="footer">
    <p>© 2025 ExpenseTracker_Team. All rights reserved.</p>
    <p>Designed by Masrafi & Anne</p>
  </footer>


  <script>
    function formatDate(dateStr) {
      const date = new Date(dateStr);
      return `${String(date.getDate()).padStart(2, '0')}/${
        String(date.getMonth() + 1).padStart(2, '0')}/${date.getFullYear()}`;
    }

    function fetchData() {
      fetch("../backend/expense_handler.php?action=read")
        .then(res => res.json())
        .then(data => {
          const list = document.getElementById("transaction-list");
          list.innerHTML = "";
          let income = 0, expense = 0;

          data.forEach(item => {
            const div = document.createElement("div");
            div.classList.add("transaction-card");
            div.innerHTML = `
              <div class="transaction-title">${item.title}</div>
              <div class="transaction-details">
                <span class="amount ${item.category.toLowerCase()}">৳${parseFloat(item.amount).toFixed(2)}</span>
                <span class="type">${item.category}</span>
                <span class="date">${formatDate(item.created_at)}</span>
                <button onclick="deleteExpense(${item.id})">Delete</button>
              </div>
            `;
            list.appendChild(div);

            item.category === "Income" ? income += parseFloat(item.amount) : expense += parseFloat(item.amount);
          });

          document.getElementById("updateIncome").innerText = `৳${income.toFixed(2)}`;
          document.getElementById("updateExpense").innerText = `৳${expense.toFixed(2)}`;
          document.getElementById("updateBalance").innerText = `৳${(income - expense).toFixed(2)}`;
        })
        .catch(console.error);
    }

    function deleteExpense(id) {
      if (!confirm("Are you sure you want to delete this entry?")) return;
      fetch("../backend/expense_handler.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `action=delete&id=${id}`
      }).then(fetchData);
    }

    document.getElementById("expenseForm").addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      formData.append("action", "create");

      fetch("../backend/expense_handler.php", {
        method: "POST",
        body: new URLSearchParams(formData)
      }).then(() => {
        this.reset();
        fetchData();
      });
    });

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

    fetchData();
  </script>
</body>
</html>