<?php
require_once(__DIR__ . "/../../config.php");
require_once(rootDirectory() . "/util/NavBar.php");
require_once(rootDirectory() . "/util/EventFactory.php");
require_once(rootDirectory() . "/util/UserFactory.php");
startDefaultSessionWith();
$pagename = '/closecontact/see';
ob_start();

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

        echo "event Id is " . $_SESSION["eventToDisplay"] . "<br>";
        $eventToDisplay = $ef->getEvent($_SESSION["eventToDisplay"]);

        // echo $eventToDisplay->getStartDate();

        print_r($eventToDisplay);
        // echo get_class($eventToDisplay);

        // echo gettype($eventToDisplay);
        // echo "<br>";
        $participantsOfTheEvent = $user->getParticipants($eventToDisplay->getEventID());
        // print_r($participantsOfTheEvent);

        $contact_data = [];
        $non_contact_data = [];


        $user->getCloseContacts();
        foreach ($participantsOfTheEvent as $participant) {
            if ($participant->getId() != $user->getId()) {
                if ($user->isContacted($participant->getId())) {
                    $contact_data[] = ["name"=>$participant->getFirstName() . " " . $participant->getLastName(), "id"=>$participant->getId()];
                } else {
                    $non_contact_data[] = ["name"=>$participant->getFirstName() . " " . $participant->getLastName(), "id"=>$participant->getId()];
                }
            }
        }


        // print_r($non_contact_data);
        // echo "<br>";
        // print_r($non_added_data);

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
            'added' => $contact_data,
            "nonAdded" => $non_contact_data,
            "eventName" => $eventToDisplay->getTitle(),
        ]);

        // add a participants as close contact
        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["add"])) {

            $_SESSION["add"] = $_POST["add"];
            echo "inside post if " . $_POST["add"];
            unset($_POST);

            header("Refresh:0");

        } else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_SESSION["add"])){
            $userIdToAdd = $_SESSION["add"];
            unset($_SESSION["add"]);

            echo "added USER WITH ID: " . $userIdToAdd;

            // TODO: add some boundary conditions
            $user->addCloseContact($userIdToAdd, 1);

            header("Refresh:0");
        }

        // remove a participants from the close contact
        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["remove"])) {

            $_SESSION["remove"] = $_POST["remove"];
            echo "inside post if " . $_POST["remove"];
            unset($_POST);
            // echo "<script> document.location.reload() </script>";
            header("Refresh:0");

        } else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_SESSION["remove"])){
            $userIdToRemove = $_SESSION["remove"];
            unset($_SESSION["remove"]);

            echo "removed USER WITH ID: " . $userIdToRemove;

            // TODO: add some boundary conditions
            $user->deleteCloseContact($userIdToRemove);

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