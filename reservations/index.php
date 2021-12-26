<?php
    include_once("../config.php");
    require_once(rootDirectory()."/util/NavBar.php");
    require_once(rootDirectory() . "/util/UserFactory.php");
    require_once(rootDirectory() . "/util/SportsEvent.php");
    require_once(rootDirectory() . "/util/CustomException.php");
    startDefaultSessionWith();
    ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reservation Management System</title>

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
    } else if ($_SESSION['usertype'] != SportsCenterStaff::TABLE_NAME && $_SESSION['usertype'] != UniversityAdministration::TABLE_NAME) {
        header("Location: ../index.php");
    } else {
        $engine = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
        ));
        $usertype  = $_SESSION['usertype'] ?? SportsCenterStaff::TABLE_NAME;
        $uf = new UserFactory();
        $ef = new EventFactory($conn);
        $user = $uf->makeUserById($conn,$usertype, $_SESSION["id"]);

        $eventsOfUser = $user->getEventsControlledByMe();

        // we need to select sports events from all events where user is controller
        $sportsData_past = array();
        $sportsData_future = array();

        foreach ($eventsOfUser as $event) {
            $eventToCheck = $ef->getEvent($event->getEventID());
            if (get_class($eventToCheck) == "SportsEvent") {
                // format the data that will be given to the mustache engine
                $formattedData = ["firstEl"=>$eventToCheck->getTitle(), "secondEl"=>$eventToCheck->getPlace(), "thirdEl"=>$eventToCheck->getStartDate()->format("d") . "-" .
                    $eventToCheck->getStartDate()->format('M')
                    ,"fourthEl"=>$eventToCheck->getStartDate()->format('h') . ":" . $eventToCheck->getStartDate()->format('i'). "-" .
                    $eventToCheck->getEndDate()->format('h') . ":" . $eventToCheck->getEndDate()->format('i'),
                    "fifthEl"=>$eventToCheck->getCurrentNumberOfParticipants() . "/" . $eventToCheck->getMaxNoOfParticipant(),
                    "eventId"=>$eventToCheck->getEventID()];

                // differentiate past and future sports events
                if ($eventToCheck->getStartDate() <= new DateTime("now")) {
                    $sportsData_past[] = $formattedData;
                } else {
                    $sportsData_future[] = $formattedData;
                }

            }
        }

        $navbar = new NavBar($usertype);
        echo $navbar->draw();
        echo "<div class=\"centerwrapper\">
            <h2>Reservations Page</h2>
    </div>";

        // RENDER HTMLs
        echo $engine->render("list6ColButton", ["row" => $sportsData_future,
            "title"=>"Upcoming Sports Events",
            "column1" => "Name","column2"=>"Place", "column3"=>"Day Slot", "column4"=>"Time Slot", "column5"=>"Quota", "column6"=>"See Participants"]);

        echo $engine->render("list6ColButton", ["row" => $sportsData_past,
            "title"=>"Past Sports Events",
            "column1" => "Name","column2"=>"Place", "column3"=>"Day Slot", "column4"=>"Time Slot", "column5"=>"Quota", "column6"=>"See Participants"]);

        // if see button is pressed go to the reservation/see page
        if(isset($_POST["seeEvent"])) {
            $_SESSION["seeEvent"] = $_POST["seeEvent"];
            header("Location: ../../reservations/see");
        }
    }
    ?>



</div>


</body>
</html>