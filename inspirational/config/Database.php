<?php

class Database{
    private $hostName= "172.16.238.12";
    private $dbName = "quotes_api";
    private $userName = "amirhpg";
    private $password = "123581321";
    private $pdo;

    // connection
    public function __construct()
    {
        $this->pdo = null;
        try{
            $this->pdo = new PDO("mysql:host=$this->hostName;dbname=$this->dbName;",$this->userName,$this->password);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        }catch(Exception $e){
            echo "Error : ".$e->getMessage();
        }
    }

    public function fetchAll($query){
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if($rowCount <= 0)
            return 0;
        else
            return $stmt->fetchAll();

    }

    public function fetch($query,$parameter){
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$parameter]);
        $rowCount = $stmt->rowCount();

        if($rowCount <= 0)
            return 0;
        else
            return $stmt->fetch();

    }
}