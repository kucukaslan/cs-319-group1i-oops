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
    <title>Reset Password</title>
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

//startDefaultSessionWith();
session_start();
$conn = getDatabaseConnection();

if (isset($conn) && $_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['pwr'])) {
    $pwr = urldecode($_GET['pwr']);
//$_SESSION['token'] = $pwr;
    $prh = new PasswordResetHandler($conn);

    if ($prh->verifyExistenceOfToken($pwr)) {
//todo  TAKE NEW PASSWORD FROM USER
        $crypt_safe_rand_int = random_int(PHP_INT_MIN, PHP_INT_MAX);
        $hash_of_rand_int = hash("sha256", $crypt_safe_rand_int);
        $newPassword = substr(hash("sha256", $hash_of_rand_int), 0, 16);
//todo CALL UPDATE PASSWORD METHOD

        ?>
        <section class="hero is-primary is-fullheight">
            <div class="hero-body">
                <div class="container">
                    <div class="columns is-centered">
                        <div class="column is-two-thirds">
                            <?php if ($prh->updatePassword($pwr, $newPassword)): ?>
                                <article class="message">
                                    <div class="message-header">
                                        <p>Password Reset!</p>
                                    </div>
                                    <div class="message-body">
                                        Your password has been changed to <i><?= $newPassword ?></i>.<br> <a
                                                href="../login">Login Page</a>
                                    </div>
                                </article>
                            <?php else: ?>
                                <article class="message">
                                    <div class="message-header">
                                        <p>Password Not Reset!</p>
                                    </div>
                                    <div class="message-body">
                                        There was an error.<br> <a href="../login">Login Page</a>
                                    </div>
                                </article>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php


    } else {
        header("location: ..");
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
