<?php
require_once(__DIR__ . "/../config.php");
require_once(rootDirectory() . "/util/NavBar.php");
require_once(rootDirectory() . "/util/UserFactory.php");
startDefaultSessionWith();
$pagename = '/closecontact';
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Close Contacts</title>

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
        $usertype = $_SESSION['usertype'] ?? Student::TABLE_NAME;
        $uf = new UserFactory();
        $user = $uf->makeUserById($conn, $usertype, $_SESSION["id"]);

        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
        ));

        $navbar = new NavBar($usertype, $pagename);
        echo $navbar->draw();

        $imgSource = "../srcs/default_profile_pic.jpg";

        $contact_list = [];

         // fetch data from the database
        $event_lecture = $user->getEventsIParticipate(CourseEvent::TABLE_NAME);
        $event_sports = $user->getEventsIParticipate(SportsEvent::TABLE_NAME);

        // contacts is array of user objects
        $contacts = $user->getCloseContacts();

        // add close contacts to contact list
        foreach ($contacts as $contact) {
            if ($contact->getId() != $user->getId()) {
                $contact_list[] = ["imgsource" => $imgSource, "name" => $contact->getFirstName() . " " . $contact->getLastName(),
                    "id" => $contact->getId()];
            }
        }

        // get contents of the events I participate
        $spors_data = [];
        foreach ($event_sports as $event) {
            $spors_data[] = ["firstEl"=>$event->getTitle(), "secondEl"=>$event->getPlace(), "thirdEl"=>$event->getStartDate()->format("d") . "-" .
                $event->getStartDate()->format('M')
                ,"fourthEl"=>$event->getStartDate()->format('h') . ":" . $event->getStartDate()->format('i'). "-" .
                    $event->getEndDate()->format('h') . ":" . $event->getEndDate()->format('i'),
                "fifthEl"=>$event->getCurrentNumberOfParticipants() . "/" . $event->getMaxNoOfParticipant(),
                "eventId"=>$event->getEventID()];
        }

        $lecture_data = [];
        foreach ($event_lecture as $event) {
            $lecture_data[] = ["firstEl" => $event->getTitle(), "secondEl" => $event->getPlace(), "eventId" => $event->getEventID()];
        }

        // RENDERING HTMLs
        // render close contacts
        echo $m->render("contactlist", ["person" => $contact_list]);

        // render lectures
        echo $m->render('listWith3ColumnsAndButton',
            ['row' => $lecture_data
                , "title" => "Your Lectures", "column1" => "Name", "column2" => "Info", "column3" => "Participants"]);

        echo $m->render("list6ColButton", ["row" => $spors_data,
            "title"=>"Your Sports Events",
            "column1" => "Name","column2"=>"Place", "column3"=>"Day Slot", "column4"=>"Time Slot", "column5"=>"Quota", "column6"=>"See Participants"]);

        // add close contact component
        echo $m->render("addclosecontact");

        // add to close contac
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["closeContact"])) {

            $_SESSION["closeContact"] = $_POST["closeContact"];
            unset($_POST);

            header("Refresh:0");

        } else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_SESSION["closeContact"])) {
            $userIdToAdd = $_SESSION["closeContact"];
            unset($_SESSION["closeContact"]);
            $user->addCloseContact($userIdToAdd);

            header("Refresh:0");
        }

        // implementation of deleting user from the close contact list
        // check if a button is pressed for any user in the table
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["removeCloseContact"])) {
            $_SESSION["removeCloseContact"] = $_POST["removeCloseContact"];
            unset($_POST);

            header("Refresh:0");
        } else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_SESSION["removeCloseContact"])) {
            $userIdToDelete = $_SESSION["removeCloseContact"];
            unset($_SESSION["removeCloseContact"]);

            if ($user->deleteCloseContact($userIdToDelete)) {
                unset($_SESSION['cerr']);
            }
            header("Refresh:0");
        }

        // go to the see event page if see button is pressed
        if (isset($_POST["goEvent"]) || isset($_POST["seeEvent"]) ) {
            if (isset($_POST["goEvent"]))
                $_SESSION["eventToDisplay"] = $_POST["goEvent"];
            else
                $_SESSION["eventToDisplay"] = $_POST["seeEvent"];

            header("Location: ../../closecontact/see");
        }
    }
    ?>

</div>


</body>
</html>