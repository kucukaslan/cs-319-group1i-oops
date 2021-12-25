<?php
include_once("../config.php");
require_once(rootDirectory() . "/util/UserFactory.php");
require_once(rootDirectory() . "/util/EventFactory.php");
require_once(rootDirectory() . "/util/NavBar.php");
startDefaultSessionWith();
$pagename = "/events";
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <style>

    </style>
</head>
<body>
<div class="container">

    <?php
    require_once rootDirectory() . '/vendor/autoload.php';

    $conn = getDatabaseConnection();
    echo "id is" . $_SESSION["id"];

    if (!isset($_SESSION['id']) || !isset($conn)) {
        header("location: ../login");
    }

    $usertype = $_SESSION['usertype'] ?? Student::TABLE_NAME;
    $title = <<<EOF
    <div class="centerwrapper">
    <div class="centerdiv">
        <p class="title">Events Page</p>
    </div>
</div>
EOF;
    $uf = new UserFactory();
    $ef = new EventFactory($conn);
    $user = $uf->makeUserById($conn, $usertype, $_SESSION["id"]);

    $navbar = new NavBar($usertype);
    echo $navbar->draw();

    $m = new Mustache_Engine(array(
        'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
    ));

    // get all lectures where the user is participant and all sport events
    $lectures = $user->getEventsIParticipate(CourseEvent::TABLE_NAME);
    $sports = $ef->getEvents(SportsEvent::TABLE_NAME);

    // format the lecture data
    $lecture_data = [];
    foreach ($lectures as $lecture) {
        $lecture_data[] = ["firstEl"=>$lecture->getTitle(), "secondEl"=>$lecture->getPlace(),
        "hiddenValue"=>$lecture->getEventId()];
    }

    $sports_data_enrolled = [];
    $sports_data_not_enrolled = [];

    // set the property of eventIParticipate from the database
    // this data will be used to determine if the user has participated the
    // sports event
    $user->getEventsIParticipate();

    // format data
    foreach ($sports as $sport) {
        $formattedData = ["place"=>$sport->getPlace(), "dayslot"=>$sport->getStartDate()->format("d") . "-" . $sport->getStartDate()->format('M')
            , "timeslot"=>$sport->getStartDate()->format('h') . ":" . $sport->getStartDate()->format('i'). "-" .
            $sport->getEndDate()->format('h') . ":" . $sport->getEndDate()->format('i')
            , "eventId"=>$sport->getEventId(),
            "value"=>"Leave"];
        if ($user->doIParticipate($sport->getEventId())) {
            $sports_data_enrolled[] = $formattedData;
        } else {
            $sports_data_not_enrolled[] = $formattedData;
        }
    }

    // RENDER HTMLs
    echo $title;
    echo $m->render("listWith3ColumnsAndForm", ["row" => $lecture_data,
            "title" => "Enrolled Courses", "column1" => "Course Code", "column2" => "Place", "column3" => "Leave Course"]
    );

    echo $m->render('eventssports', [
        'enrolledevent' => $sports_data_enrolled,
        "notenrolledevent" => $sports_data_not_enrolled
    ]);

    // enroll an event if the enroll button is pressed
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["enroll"])) {
        $_SESSION["enroll"] = $_POST["enroll"];
        echo "inside post if remove" . $_POST["enroll"];
        unset($_POST);

        header("Refresh:0");

    } else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_SESSION["enroll"])) {
        $eventIdToEnroll = $_SESSION["enroll"];
        unset($_SESSION["enroll"]);
        echo "ENROLLING " . $eventIdToEnroll;

        $eventToEnroll = $ef->getEvent($eventIdToEnroll);

        if ($eventToEnroll->getCurrentNumberOfParticipants() < $eventToEnroll->getMaxNoOfParticipant()) {
            if (!$user->joinSportsActivity($eventIdToEnroll)) {
                //TODO: Error message
            }
        } else {
            // TODO: Error message stating that event is full
        }

        header("Refresh:0");
    }

    // cancel sports event or leave the course
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["cancel"])) {
        $_SESSION["cancel"] = $_POST["cancel"];
        echo "inside post if remove" . $_POST["cancel"];
        unset($_POST);

        header("Refresh:0");

    } else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_SESSION["cancel"])) {
        $eventIdToCancel = $_SESSION["cancel"];
        unset($_SESSION["cancel"]);
        echo "canceling  " . $eventIdToCancel;

        if (!$user->leaveEvent($eventIdToCancel)) {
            // TODO: Error message
        }

        header("Refresh:0");
    }
    ?>
</div>

</body>
</html>