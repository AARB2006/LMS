<?php
    
    
    function hashPassword($password){

        return password_hash($password,PASSWORD_DEFAULT);
    }

    function verifyHash($inputPassword , $hashedPassword){
        return password_verify($inputPassword , $hashedPassword);
    }