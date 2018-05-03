<?php
    include "conn.php";
    $dsn        = "mysql:dbname=$db;host=$host";
    try {
    $connection = new PDO($dsn,$username,$password,
            Array(PDO::ATTR_PERSISTENT=>true));
    $connection->setAttribute(PDO::ATTR_ERRMODE, 
            PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e)
    {
        echo "connection failed : ".$e->getMessage();
    }

