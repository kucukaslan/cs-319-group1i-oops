<?php
include_once("../config.php");
include_once(rootDirectory() . "/util/Student.php");
require_once rootDirectory() . '/vendor/autoload.php';

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
));
echo $m->render('registration', array('title' => 'Registration', 'id' => 'University ID', 'pass' => 'Password',
    'name' => 'Your Name','surname' => 'Your Surname','mail' => 'Bilkent Email'));

startDefaultSessionWith();
$conn = getDatabaseConnection();

if (isset($conn) && $_SERVER["REQUEST_METHOD"] == "POST") {
    // userid and password sent from the form
    $userid = $_POST['userid'];
    $password = $_POST['password'];
    $usermail = $_POST['BilkentMail'];
    $username = $_POST['UserName'];
    $usersurname = $_POST['Surname'];


    //$std = new Student()



    try {
        $std = new Student($conn, $userid, $password);
        $_SESSION['firstname'] = $std->getFirstName();
        $_SESSION['lastname'] = $std->getLastName();
        $_SESSION['id'] = $std->getId();
        $_SESSION['email'] = $std->getEmail();
        header("location: .."); //redirect to main page

    } catch (Exception $e) {
        echo "<script type='text / javascript'>alert('" . $e->getMessage() . $_POST['password'] . "');</script>";
    }
}
?>