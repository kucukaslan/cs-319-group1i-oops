<?php
require_once("Event.php");

class CourseEvent extends Event {

    const TABLE_NAME = "course";
    const TABLE_PREFIX =  parent::TABLE_NAME . "_";

    // properties



    // todo : muh database ekle, yukarıya table name ekle.
    public function insertToDatabase() : bool
    {
        return false;
    }
}
