<?php
require_once("Vaccine.php");
class VaccineFactory
{

    // this function makes a vaccine object from the database using the id
    public function makeVaccineById(PDO $conn, int $id): ?Vaccine
    {
        $sql = "SELECT * FROM ".Vaccine::TABLE_NAME." WHERE vaccine_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(isset($result["vaccine_id"]))
        {
            $vaccine = new Vaccine();
            $vaccine->setId($result["vaccine_id"]);
            $vaccine->setCvxCode($result["cvx_code"]);
            $vaccine->setVaccineName($result["vaccine_name"]);
            $vaccine->setVaccineType($result["vaccine_type"]);
            return $vaccine;
        }
        else
        {
            return null;
        }
    }

        // this function makes a vaccine object from the database
    public function makeVaccineByCvxCode(PDO $conn, int $cvx_code): ?Vaccine
    {
        $sql = "SELECT * FROM ".Vaccine::TABLE_NAME." WHERE cvx_code = :cvx_code";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":cvx_code", $cvx_code);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(isset($result["vaccine_id"]))
        {
            $vaccine = new Vaccine();
            $vaccine->setId($result["vaccine_id"]);
            $vaccine->setCvxCode($result["cvx_code"]);
            $vaccine->setVaccineName($result["vaccine_name"]);
            $vaccine->setVaccineType($result["vaccine_type"]);
            return $vaccine;
        }
        else
        {
            return null;
        }
    }


}