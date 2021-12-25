<?php
    include_once("../../config.php");
    require_once(rootDirectory()."/util/NavBar.php");
    require_once(rootDirectory() . "/util/UserFactory.php");
    require_once(rootDirectory() . "/util/AllowanceFacade.php");
    startDefaultSessionWith();
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reservation Management System</title>

    <link rel="stylesheet" href="../../styles.css">
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
        $usertype  = $_SESSION['usertype'] ?? Student::TABLE_NAME;
        $uf = new UserFactory();
        $ef = new EventFactory($conn);
        $user = $uf->makeUserById($conn,$usertype, $_SESSION["id"]);

        $navbar = new NavBar($usertype);
        echo $navbar->draw();
        echo "<div class=\"centerwrapper\">
            <h2>See Event Page</h2>
    </div>";

        echo "event is is " . $_SESSION["seeEvent"] . "<br>";
        $thisEvent = $ef->getEvent($_SESSION["seeEvent"]);

        $participants = $user->getParticipants($thisEvent->getEventID());

        $participants_data = array();
        foreach ($participants as $participant) {
            $af = new AllowanceFacade($conn, Student::TABLE_NAME, $participant->getId());

            if ($af->getIsAllowed()) {
                $allowance = "Allowed";
            } else {
                $allowance = "Not Allowed";
            }

            $participants_data[] = ["firstEl"=>$participant->getFirstName() . " " . $participant->getLastName(), "secondEl"=> $allowance];
        }

        echo $engine->render("listWith2Column", ["row" => $participants_data,
            "title"=>"Sports Event Details", "column1"=>"Name", "column2"=>"Allowance Status"]);


    }

    ?>

</div>


</body>
</html>