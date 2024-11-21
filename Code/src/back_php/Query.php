<?php

class Query {
    private $host;
    private $username;
    private $password;
    private $connection;


    public function __construct($dbname, $username = "root", $password = "") {
        $this->host = "mysql:host=localhost;dbname=".$dbname.";charset=utf8";
        $this->username = $username;
        $this->password = $password;
        $this->connect();
    }

    private function connect(){
        try {
            $this->connection = new PDO($this->host, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
    
    
    // Méthode pour obtenir la connexion PDO
    public function getConnection() {
        return $this->connection;
    }
    
    public function getResults($query, $args){
        $res = $this->connection->prepare($query);
        $res->execute($args);
        return $res;
    }
    
    public function closeBD(){
        $this->connection = null;
    }
    
    public function closeStatement($statement){
        $statement->closeCursor();
    }
    
    public function insertLines($query, $args){
        $insert = $this->connection->prepare($query);
        $insert->execute($args);
        $this->closeStatement($insert);
    }
    
    public function deleteLines($query, $args){
        $delete = $this->connection->prepare($query);
        $delete->execute($args);
        $this->closeStatement($delete);
    }

    public function UpdateLines($query, $args){
        $update = $this->connection->prepare($query);
        $update->execute($args);
        $this->closeStatement($update);
    }
}


?>