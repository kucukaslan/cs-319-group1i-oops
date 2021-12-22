<?php
    require_once( rootDirectory().'/constants.php'); // all constants are defined here
    require_once( rootDirectory().'/util/ConsoleLogger.php');
    const SESSION_TIMEOUT_DURATION = 600; // in seconds
    const SESSION_REGENERATE_ID_DURATION = 300; // in seconds
    function getDatabaseConnection() : PDO {
        try {
            $conn = new PDO("mysql:host=".servername.";dbname=".dbname,username, password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
            //echo '<script>console.log("'.addslashes(DirectoryManager::rootDirectory()).'")</script>';
            return $conn;

        } catch(PDOException $e) {

            error_log( servername ." ". username);
            error_log( "Connection failed: " . $e->getMessage());
            // todo we might want to redirect to a proper error page from here
            exit(); // stop the script
        }
    }

    /*
    * Start a session with the default settings
    */
    // __PREV_ACTIVITY is hold only for debugging purposes
    //  Refer to discussion https://stackoverflow.com/a/1270960/13555389
    function startDefaultSessionWith(array $options = array() ): bool {
        header('Cache-Control: no cache'); //no cache
        session_cache_limiter('nocache'); // back page fix
        $result = session_start($options);


        
        if (!isset($_SESSION['__LAST_ACTIVITY'])) { // if session variable not set, set it
            $_SESSION['__LAST_ACTIVITY'] = time();
            $_SESSION['__PREV_ACTIVITY'] = $_SESSION['__LAST_ACTIVITY'];  // update PREV activity time

        } 
        else if (time() - $_SESSION['__LAST_ACTIVITY'] > SESSION_TIMEOUT_DURATION) {// if last request was more than 10 minutes log the user out.
            //header("location: logout.php"); 
            // Clear all the session variables
            $_SESSION = array();
            /*
                for some reason that I couldn't understand as of "1638571984", 
                calling session_destroy() does avoid the automatical logout 
            */
            // Destroy the session
            //session_destroy();

        } 
        else if (time() - $_SESSION['__LAST_ACTIVITY'] > SESSION_REGENERATE_ID_DURATION) { // if last request was more than 5 minutes ago regenerate session (id)
            session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
            $_SESSION['__PREV_ACTIVITY'] = $_SESSION['__LAST_ACTIVITY'];  // update PREV activity time
            $_SESSION['__LAST_ACTIVITY'] = time();  // update last activity time
        } 
        else {
            $_SESSION['__PREV_ACTIVITY'] = $_SESSION['__LAST_ACTIVITY'];  // update PREV activity time
            $_SESSION['__LAST_ACTIVITY'] = time();  // update last activity time
        }
        return $result;

    }

    // Derived from https://stackoverflow.com/a/64927245/13555389
    function rootDirectory() : string
    {
        return dirname(__FILE__);
    }

    function getConsoleLogger() : ConsoleLogger
    {
        return ConsoleLogger::getInstance();
    }