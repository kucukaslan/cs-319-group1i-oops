<?php

session_start();

// Clear all the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

header('location: ./Login');
