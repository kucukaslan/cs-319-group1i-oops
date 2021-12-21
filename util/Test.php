<?php

class Test
{
    // CONSTANTS
    const TABLE_NAME = "covid_test";

    // object properties
    private int $test_id;
    protected ?string $test_date;
    protected ?string $result; // it is enum with the possible values: 'POSITIVE', 'NEGATIVE', 'UNKNOWN', 'PENDING'
    protected int $user_id;
    protected $document;

    // constructor with $db as database connection
    public function getTableName(): string {
        return get_called_class()::TABLE_NAME;
    }

    /**
     * @return int
     */
    public function getTestId(): int
    {
        return $this->test_id;
    }

    /**
     * @param int $test_id
     */
    public function setTestId(int $test_id): void
    {
        $this->test_id = $test_id;
    }

    /**
     * @return string|null
     */
    public function getTestDate(): ?string
    {
        return $this->test_date;
    }

    /**
     * @param string|null $test_date
     */
    public function setTestDate(?string $test_date): void
    {
        $this->test_date = $test_date;
    }

    /**
     * @return string|null
     */
    public function getResult(): ?string
    {
        return $this->result;
    }

    /**
     * @param string|null $result
     */
    public function setResult(?string $result): void
    {
        $this->result = $result;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return bool
     */
    public function isDocument(): bool
    {
        return $this->document;
    }

    /**
     * @param bool $document
     */
    public function setDocument($document): void
    {
        $this->document = $document;
    }

    public static function getTestsOfUser(int $id, PDO $conn) : array
    {

        /*
         *  $sql = "SELECT * FROM ".self::RELATION_TABLE_NAME." NATURAL JOIN ".Vaccine::TABLE_NAME ."   WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $this->userId);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $vaccines = array();
        foreach($result as $row)
        {
            $vaccine = new Vaccine();
            $vaccine->setId($row["vaccine_id"]);
            $vaccine->setCvxCode($row["cvx_code"]);
            $vaccine->setVaccineName($row["vaccine_name"]);
            $vaccine->setVaccineType($row["vaccine_type"]);
            $vaccine->setVaccineDate($row["administration_date"]);
            $vaccines[] = $vaccine;
        }
        return $vaccines;
         */

        $sql = "SELECT * FROM ".Test::TABLE_NAME ." JOIN ".User::TABLE_NAME .
            " ON ".Test::TABLE_NAME .".user_id =  ".User::TABLE_NAME .".id   WHERE ". Test::TABLE_NAME .".user_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $tests = array();
        foreach($result as $row)
        {
            $test = new Test();
            $test->settestId($row["test_id"]);
            $test->setTestDate($row["test_date"]);
            $test->setResult($row["result"]);
            $test->setDocument($row["document"]);
            $tests[] = $test;
        }
        return $tests;

    }

    public static function getTestsOfUserFuture(int $id, PDO $conn) : array
    {

        

        $sql = "SELECT * FROM ".Test::TABLE_NAME ." JOIN ".User::TABLE_NAME .
            " ON ".Test::TABLE_NAME .".user_id =  ".User::TABLE_NAME .".id   WHERE ". Test::TABLE_NAME .".user_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $tests = array();
        foreach($result as $row)
        {
            $test = new Test();
            $test->settestId($row["test_id"]);
            $test->setTestDate($row["test_date"]);
            $test->setResult($row["result"]);
            $test->setDocument($row["document"]);
            $tests[] = $test;
        }
        return $tests;

    }

    public static function getTestsOfUserPast(int $id, PDO $conn) : array
    {
        $DateAndTime = date('m-d-Y h:i:s a', time());

        

        $sql = "SELECT * FROM ".Test::TABLE_NAME ." JOIN ".User::TABLE_NAME .
            " ON ".Test::TABLE_NAME .".user_id =  ".User::TABLE_NAME .".id   WHERE ". Test::TABLE_NAME .".user_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $tests = array();
        foreach($result as $row)
        {
            $test = new Test();
            $test->settestId($row["test_id"]);
            $test->setTestDate($row["test_date"]);
            $test->setResult($row["result"]);
            $test->setDocument($row["document"]);
            $tests[] = $test;
        }
        return $tests;

    }
}
