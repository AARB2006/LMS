<?php

    require_once __DIR__ . '/../../vendor/autoload.php'; // go up 2 folders to LMS root

    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); // path to project root with .env
    $dotenv->load();

    function encryptData($data){

        global $_ENV;
        
        $key = $_ENV['ENCRYPTION_KEY'];
        $iv  = $_ENV["ENCRYPTION_IV"];
        $encryptionAlgorithm = $_ENV['ENCRYPTION_ALGORITHM'];

        $encryptedData = openssl_encrypt($data, $encryptionAlgorithm, $key, 0, $iv);

        if ($encryptedData === false) {
            throw new Exception("Encryption failed");
        }

        return $encryptedData;

    }

    function decryptData($data){

        global $_ENV;

        $key = $_ENV['ENCRYPTION_KEY'];
        $iv  = $_ENV["ENCRYPTION_IV"];
        $encryptionAlgorithm = $_ENV['ENCRYPTION_ALGORITHM'];

        $decryptedData = openssl_decrypt($data, $encryptionAlgorithm, $key, 0, $iv);

        if ($decryptedData === false) {
            throw new Exception("Decryption failed");
        }

        return $decryptedData;

    }
