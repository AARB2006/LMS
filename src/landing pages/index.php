<?php

    //creates the first admin account when the site is first run (and db is still empty)
    require_once '../connections and functions/createAdmin.php';
    require_once '../connections and functions/dbconn.php';
    require_once '../security/hashing.php';
    session_start();

    $conn = connect(); // Connect to the database
    createAdmin($conn); // Create the admin user if no users exist

    //add the login logic here

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../output.css">
</head>
<body class = "flex flex-col min-h-screen bg-[url('../../imgs/background.jpg')] bg-cover bg-center">
    <!-- This is a landing page for the project. -->

    <div class="flex items-center justify-center min-h-screen">

        <div class="bg-white p-8 rounded-lg shadow-lg">

           <div class="flex">

            <!-- Left Side -->
            <div class="flex-1 border-r border-gray-600 pr-7 flex flex-col items-center">
                <!-- Logo -->
                <img src="../../imgs/cc.png" alt="logo" class="w-120 object-contain mx-auto mb-2">
                
                <!-- Contact Info -->
                <div class="text-center">

                    <div class="flex items-center justify-center space-x-4">
                        <h3 class="font-semibold text-xl text-gray-700">Contact Us:</h3>
                        
                        <button class="w-8 h-8 bg-[rgb(27,27,27)] text-white rounded-md hover:bg-[rgb(69,69,69)] transition-colors">
                            <i class="fas fa-phone"></i>
                        </button>
                        <button class="w-8 h-8 bg-[rgb(27,27,27)] text-white rounded-md hover:bg-[rgb(69,69,69)] transition-colors">
                            <i class="fas fa-envelope"></i>
                        </button>
                        <button class="w-8 h-8 bg-[rgb(27,27,27)] text-white rounded-md hover:bg-[rgb(69,69,69)] transition-colors">
                            <i class="fas fa-globe"></i>
                        </button>
                        
                    </div>

                </div>

            </div>

            <!-- Right Side -->
            <div class="flex-1 pl-7">

                <form action="" method = "POST" class="space-y-4">

                        <p id = "errormsg" class = "bg-[rgb(223,172,172)] p-2 rounded-lg hidden">Invalid Login,please Try Again</p>

                        <!-- Username Input -->
                        <div class="space-y-2">
                            <input type="text" 
                                name="username" 
                                placeholder="Username:"
                                class="w-full p-2 border border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                        </div>

                        <!-- Password Input -->
                        <div class="space-y-2">
                            <input type="password" 
                                name="password" 
                                id="password"
                                placeholder="Password:"
                                class="w-full p-2 border border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                            class="w-full bg-[rgb(27,27,27)] text-white py-2 rounded-lg hover:bg-[rgb(69,69,69)] transition-colors font-semibold">
                            Log In
                        </button>

                        <!-- Forgot Password Link -->
                        <div class="text-center">
                            <a href="#" class="text-sm text-black hover:text-black hover:underline">
                                Forgot Username or Password?
                            </a>
                        </div>
                        
                    </form>
            </div>

        </div>

    </div>
    

</body>
</html>
