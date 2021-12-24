<?php
require_once(__DIR__ . "/../config.php");
require_once(rootDirectory() . '/vendor/autoload.php');
require_once(rootDirectory() . "/util/UserFactory.php");
require_once(rootDirectory() . "/util/CustomException.php");

?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In</title>
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
    $usertype = $_POST['usertype'] ?? Student::TABLE_NAME;


    try {
        $uf = new UserFactory();
        $_SESSION['usertype'] = $usertype;
        $std = $uf->makeUserByLogin($conn, $usertype, $userid, $password);
        $_SESSION['firstname'] = $std->getFirstName();
        $_SESSION['lastname'] = $std->getLastName();
        $_SESSION['id'] = $std->getId();
        $_SESSION['email'] = $std->getEmail();
        $_SESSION['hescode'] = $std->getHESCode();
        header("location: .."); //redirect to main page

    } 
    catch (UserDoesNotExistsException $e) {
        echo $m->render('login', [
            'title' => 'Login',
            'id' => 'University ID',
            'pass' => 'Password',
            'idErr' => 'No user with the given University ID. Have you registered? Have you chosen correct user type?',
        ]);
        }
    catch(PasswordIsWrongException $e) { 
        echo $m->render('login', [
            'title' => 'Log in',
            'id' => 'University ID',
            'pass' => 'Password',
            'passErr' => 'Password is wrong'
        ]);
    } 
    catch(Exception $e) {
        echo $m->render('login', [
            'title' => 'Login',
            'id' => 'University ID',
            'pass' => 'Password'
        ]);
    }
    } 
else {
    echo $m->render('login', [
        'title' => 'Login',
        'id' => 'University ID',
        'pass' => 'Password'
    ]);
}
?>
</body>
</html>
