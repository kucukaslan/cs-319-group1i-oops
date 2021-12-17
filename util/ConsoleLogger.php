<?php 


/**
 * It writes 'echo'es a javascript console log message
 * surrounded with the <script> tags.
 * beware of the place you trying to insert.
 */
class ConsoleLogger {
    private static $instance;
    
    private function __construct() {
    }

    /**
     * by default it includes the current datetime, but can be disabled with third argument.
     */
    public function log(string $tag, string $message, bool $timestamp = true) {
        // uncomment only in local development environment!
        // DO NOT FORGET TO ADD TODO when you uncommented
        // and comment it out when you are done with debugging
        
        // echo '<script> console.log(\''.$tag. " : ".($timestamp ? date("Y-m-d H:i:s").": " : "" ).$message.'\')</script>';
    }

    public static function getInstance() : ConsoleLogger {
        if (self::$instance == null) {
            self::$instance = new ConsoleLogger();
        }
        return self::$instance;
    }

}