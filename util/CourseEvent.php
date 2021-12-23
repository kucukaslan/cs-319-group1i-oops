<?php
require_once("Event.php");

class CourseEvent extends Event {

    const TABLE_NAME = "course";
    const TABLE_PREFIX =  parent::TABLE_NAME . "_";
    const semesters = ['FALL', 'SPRING', 'SUMMER'];
    
    // properties
    protected ?int $year;
    protected ?string $semester;


    // todo : muh database ekle, yukarıya table name ekle.
    public function insertToDatabase() : bool
    {
        return false;
    }

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
    
}
