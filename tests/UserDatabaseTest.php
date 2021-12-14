<?php
declare(strict_types=1);
require_once("config.php");
require_once("util/UserFactory.php");

use PHPUnit\Framework\TestCase;

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
        $uf = new UserFactory(Student::TABLE_NAME);
        $u1 = $uf->makeUserByLogin($conn, 1, 123);

        $this->assertInstanceOf( User::class, $u1);
        $this->assertInstanceOf( Student::class, $u1);

        $id = random_int(10000,99999);
        $u1 = $uf->makeUserByRegister($conn, $id, 123, "UNIT", "TEST", $id."@test.com");
        $this->assertInstanceOf( User::class, $u1);
        $this->assertInstanceOf( Student::class, $u1);
        $this->assertEquals($id, $u1->getId());
        $this->assertEquals("UNIT", $u1->getFirstName());
        $this->assertEquals("TEST", $u1->getLastName());
        $this->assertEquals($id."@test.com", $u1->getEmail());
        $this->assertEquals(123, $u1->getPassword());
        return $u1;
    }
    
    /**
    *   @depends testDatabaseConnection
    */
    public function testCreateAcademicStaff(PDO $conn): AcademicStaff
    {
        $uf = new UserFactory(AcademicStaff::TABLE_NAME);
        $u1 = $uf->makeUserByLogin($conn, 1, 123);

        $this->assertInstanceOf( User::class, $u1);
        $this->assertInstanceOf( AcademicStaff::class, $u1);

        
        $id = random_int(10000,99999);
        $u1 = $uf->makeUserByRegister($conn, $id, 123, "UNIT", "TEST", $id."@test.com");
        $this->assertInstanceOf( User::class, $u1);
        $this->assertInstanceOf( AcademicStaff::class, $u1);
        $this->assertEquals($id, $u1->getId());
        $this->assertEquals("UNIT", $u1->getFirstName());
        $this->assertEquals("TEST", $u1->getLastName());
        $this->assertEquals($id."@test.com", $u1->getEmail());
        $this->assertEquals(123, $u1->getPassword());

        return $u1;
    }

    /**
    *   @depends testDatabaseConnection
    */
    public function testCreateUniversityAdministration(PDO $conn): UniversityAdministration
    {
        $uf = new UserFactory(UniversityAdministration::TABLE_NAME);
        $u1 = $uf->makeUserByLogin($conn, 1, 123);

        $this->assertInstanceOf( User::class, $u1);
        $this->assertInstanceOf( UniversityAdministration::class, $u1);

        
        $id = random_int(10000,99999);
        $u1 = $uf->makeUserByRegister($conn, $id, 123, "UNIT", "TEST", $id."@test.com");
        $this->assertInstanceOf( User::class, $u1);
        $this->assertInstanceOf( UniversityAdministration::class, $u1);
        $this->assertEquals($id, $u1->getId());
        $this->assertEquals("UNIT", $u1->getFirstName());
        $this->assertEquals("TEST", $u1->getLastName());
        $this->assertEquals($id."@test.com", $u1->getEmail());
        $this->assertEquals(123, $u1->getPassword());
        return $u1;
    }
    
    /**
    *   @depends testDatabaseConnection
    */
    public function testCreateSportsCenterStaff(PDO $conn): SportsCenterStaff
    {
        $uf = new UserFactory(SportsCenterStaff::TABLE_NAME);
        $u1 = $uf->makeUserByLogin($conn, 1, 123);

        $this->assertInstanceOf( User::class, $u1);
        $this->assertInstanceOf( SportsCenterStaff::class, $u1);

        
        $id = random_int(10000,99999);
        $u1 = $uf->makeUserByRegister($conn, $id, 123, "UNIT", "TEST", $id."@test.com");
        $this->assertInstanceOf( User::class, $u1);
        $this->assertInstanceOf( SportsCenterStaff::class, $u1);
        $this->assertEquals($id, $u1->getId());
        $this->assertEquals("UNIT", $u1->getFirstName());
        $this->assertEquals("TEST", $u1->getLastName());
        $this->assertEquals($id."@test.com", $u1->getEmail());
        $this->assertEquals(123, $u1->getPassword());


        $u1 = $uf->makeUserById($conn, 1);

        $this->assertInstanceOf( User::class, $u1);
        $this->assertInstanceOf( SportsCenterStaff::class, $u1);
        return $u1;
    }
}