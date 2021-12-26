<?php
include_once("../config.php");
require_once(rootDirectory() . "/util/UserFactory.php");
require_once(rootDirectory() . "/util/EventFactory.php");
require_once(rootDirectory() . "/util/NavBar.php");
require_once(rootDirectory() . "/util/AllowanceFacade.php");

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
<div class="container has-text-centered">

    <?php
    require_once rootDirectory() . '/vendor/autoload.php';

    $conn = getDatabaseConnection();

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
    $lErr = false;
    $navbar = new NavBar($usertype);
    echo $navbar->draw();

    // create an allowance facade instance to determine the allowance
    // of the user
    $aFacade = new AllowanceFacade($conn,$_SESSION['usertype'], $_SESSION['id']);

    $allowanceSituation = $aFacade->getIsAllowed();
    $allowanceSituationasString = "";

    if ( $allowanceSituation === false )
    {
        $allowanceSituationasString = "You are not Allowed in Campus ! ";
    }

    $m = new Mustache_Engine(array(
        'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
    ));

    $lectures = $user->getEventsIParticipate(CourseEvent::TABLE_NAME);
    $sports = $ef->getEvents(SportsEvent::TABLE_NAME);

    $lecture_data = [];
    foreach ($lectures as $lecture) {
        $lecture_data[] = [
            "firstEl" => $lecture->getTitle(),
            "secondEl" => $lecture->getPlace(),
            "hiddenValue" => $lecture->getEventId()
        ];
    }

    $sports_data_enrolled = [];
    $sports_data_not_enrolled = [];

    // set the property of eventIParticipate from the database
    // this data will be used to determine if the user has participated the
    // sports event
    $user->getEventsIParticipate();

    // format data
    foreach ($sports as $sport) {
        $formattedData = [
            "title"=>$sport->getTitle(),"place" => $sport->getPlace(),
            "dayslot" => $sport->getStartDate()->format("d") . "-" . $sport->getStartDate()->format('M'),
            "timeslot" => $sport->getStartDate()->format('h') . ":" . $sport->getStartDate()->format('i') . "-" .
                $sport->getEndDate()->format('h') . ":" . $sport->getEndDate()->format('i'),
            "eventId" => $sport->getEventId(), "quota"=>$sport->getCurrentNumberOfParticipants() . "/" . $sport->getMaxNoOfParticipant(),
            "value" => "Leave"];
        if ($sport->getCanPeopleJoin() && $sport->getCurrentNumberOfParticipants() < $sport->getMaxNoOfParticipant()) {
            if ($user->doIParticipate($sport->getEventId())) {
                $sports_data_enrolled[] = $formattedData;
            } else {
                $sports_data_not_enrolled[] = $formattedData;
            }
        }
    }
    // enroll an event if the enroll button is pressed
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["enroll"])) {
        $_SESSION["enroll"] = $_POST["enroll"];
        unset($_POST);

        header("Refresh:0");

    } else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_SESSION["enroll"])) {
        $eventIdToEnroll = $_SESSION["enroll"];
        unset($_SESSION["enroll"]);

        $eventToEnroll = $ef->getEvent($eventIdToEnroll);
        echo $eventToEnroll->getCurrentNumberOfParticipants();
        echo $eventToEnroll->getMaxNoOfParticipant();

        if ($eventToEnroll->getCurrentNumberOfParticipants() < $eventToEnroll->getMaxNoOfParticipant()) {

            unset($_SESSION['lerr']);
            if ($user->joinSportsActivity($eventIdToEnroll)) {
                unset($_SESSION['lerr']);
            } else {

                $e = $ef->getEvent($eventIdToEnroll);
                $_SESSION['lerr'] = 'Failed to enroll ' . $e->getTitle() . ' ' . $e->getPlace() . ' ' .
                    $e->getStartDate()->format('d M h:i') . ' ' . $e->getEndDate()->format('h:i');
            }
        } else {
            $e = $ef->getEvent($eventIdToEnroll);
            $_SESSION['lerr'] = 'Event ' . $e->getTitle() . ' ' . $e->getPlace() . ' ' .
                $e->getStartDate()->format('d M h:i') . ' ' . $e->getEndDate()->format('h:i') . ' is full';
        }
        header("Refresh:0");
    }

    // cancel sports event or leave the course
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["cancel"])) {
        $_SESSION["cancel"] = $_POST["cancel"];
        unset($_POST);

        header("Refresh:0");

    } else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_SESSION["cancel"])) {
        $eventIdToCancel = $_SESSION["cancel"];
        unset($_SESSION["cancel"]);

        if (!$user->leaveEvent($eventIdToCancel)) {
            $e = $ef->getEvent($eventIdToCancel);
            if ($e instanceof CourseEvent) {
                $_SESSION['lerr'] = 'Failed to leave ' . $e->getTitle();
            }
            if ($e instanceof SportsEvent) {
                $_SESSION['lerr'] = 'Failed to leave ' . $e->getTitle() . ' ' . $e->getPlace() . ' ' .
                    $e->getStartDate()->format('d M h:i') . ' ' . $e->getEndDate()->format('h:i');
            }
        } else {
            unset($_SESSION['lerr']);
        }
        header("Refresh:0");

    }

    // display error message
    ?> <?php if (isset($_SESSION['lerr'])): ?>

        <div class="notification is-danger is-light">
            <?= htmlspecialchars($_SESSION['lerr'], ENT_HTML5 | ENT_QUOTES); ?>
        </div>

    <?php endif; ?> <?php

    // RENDER HTMLs
    echo $title;
    echo $m->render("listWith3ColumnsAndForm", ["row" => $lecture_data, "title" =>
        "Enrolled Courses", "column1" => "Course Code", "column2" => "Place", "column3" => "Leave Course"]);

    echo $m->render('eventssports', ['enrolledevent' => $sports_data_enrolled, "notenrolledevent" =>
        $sports_data_not_enrolled, 'notAllowed' => $allowanceSituationasString]);


    ?>
</div>

</body>
</html>
