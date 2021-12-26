<?php
require_once(__DIR__ . "/../config.php");
require_once(rootDirectory() . '/vendor/autoload.php');
require_once(rootDirectory() . "/util/UserFactory.php");
require_once(rootDirectory() . "/util/CustomException.php");
require_once(rootDirectory() . "/util/PasswordResetHandler.php");


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

        /*
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message sent!';
        //Section 2: IMAP
        //Uncomment these to save your message in the 'Sent Mail' folder.
        #if (save_mail($mail)) {
        #    echo "Message saved!";
        #}
    }
        */


        $prh = new PasswordResetHandler($conn);
        ?><?php if (!$prh->sendPasswordResetEmail($std)): ?>
            <article class="message">
                <div class="message-header">
                    <p>Email Error!</p>
                </div>
                <div class="message-body">
                    There was a problem with sending the reset email.
                </div>
            </article>
        <?php endif; ?><?php


        /*else {
            echo 'Message sent!';
            //Section 2: IMAP
            //Uncomment these to save your message in the 'Sent Mail' folder.
            if (\PHPMailer\PHPMailer\PHPMailer::save_mail($mail)) {
                echo "Message saved!";
            }


        }
        */
    } catch
    (UserDoesNotExistsException $e) {
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
} else {
    echo $m->render('forgot', [
        'title' => 'Forgot Password',
        'id' => 'University ID',
    ]);
}
?>


</body>
</html>
