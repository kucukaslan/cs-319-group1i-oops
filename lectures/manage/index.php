<?php
include_once("../../config.php");
require_once(rootDirectory() . "/util/NavBar.php");
require_once(rootDirectory() . "/util/UserFactory.php");
require_once(rootDirectory() . "/util/EventFactory.php");
startDefaultSessionWith();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Details</title>

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
        // definitions
        $engine = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
        ));
        $usertype  = $_SESSION['usertype'] ?? Student::TABLE_NAME;
        $uf = new UserFactory();
        $ef = new EventFactory($conn);
        $user = $uf->makeUserById($conn,$usertype, $_SESSION["id"]);
        $lectureId = $_SESSION["lectureToDisplay"];
        echo "Lecture id: " . $lectureId;


        // create event object whose participants will be displayed
        $thisLecture = $ef->getEvent($lectureId, CourseEvent::TABLE_NAME);


        $participants = $thisLecture->getParticipants();

        print_r($participants);

        $navbar = new NavBar($usertype);
        echo $navbar->draw();

        $courseCode = $thisLecture->getTitle();
        $courseCodeAndDate_HTML = <<<EOF
<section class="hero is-primary">
    <div class="hero-body">
        <div class="columns is-centered">
            <div class="column is-narrow">
            <h2> Manage Course </h2>
<h4><b>Course Code:  $courseCode </b></h4>
</div>
</div>
</div>
</section>
EOF;
        echo $courseCodeAndDate_HTML;

        // create participants data to display
        $participants_data = [];

        /*foreach ($participants as $participant) {
            $participants_data[] = ["firstEl"=>$participant->getFirstName() . " " . $participant->getLastName, "secondEl"=>$participant->getId(),
            "thirdEl"=>$participant->]
        }
*/

        echo $engine->render("listWith3Columns", ["row"=>$participants_data]);




        /*$idOfTheParticipant = 1;
        echo $engine->render("listWith3ColumnsAndForm", ["row" => [
            ['firstEl' => "hikmet simsir", 'secondEl' => "allowed", "value" => "Remove", "buttonName" => "Manage"],
            ['firstEl' => "hikmet simsir", 'secondEl' => "allowed", "value" => "Remove", "buttonName" => "Manage"]
        ], "title" => "Participants of the Event",
            "column1" => "Student Name", "column2" => "Risk Status", "column3" => "RemoveStudent"]);

        echo $engine->render("listWith2ColumnsAndForm", ["row" => [
            ['firstEl' => "hikmet simsir teacher", "value" => "Remove", "buttonName" => "name"],
            ['firstEl' => "hikmet simsir teacher", "value" => "Remove", "buttonName" => "name"]
        ], "title" => "Coordinators",
            "column1" => "Coordinators", "column2" => "Remove Coordinator"]);
        $addCoordinatorButton = <<<EOF
<section class="hero is-success">
    <div class="hero-body">
        <div class="columns is-centered">
            <div class="columns">
                <div class="column is-narrow">
                    <form class="box" id="loginForm" action="" method="post">
                        <div class="field">
                            <div class="control">
                                <input type="submit" class="button is-info" name="" value="Add Coordinator">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
EOF;
       echo $addCoordinatorButton;*/

    }

    ?>

</div>

</body>
</html>

