<?php
// Function to create an admin user in the database

    include 'dbconn.php'; 
    $conn = connect(); // Connect to the database
    
    require_once '../security/encryption.php';
    require_once '../security/hashing.php';

    require_once __DIR__ . '/../../vendor/autoload.php'; // go up 2 folders to LMS root

    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); // path to project root with .env
    $dotenv->load();

    



    //create the first admin user at startup
    function createAdmin($conn){

        $check = "SELECT * FROM users";
        $result = mysqli_query($conn, $check);

        if (mysqli_num_rows($result) > 0){ //there are already existing rows in database. DO NOT create an admin account
            // mysqli_close($conn);
        
        }

        // If no rows exist, create the admin user

        else{

            $name = " Admin User"; // Default name for the first admin user
            $username = $_ENV['FIRST_USERNAME'];
            $password = $_ENV['FIRST_PASSWORD'];
            $email = $_ENV['FIRST_EMAIL'];
            
            //hash password
            $hashedPassword = hashPassword($password);

            $query = "INSERT INTO users (name, username, email, password, role) 
                 VALUES (?, ?, ?, ?, 'admin')";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $name, $username, $email, $hashedPassword);
            
             
            $stmt->execute();
            
            $stmt->close();
            // mysqli_close($conn);
            
        }

    

    }
