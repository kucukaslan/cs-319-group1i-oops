<?php
require_once("Vaccine.php");
class VaccineManager
{
    const RELATION_TABLE_NAME = "vaccine_administration";

    // attributes
    private $conn;
    private $userId;   

    // constructor with user id
    public function __construct(PDO $conn, int $userId)
    {
        $this->conn = $conn;
        $this->userId = $userId;
    }

    // select user's vaccines from the database
    public function getUserVaccines() : array
    {
        $sql = "SELECT * FROM ".self::RELATION_TABLE_NAME." NATURAL JOIN ".Vaccine::TABLE_NAME ."   WHERE user_id = :user_id";
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
    }

    // solves hanoi tower problem
    public function solveHanoiTower(int $n, string $source, string $destination, string $aux) : void
    {
        if($n == 1)
        {
            echo "Move disk 1 from ".$source." to ".$destination."\n";
        }
        else
        {
            $this->solveHanoiTower($n-1, $source, $aux, $destination);
            echo "Move disk ".$n." from ".$source." to ".$destination."\n";
            $this->solveHanoiTower($n-1, $aux, $destination, $source);
        }
    }

    // add a vaccine with administration date to the database
    public function addVaccine(Vaccine $vaccine, DateTime $administrationDate)
    {
        $sql = "INSERT INTO ".self::RELATION_TABLE_NAME." (user_id, vaccine_id, administration_date) VALUES (:user_id, :vaccine_id, :administration_date)";
        $stmt = $this->conn->prepare($sql);
        $vid = $vaccine->getId();
        $date = $administrationDate->format(DateTimeInterface::RFC3339);
        $stmt->bindParam(":user_id", $this->userId);
        $stmt->bindParam(":vaccine_id", $vid);
        $stmt->bindParam(":administration_date",$date );
        $stmt->execute();
    }
}