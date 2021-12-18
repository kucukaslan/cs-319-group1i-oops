<?php
include_once("../../config.php");
require_once(rootDirectory() . "/util/NavBar.php");
require_once(rootDirectory() . "/util/UserFactory.php");
startDefaultSessionWith();

$courseCode = "Math-123";
$date = "1.2.3232";
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
        $usertype = $_SESSION['usertype'] ?? Student::TABLE_NAME;
        $courseCodeAndDate = <<<EOF
<section class="hero is-primary">
    <div class="hero-body">
        <div class="columns is-centered">
            <div class="column is-narrow">
            <h2> Manage Course </h2>
<h4><b>Course Code: $courseCode  Date: $date </b></h4>
</div>
</div>
</div>
</section>
EOF;

        $navbar = new NavBar($usertype);
        echo $navbar->draw();
        echo $courseCodeAndDate;

        $idOfTheParticipant = 1;
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
        echo $addCoordinatorButton;
        /*$m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory().'/templates'),
        ));
        echo $m->render('navbar', ['links' => [
            ['href' => '../../', 'title' => 'Main Menu'],
            ['href' => '../../administration', 'title' => 'Administration','id'=> 'selected'],
            ['href' => '../../lectures', 'title' => 'Lectures'],
            //['href' =>'../../reservations', 'title' => 'Reservations'],
            ['href' => '../../events', 'title' => 'Events'],
            ['href' => '../../closecontact', 'title' => 'Close Contact'],
            ['href' => '../../profile', 'title' => 'Profile'],
            ['href' => '../../logout.php', 'title' => 'Logout', 'id' => 'logout']]]
        );*/

        /*
                echo '<div class="centerwrapper">
                        <div class="centerdiv">
                            <br><br>
                            <h2>Event Details Page</h2>
                            <br>';
                echo "<h3> <abbr title='Your Majesties, Your Excellencies, Your Highnesses'>Hey</abbr> " . $_SESSION['firstname'] . " </h3>";
                echo "<i> Welcome to the <abbr title='arguably'>smallest</abbr> ... University Contact Tracing Service, <abbr title='of course by us'> <b>ever</b></abbr>!</i>";
                echo "<br>  ".( (array_key_exists('courseid',$_GET)) ? "You are seeing the details of the course " . $_GET['courseid']  :  "The course details cannot be found!");
                echo '<form method="get" action="../"><div class="form-group">
                                <input type="submit" class="button button_submit" value="Return to Close Contact Page">
                            </div>
                        </form>
                    </div>
                </div>';*/

    }

    ?>

</div>

</body>
</html>