<?php
require_once("User.php");
class Event
{
    // CONSTANTS
    const TABLE_NAME = "event";
    const PARTICIPATION_TABLE_NAME = "event_participation";
    const CONTROL_TABLE_NAME = "event_control";
    const CONTACT_TABLE_NAME = "contact";

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
    /*
    * Temporarily placed default arguments however this constructor might be replaced with empty constructor in future! 
    */
    public function __construct(?PDO $conn = null, ?int $eventID = 0, ?string $title = "", ?DateTime $startDate = null, ?bool $canPeopleJoin = false, ?string $place= "", ?int $maxNoOfParticipant = 0, $participants = null, ?int $currentNumberOfParticipants = 0)
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
    public function getControllers($usertype = AcademicStaff::TABLE_NAME): ?array
    {
        if( isset($this->conn) ){  // todo table name 'event_participation' is hardcoded, to be fixed! 
            $sql = 'Select * from '.User::TABLE_NAME. " NATURAL JOIN  ".self::CONTROL_TABLE_NAME.' where event_id = :event_id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['event_id' => $this->getEventID()]);
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $users = [];
            foreach ($row as $user) {
                $users[$user['user_id']] = (new UserFactory())->makeUser( $usertype);
                $users[$user['user_id']]->setDatabaseConnection($this->conn);
                $users[$user['user_id']]->setId($user['user_id']);
                $users[$user['user_id']]->setFirstname($user['name']);
                $users[$user['user_id']]->setLastName($user['lastname']);
                $users[$user['user_id']]->setEmail($user['email']);
                $users[$user['user_id']]->setHESCode($user['hes_code'] ?? "");
            }           
            $this->setParticipants( $users);
        }
        return $this->participants;
    }

    /**
     * @return array|null
     */
    public function getParticipants($usertype = Student::TABLE_NAME): ?array
    {
        if( isset($this->conn) ){  // todo table name 'event_participation' is hardcoded, to be fixed! 
            $sql = 'Select * from '.User::TABLE_NAME. " NATURAL JOIN  ".self::PARTICIPATION_TABLE_NAME.' where event_id = :event_id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['event_id' => $this->getEventID()]);
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $users = [];
            foreach ($row as $user) {                
                $users[$user['user_id']] = (new UserFactory())->makeUser( $usertype);
                $users[$user['user_id']]->setDatabaseConnection($this->conn);
                $users[$user['user_id']]->setId($user['user_id']);
                $users[$user['user_id']]->setFirstname($user['name']);
                $users[$user['user_id']]->setLastName($user['lastname']);
                $users[$user['user_id']]->setEmail($user['email']);
                $users[$user['user_id']]->setHESCode($user['hes_code'] ?? "");
            }           
            $this->setParticipants( $users);
        }
        return $this->participants;
    }



    /**
     * @param array|null $participants
     */
    public function setParticipants(array $participants): void
    {
        $this->participants = $participants;
    }

    /**
     * @return int|null
     */
    public function getCurrentNumberOfParticipants(): ?int
    {
        if( isset($this->conn) ){  // todo table name 'event_participation' is hardcoded, to be fixed! 
            $sql = 'Select count(*) as count from event_participation where event_id = :event_id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['event_id' => $this->getEventID()]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->setCurrentNumberOfParticipants($row['count']);
        }
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
