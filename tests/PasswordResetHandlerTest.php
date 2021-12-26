<?php
declare(strict_types=1);
require_once(__DIR__."/../config.php");
require_once(rootDirectory()."/util/PasswordResetHandler.php");

use PHPUnit\Framework\TestCase;

// the terminal command is (for windows):
// .\vendor\bin\phpunit tests
final class PasswordResetHandlerTest extends TestCase
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
    public function testPRH(PDO $conn) {
        $prh = new PasswordResetHandler($conn);
        // $this->assertTrue($prh->updatePassword("7064113da3f5c27c2c00097649ff8fa2c8c3f0f1a50fc97ff842ee59756f2af9", 123));
        
        $token = $prh->generateTokenForUser(1);
        $this->assertTrue($prh->verifyExistenceOfToken($token));
        $newToken = $prh->generateTokenForUser(1);
        $this->assertNotTrue($prh->verifyExistenceOfToken($token));
        $this->assertTrue($prh->verifyExistenceOfToken($newToken));
        $this->assertTrue($prh->updatePassword($newToken, 123));   
    }

}