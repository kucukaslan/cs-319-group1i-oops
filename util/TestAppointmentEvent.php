<?php
require_once("Event.php");

class TestAppointmentEvent extends Event {

    const TABLE_NAME = "test_appointment";
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



    // todo : muh database ekle, yukarıya table name ekle.
    public function insertToDatabase() : bool
    {
        return false;
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
