<?php

function createDbConnection(){
    $ini = parse_ini_file("myconf.ini");

    $host=$ini["host"];
    $db=$ini["db"];
    $username=$ini["username"];
    $pw=$ini["pw"];


    try{
        $dbcon = new PDO("mysql:host=$host;dbname=$db", $username, $pw);
        return $dbcon;
    }catch( PDOException $e){
        echo $e->getMessage();
    }

    return null;
}