<?php

class User
{
    // CONSTANTS
    const TABLE_NAME = "user";

    // database connection and table name
    private $conn;
    protected $password;

    // object properties
    protected $id;
    protected $firstname;
    protected $lastname;
    protected $email;

    public function __construct($db, $id, $password)
    {
        $this->conn = $db;
        $this->id = $id;
        $this->password = $password;

        $pwVerified = $this->verifyPassword();
        if (!$pwVerified) {
            throw new Exception("Password is incorrect.");
        }

    }

    public function getFirstName()
    {
        return $this->firstname;
    }
    public function getLastName()
    {
        return $this->lastname;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getId()
    {
        return $this->id;
    }
    

    public function verifyPassword() : bool
    {
        // verify password from database 
        $query = "SELECT * FROM ".$this->getTableName()." WHERE s_id = :id "; // . $this->id;
        $stmt = $this->conn->prepare($query);
        // var_dump($query);
        // echo '<br>';
        $stmt->execute(array('id'=>$this->id));
        // var_dump($stmt);
        // echo '<br>';
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($row);
        // echo '<br>';
        if (password_verify($this->password, $row['password_hash'])) {
            $this->firstname = $row['name'];
            $this->lastname = $row['lastname'];
            $this->email = $row['email'];
            $this->password = "The password is verified and no longer needed to be stored!";
            return true;
        } else {
            $this->id = null;
            return false;
        }
    }


    public function updateToDatabase()
    {
        $query = "UPDATE " . $this->getTableName() . " SET name = :name, lastname = :lastname, email = :email WHERE s_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    }
    public function setPassword(string $newPassword)
    {
        $query = "UPDATE " . $this->getTableName() . " SET password_hash = :password_hash WHERE s_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password_hash', password_hash($newPassword, PASSWORD_DEFAULT));
    }

    public function getTableName(): string {
        return get_called_class()::TABLE_NAME;
    }
}
