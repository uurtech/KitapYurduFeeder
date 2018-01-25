<?php

class Connection {

    public $pdo = "";

    function __construct(){
        try{
        $this->pdo = new PDO("mysql:host=localhost;dbname=VERİTABANIN;charset=utf8;","KULLANICI ADIN","ŞİFREN");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    
}
?>