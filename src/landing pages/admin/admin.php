<?php
    require_once '../../connections and functions/dbconn.php';
    $conn = connect();
    session_start();
    $isLoggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
    $displayName = isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8') : 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Document</title>
  <link rel="stylesheet" href="../../../../dist/output.css"/>
</head>
<body class="flex min-h-screen bg-gray-50">

  <!-- Sidebar (collapsed by default) -->
  <div id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-gray-900 text-white transform -translate-x-full transition-transform duration-300 z-50">
    <div class="p-6 font-bold text-xl border-b border-gray-700">Admin Menu</div>
    <ul class="p-4 space-y-4">
      <li><a href="#" class="block hover:text-gray-300">Dashboard</a></li>
      <li><a href="#" class="block hover:text-gray-300">Courses</a></li>
      <li><a href="#" class="block hover:text-gray-300">Students</a></li>
      <li><a href="#" class="block hover:text-gray-300">Professors</a></li>
      <li><a href="#" class="block hover:text-gray-300">Settings</a></li>
    </ul>
  </div>

  <!-- Main Content (default full width, shifts if sidebar opens) -->
  <div id="mainContent" class="flex-1 transition-all duration-300">

    <!-- Top Bar -->
    <div class="shadow-md sticky top-0 w-full p-4 flex items-center justify-between bg-white z-40">
      <div class="flex items-center space-x-10">
        <img id="menuToggle" src="../../../imgs/menu-hamburger-navigation-512-4184062329.png" alt="Menu" class="h-6 w-auto object-scale-down pl-4 cursor-pointer">
        <a href="#" class="flex items-center space-x-2">
          <img src="../../../imgs/cc.png" alt="Logo" class="h-10 w-auto object-scale-down">
          <span class="text-lg font-semibold text-gray-800">Learning Management System (Admin's Side)</span>
        </a>
    </div>

      <!-- Username Dropdown -->
    <div class="relative pr-7">
        <button id="dropdownToggle" class="flex items-center space-x-2 text-black font-medium hover:text-gray-500 focus:outline-none">
          <span><?php echo $displayName; ?>'s Account</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-10">
          <ul class="py-2 text-sm text-gray-700">
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">View Profile</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Settings</a></li>
            <li><a href="../../security/logout.php" class="block px-4 py-2 hover:bg-gray-100 text-red-500">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Announcements Section -->
    <div class="flex justify-center mt-4 p-4">
      <div class="w-full max-w-[1400px] p-5 rounded-lg shadow-md bg-white">
        <div class="mb-6 pb-2 border-b border-gray-300">
          <h1 class="text-gray-800 font-bold text-[20px] pl-2">Latest Announcements</h1>
          <div></div>
        </div>

        <div class="flex flex-wrap justify-between gap-4">
          <button class="bg-[rgb(27,27,27)] hover:bg-[rgb(69,69,69)] font-semibold px-10 py-6 rounded shadow w-full sm:w-[320px] text-white text-center">
            Add Announcement
          </button>
          <button class="bg-[rgb(27,27,27)] hover:bg-[rgb(69,69,69)] font-semibold px-10 py-6 rounded shadow w-full sm:w-[320px] text-white text-center">
            Add Course
          </button>
          <button class="bg-[rgb(27,27,27)] hover:bg-[rgb(69,69,69)] font-semibold px-10 py-6 rounded shadow w-full sm:w-[320px] text-white text-center">
            Add Student
          </button>
          <button class="bg-[rgb(27,27,27)] hover:bg-[rgb(69,69,69)] font-semibold px-10 py-6 rounded shadow w-full sm:w-[320px] text-white text-center">
            Add Professor
          </button>
        </div>
      </div>
    </div>

  </div>

  <!-- JavaScript -->
  <script>
    // Dropdown menu
    const toggleBtn = document.getElementById("dropdownToggle");
    const dropdown = document.getElementById("dropdownMenu");
    toggleBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      dropdown.classList.toggle("hidden");
    });
    window.addEventListener("click", () => {
      dropdown.classList.add("hidden");
    });

    // Sidebar toggle
    const sidebar = document.getElementById("sidebar");
    const menuToggle = document.getElementById("menuToggle");
    const mainContent = document.getElementById("mainContent");
    let isSidebarOpen = false;

    menuToggle.addEventListener("click", () => {
      isSidebarOpen = !isSidebarOpen;
      if (isSidebarOpen) {
        sidebar.classList.remove("-translate-x-full");
        mainContent.classList.add("pl-64");
      } else {
        sidebar.classList.add("-translate-x-full");
        mainContent.classList.remove("pl-64");
      }
    });
  </script>

</body>
</html>
