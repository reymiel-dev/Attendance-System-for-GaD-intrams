<?php

    $host = "localhost";
    $dbname = "attendance_db";
    $username = "root";
    $password = "";

    try {
        $conn = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4", 
            $username, $password
        );

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    } catch (PDOException $e) {
        die("connection failed: " . $e->getMessage());
    }

?>