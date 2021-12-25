<?php
require_once("Event.php");

class SportsEvent extends Event {

    const TABLE_NAME = "sport";
    const TABLE_PREFIX =  parent::TABLE_NAME . "_";



    // properties
    protected ?DateTime $endDate;
    protected ?DateTime $startDate;



    /**
     * @return DateTime|null
     */
    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    /**
     * @param DateTime|null $endDate
     */
    public function setEndDate(?DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }



    public function insertToSpecializedTable(): bool
    {
        try {
            $sql = "INSERT INTO " . SportsEvent::TABLE_PREFIX . SportsEvent::TABLE_NAME . " (event_id, end_date, start_date) VALUES (:event_id, :end_date, :start_date)";
            $stmt = $this->conn->prepare($sql);
            $event_id = $this->getEventID();
            $end = $this->getEndDate()->format(DateTimeInterface::RFC3339);
            $start = $this->getStartDate()->format(DateTimeInterface::RFC3339);
            $stmt->bindParam(":event_id", $event_id);
            $stmt->bindParam(":end_date", $end);
            $stmt->bindParam(":start_date", $start);
            $stmt->execute();
            return true;
        }
        catch(PDOException $e)
        {
            //echo $e->getMessage();
            return false;
        }
    }


    /**
     * @return DateTime|null
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }


    /**
     * @param DateTime|null $startDate
     */
    public function setStartDate(?DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

}
