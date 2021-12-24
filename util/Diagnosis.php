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
    
}
