<?php

require_once(__DIR__."/../util/SportsEvent.php");
require_once(__DIR__."/../util/CourseEvent.php");

class EventFactory {

    private PDO $conn;
    function __construct( PDO $databaseConnection ) {
        $this->conn = $databaseConnection;
    }

    public function getEvents(string  $type) : array | null {
        if($type == SportsEvent::TABLE_NAME)
            $sql = "SELECT * FROM ". SportsEvent::TABLE_NAME;
        else if ($type == CourseEvent::TABLE_NAME)
            $sql = "SELECT * FROM ". CourseEvent::TABLE_NAME;
        else
            return null;

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();//[$type]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $events = array();
        foreach($result as $row)
        {
            $id =$row["event_id"];
            $event = $this->makeEvent($type);
            $event->setConn($this->conn);
            $event->setEventID($id);
            $event->setTitle($row["event_name"]);
            $event->setPlace($row["place"]);
            $event->setMaxNoOfParticipant($row['max_no_of_participant']);

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

    private function makeEvent(String $type) : Event {
        if($type == SportsEvent::TABLE_NAME)
            return new SportsEvent();
        else if ($type == CourseEvent::TABLE_NAME)
            return new CourseEvent();
        else
            return null;
    }
}
?>