<?php

    $host = 'localhost';
    $port = 3306;
    $dbname = 'abinbev';
    $user = 'root';
    $password = 'raksh_2906';

    try{
        $dbh = new PDO("mysql:host={$host};port={$port};dbname={$dbname}",$user,$password);
    }
    catch(PDOException $e){
        echo 'Connection Failed: '.$e->getMessage();
    }

?>