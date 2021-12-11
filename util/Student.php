<?php 
include("User.php");

class Student extends User {

    const TABLE_NAME = "student";
    const TABLE_PREFIX = "user_";

    
    public function insertToDatabase() : bool
    { 
        parent::insertToDatabase();
        
        try 
        { 
            $query = "INSERT INTO " . self::TABLE_PREFIX.self::TABLE_NAME . " (id) VALUES (:id)";
            $statement = $this->conn->prepare($query);
            $statement->bindParam(":id", $this->id);
            $statement->execute();
            return true;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            throw new Exception("Error inserting student into database" . self::TABLE_PREFIX.self::TABLE_NAME );
            return false;
        }
    }
}
