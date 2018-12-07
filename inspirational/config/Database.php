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

    public function executeCall($username,$calls_allowed,$timeOutSeconds){
        $query = "SELECT plan, calls_made, time_start, time_end FROM users WHERE username = '$username'";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$username]);
        $result = $stmt->fetch();

        //if it is timeout or eaual to zero set it to true
        $timeOut = data(time()) - $result['time_start'] >= $timeOutSeconds || $result['time_start'] === 0;

        //update calls made with respece to time out
        $query = "UPDATE users SET calls_made = ";
        $query .= $timeOut ? " 1, time_start = ".date(time())." , time_end = ".strtotime("+$timeOutSeconds seconds") : "calls_made + 1";
        $query .= " WHERE username = ?";

        //Insted of fetching again using select all update variables
        $result['calls_made'] = $timeOut ? 1 : $result['calls_made'] + 1;
        $result['time_end'] = $timeOut ? strtotime("+ $timeOutSeconds seconds") : $result['time_end'];

        //execute code with respect to plans
        if($result['plan'] == "unlimited"){
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$userName]);
            return $result;
        }else{
            // if no time out and calls made is greateher than calls allowed return -1
            if($timeOut == false && $result['calls_made'] >= calls_allowed){
                return -1;
            }else{
                // grant access
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$username]);
                return $result;
            }
        }

    }

    public function insertOne($query, $body, $user_id,$category_id,$date){
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$body,$category_id,$date,$id]);

    }

    public function updateOne($query,$body,$category_id,$date,$id){
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$body,$category_id,$date,$id]);
    }

    public function deleteOne($query,$id){
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);
    }

    public function insertUser($query,$firstName,$lastName,$password,$username){
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$firstName,$lastName,$password,$username]);

    }


}