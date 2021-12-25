<?php
include_once("../config.php");
include_once(rootDirectory() . "/util/Student.php");
include_once(rootDirectory() . "/util/UserFactory.php");
require_once rootDirectory() . '/vendor/autoload.php';
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <style>

    </style>
</head>
<body>
<?php
$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
));


startDefaultSessionWith();
$conn = getDatabaseConnection();

if (isset($conn) && $_SERVER["REQUEST_METHOD"] == "POST") {
    // userid and password sent from the form
    $userid = $_POST['userid'];
    $password = $_POST['password'];
    $usermail = $_POST['BilkentEmail'];
    $username = $_POST['UserName'];
    $usersurname = $_POST['UserSurname'];

    $usertype = $_SESSION['usertype'] ?? Student::TABLE_NAME;

    try {
        $uf = new UserFactory();
        $std = $uf->makeUserByRegister($conn, $usertype, $userid, $password, $username, $usersurname, $usermail);
        $_SESSION['firstname'] = $std->getFirstName();
        $_SESSION['lastname'] = $std->getLastName();
        $_SESSION['id'] = $std->getId();
        $_SESSION['email'] = $std->getEmail();

        $std->insertToDatabase();
        header("location: .."); //redirect to main page

    } 
    catch (EmailAlreadyExistsException $e) {
        getConsoleLogger()->log("Debug", $e->getMessage());
        echo $m->render('registration', [
            'id' => 'University ID',
            'pass' => 'Password',
            'name' => 'Your Name',
            'surname' => 'Your Surname',
            'mail' => 'Bilkent Email',
            'emailErr' => 'Email address is already being used! Have you forgotten your credentials?'
        ]);
    }
    catch (UserIdAlreadyExistsException $e) { // id is taken
        getConsoleLogger()->log("Debug", $e->getMessage());
        echo $m->render('registration', [
            'id' => 'University ID',
            'pass' => 'Password',
            'name' => 'Your Name',
            'surname' => 'Your Surname',
            'mail' => 'Bilkent Email',
            'idErr' => 'ID already is already being used! Have you forgotten your credentials?'
        ]);
    } 
    catch(Exception $e) {
        getConsoleLogger()->log("Debug", $e->getMessage());
        echo $m->render('registration', [
            'id' => 'University ID',
            'pass' => 'Password',
            'name' => 'Your Name',
            'surname' => 'Your Surname',
            'mail' => 'Bilkent Email'
        ]);
    }
    
} else {
    echo $m->render('registration', [
        'id' => 'University ID',
        'pass' => 'Password',
        'name' => 'Your Name',
        'surname' => 'Your Surname',
        'mail' => 'Bilkent Email'
    ]);
}
?>
</body>
</html>
