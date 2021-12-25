<?php

declare(strict_types=1);
require_once(__DIR__ . "/../config.php");
require_once(rootDirectory() . "/util/UserFactory.php");

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertTrue;

// the terminal command is (for windows):
// .\vendor\bin\phpunit tests
final class UserDatabaseTest extends TestCase
{
    public function testDatabaseConnection(): PDO
    {
        $conn = getDatabaseConnection();
        $this->assertNotNull($conn);
        $this->assertInstanceOf(PDO::class, $conn);
        return $conn;
    }

    /**
     *   @depends testDatabaseConnection
     */
    public function testCreateStudent(PDO $conn): Student
    {
        $uf = new UserFactory();
        $user = $uf->makeUserByLogin($conn, Student::TABLE_NAME, 1, 123);

        $this->assertInstanceOf(User::class, $user);
        $this->assertInstanceOf(Student::class, $user);

        $id = random_int(10000, 99999);
        $u1 = $uf->makeUserByRegister($conn, Student::TABLE_NAME, $id, 123, "UNIT", "TEST", $id . "@test.com");
        $this->assertInstanceOf(User::class, $u1);
        $this->assertInstanceOf(Student::class, $u1);
        $this->assertEquals($id, $u1->getId());
        $this->assertEquals("UNIT", $u1->getFirstName());
        $this->assertEquals("TEST", $u1->getLastName());
        $this->assertEquals($id . "@test.com", $u1->getEmail());
        $this->assertEquals(123, $u1->getPassword());
        return $user;
    }

    /**
     *   @depends testDatabaseConnection
     */
    public function testCreateAcademicStaff(PDO $conn): AcademicStaff
    {
        $uf = new UserFactory();
        $u1 = $uf->makeUserByLogin($conn, AcademicStaff::TABLE_NAME, 1, 123);

        $this->assertInstanceOf(User::class, $u1);
        $this->assertInstanceOf(AcademicStaff::class, $u1);


        $id = random_int(10000, 99999);
        $u1 = $uf->makeUserByRegister($conn, AcademicStaff::TABLE_NAME, $id, 123, "UNIT", "TEST", $id . "@test.com");
        $this->assertInstanceOf(User::class, $u1);
        $this->assertInstanceOf(AcademicStaff::class, $u1);
        $this->assertEquals($id, $u1->getId());
        $this->assertEquals("UNIT", $u1->getFirstName());
        $this->assertEquals("TEST", $u1->getLastName());
        $this->assertEquals($id . "@test.com", $u1->getEmail());
        $this->assertEquals(123, $u1->getPassword());

        return $u1;
    }

    /**
     *   @depends testDatabaseConnection
     */
    public function testCreateUniversityAdministration(PDO $conn): UniversityAdministration
    {
        $uf = new UserFactory();
        $u1 = $uf->makeUserByLogin($conn, UniversityAdministration::TABLE_NAME, 1, 123);

        $this->assertInstanceOf(User::class, $u1);
        $this->assertInstanceOf(UniversityAdministration::class, $u1);


        $id = random_int(10000, 99999);
        $u1 = $uf->makeUserByRegister($conn, UniversityAdministration::TABLE_NAME, $id, 123, "UNIT", "TEST", $id . "@test.com");
        $this->assertInstanceOf(User::class, $u1);
        $this->assertInstanceOf(UniversityAdministration::class, $u1);
        $this->assertEquals($id, $u1->getId());
        $this->assertEquals("UNIT", $u1->getFirstName());
        $this->assertEquals("TEST", $u1->getLastName());
        $this->assertEquals($id . "@test.com", $u1->getEmail());
        $this->assertEquals(123, $u1->getPassword());
        return $u1;
    }

    /**
     *   @depends testDatabaseConnection
     */
    public function testCreateSportsCenterStaff(PDO $conn): SportsCenterStaff
    {
        $uf = new UserFactory();
        $u1 = $uf->makeUserByLogin($conn, SportsCenterStaff::TABLE_NAME, 1, 123);

        $this->assertInstanceOf(User::class, $u1);
        $this->assertInstanceOf(SportsCenterStaff::class, $u1);


        $id = random_int(10000, 99999);
        $u1 = $uf->makeUserByRegister($conn, SportsCenterStaff::TABLE_NAME, $id, 123, "UNIT", "TEST", $id . "@test.com");
        $this->assertInstanceOf(User::class, $u1);
        $this->assertInstanceOf(SportsCenterStaff::class, $u1);
        $this->assertEquals($id, $u1->getId());
        $this->assertEquals("UNIT", $u1->getFirstName());
        $this->assertEquals("TEST", $u1->getLastName());
        $this->assertEquals($id . "@test.com", $u1->getEmail());
        $this->assertEquals(123, $u1->getPassword());


        $u1 = $uf->makeUserById($conn, SportsCenterStaff::TABLE_NAME, 1);

        $this->assertInstanceOf(User::class, $u1);
        $this->assertInstanceOf(SportsCenterStaff::class, $u1);
        return $u1;
    }

    /**
     * @depends testDatabaseConnection
     * @depends testCreateStudent
     */
    public function testContact(PDO $conn, Student $student)
    {

        $theRelationExists = $student->deleteCloseContact(2, 2);
        assertTrue($student->addCloseContact(2, 2));

        $contacts = $student->getCloseContacts();
        foreach ($contacts as $contact) {
            $this->assertInstanceOf(User::class, $contact);
        }
        assertTrue($student->deleteCloseContact(2, 2));

        if ($theRelationExists) { // add relation back
            $student->addCloseContact(2, 2);
        }
    }


    /**
     * @depends testDatabaseConnection
     * @depends testCreateStudent
     */
    public function testStudentEventOperation(PDO $conn, Student $student)
    {
        $events = $student->getEventsIParticipate(search_key:"AND");
        foreach ($events as $event) {
            //var_export($event);
            $this->assertInstanceOf(Event::class, $event);
        }
        $controlledevents = $student->getEventsControlledByMe();
        foreach ($controlledevents as $event) {
            $this->assertInstanceOf(Event::class, $event);
        }
        $coparticipants = $student->getParticipants($events[array_key_first($events)]->getEventId());
        foreach ($coparticipants as $participant) {
            $this->assertInstanceOf(User::class, $participant);
        }
        /* 
        var_dump( $events);
        var_dump( $controlledevents);
        var_dump( $coparticipants);
        */
    }
}
