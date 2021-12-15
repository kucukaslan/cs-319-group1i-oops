<?php
require_once("User.php");

class SportsCenterStaff extends User {

    const TABLE_NAME = "sports_center_staff";
    const TABLE_PREFIX =  parent::TABLE_NAME . "_";


    protected function insertToSpecializedTable() : bool
    {
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
