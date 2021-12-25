<?php
declare(strict_types=1);
require_once(__DIR__."/../config.php");
require_once(rootDirectory()."/util/EventFactory.php");

use PHPUnit\Framework\TestCase;

// the terminal command is (for windows):
// .\vendor\bin\phpunit tests
final class EventDatabaseTest extends TestCase
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
    public function testGetEvents(PDO $conn): array
    {
        $ef = new EventFactory($conn);
        $sev = $ef->getEvents( SportsEvent::TABLE_NAME);
        foreach($sev as $e)
        {
            $this->assertInstanceOf(Event::class, $e);
            $this->assertInstanceOf(SportsEvent::class, $e);
        }
        // echo "-----------------------------------------------------\n";
        // var_dump($sev);

        $cev = $ef->getEvents( CourseEvent::TABLE_NAME);
        foreach($cev as $e)
        {
            $this->assertInstanceOf(Event::class, $e);
            $this->assertInstanceOf(CourseEvent::class, $e);
        }
        // echo "-----------------------------------------------------\n";
        // var_dump($cev);

        $tev = $ef->getEvents( TestAppointmentEvent::TABLE_NAME);
        foreach($tev as $e)
        {
            $this->assertInstanceOf(Event::class, $e);
            $this->assertInstanceOf(TestAppointmentEvent::class, $e);
        }
        // echo "-----------------------------------------------------\n";
        // var_dump($tev);

        return $cev;
    }

    /**
     * @depends testDatabaseConnection
     * @depends testGetEvents
     */
    public function testGetControllerPartipant(PDO $conn,  array $events): array{
        //var_dump($events);
        $ev = $events[array_key_first($events)];
        $this->assertInstanceOf(Event::class, $ev);

        $ctrl = $ev->getControllers();
        $ptcp = $ev->getParticipants();

        foreach($ctrl as $c)
        {
            $this->assertInstanceOf(User::class, $c);
        }
        foreach($ptcp as $c)
        {
            $this->assertInstanceOf(User::class, $c);
        }
        // var_dump($ctrl);
        // var_dump($ptcp);
        return $ptcp;
    }

    /**
     * @depends testDatabaseConnection
     * @depends testGetControllerPartipant
     */
    /*
    public function testGetParticipant(PDO $conn, array $ptcp): array{

        foreach($ptcp as $c)
        {
            $c->setDatabaseConnection($conn);
            $this->assertInstanceOf(User::class, $c);
            $c->joinEvent(3);
        }
        // var_dump($ctrl);
        // var_dump($ptcp);
        return $ptcp;
    }
    */

    /**
     * @depends testDatabaseConnection
     */
    public function testCreateEvent(PDO $conn){
        $ef = new EventFactory($conn);

        $ce = $ef->getEvent(1);

        $this->assertInstanceOf(CourseEvent::class, $ce);
        $this->assertTrue($ce->insertToDatabase());

        $se = $ef->getEvent(2);
        $this->assertInstanceOf(SportsEvent::class, $se);
        $this->assertTrue($se->insertToDatabase());
        
        $tae = $ef->getEvent(5);
        $this->assertInstanceOf(TestAppointmentEvent::class, $tae);
        $this->assertTrue($tae->insertToDatabase());        
    }
    
    /**
     * @depends testDatabaseConnection
    
    public function testGetUsers(PDO $conn)
    {
        $ef = new EventFactory($conn);
        for($i = 0; $i< 10 ; $i++) {
            var_dump( $ef->getEvent($i));
        }
        
    }
     */
}