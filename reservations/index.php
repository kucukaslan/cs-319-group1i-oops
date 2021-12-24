<?php
    include_once(__DIR__."../config.php");
    require_once(rootDirectory()."/util/NavBar.php");
    require_once(rootDirectory() . "/util/UserFactory.php");
    require_once(rootDirectory() . "/util/SportsEvent.php");
    require_once(rootDirectory() . "/util/CustomException.php");
    startDefaultSessionWith();
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
    } else {
        $engine = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
        ));
        $usertype  = $_SESSION['usertype'] ?? SportsCenterStaff::TABLE_NAME;
        $uf = new UserFactory();
        $user = $uf->makeUserById($conn,$usertype, $_SESSION["id"]);

        $eventsOfUser = $user->getEventsIParticipate(SportsEvent::TABLE_NAME);

        // add sportsevents  to events  list
        $i = 0;
        foreach($eventsOfUser as $sportsEvent) {
            // mustafa "datetime"ı string'e çevirmerk için "format" metodunu kullanabilirsin
            $events_list[] = ["firstEl" => $sportsEvent->getPlace(), "secondEl" => $sportsEvent->getStartDate()->format("Y-m-d H:i:s")/* ->format(DateTimeInterface::RFC3339)*/, 
                "Quota" => $sportsEvent->getCurrentNumberOfParticipants() . " /" . $sportsEvent->getMaxNoOfParticipant(),
                "id" => $sportsEvent->getEventId()];
            // get all participants in a specific event
            $participantsList = $sportsEvent->getParticipants();

            $i++;
        }




        $navbar = new NavBar($usertype);
        echo $navbar->draw();
        echo "<div class=\"centerwrapper\">
            <h2>Reservations Page</h2>
    </div>";


        echo $engine->render("list5ColButton", ["row" => $events_list,
            "title"=>"Upcoming Events",
            "column1"=>"Place", "column2"=>"Day Slot", "column3"=>"Time Slot", "column4"=>"Quota", "column5"=>"See Participants"]);

        echo $engine->render("list5ColButton", ["row" => [
            ['firstEl' => 'Main Sports Hall', 'secondEl' => '12.2', "thirdEl"=>"13-12", "fourthEl"=>"10/40","buttonName"=>"See", "buttonLink"=>"../../reservations/see"],
            ['firstEl' => 'Main Sports Hall', 'secondEl' => '12.2', "thirdEl"=>"13-12", "fourthEl"=>"10/40","buttonName"=>"See", "buttonLink"=>"../../reservations/see"]],
            "title"=>"Past Events",
            "column1"=>"Place", "column2"=>"Day Slot", "column3"=>"Time Slot", "column4"=>"Quota", "column5"=>"Past Participants"]);

    }

    ?>



</div>


</body>
</html>