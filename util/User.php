<?php
require_once ("EventParticipant.php");
require_once("Event.php");
require_once("EventFactory.php");
require_once("CustomException.php");
// Sometimes we just don't care the type of the user,
// so why not allow an instance of User? 
abstract class User implements EventParticipant {
    // CONSTANTS
    const TABLE_NAME = "user";

    // database connection and table name
    protected ?PDO $conn;
    protected ?string $password;

    // object properties
    protected ?int $id;
    protected ?string $firstname;
    protected ?string $lastname;
    protected ?string $email;
    protected ?string $HESCode;
    protected ?string $HESCodeStatus;
    protected ?array $closeContacts;
    protected ?array $eventsIParticipate;


    public function __construct()
    {
        $this->conn = null;
        $this->id = null;
        $this->password = null;
        $this->firstname = null;
        $this->lastname = null;
        $this->email = null;
        $this->HESCode = null;
        $this->closeContacts = null;
        $this->eventsIParticipate = null;
    }


    // someone: spesifik bir user için database'de olan tüm vaccineleri getiren bir metot yaz muh.
    // Muhammed: VaccineManager yapıyor onu. 2021.12.17

    public function getFirstName(): string
    {
        return $this->firstname;
    }
    public function getLastName(): string
    {
        return $this->lastname;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return PDO|null
     */
    public function getConn(): ?PDO
    {
        return $this->conn;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }


    public function verifyPassword(): bool
    {
        // verify password from database 
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE id = :id "; // . $this->id;
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array('id' => $this->id));

        if($stmt->rowCount() < 1) {
            throw new UserDoesNotExistsException();
            return false;
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($row);
        // echo '<br>';


        if (password_verify($this->getPassword(), $row['password_hash'])) {
            $this->firstname = $row['name'];
            $this->lastname = $row['lastname'];
            $this->email = $row['email'];
            $this->password = "The password is verified and no longer needed to be stored!";
            $this->HESCode = $row['hescode'];
            return true;
        } else {
            throw new PasswordIsWrongException();
            $this->id = null;
            return false;
        }
    }

    /*
        This might be an example of template method pattern.
    */

    public function insertToDatabase(): bool
    {
        try {
            $query = "INSERT INTO " . User::TABLE_NAME . " (id, password_hash, name, lastname, email, hescode) VALUES (:id, :password_hash, :name, :lastname, :email, :hescode)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array('id' => $this->id, 'password_hash' => password_hash($this->password, PASSWORD_ARGON2I), 'name' => $this->firstname, 'lastname' => $this->lastname, 'email' => $this->email, 'hescode' => $this->HESCode));

            return insertToSpecializedTable();
        } catch (Exception $e) {
            echo $e->getMessage();
            throw new Exception("Error inserting to database." . $this->getTableName());
            return false;
        }
    }

    protected abstract function insertToSpecializedTable(): bool;

    public function updateToDatabase()
    {
        $query = "UPDATE " . $this->getTableName() . " SET name = :name, lastname = :lastname, email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    }

    public function updateHESCode(string $HESCode): bool
    {
        try {
            $query = "UPDATE " . $this->getTableName() . " SET hescode = :hescode WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':hescode', $HESCode);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function deleteHESCode(): bool
    {
        try {
            $query = "UPDATE " . $this->getTableName() . " SET hescode = NULL WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function updatePassword(string $newPassword)
    {
        $query = "UPDATE " . $this->getTableName() . " SET password_hash = :password_hash WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $hash = password_hash($newPassword, PASSWORD_ARGON2I);
        $stmt->bindParam(':password_hash', $hash);
    }

    public function getTableName(): string
    {
        return get_called_class()::TABLE_NAME;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string|null $HESCode
     */
    public function setHESCode(?string $HESCode): void
    {
        $this->HESCode = $HESCode;
    }

    /**
     * @return string|null
     */
    public function getHESCode(): ?string
    {
        return $this->HESCode;
    }

    public function setDatabaseConnection(PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param int $id is user if to add the database
     * @return bool if addition is successful.
     */
    public function addCloseContact(int $contacted_user_id, int $event_id): bool
    {
        // TODO: write given id to the database
        try {
            $query = "INSERT INTO " . Event::CONTACT_TABLE_NAME . " (main_user_id , contacted_user_id , event_id) 
            VALUES (:id, :contacted_user_id, :event_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array(
                'id' => $this->id,
                'contacted_user_id' => $contacted_user_id,
                'event_id' => $event_id
            ));
            return true;
        } catch (PDOException $e) {
            getConsoleLogger()->log("User Contact", "Cannot add contact");
            return false;
        }
    }

    /*
    * if the event_id is not given then all the contact relations with that user will be deleted
    * if the event_id is given then only the relations on that event will be deleted
    */
    public function deleteCloseContact(int $contacted_user_id, int $event_id = -1): bool
    {
        try {
            $sql = "DELETE FROM " . Event::CONTACT_TABLE_NAME . " WHERE " . Event::CONTACT_MAIN_USER_ID_COLUMN . " = :id AND " .
                Event::CONTACT_CONTACTED_USER_ID_COLUMN . " = :contacted_user_id " . ($event_id == -1 ? "" : " AND event_id = :event_id");
            $stmt = $this->conn->prepare($sql);
            $id = $this->id;
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':contacted_user_id', $contacted_user_id);
            if ($event_id != -1) {
                $stmt->bindParam(':event_id', $event_id);
            }
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            getConsoleLogger()->log("User Contact", "Cannot delete contact");
            return false;
        }
    }

    /**
     * @return array of closed contacts
     * each are Student objects whose "name", "lastname" and "email" are set.
     * if the event_id is given then only the contacts of that event will be returned.
     * else all the contacts will be returned, though it may contain repeating entries as of 2021.12.22 00:44.
     */
    public function getCloseContacts(int $event_id = -1): array
    {
        try {
            $sql = "SELECT * FROM " . Event::CONTACT_TABLE_NAME . " JOIN " . User::TABLE_NAME . ' ON '
                . Event::CONTACT_TABLE_NAME . '.' . Event::CONTACT_CONTACTED_USER_ID_COLUMN . ' = ' . User::TABLE_NAME . ".id  WHERE main_user_id = :id"
                . ($event_id == -1 ? "" : " AND event_id = :event_id");

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $this->id);
            if ($event_id != -1) {
                $stmt->bindParam(':event_id', $event_id);
            }
            $stmt->execute();
            $result = $stmt->fetchAll();
            $this->closeContacts = array();
            foreach ($result as $row) {
                // todo User'ın type'ının bir önemi yok
                // User class'ı da abstract şimdilik Student olarak oluşturuyorum.
                $u = new Student();
                $u->setId($row['id']);
                $u->setFirstname($row['name']);
                $u->setLastname($row['lastname']);
                $u->setEmail($row['email']);
                $this->closeContacts[$row['id']] = $u;
            }
            return $this->closeContacts;
        } catch (PDOException $e) {
            getConsoleLogger()->log("User Contact", $e->getMessage());
            throw $e;
        }
    }

    public function getEventsIParticipate( $event_type =Event::TABLE_NAME ): array {
        $sql = "SELECT * FROM ". $event_type." NATURAL JOIN ".Event::PARTICIPATION_TABLE_NAME." WHERE user_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $this->eventsIParticipate = array();
        $ef = new EventFactory($this->conn);
        foreach ($result as $row) {
            $e = $ef->makeEventFromDBRow($event_type, $row);

            $this->eventsIParticipate[$row['event_id']] = $e;
        }
        return $this->eventsIParticipate;
    }

    public function doIParticipate(int $eventId): bool {
        foreach ($this->eventsIParticipate as $event) {
            if ($eventId == $event->getEventId()) {
                return true;
            }
        }
        return false;
    }

    public function getEventsControlledByMe() : array {
        $sql = "SELECT * FROM ". Event::TABLE_NAME." NATURAL JOIN ".Event::CONTROL_TABLE_NAME."  WHERE user_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $events = array();
        foreach ($result as $row) {
            $e = new Event();
            $e->setEventId($row['event_id']);
            $e->setTitle($row['event_name']);
            $e->setPlace($row['place']);
            $e->setMaxNoOfParticipant($row['max_no_of_participant']);
            $e->setCanPeopleJoin($row['can_people_join']);
            $events[$row['event_id']] = $e;
        }
        return $events;
    }

    /*
    * Return the participants of the specified event
    * users are Student objects whose "name", "lastname" and "email" are set,
    * as well as their ids.
    */
    public function getParticipants(int $eventId): array {
        $sql = "SELECT * FROM ".User::TABLE_NAME. " INNER JOIN " . Event::PARTICIPATION_TABLE_NAME." ON user_id = id WHERE event_id = :event_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':event_id', $eventId);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $users = array();
        $uf = new UserFactory();
        foreach ($result as $row) {
            $u = $uf->makeUserByInformation($this->conn, Student::TABLE_NAME,
                $row['user_id'], $row['name'], $row['lastname'], $row['email']);
            $users[$row['user_id']] = $u;
        }
        return $users;
    }


    /**
     * @param int $userId to search in close contacts
     * @return bool if user is contacted with user specified with id
     */
    public function isContacted(int $userId): bool {
        if ($this->closeContacts == null)
            return false;
        foreach ($this->closeContacts as $contact) {
            if ($userId == $contact->getId()) {
                return true;
            }
        }
        return false;
    }

    public function joinSportsActivity(int $activityId): bool {
        return $this->joinEvent($activityId);
    }

    public function cancelSportsAppointment(int $activityId): bool {
        return $this->leaveEvent($activityId);
    }

    public function joinEvent(int $event_id): bool {
        try {
            $sql = "INSERT INTO " . Event::PARTICIPATION_TABLE_NAME . " (user_id, event_id) VALUES (:user_id, :event_id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $this->id);
            $stmt->bindParam(':event_id', $event_id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            getConsoleLogger()->log("User Join Event", $e->getMessage());
            //throw $e;
            return false;
        }
    }

    public function leaveEvent(int $event_id): bool {
        try {
            $sql = "DELETE FROM " . Event::PARTICIPATION_TABLE_NAME . " WHERE user_id = :user_id AND event_id = :event_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $this->id);
            $stmt->bindParam(':event_id', $event_id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            getConsoleLogger()->log("User Leave Event", $e->getMessage());
            //throw $e;
        }
    }
}
