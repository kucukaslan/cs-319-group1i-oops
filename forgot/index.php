<?php
require_once(__DIR__ . "/../config.php");
require_once(rootDirectory() . '/vendor/autoload.php');
require_once(rootDirectory() . "/util/UserFactory.php");
require_once(rootDirectory() . "/util/CustomException.php");

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

        $_SESSION['usertype'] = $usertype;
        echo 111;

        $std = $uf->makeUserById(db: $conn, id: $userid);

        echo 1231;


        $_SESSION['firstname'] = $std->getFirstName();
        $_SESSION['lastname'] = $std->getLastName();
        $_SESSION['id'] = $std->getId();
        $_SESSION['email'] = $std->getEmail();
        echo $m->render('forgot', [
            'title' => 'Forgot Password',
            'id' => 'University ID',
            'sent' => $_SESSION['email'],
        ]);
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;
        $mail->Username = 'ThatIsagoalforitsownsake@gmail.com';
        $mail->Password = 'StackPop';
        $mail->setFrom('ThatIsagoalforitsownsake@gmail.com', 'ForThyHealth');
        $mail->addAddress(/*$_SESSION['email']*/ 'mustafayasiraltunhan@gmail.com', $_SESSION['firstname'] . ' ' . $_SESSION['lastname']);
        $mail->Subject = 'Password Reset';
        $mail->Body = 'password resetted';
        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message sent!';
            //Section 2: IMAP
            //Uncomment these to save your message in the 'Sent Mail' folder.
            if (\PHPMailer\PHPMailer\PHPMailer::save_mail($mail)) {
                echo "Message saved!";
            }
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
} else {
    echo $m->render('forgot', [
        'title' => 'Forgot Password',
        'id' => 'University ID',
    ]);
}
?>
</body>
</html>
