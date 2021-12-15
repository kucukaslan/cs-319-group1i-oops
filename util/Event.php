<?php
require_once("User.php");
class Event
{
    // CONSTANTS
    const TABLE_NAME = "event";

    // database connection and table name
    protected ?PDO $conn;

    // object properties
    protected ?int $eventID;
    protected ?string $title;
    protected ?DateTime $startDate;
    protected ?bool $canPeopleJoin;
    protected ?string $place;
    protected ?int $maxNoOfParticipant;
    protected ?array $participants;
    protected ?int $currentNumberOfParticipants;


    // constructors and methods

    /**
     * @param PDO|null $conn
     * @param int|null $eventID
     * @param string|null $title
     * @param DateTime|null $startDate
     * @param bool|null $canPeopleJoin
     * @param string|null $place
     * @param int|null $maxNoOfParticipant
     * @param User|null $participants
     * @param int|null $currentNumberOfParticipants
     */
    public function __construct(?PDO $conn, ?int $eventID, ?string $title, ?DateTime $startDate, ?bool $canPeopleJoin, ?string $place, ?int $maxNoOfParticipant, $participants, ?int $currentNumberOfParticipants)
    {
        $this->conn = $conn;
        $this->eventID = $eventID;
        $this->title = $title;
        $this->startDate = $startDate;
        $this->canPeopleJoin = $canPeopleJoin;
        $this->place = $place;
        $this->maxNoOfParticipant = $maxNoOfParticipant;
        $this->participants = $participants;
        $this->currentNumberOfParticipants = $currentNumberOfParticipants;
    }


    public function addParticipant(User $user): void
    {
        if( $this->getCurrentNumberOfParticipants() != $this->getMaxNoOfParticipant()) {
            $this->participants[$user->getId()] = $user;
            $this->currentNumberOfParticipants++;

            $this->setCanPeopleJoin($this->getCurrentNumberOfParticipants() != $this->getMaxNoOfParticipant());
        }

        else{
            return;
        }


    }

    public function removeParticipant(User $user): void{
        unset($this->participants[$user->getId()]);
        if($this->currentNumberOfParticipants > 0) {
            $this->currentNumberOfParticipants--;
        }
        $this->setCanPeopleJoin($this->getCurrentNumberOfParticipants() != $this->getMaxNoOfParticipant());

    }


    /**
     * @return array|null
     */
    public function getParticipants(): ?array
    {
        return $this->participants;
    }

    /**
     * @param array|null $participants
     */
    public function setParticipants($participants): void
    {
        $this->participants = $participants;
    }

    /**
     * @return int|null
     */
    public function getCurrentNumberOfParticipants(): ?int
    {
        return $this->currentNumberOfParticipants;
    }

    /**
     * @param int|null $currentNumberOfParticipants
     */
    public function setCurrentNumberOfParticipants(?int $currentNumberOfParticipants): void
    {
        $this->currentNumberOfParticipants = $currentNumberOfParticipants;
    }




    /**
     * @return PDO|null
     */
    public function getConn(): ?PDO
    {
        return $this->conn;
    }

    /**
     * @param PDO|null $conn
     */
    public function setConn(?PDO $conn): void
    {
        $this->conn = $conn;
    }

    /**
     * @return int|null
     */
    public function getEventID(): ?int
    {
        return $this->eventID;
    }

    /**
     * @param int|null $eventID
     */
    public function setEventID(?int $eventID): void
    {
        $this->eventID = $eventID;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
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

    /**
     * @return bool|null
     */
    public function getCanPeopleJoin(): ?bool
    {
        return $this->canPeopleJoin;
    }

    /**
     * @param bool|null $canPeopleJoin
     */
    public function setCanPeopleJoin(?bool $canPeopleJoin): void
    {
        $this->canPeopleJoin = $canPeopleJoin;
    }

    /**
     * @return string|null
     */
    public function getPlace(): ?string
    {
        return $this->place;
    }

    /**
     * @param string|null $place
     */
    public function setPlace(?string $place): void
    {
        $this->place = $place;
    }

    /**
     * @return int|null
     */
    public function getMaxNoOfParticipant(): ?int
    {
        return $this->maxNoOfParticipant;
    }

    /**
     * @param int|null $maxNoOfParticipant
     */
    public function setMaxNoOfParticipant(?int $maxNoOfParticipant): void
    {
        $this->maxNoOfParticipant = $maxNoOfParticipant;
    }




}
