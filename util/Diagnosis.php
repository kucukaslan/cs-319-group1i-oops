<?php

class Diagnosis
{
    // CONSTANTS
    const TABLE_NAME = "diagnosis";

    // object properties
    private ?int $diagnosisId;
    protected ?string $type; // it is enum with the possible values: 'POSITIVE', 'NEGATIVE', 'UNKNOWN', 'PENDING'
    protected ?int $result;
    protected ?DateTime $diagnosisDate;
    protected ?int $userId;

    public function __construct()
    {
        $this->diagnosisId = null;
        $this->type = null;
        $this->result = null;
        $this->diagnosisDate = null;
        $this->userId = null;
    }


    // constructor with $db as database connection
    public function getTableName(): string {
        return get_called_class()::TABLE_NAME;
    }

    
    public function getDiagnosisId(): int
    {
        return $this->diagnosisId;
    }

    
    public function setDiagnosisId(int $diagnosisId): void
    {
        $this->diagnosisId = $diagnosisId;
    }

    
    public function getDiagnosisDate(): ?DateTime
    {
        return $this->diagnosisDate;
    }

    
    public function setDiagnosisDate(?DateTime $diagnosisDate): void
    {
        $this->diagnosisDate = $diagnosisDate;
    }

    
    public function getResult(): ?int
    {
        return $this->result;
    }

    
    public function setResult(?int $result): void
    {
        $this->result = $result;
    }

    
    public function getUserId(): int
    {
        return $this->userId;
    }

    
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
    public function __toString() : string
    {
        $stringVersion = $this->diagnosisDate->format('Y-m-d H:i:s');
        return " " .$this->diagnosisId ." " . $this->type . " " . $this->result ." " .$stringVersion . " ". $this->userId;
    }

    public static function getDiagnosisesOfUser(int $id, PDO $conn) : array
    {



    $sql = "SELECT * FROM ".Diagnosis::TABLE_NAME ." JOIN ".User::TABLE_NAME .
    " ON ".Diagnosis::TABLE_NAME .".user_id =  ".User::TABLE_NAME .".id   WHERE ". Diagnosis::TABLE_NAME .".user_id = :id";
     $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $diagnosises = array();
    foreach($result as $row)
    {
        $diagnosis = new Diagnosis();
        $diagnosis->setType($row["type"]);
        $diagnosis->setResult($row["result"]);
        $diagnosis->setDiagnosisDate(new DateTime($row["date"]));
        $diagnosis->setDiagnosisId($row["diagnosis_id"]);
        $diagnosis->setUserId($row["user_id"]);
        
        $diagnosises[] = $diagnosis;
    }
    return $diagnosises;

    }
    
}
