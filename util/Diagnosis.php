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
    protected ?string $resultAsString;

    public function __construct()
    {
        $this->diagnosisId = null;
        $this->type = null;
        $this->result = null;
        $this->diagnosisDate = null;
        $this->userId = null;
    }

    public function getResultAsString(): string {

        if( $this->result == 0){

            $this->resultAsString = "Covid Positive";
        }

        else{
            $this->resultAsString = "Covid Negative";
        }

        return $this->resultAsString;

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

    $stmt->bindParam(":id", $id);
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
    
    public function updateDiagnosisesOfUser(PDO $conn) : void
    {
       
        //INSERT INTO `table_name`(column_1,column_2,...) VALUES (value_1,value_2,...);

        $sql = "INSERT INTO ". Diagnosis::TABLE_NAME ."  (type, result, date, user_id) VALUES (:type, :result, :date, :user_id) ";
        $stmt = $conn->prepare($sql);
    $stmt->bindParam(":user_id", $_SESSION['id']);
   // echo $this->diagnosisDate->format('Y-m-d H:i:s');
    $stringDate = $this->diagnosisDate->format('Y-m-d H:i:s');

    $stmt->bindParam(":date", $stringDate);
    $stmt->bindParam(":result", $this->result);
    $stmt->bindParam(":type", $this->type);

    $stmt->execute();
    }
    

}
