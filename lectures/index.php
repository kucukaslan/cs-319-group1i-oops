<?php
include_once("../config.php");
require_once(rootDirectory()."/util/NavBar.php");
require_once(rootDirectory() . "/util/UserFactory.php");
startDefaultSessionWith();
ob_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lectures <?php echo isset($_SESSION['firstname']) ? " of " . $_SESSION['firstname'] : "" ?></title>

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
    }    
    else if ($_SESSION['usertype'] != AcademicStaff::TABLE_NAME && $_SESSION['usertype'] != UniversityAdministration::TABLE_NAME) {
        header("Location: ../index.php");
    }  else {
        $engine = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
        ));
        $usertype  = $_SESSION['usertype'] ?? Student::TABLE_NAME;
        $uf = new UserFactory();
        $ef = new EventFactory($conn);
        $user = $uf->makeUserById($conn,$usertype, $_SESSION["id"]);

        $navbar = new NavBar($usertype);
        echo $navbar->draw();

        $lectures = $user->getEventsControlledByMe();

        // create data to display
        $lecture_data = [];
        foreach ($lectures as $event) {
            $eventToCheck = $ef->getEvent($event->getEventID());

            if (get_class($eventToCheck) == "CourseEvent") {
                $lecture_data[] = ["firstEl" => $event->getTitle(), "secondEl" => $event->getPlace(),
                    "eventId" => $event->getEventID()];
            }
        }

        // render upcoming lectures
        echo $engine->render("listWith3ColumnsAndButton", ["row" => $lecture_data, "title"=>"Your Lectures",
        "column1"=>"Course Code", "column2"=>"Place", "column3"=>"Manage Lecture"]);

        // if see event button is pressed, move to this page
        // go to the see event page if see button is pressed
        if(isset($_POST["goEvent"])) {
            $_SESSION["lectureToDisplay"] = $_POST["goEvent"];

            header("Location: ../../lectures/manage");
        }

    }
    ?>
</div>
</body>
</html>