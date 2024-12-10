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
            include("../back_php/Affichage_gen.php");
            afficherErreur("Erreur lors de la connexion à la base de données : " . $e->getMessage());
            exit();
        }
    }
    
    
    // Méthode pour obtenir la connexion PDO
    public function getConnection() {
        return $this->connection;
    }
    
    public function getResults($query, $args){
        $res = $this->connection->prepare($query);
        $res->execute($args);
        $rows = $res->fetch();
        $this->closeStatement($res);
        return $rows !== false ? $rows : []; // renvoie une liste vide plutot que false pour un aspect pratique
    }

    public function getResultsAll($query, $args){
        $res = $this->connection->prepare($query);
        $res->execute($args);
        $rows = $res->fetchAll();
        $this->closeStatement($res);
        return $rows !== false ? $rows : [];
    }
    
    public function closeBD(){
        $this->connection = null;
    }
    
    private function closeStatement($statement){
        $statement->closeCursor();
    }
    /**
     * Insère des lignes dans la base de données.
     */
    public function insertLines($query, $args){
        $insert = $this->connection->prepare($query);
        $insert->execute($args);
        $this->closeStatement($insert);
    }
    /**
     * Supprime des lignes dans la base de données.
     */
    public function deleteLines($query, $args){
        $delete = $this->connection->prepare($query);
        $delete->execute($args);
        $this->closeStatement($delete);
    }
    /**
     * Met à jour des lignes dans la base de données.
     */
    public function UpdateLines($query, $args){
        $update = $this->connection->prepare($query);
        $update->execute($args);
        $this->closeStatement($update);
    }
    /**
     * Récupère le dernier ID ajouté
     */
    public function getLastInsertId() {
        return $this->connection->lastInsertId();
    }
}


?>