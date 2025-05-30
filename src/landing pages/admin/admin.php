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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../output.css">
</head>
<body class = "flex flex-col min-h-screen">

    

    <div
        class = "bg-[#f3f8f1] shadow-md sticky top-0 w-full p-4 flex items-center justify-between"
    >
        <div class="flex items-center space-x-10">

            <img src="../../../imgs/menu-hamburger-navigation-512-4184062329.png" alt="" class="h-6 w-auto object-scale-down pl-4">
            <a href="#" class="flex items-center space-x-2">
            <img src="../../../imgs/cc.png" alt="" class="h-10 w-auto object-scale-down">
            <span class="text-lg font-semibold text-gray-800">Learning Management System (Admin's Side)</span>
            </a>

        </div>
        
        <!-- Right: Username Dropdown -->
    <div class="relative pr-7">

      <!-- Dropdown Button -->
      <button id="dropdownToggle" class="flex items-center space-x-2 text-black font-medium hover:text-gray-500 focus:outline-none">
        <span><?php echo $displayName; ?>'s Account</span>
        <!-- Down Arrow Icon -->
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>

      </button>

      <!-- Dropdown Menu -->
      <div id="dropdownMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-10">

        <ul class="py-2 text-sm text-gray-700">
          <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">View Profile</a></li>
          <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Settings</a></li>
          <li><a href="../../security/logout.php" class="block px-4 py-2 hover:bg-gray-100 text-red-500">Logout</a></li>
        </ul>

      </div>

    </div>

  </div>

  <!-- JavaScript for Toggle -->
  <script>

    const toggleBtn = document.getElementById("dropdownToggle");
    const dropdown = document.getElementById("dropdownMenu");

    toggleBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      dropdown.classList.toggle("hidden");
    });

    // Click outside to close
    window.addEventListener("click", () => {
      dropdown.classList.add("hidden");
    });

  </script>

</body>
</html>