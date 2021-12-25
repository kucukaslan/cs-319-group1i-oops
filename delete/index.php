<?php 
    include_once("../config.php");
    startDefaultSessionWith();
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events <?php echo isset($_SESSION['firstname']) ? " of ".$_SESSION['firstname'] : "" ?></title>

    <link rel="stylesheet" href="../styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
<div class="container">

    <?php
    require_once rootDirectory().'/vendor/autoload.php';

    $conn = getDatabaseConnection();

    if (!isset($_SESSION['id'])) {
        echo "<div class='centerwrapper'> <div class = 'centerdiv'>"
            . "You haven't logged in";
        echo "<form method='get' action=\"..\"><div class=\"form-group\">
                        <input type=\"submit\" class=\"button button_submit\" value=\"Go to Login Page\">
                    </div> </form>";
        echo "</div> </div>";
        exit();
    } else {
        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/../templates'),
        ));
        echo $m->render('navbar', ['links' => [
                ['href' => '../', 'title' => 'Main Menu'],
                ['href' => '../events', 'title' => 'Events'],
                ['href' => '../closecontact', 'title' => 'Close Contact'],
                ['href' => '../profile', 'title' => 'Profile'],
                ['href' => '../logout.php', 'title' => 'Logout', 'id' => 'logout']]]
        );

    }

    ?>
    <div class="centerwrapper">
        <div class="centerdiv">
            <br><br>
            <h2>DELETE USER PAGE</h2>
            <br>
        </div>
    </div>

</div>


</body>
</html>