<?php
declare(strict_types=1);
require_once(__DIR__."/../config.php");
require_once("util/UserFactory.php");
require_once("util/VaccineFactory.php");
require_once("util/VaccineManager.php");

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertTrue;

// the terminal command is (for windows):
// .\vendor\bin\phpunit tests
final class VaccineDatabaseTest extends TestCase
{
    private PDO $conn;

    public function testDatabaseConnection(): PDO
    {
        $conn = getDatabaseConnection();
        $this->assertNotNull($conn);
        $this->assertInstanceOf(PDO::class, $conn);
        $this->conn = $conn;
        return $conn;
    }
    
    /**
    *   @depends testDatabaseConnection
    */
    public function testCreateStudent(PDO $conn): Student
    {
        $uf = new UserFactory();
        $u1 = $uf->makeUserByLogin($conn,Student::TABLE_NAME, 1, 123);

        $this->assertInstanceOf( User::class, $u1);
        $this->assertInstanceOf( Student::class, $u1);
        return $u1;
    }

    /**
     * @depends testDatabaseConnection
     */
    public function testCreateVaccine(PDO $conn): Vaccine
    {
        $vax =new Vaccine();
        assertInstanceOf(Vaccine::class, $vax);
        
        $vf =new VaccineFactory();
        $vax =$vf->makeVaccineByCvxCode($conn, 208);
        assertInstanceOf(Vaccine::class, $vax);

        $vax =$vf->makeVaccineById($conn, 1);
        assertInstanceOf(Vaccine::class, $vax);
        
        return $vax;
    }

    /**
     * @depends testDatabaseConnection
     * @depends testCreateStudent
     */
    public function testGetUserVaccine(PDO $conn, User $u1): void
    {
        $vm = new VaccineManager($conn, $u1 -> getId());
        $vaccines = $vm->getUserVaccines();
        assertTrue(is_array($vaccines));
        assertTrue(count($vaccines) > 0);
        assertInstanceOf( Vaccine::class, $vaccines[0] );
        $now = new DateTime();
        $vid = $vaccines[0]->getId();
        $vax = (new VaccineFactory())->makeVaccineById($conn, $vid);
        $vm->insertVaccination($vax, $now);
        $newvaccines = $vm->getUserVaccines();
        $cnt = count($newvaccines);
        assertTrue($cnt == 1 + count($vaccines)); // it really added
        $vm->deleteVaccination( end($newvaccines));
        $deletedVaccines = $vm->getUserVaccines();
        assertTrue( $cnt == 1 + count($deletedVaccines)); // it really deleted

    }
}
?>