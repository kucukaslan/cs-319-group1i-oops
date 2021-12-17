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
        //var_dump($sev);

        $cev = $ef->getEvents( CourseEvent::TABLE_NAME);
        foreach($cev as $e)
        {
            $this->assertInstanceOf(Event::class, $e);
            $this->assertInstanceOf(CourseEvent::class, $e);
        }

        //var_dump($cev);
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

}