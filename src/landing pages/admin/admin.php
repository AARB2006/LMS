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

    

    <div>
        <div>
            <h1>Add User</h1>

        </div>
        <div>
            <h1>Add Course</h1>
        </div>
    </div>

</body>
</html>