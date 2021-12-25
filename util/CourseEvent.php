<?php
require_once("Event.php");

class CourseEvent extends Event {

    const TABLE_NAME = "course";
    const TABLE_PREFIX =  parent::TABLE_NAME . "_";
    const semesters = ['FALL', 'SPRING', 'SUMMER'];
    
    // properties
    protected ?int $year;
    protected ?string $semester;

    /**
     * @return int|null
     */
    public function getYear(): ?int
    {
        return $this->year;
    }

    /**
     * @param int|null $year
     * @return CourseEvent
     */
    public function setYear(?int $year): CourseEvent
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSemester(): ?string
    {
        return $this->semester;
    }

    /**
     * @param string|null $semester
     * @return CourseEvent
     */
    public function setSemester(?string $semester): CourseEvent
    {
        $this->semester = $semester;
        return $this;
    }

    /*
     * Complete the Insertion of a CourseEvent to the database
     * @return bool
     */
    public function insertToSpecializedTable(): bool
    {
       // var_dump($this);

        try {
            $sql = "INSERT INTO " . CourseEvent::TABLE_PREFIX.CourseEvent::TABLE_NAME . " (event_id, year, semester) VALUES (:event_id, :year, :semester)";
            //echo $sql.PHP_EOL;
            $event_id= $this->getEventID();
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":event_id", $event_id);
            $stmt->bindParam(":year", $this->year);
            $stmt->bindParam(":semester", $this->semester);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
        
    }
    
}
