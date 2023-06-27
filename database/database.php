<?php
    try{
        $con = new PDO("mysql:dbname=f1_db;host=localhost","root","");
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
    
?>