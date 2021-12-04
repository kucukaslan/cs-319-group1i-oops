<?php
include_once("config.php");
startDefaultSessionWith();

// Clear all the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

header('location: ./login');
