<?php
require_once(__DIR__ . "/../config.php");
require_once(rootDirectory() . '/vendor/autoload.php');
require_once(rootDirectory() . "/util/UserFactory.php");
require_once(rootDirectory() . "/util/CustomException.php");
require_once(rootDirectory() . "/util/PasswordResetHandler.php");

use League\OAuth2\Client\Provider\Google;

//Alias the League Google OAuth2 provider class

?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
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
    $usertype = $_POST['usertype'] ?? User::TABLE_NAME;


    try {
        $uf = new UserFactory();

        $std = $uf->makeUserById(db: $conn, id: $userid);


        echo $m->render('forgot', [
            'title' => 'Forgot Password',
            'id' => 'University ID',
            'sent' => $std->getEmail(),
        ]);

        $prh = new PasswordResetHandler($conn);
        
        
        if (!$prh->sendPasswordResetEmail($std)) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message sent!';
            //Section 2: IMAP
            //Uncomment these to save your message in the 'Sent Mail' folder.
            /*if (\PHPMailer\PHPMailer\PHPMailer::save_mail($mail)) {
                echo "Message saved!";
            }
            */
        }

    } catch (UserDoesNotExistsException $e) {
        echo $m->render('forgot', [
            'title' => 'Forgot Password',
            'id' => 'University ID',
            'idErr' => 'No user with the given University ID. Have you registered? Have you chosen correct user type?',
        ]);
    } catch (Exception $e) {
        echo $m->render('forgot', [
            'title' => 'Forgot Password',
            'id' => 'University ID',
            'pass' => 'Password'
        ]);
    }
} 
else if(isset($conn) && $_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['pwr'])) {
    $pwr = $_GET['pwr'];

    $prh = new PasswordResetHandler($conn);

    if($prh->verifyExistenceOfToken($pwr)) {
        //
        echo "Token is valid";
        echo "take new password from user";
        echo "update password";
        //todo  TAKE NEW PASSWORD FROM USER
        $newPassword = "123";
        //todo CALL UPDATE PASSWORD METHOD
        if( $prh->updatePassword($pwr, $newPassword)) {
            echo "password is changed";
        } else {
            echo "password is not changed";
        }

    } else {
        echo $m->render('forgot', [
            'title' => 'Forgot Password',
            'id' => 'University ID',
            'pass' => 'Password',
            'pwrErr' => 'Invalid token. Please try again.',
        ]);
    }
}
else {
    echo $m->render('forgot', [
        'title' => 'Forgot Password',
        'id' => 'University ID',
    ]);
}
?>


</body>
</html>
