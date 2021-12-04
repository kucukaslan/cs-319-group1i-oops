<?php
require_once 'vendor/autoload.php';

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/templates'),
));
echo $m->render('login', array('title' => 'Login', 'id' => 'University ID', 'pass' => 'Password'));


include("config.php");
include("util/Student.php");

session_start();
$conn = getDatabaseConnection();

if (isset($conn) && $_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from the form
    $userid = $_POST['userid'];
    $password = $_POST['password'];


    // var_dump($std);
    // echo '<br>';

    try {
        $std = new Student($conn, $userid, $password);
        // session_start();
        $_SESSION['firstname'] = $std->getFirstName();
        $_SESSION['lastname'] = $std->getLastName();
        $_SESSION['id'] = $std->getId();
        $_SESSION['email'] = $std->getEmail();
        header("location: .."); //redirect to main page

    } catch (Exception $e) {
        echo "<script type='text/javascript'>alert('" . $e->getMessage() . "');</script>";
    }
}
?>