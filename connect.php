<?php
    // Establishing the database connection

    // Define the Datasource name, database user id and password for access the database
    
    define('DB_DSN','mysql:host=localhost;dbname=452422;charset=utf8');
    define('DB_USER','452422');
    define('DB_PASS','0723123');     
    
/*
    define('DB_DSN','mysql:host=fdb34.awardspace.net:3306;dbname=4154139_project;charset=utf8');
    define('DB_USER','4154139_project');
    define('DB_PASS','a0723123Z1@'); 
  */   
    try {
        // Creating new PDO connection to MySQL.
        $db = new PDO(DB_DSN, DB_USER, DB_PASS);
        //,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    } catch (PDOException $e) {
        print "Error: " . $e->getMessage();
        die(); 
    }
?>