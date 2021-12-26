<?php
include_once("../../config.php");
require_once(rootDirectory() . "/util/NavBar.php");
require_once(rootDirectory() . "/util/UserFactory.php");
require_once(rootDirectory() . "/util/EventFactory.php");
require_once(rootDirectory() . "/util/AllowanceFacade.php");
startDefaultSessionWith();
ob_start();

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
    } 
    else if ($_SESSION['usertype'] != AcademicStaff::TABLE_NAME && $_SESSION['usertype'] != UniversityAdministration::TABLE_NAME) {
        header("Location: ../index.php");
    }  else {
        // definitions
        $engine = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
        ));
        $usertype = $_SESSION['usertype'] ?? Student::TABLE_NAME;
        $uf = new UserFactory();
        $ef = new EventFactory($conn);
        $user = $uf->makeUserById($conn, $usertype, $_SESSION["id"]);
        $lectureId = $_SESSION["lectureToDisplay"];


        // create event object whose participants will be displayed
        $thisLecture = $ef->getEvent($lectureId);

        $participants = $user->getParticipants($lectureId);

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
        $coins_data = [];
        $ef = new EventFactory($conn);
        $e = $ef->getEvent($lectureId);
        $instructors = $e->getControllers();
        foreach ($instructors as $instructor) {

            $af = new AllowanceFacade($conn, User::TABLE_NAME, $instructor->getId());

            if ($af->getIsAllowed()) {
                $allowance = "Allowed";
            } else {
                $allowance = "Not Allowed";
            }


            $coins_data[] = ["firstEl" => $instructor->getFirstName() . " " . $instructor->getLastName(), "secondEl" => $instructor->getId(),
                "thirdEl" => $allowance];

        }

        echo $engine->render("demonstrateInstructorsInCourse", ["row" => $coins_data,
            "column1" => "Name", "column2" => "Id", "column3" => "Allowance", "title" => "Coinstructors of the Event"]);
        // create participants data to display
        $participants_data = [];

        foreach ($participants as $participant) {
            $af = new AllowanceFacade($conn, User::TABLE_NAME, $participant->getId());

            if ($af->getIsAllowed()) {
                $allowance = "Allowed";
            } else {
                $allowance = "Not Allowed";
            }

            $participants_data[] = ["firstEl" => $participant->getFirstName() . " " . $participant->getLastName(), "secondEl" => $participant->getId(),
                "thirdEl" => $allowance];

        }

        echo $engine->render("demonstrateStudentsInCourse", ["row" => $participants_data,
            "column1" => "Name", "column2" => "Id", "column3" => "Allowance", "title" => "Participants of the Event"]);

    }
    ?>

</div>

</body>
</html>

