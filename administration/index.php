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
        $lectures = $ef->getEvents(CourseEvent::TABLE_NAME);
        $sports = $ef->getEvents(SportsEvent::TABLE_NAME);

        // format data
        $lecture_data = [];
        foreach ($lectures as $lecture) {
            $lecture_data[] = ["firstEl"=>$lecture->getTitle(), "secondEl"=>$lecture->getPlace(),
                "eventId"=>$lecture->getEventId()];
        }

        $sports_data = [];

        // format data
        foreach ($sports as $sport) {
            $sports_data[] = ["firstEl"=>$sport->getPlace(), "secondEl"=>$sport->getStartDate()->format("d") . "-" .
                $sport->getStartDate()->format('M')
                , "thirdEl"=>$sport->getStartDate()->format('h') . ":" . $sport->getStartDate()->format('i'). "-" .
                    $sport->getEndDate()->format('h') . ":" . $sport->getEndDate()->format('i'),
                "fourthEl"=>$sport->getCurrentNumberOfParticipants() . "/" . $sport->getMaxNoOfParticipant(),
                "eventId"=>$sport->getEventID()];;
        }

        // render lecture events
        echo $my_engine->render("searchBar", ["title"=>"Lectures", "eventType"=>"Lecture"]);
        echo $engine->render("listWith3ColumnsAndButton", ["row" => $lecture_data, "column1"=>"Course Code",
            "column2"=>"Place", "column3"=>"Manage Lecture"]);

        // render sports events
        echo $my_engine->render("searchBar", ["title"=>"Sports Events","eventType"=>"Sports Event",
            "column1"=>"Place", "column2"=>"Day Slot", "column3"=>"Time Slot", "column4"=>"Quota", "column5"=>"See Participants"]);

        echo $engine->render("list5ColButton", ["row" => $sports_data, "column1"=>"Place", "column2"=>"Day Slot", "column3"=>"Time Slot",
            "column4"=>"Quota", "column5"=>"See Participants", "title"=>"Sports Events"]);



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


    }

    ?>
</div>


</body>
</html>