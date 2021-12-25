<?php
declare(strict_types=1);
require_once(__DIR__."/../config.php");
require_once(rootDirectory()."/util/EventFactory.php");

use PHPUnit\Framework\TestCase;

// the terminal command is (for windows):
// .\vendor\bin\phpunit tests
final class PCRTestTest extends TestCase
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
    /* muhammet burayı yazdı haberin olsun.
    public function testCreateStudent(PDO $conn) {
        $ef = new EventFactory();
        $e1 = $ef->makeEventByLogin($conn,Student::TABLE_NAME, 1, 123);
    
        $this->assertInstanceOf( Event::class, $e1);
        $this->assertInstanceOf( Student::class, $e1);
    
        $id = random_int(10000,99999);
        $e1 = $ef->makeEventByRegister($conn, Student::TABLE_NAME, $id, 123, "UNIT", "TEST", $id."@test.com");
        $this->assertInstanceOf( Event::class, $e1);
        $this->assertInstanceOf( Student::class, $e1);
        $this->assertEquals($id, $e1->getId());
        $this->assertEquals("UNIT", $e1->getFirstName());
        $this->assertEquals("TEST", $e1->getLastName());
        $this->assertEquals($id."@test.com", $e1->getEmail());
        $this->assertEquals(123, $e1->getPassword());
        return $e1;
        
    }
    */
}