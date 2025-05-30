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
        
        <nav>
            <ul class = "flex space-x-4">
                <li>
                    <h2 class = "text-black pr-7 hover:underline hover:text-gray-400"><?php echo $displayName; ?>'s Account</h2>
                </li>
            </ul>
        </nav>
        
    </div>



</body>
</html>