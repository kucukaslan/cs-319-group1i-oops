<?php
include_once("../../config.php");
require_once(rootDirectory() . "/util/NavBar.php");
require_once(rootDirectory() . "/util/UserFactory.php");
require_once(rootDirectory() . "/util/EventFactory.php");
require_once(rootDirectory() . "/util/AllowanceFacade.php");
startDefaultSessionWith();
$conn = getDatabaseConnection();
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
    <form method="post" enctype="multipart/form-data">
        Choose Your File <input class="button is-info" type="file" name="file"/> <input type="submit" name="submit"
                                                                                        value="Start Upload">

    </form>
    <?php
    if (isset($conn) && $_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_FILES['file'])) {
            $filename = $_FILES['file']['tmp_name'];
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
            $reader->setReadDataOnly(TRUE);
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filename);
            $ads = $spreadsheet->getActiveSheet();
            $maxRow = $ads->getHighestRow();
            $ids = [];
            for ($row = 2; $row <= $maxRow; ++$row) {
                $col = 4;
                $value = intval($ads->getCellByColumnAndRow($col, $row)->getValue());
                echo $value . "<br>";
                $ids[] = $value;
            }
            print_r($ids);
        }
        //todo add to lecture.
    }


    ?>
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
        //echo "Lecture id: " . $lectureId;


        // create event object whose participants will be displayed
        $thisLecture = $ef->getEvent($lectureId);

        //print_r($thisLecture);
        $participants = $user->getParticipants($lectureId);

        // print_r($participants);
        //echo "No of participants is " . sizeof($participants);

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

        // print_r($participants);

         foreach ($participants as $participant) {
            /*if ($participant->getId() == 22104260) {
                echo " cont ";
                break;
            }*/

            $af = new AllowanceFacade($conn, User::TABLE_NAME, $participant->getId());

            if ($af->getIsAllowed()) {
                $allowance = "Allowed";
            } else {
                $allowance = "Not Allowed";
            }

            $participants_data[] = ["firstEl"=>$participant->getFirstName() . " " . $participant->getLastName(), "secondEl"=>$participant->getId(),
            "thirdEl"=> $allowance];

        }

         // this user causes an error ????
        // $af = new AllowanceFacade($conn, Student::TABLE_NAME, 22104260);

        echo $engine->render("demonstrateStudentsInCourse", ["row" => $participants_data,
            "column1" => "Name", "column2" => "Id", "column3" => "Allowance", "title" => "Participants of the Event"]);
        echo "121";
        ?>


        <?php

        /*$d1 = new DateTime("2009-12-22");
        $d2 = new DateTime('now');
        echo intval($d2->diff($d1)->format('%R%a'));*/

        /*$addCoordinatorButton = <<<EOF
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

