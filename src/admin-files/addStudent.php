<?php
    require_once '../connections and functions/dbconn.php';
    require_once '../security/hashing.php';
    $conn = connect();
    session_start();
    $isLoggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
    $displayName = isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8') : 'Guest';

    // Initialize error/success flags
    $addManualFailed = false;
    $loginSuccess   = false;

    $fileFailed  = "";
    $fileSuccess = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type'])) {

        if ($_POST['form_type'] === "manual") {

            if (empty($_POST['name']) || empty($_POST['username']) || empty($_POST['email'])) {
                $_SESSION['login_error'] = true;
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $isEmailValid = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                if ($isEmailValid) {
                    $username = htmlspecialchars(trim($_POST['username'] ?? ''), ENT_QUOTES, 'UTF-8');
                    $email    = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                    $name     = ucwords(strtolower(preg_replace('/\s+/', ' ', trim($_POST['name']))));

                    $search = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
                    $search->bind_param("ss", $username, $email);
                    $search->execute();
                    $search->store_result();

                    if ($search->num_rows > 0) {
                        $_SESSION['login_error'] = true;
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    }
                    $search->close();

                    $defaultPassword = hashPassword("changeme");
                    $sql = "INSERT INTO users (name, username, email, password, role) VALUES (?, ?, ?, ?, 'student')";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssss", $name, $username, $email, $defaultPassword);
                    $stmt->execute();
                    $stmt->close();

                    // On success, set session and reload
                    $_SESSION['login_success'] = true;
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                }
            }
        }

        if ($_POST['form_type'] === "csv") {

            $file = $_FILES['csvFile']['tmp_name'];

            if (!is_uploaded_file($file)) {
                $_SESSION['file_error'] = "File upload failed. Please Try Again.";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }

            if (($handle = fopen($file, "r")) !== false) {

                $rows = [];
                $lineNumber = 1;

                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    // Must have exactly 3 fields and no empty entries
                    if (count($data) !== 3 || in_array("", array_map('trim', $data))) {
                        $_SESSION['file_error'] = "Invalid Inputs In File. Please Try Again.";
                        fclose($handle);
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    }
                    $rows[] = $data; // Store valid row
                    $lineNumber++;
                }
                fclose($handle);

                $defaultPassword = hashPassword("changeme");
                $insertedCount = 0;

                foreach ($rows as $data) {
                    list($name, $username, $email) = $data;

                    // Clean and normalize input data
                    $name     = ucwords(strtolower(preg_replace('/\s+/', ' ', trim($name))));
                    $username = htmlspecialchars(trim($username), ENT_QUOTES, 'UTF-8');
                    $email    = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $_SESSION['file_error'] = "Invalid Inputs In File. Please Try Again.";
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    }

                    $searchCSV = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
                    $searchCSV->bind_param("ss", $username, $email);
                    $searchCSV->execute();
                    $searchCSV->store_result();

                    if ($searchCSV->num_rows > 0) {
                        $_SESSION['file_error'] = "Username or Email already exists. Please Try Again.";
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    }
                    $searchCSV->close();

                    $stmtCSV = $conn->prepare("INSERT INTO users (name, username, email, password, role) VALUES (?, ?, ?, ?, 'student')");
                    $stmtCSV->bind_param("ssss", $name, $username, $email, $defaultPassword);
                    $stmtCSV->execute();
                    $stmtCSV->close();

                    $insertedCount++;
                }

                // On success, set session and reload
                $_SESSION['csv_success'] = "$insertedCount student(s) added successfully.";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        }
    }

    // Capture session–error flags into local variables
    if (isset($_SESSION['login_error'])) {
        $addManualFailed = true;
        unset($_SESSION['login_error']);
    }

    if (isset($_SESSION['file_error'])) {
        $fileFailed = $_SESSION['file_error'];
        unset($_SESSION['file_error']);
    }

    // Capture “success” flags
    if (isset($_SESSION['login_success'])) {
        $loginSuccess = true;
        unset($_SESSION['login_success']);
    }

    if (isset($_SESSION['csv_success'])) {
        $fileSuccess = $_SESSION['csv_success'];
        unset($_SESSION['csv_success']);
    }

    if ($conn) {
        mysqli_close($conn);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Bruh</title>
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
        <a href="/src/landing pages/admin/admin.php" class="flex items-center space-x-2">
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

    <!-- Main Content Area -->
    <div class="flex justify-center mt-4 p-4">
      <div class="w-full max-w-[1400px] p-5 rounded-lg shadow-md bg-white items-center">

        <!-- Two Columns: each column has its own H1 + form -->
        <div class="flex justify-center space-x-12">

          <!-- Column 1: Manual Entry -->
          <div class="flex flex-col items-center space-y-4">
            <h1 class="text-xl font-semibold">Add Student’s Information Manually</h1>
            <form action="" method="post" class="flex flex-col space-y-4 w-80">
              <input type="hidden" name="form_type" value="manual" />

              <?php if ($addManualFailed): ?>
                <script>
                  alert("Manual entry failed: Invalid input. Please try again.");
                </script>
              <?php endif; ?>

              <?php if ($loginSuccess): ?>
                <script>
                  alert("Student added successfully (manual).");
                </script>
              <?php endif; ?>

              <label for="name">Name:</label>
              <input
                type="text"
                name="name"
                placeholder="First to Last Name (i.e. John Doe)"
                class="w-full p-2 border border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition-all"
              />
              <label for="username">Username:</label>
              <input
                type="text"
                name="username"
                placeholder="Student ID without dash (i.e. 205512345)"
                class="w-full p-2 border border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition-all"
              />
              <label for="email">Email:</label>
              <input
                type="email"
                name="email"
                placeholder="Enter Valid Email Address"
                class="w-full p-2 border border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition-all"
              />
              <button
                type="submit"
                class="mt-4 mb-4 bg-[rgb(27,27,27)] text-white py-2 rounded hover:bg-[rgb(69,69,69)]"
              >
                Add Student
              </button>
            </form>
          </div>

          <!-- Vertical Divider -->
          <div class="w-px bg-gray-300"></div>

          <!-- Column 2: CSV Upload -->
          <div class="flex flex-col items-center space-y-4">
            <h1 class="text-xl font-semibold">Add Student/s Information via CSV File</h1>
            <div class="text-sm text-gray-700 mb-4">
              <p class="mb-2">*Ensure the CSV file follows the required format listed below.</p>
            </div>
            <form action="" method="post" enctype="multipart/form-data" class="flex flex-col space-y-4 w-80">
              <input type="hidden" name="form_type" value="csv" />

              <?php if ($fileFailed !== ""): ?>
                <script>
                  alert("CSV upload failed: <?= addslashes($fileFailed) ?>");
                </script>
              <?php endif; ?>

              <?php if ($fileSuccess !== ""): ?>
                <script>
                  alert("<?= addslashes($fileSuccess) ?>");
                </script>
              <?php endif; ?>

              <input
                type="file"
                name="csvFile"
                accept=".csv"
                required
                class="px-4 py-2 border border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition-all"
              />
              <button
                type="submit"
                class="mt-2 bg-[rgb(27,27,27)] text-white py-2 rounded hover:bg-[rgb(69,69,69)]"
              >
                Upload CSV
              </button>

              <div class="text-sm text-gray-700">
                <p class="mb-2 font-medium">Required CSV Format:</p>
                <ul class="list-disc pl-5 space-y-1">
                  <li><strong>Columns (no headers):</strong> Name, Username, Email</li>
                  <li><strong>File type:</strong> <span class="font-medium">.csv</span> only</li>
                  <li>Usernames and Email Addresses <strong>must be unique</strong></li>
                </ul>
              </div>

              <a
                href="../connections and functions/downloadCSVSample.php"
                class="mt-4 text-black underline hover:text-gray-500 text-sm"
              >
                Download sample CSV template
              </a>
            </form>
          </div>

        </div>

      </div>
    </div>

  </div>

  <!-- JavaScript for sidebar & dropdown -->
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
