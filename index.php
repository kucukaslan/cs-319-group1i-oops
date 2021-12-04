<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main Page</title>
    <link rel="stylesheet" href="./styles.css">

    <meta name="author" content="CS319">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
<!--
<header class="navbar">
    <div class="navbar-text">Simple e-trade app <div class="navbar-text" style="text-align:right">Logout</div>
    </div>
</header>
-->
<div class="container">
    <p id='info'>
        <?php
        // Why do we try to connect database before user is logged in? (talking specifically for this page)
        include("config.php");
        session_start();
        $conn = getDatabaseConnection();

        if (!isset($_SESSION['id']) || !isset($conn)) {
            header("location: ./login");
        } else {
            $id = $_SESSION['id'];
            require_once './vendor/autoload.php';
            $navbar = new Mustache_Engine(array(
                'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/templates'),
            ));
            echo $navbar->render('navbar', ['links' => [
                    ['href' => './', 'title' => 'Main Menu'],
                    ['href' => './events', 'title' => 'Events'],
                    ['href' => './closecontact', 'title' => 'Close Contact'],
                    ['href' => './profile', 'title' => 'Profile'],
                    ['href' => './logout.php', 'title' => 'Logout', 'id' => 'logout']]]
            );
            $welcome = new Mustache_Engine(array(
                'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/templates'),
            ));
            echo $welcome->render('welcome', ['firstname' => $_SESSION['firstname'], 'lastname' => $_SESSION['lastname']]
            );
            echo $welcome->render('markup', [
                    'i' => true,
                    'top' => 'Welcome to the',
                    'markup' => ['top' => 'smallest', 'bm' => false, 'b' => true, 'markup' => false, 'bot' => false
                    ], 'bot' => ' ... University Contact Tracing Service, ',
                    'bm' => ['top' => 'dwdw', 'bm' => false]]

            );
            /*
                                 ['text' => 'Welcome to the', 'i' => true],
                    ['text' => 'smallest', 'i' => true, 'b' => true],
                    ['text' => ' ... University Contact Tracing Service, ', 'i' => true],
                    ['text' => 'ever', 'i' => true, 'b' => true],
             * */
        }

        ?>
    </p>
    <div class="centerwrapper">
        <div class="centerdiv">
            <br><br>
            <h2>Some Titles/Tables in the Main Menu</h2>

            <br>
        </div>
    </div>

    <form method='post' action="./profile">
        <div class="form-group">
            <input type="submit" class="button button_submit" value="Go to Profile Page">
        </div>
    </form>
</div>
</body>
</html>

