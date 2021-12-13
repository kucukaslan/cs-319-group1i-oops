<?php

class Vaccine
{
    // CONSTANTS
    const TABLE_NAME = "vaccine";

    // database connection and table name
    protected ?PDO $conn;

    // object properties
    protected ?string $vaccineName;
    protected ?DateTime $dateApplied;

    public function __construct(string $date, string $type) {

        $this->vaccineName = $type;
        $this->dateApplied = new DateTime($date);

    }

    /**
     * @return string|null
     */
    public function getVaccineName(): ?string
    {
        return $this->vaccineName;
    }

    /**
     * @return DateTime|null
     */
    public function getDateApplied(): ?DateTime
    {
        return $this->dateApplied;
    }

    /**
     * @return PDO|null
     */
    public function getConn(): ?PDO
    {
        return $this->conn;
    }


    public function insertToDatabase() : bool
    {
        try {
            $query = "INSERT INTO ".User::TABLE_NAME." (id, password_hash, name, lastname, email, hescode) VALUES (:id, :password_hash, :name, :lastname, :email, :hescode)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array('id'=>$this->id, 'password_hash'=>password_hash( $this->password, PASSWORD_ARGON2I), 'name'=>$this->firstname, 'lastname'=>$this->lastname, 'email'=>$this->email, 'hescode'=>$this->HESCode));

            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            throw new Exception("Error inserting to database.".$this->getTableName());
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


    public function getTableName(): string {
        return get_called_class()::TABLE_NAME;
    }

    /**
     * @param PDO|null $conn
     */
    public function setConn(?PDO $conn): void
    {
        $this->conn = $conn;
    }

    /**
     * @param string|null $vaccineName
     */
    public function setVaccineName(?string $vaccineName): void
    {
        $this->vaccineName = $vaccineName;
    }

    /**
     * @param Date|null $dateApplied
     */
    public function setDateApplied(?Date $dateApplied): void
    {
        $this->dateApplied = $dateApplied;
    }






}
