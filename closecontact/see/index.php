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
        echo "<br>";
        $participantsOfTheEvent = $lectureToDisplay->getParticipants();
        print_r($participantsOfTheEvent);

        $added_data = [];
        $non_added_data = [];



        foreach ($participantsOfTheEvent as $participant) {
            if ($participant->isContacted($userId)) {
                $added_data[] = ["name"=>$participant->getFirstName() . $participant->getLastName(), "id"=>$participant->getId()];
            } else {
                $non_added_data[] = ["name"=>$participant->getFirstName() . $participant->getLastName(), "id"=>$participant->getId()];
            }
        }

        echo "<br>";
        print_r($non_added_data);

        echo '<header>';
        $navbar = new NavBar($usertype, $pagename);
        echo $navbar->draw();
        echo "<h2>Event Details Page</h2>";
        echo '</header>';

        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
        ));


        // render participant
        echo $m->render('eventparticipants', [
            'added' => $added_data,
            "nonAdded" => $non_added_data,
            "eventName" => $lectureToDisplay->getTitle(),
        ]);

        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["add"])) {

            $_SESSION["add"] = $_POST["add"];
            echo "inside post if " . $_POST["add"];
            unset($_POST);
            // echo "<script> document.location.reload() </script>";
            header("Refresh:0");

        } else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_SESSION["add"])){
            $userIdToAdd = $_SESSION["add"];
            unset($_SESSION["add"]);

            //if ($user->addCloseContact($userIdToAdd, 1)) {
                echo "added USER WITH ID: " . $userIdToAdd;
            /*} else {
                echo "DID NOT MANAGE TO add " . $userIdToAdd;
            }*/
            // echo "<script> document.location.reload() </script>";
            header("Refresh:0");
        }

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