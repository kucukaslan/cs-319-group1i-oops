<?php
    include_once("../config.php");
    require_once(rootDirectory() . "/util/NavBar.php");
    require_once(rootDirectory() . "/util/UserFactory.php");
    startDefaultSessionWith();
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administration System</title>

    <link rel="stylesheet" href="../styles.css">
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
        $my_engine = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/administration/templates'),
        ));
        $engine = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
        ));

        $usertype = $_SESSION['usertype'] ?? Student::TABLE_NAME;

        $uf = new UserFactory();
        $ef = new EventFactory($conn);
        $user = $uf->makeUserById($conn, $usertype, $_SESSION["id"]);

        $navbar = new NavBar($usertype);
        echo $navbar->draw();
?>
        <h2>Admin Page</h2>
        <?php

        $lecture_key = '';
        $sports_key = "";
        if (isset($_SESSION["lectureToDisplay"])) {
            $lecture_key = $_SESSION["lectureToDisplay"];
            unset($_SESSION["lectureToDisplay"]);

            $lectures = $ef->getEvents(CourseEvent::TABLE_NAME, $lecture_key);
            $sports = $ef->getEvents(SportsEvent::TABLE_NAME);
        } else if (isset($_SESSION["sportsToDisplay"])) {
            $sports_key = $_SESSION["sportsToDisplay"];
            unset($_SESSION["sportsToDisplay"]);

            $lectures = $ef->getEvents(CourseEvent::TABLE_NAME);
            $sports = $ef->getEvents(SportsEvent::TABLE_NAME, $sports_key);
        } else {
            $lectures = $ef->getEvents(CourseEvent::TABLE_NAME);
            $sports = $ef->getEvents(SportsEvent::TABLE_NAME);
        }

        // format data
        $lecture_data = [];
        foreach ($lectures as $lecture) {
            $lecture_data[] = ["firstEl"=>$lecture->getTitle(), "secondEl"=>$lecture->getPlace(),
                "eventId"=>$lecture->getEventId()];
        }

        $sports_data = [];

        // format data
        foreach ($sports as $sport) {
            $sports_data[] = ["firstEl"=>$sport->getTitle(), "secondEl"=>$sport->getPlace(), "thirdEl"=>$sport->getStartDate()->format("d") . "-" .
                $sport->getStartDate()->format('M')
                , "fourthEl"=>$sport->getStartDate()->format('h') . ":" . $sport->getStartDate()->format('i'). "-" .
                    $sport->getEndDate()->format('h') . ":" . $sport->getEndDate()->format('i'),
                "fifthEl"=>$sport->getCurrentNumberOfParticipants() . "/" . $sport->getMaxNoOfParticipant(),
                "eventId"=>$sport->getEventID()];;
        }

        // render lecture events
        echo $my_engine->render("searchBar", ["title"=>"Lectures", "value"=>$lecture_key,"eventType"=>"Lecture"]);
        echo $engine->render("listWith3ColumnsAndButton", ["row" => $lecture_data, "column1"=>"Course Code",
            "column2"=>"Place", "column3"=>"Manage Lecture"]);

        // render sports events
        echo $my_engine->render("searchBar", ["title"=>"Sports Events","value"=>$sports_key, "eventType"=>"Sports",
            "column1"=>"Place", "column2"=>"Day Slot", "column3"=>"Time Slot", "column4"=>"Quota", "column5"=>"See Participants"]);

        echo $engine->render("list6ColButton", ["row" => $sports_data, "column1"=>"Name","column2"=>"Place", "column3"=>"Day Slot", "column4"=>"Time Slot",
            "column5"=>"Quota", "column6"=>"See Participants", "title"=>"Sports Events"]);

        if(isset($_POST["goEvent"])) {
            $_SESSION["eventToDisplay"] = $_POST["goEvent"];

            echo "go " . $_POST["goEvent"];
            header("Location: ../../administration/see");
        }
        if(isset($_POST["seeEvent"])) {
            $_SESSION["eventToDisplay"] = $_POST["seeEvent"];

            echo "go " . $_POST["seeEvent"];
            header("Location: ../../administration/see");
        }


        if(isset($_POST["Lecture"])) {
            $_SESSION["lectureToDisplay"] = $_POST["Lecture"];
            echo "go " . $_POST["Lecture"];
            unset($_POST);
            header("Refresh:0");
        }

        if(isset($_POST["Sports"])) {
            $_SESSION["sportsToDisplay"] = $_POST["Sports"];
            echo "go " . $_SESSION["sportsToDisplay"];
            unset($_POST);
            header("Refresh:0");
        }
    }

    ?>
</div>


</body>
</html>