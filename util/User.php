<?php

class User
{
    // CONSTANTS
    const TABLE_NAME = "user";

    // database connection and table name
    private ?PDO $conn;
    protected ?string $password;

    // object properties
    protected ?int $id;
    protected ?string $firstname;
    protected ?string $lastname;
    protected ?string $email;


    public function __construct() {
        $this->conn = null;
        $this->id = null;
        $this->password = null;
        $this->firstname = null;
        $this->lastname = null;
        $this->email = null;


    }

    public function __construct2($db, $id, $password,$firstname,$lastname,$email)
    {
        $this->conn = $db;
        $this->id = $id;
        $this->password = $password;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;

        // todo insert to database: muh ekle.


    }


    /**
     * @throws Exception
     */
    public function __construct3($db, $id, $password)
    {
        $this->conn = $db;
        $this->id = $id;
        $this->password = $password;

        $pwVerified = $this->verifyPassword();
        if (!$pwVerified) {
            throw new Exception("Password is incorrect.");
        }

    }

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

        echo "pw: ".$this->getPassword();
        echo "pw: ".$this->password;


        if (password_verify($this->getPassword(), $row['password_hash'])) {
            $this->firstname = $row['name'];
            $this->lastname = $row['lastname'];
            $this->email = $row['email'];
            $this->password = "The password is verified and no longer needed to be stored!";
            return true;
        } else {
            var_dump($row);
            echo "raw pw: ".password_verify(123, $row['password_hash']);
            echo $this->getPassword()."raw hash:".password_verify($this->password, '$2a$10$j7fjSm.dNIIo7ovzBEIU7udL.IHKWl2X2ydCVm/cJHhyE50np9kw2' );
            var_dump($this);
            echo $this->password;
            $this->id = null;
            return false;
        }
    }


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

    public function addHESCode(string $HESCode) : bool {
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

    public function setDatabaseConnection( PDO $conn)
    {

        $this->conn = $conn;
    }





}
