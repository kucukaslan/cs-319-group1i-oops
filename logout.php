<?php
require_once(__DIR__ . "/config.php");
startDefaultSessionWith();

// Clear all the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

header('location: ./login');
