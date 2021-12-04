<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Page of <?php echo $_SESSION['cname'] ?></title>
    <link rel="stylesheet" href="styles.css">

    <meta name="author" content="Muhammed Can Küçükaslan">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
<div class=\"container\">

    <?php
    require_once(dirname(__FILE__) . '/vendor/autoload.php');


    include(dirname(__FILE__) . "/config.php");
    session_start();
    $conn = getDatabaseConnection();

    if (!isset($_SESSION['id'])) {
        echo "<div class='centerwrapper'> <div class = 'centerdiv'>"
            . "You haven't logged in";
        echo "<form method='get' action=\"..\"><div class=\"form-group\">
                        <input type=\"submit\" class=\"button button_submit\" value=\"Go to Login Page\">
                    </div> </form>";
        echo "</div> </div>";
    } else {
        $navbar = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/templates'),
        ));
        echo $navbar->render('navbar', ['links' => [
                ['href' => '.', 'title' => 'Main Menu'],
                ['href' => './Events', 'title' => 'Events'],
                ['href' => './CloseContact', 'title' => 'Close Contact'],
                ['href' => './Profile', 'title' => 'Profile'],
                ['href' => './Logout', 'title' => 'Logout', 'id' => 'logout']]]
        );

        echo "<h3> <abbr title='Your Majesties, Your Excellencies, Your Highnesses'>Hey</abbr> " . $_SESSION['firstname'] . " </h3>";
        echo "<i> Welcome to the <abbr title='arguably'>smallest</abbr> ... University Contact Tracing Service, <abbr title='of course by us'> <b>ever</b></abbr>!</i>";

    }

    ?>
    <div class="centerwrapper">
        <div class="centerdiv">
            <br><br>
            <h2>User Profile Page</h2>
            <br>
        </div>
    </div>


</div>


</body>
</html>