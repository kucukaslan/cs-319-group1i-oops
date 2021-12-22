<?php

require_once(__DIR__."/../util/SportsEvent.php");
require_once(__DIR__."/../util/CourseEvent.php");
require_once(__DIR__."/../util/TestAppointmentEvent.php");

class EventFactory {

    private PDO $conn;
    function __construct( PDO $databaseConnection ) {
        $this->conn = $databaseConnection;
    }

    public function getEvents(string  $event_type) : array | null {
        if($event_type == SportsEvent::TABLE_NAME)
            $sql = "SELECT * FROM ". SportsEvent::TABLE_NAME;
        else if ($event_type == CourseEvent::TABLE_NAME)
            $sql = "SELECT * FROM ". CourseEvent::TABLE_NAME;
        else if ($event_type == TestAppointmentEvent::TABLE_NAME)
            $sql = "SELECT * FROM ". TestAppointmentEvent::TABLE_NAME;
        else
            return null;

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();//[$event_type]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $events = array();
        foreach($result as $row)
        {
            $id =$row["event_id"];
            $event =  $this->makeEventFromDBRow($event_type, $row);

            // todo table name 'event_participant' is hardcoded, to be fixed! 
            
            try {
                $sql = 'Select count(*) as count from '.Event::PARTICIPATION_TABLE_NAME.' where event_id = :event_id';
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(":event_id",$id);
                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $event->setCurrentNumberOfParticipants($row['count']);
            }
            catch(Exception $e) {
                //require_once(__DIR__."/../util/Logger.php");
                error_log( strval($e));
            }

            $events[$id] = $event;
        }

        //var_dump($events);
        return $events;
    }

    public function getEvent(int $eventId, string $event_type = Event::TABLE_NAME): ?Event {
        $sql = "SELECT * FROM $event_type where event_id = :event_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":event_id", $eventId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result == null)
            return null;
        else
        {
            return $this->makeEventFromDBRow($event_type, $result);
        }
    }

    private function makeEvent(string $type) : null| Event | SportsEvent | CourseEvent | TestAppointmentEvent {
        if($type == SportsEvent::TABLE_NAME)
            return new SportsEvent();
        else if ($type == CourseEvent::TABLE_NAME)
            return new CourseEvent();
        else if ($type == TestAppointmentEvent::TABLE_NAME)
            return new TestAppointmentEvent();
        else
            return new Event();
    }

    /*
    * The type is the table name of the event, which is stored in TABLE_NAME field of the corresponding Event subclass.
    * The row is the row of the table, which is returned by the database, and is an associative array.
    * although row is meant to obtained from db query, it is allowed to use any associative array (created in any way)
    * provided that the keys of the array are the same as the column names of the table. 
    * if any of the expected keys is not found in the array, it throws Exception!
    * if the row is null, then the function returns null.
    */
    public function makeEventFromDBRow(string $type, array $row) : null| Event | SportsEvent | CourseEvent | TestAppointmentEvent {
        $event = $this->makeEvent($type);
        $event->setConn($this->conn);
        $event->setEventID($row['event_id']);
        $event->setTitle($row["event_name"]);
        $event->setPlace($row["place"]);
        $event->setMaxNoOfParticipant($row['max_no_of_participant']);
        if($type == SportsEvent::TABLE_NAME || $type == TestAppointmentEvent::TABLE_NAME) {
            $event->setStartDate(new DateTime($row['start_date']));
            $event->setEndDate(new DateTime($row['end_date']));
        }
        else if ($type == CourseEvent::TABLE_NAME) {
            $event->setYear($row['year']);
            $event->setSemester($row['semester']);
        }   
        return $event;
    }
}
?>