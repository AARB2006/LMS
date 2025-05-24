<?php
    
    require_once __DIR__ . '/../../vendor/autoload.php'; // go up 2 folders to LMS root

    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); // path to project root with .env
    $dotenv->load();


    function connect(){

        $host = $_ENV['DB_HOST'];
        $user = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];
        $dbName = $_ENV['DB_NAME'];

        $conn = mysqli_connect($host, $user, $password, $dbName);

                if ($conn->error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        return $conn;

        
    }





