<?php
require_once(__DIR__ . "/../../config.php");
require_once(rootDirectory() . "/util/NavBar.php");
require_once(rootDirectory() . "/util/EventFactory.php");
require_once(rootDirectory() . "/util/UserFactory.php");
startDefaultSessionWith();
$pagename = '/closecontact/see';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Details</title>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
<div class="container">

    <?php
    require_once rootDirectory() . '/vendor/autoload.php';

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
        $usertype = $_SESSION['usertype'] ?? Student::TABLE_NAME;
        $userId = $_SESSION["id"];

        $uf = new UserFactory();
        $ef = new EventFactory($conn);
        $user = $uf->makeUserById($conn, $usertype, $_SESSION["id"]);
        $lectureToDisplay = $ef->getEvent($_SESSION["eventToDisplay"]);
        print_r($lectureToDisplay);

        $participantsOfTheEvent = $lectureToDisplay->getParticipants();

        $added_name_list = [];
        $non_added_name_list = [];

        foreach ($participantsOfTheEvent as $participant) {
            if ($participant->isContacted($userId)) {
                $added_name_list[] = ["name"=>$participant->getName()];
            } else {
                $non_added_name_list[] = ["name"=>$participant->getName()];
            }
        }


        echo '<header>';
        $navbar = new NavBar($usertype, $pagename);
        echo $navbar->draw();
        echo "<h2>Event Details Page</h2>";
        echo '</header>';

        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
        ));


        // render participant
        echo $m->render('lectureparticipants', [
            'added' => $added_name_list,
            "nonAdded" => $non_added_name_list,
            "eventName" => $lectureToDisplay->getTitle(),
        ]);
    }

    ?>
    <div class="centerwrapper">
        <div class="centerdiv">
            <div class="columns is-centered">
                <form method='get' class="box" action="../">
                    <div class="form-group">
                        <input type="submit" class="button button_submit" value="Return to Close Contact Page">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>