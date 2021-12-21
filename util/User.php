<?php

abstract class User
{
    // CONSTANTS
    const TABLE_NAME = "user";

    // database connection and table name
    protected ?PDO $conn;
    protected ?string $password;

    // object properties
    protected ?int $id;
    protected ?string $firstname;
    protected ?string $lastname;
    protected ?string $email;
    protected ?string $HESCode;
    protected ?string $HESCodeStatus;
    

    public function __construct() {
        $this->conn = null;
        $this->id = null;
        $this->password = null;
        $this->firstname = null;
        $this->lastname = null;
        $this->email = null;
        $this->HESCode = null;
    }


    // someone: spesifik bir user için database'de olan tüm vaccineleri getiren bir metot yaz muh.
    // Muhammed: VaccineManager yapıyor onu. 2021.12.17

    public function getFirstName(): string
    {
        return $this->firstname;
    }
    public function getLastName(): string
    {
        return $this->lastname;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return PDO|null
     */
    public function getConn(): ?PDO
    {
        return $this->conn;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
    

    public function verifyPassword() : bool
    {
        // verify password from database 
        $query = "SELECT * FROM ".$this->getTableName()." WHERE id = :id "; // . $this->id;
        $stmt = $this->conn->prepare($query);
        // var_dump($query);
        // echo '<br>';
        $stmt->execute(array('id'=>$this->id));
        // var_dump($stmt);
        // echo '<br>';
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($row);
        // echo '<br>';


        if (password_verify($this->getPassword(), $row['password_hash'])) {
            $this->firstname = $row['name'];
            $this->lastname = $row['lastname'];
            $this->email = $row['email'];
            $this->password = "The password is verified and no longer needed to be stored!";
            $this->HESCode = $row['hescode'];
            return true;
        } else {
            $this->id = null;
            return false;
        }
    }

    /*
        This might be an example of template method pattern.
    */
    
    public function insertToDatabase() : bool
    { 
        try {
            $query = "INSERT INTO ".User::TABLE_NAME." (id, password_hash, name, lastname, email, hescode) VALUES (:id, :password_hash, :name, :lastname, :email, :hescode)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array('id'=>$this->id, 'password_hash'=>password_hash( $this->password, PASSWORD_ARGON2I), 'name'=>$this->firstname, 'lastname'=>$this->lastname, 'email'=>$this->email, 'hescode'=>$this->HESCode));
    
            return insertToSpecializedTable();
        } catch (Exception $e) {
            echo $e->getMessage();
            throw new Exception("Error inserting to database.".$this->getTableName());
            return false;
        }
    }

    protected abstract function insertToSpecializedTable() : bool;

    public function updateToDatabase() 
    {
        $query = "UPDATE " . $this->getTableName() . " SET name = :name, lastname = :lastname, email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    }

    public function updateHESCode(string $HESCode) : bool {
        try {
            $query = "UPDATE " . $this->getTableName() . " SET hescode = :hescode WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':hescode', $HESCode);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
            return true;
        }
        catch (PDOException $e) {
            return false;
        }
    }
    public function deleteHESCode() : bool {
        try {
            $query = "UPDATE " . $this->getTableName() . " SET hescode = NULL WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
            return true;
        }
        catch (PDOException $e) {
            return false;
        }
    }
    public function updatePassword(string $newPassword)
    {
        $query = "UPDATE " . $this->getTableName() . " SET password_hash = :password_hash WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $hash = password_hash($newPassword, PASSWORD_ARGON2I);
        $stmt->bindParam(':password_hash', $hash);
    }

    public function getTableName(): string {
        return get_called_class()::TABLE_NAME;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string|null $HESCode
     */
    public function setHESCode(?string $HESCode): void
    {
        $this->HESCode = $HESCode;
    }

    /**
     * @return string|null
     */
    public function getHESCode(): ?string
    {
        return $this->HESCode;
    }

    public function setDatabaseConnection( PDO $conn)
    {

        $this->conn = $conn;
    }

    /**
     * @param int $id is user if to add the database
     * @return bool if addition is successful.
     */
    public function addCloseContact(int $contacted_user_id, int $event_id): bool {
        // TODO: write given id to the database
       try{ 
           $query = "INSERT INTO ".Event::CONTACT_TABLE_NAME." (main_user_id , contacted_user_id , event_id) 
            VALUES (:id, :contacted_user_id, :event_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array('id'=>$this->id,
            'contacted_user_id'=>$contacted_user_id,
            'event_id'=>$event_id));
            return true;
       } catch(PDOException $e) {
            getConsoleLogger()->log("User Contact", "Cannot add contact");
            return false;
       }
    }
    public function deleteCloseContact(int $id): bool {
        // TODO:
        return TRUE;
    }

    /**
     * @return int[] array of closed contact id's
     */
    public function getCloseContacts() {
        // TODO:
        return [1, 32323, 3];
    }
    public function getNoOfCloseContacts(): int {
        // TODO:
        return 3;
    }

    /**
     * This function will be used for creating the contact list.
     * @param int $id is the user id whose name will be fetched
     * @return string name of the desired user
     */
    public function giveName(int $id): string {
        // TODO:
        if ($id == 1) {
            return "Muhammed lol";
        }
        if ($id == 32323) {
            return "hikmet simsir";
        }

        if ($id == 3) {
            return "My id is 3";
        }

        return "????";
    }
}
