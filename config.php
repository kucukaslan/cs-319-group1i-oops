<?php
include 'constants.php'; // all constants are defined here
function getDatabaseConnection() : PDO {
    try {
        $conn = new PDO("mysql:host=".servername.";dbname=".dbname,username, password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
        return $conn;

    } catch(PDOException $e) {

        echo servername ." ". username;
        echo "Connection failed: " . $e->getMessage();
        // we might want to redirect to a proper error page from here
        exit(); // stop the script
    }
}