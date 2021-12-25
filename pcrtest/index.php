<?php
include_once("../config.php");
require_once(rootDirectory()."/util/NavBar.php");
require_once(rootDirectory() . "/util/UserFactory.php");
startDefaultSessionWith();

require_once rootDirectory() . '/vendor/autoload.php';

$conn = getDatabaseConnection();

if (!isset($_SESSION['id']) || !isset($conn)) {
    header("location: ../login");
}
$usertype = $_SESSION['usertype'] ?? Student::TABLE_NAME;
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
    $title = <<<EOF
        <div class="centerwrapper">
        <div class="centerdiv">
            <p class="title">PCR Test Page</p>
        </div>
    </div>
    EOF;
    $engine = new Mustache_Engine(array(
        'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
    ));
    // $usertype  = $_SESSION['usertype'] ?? Student::TABLE_NAME;
    $uf = new UserFactory();
    $ef = new EventFactory($conn);
    $user = $uf->makeUserById($conn,$usertype, $_SESSION["id"]);

    $navbar = new NavBar($usertype);
    echo $navbar->draw();

    $appointments = $ef->getEvents(TestAppointmentEvent::TABLE_NAME);

    $iParticiapate = [];
    $iDontParticipate = [];

    $user->getEventsIParticipate();
    foreach ($appointments as $appointment) {
        if ($user->doIParticipate($appointment->getEventID())) {
            $iParticiapate[] = $ef->getEvent($appointment->getEventID());
        } else {
            $iDontParticipate[] = $ef->getEvent($appointment->getEventID());
        }
    }

    // format data
    $data_participate = [];
    $data_not_participate = [];

    foreach ($iParticiapate as $app) {
        $data_participate[] = ["place"=>$app->getPlace(), "dayslot"=>$app->getStartDate()->format("d") . "-" .
            $app->getStartDate()->format('M'), "timeslot"=>$app->getStartDate()->format('h') . ":" . $app->getStartDate()->format('i'). "-" .
            $app->getEndDate()->format('h') . ":" . $app->getEndDate()->format('i'), "eventId"=>$app->getEventID()];
    }

    foreach ($iDontParticipate as $app) {
        $data_not_participate[] = ["place"=>$app->getPlace(), "dayslot"=>$app->getStartDate()->format("d") . "-" .
            $app->getStartDate()->format('M'), "timeslot"=>$app->getStartDate()->format('h') . ":" . $app->getStartDate()->format('i'). "-" .
            $app->getEndDate()->format('h') . ":" . $app->getEndDate()->format('i'), "eventId"=>$app->getEventID()];
    }

    // cancel a test appointment
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["cancel"])) {
        $_SESSION["cancel"] = $_POST["cancel"];
        // echo "inside post if remove" . $_POST["cancel"];
        unset($_POST);

        header("Refresh:0");

    } else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_SESSION["cancel"])) {
        $eventIdToCancel = $_SESSION["cancel"];
        unset($_SESSION["cancel"]);
        // echo "canceling  " . $eventIdToCancel;

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

    // appoint a test
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["reserve"])) {
        $_SESSION["reserve"] = $_POST["reserve"];
        // echo "inside post if remove" . $_POST["enroll"];
        unset($_POST);

        header("Refresh:0");

    } else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_SESSION["reserve"])) {
        $eventIdToEnroll = $_SESSION["reserve"];
        unset($_SESSION["reserve"]);
        //   echo "ENROLLING " . $eventIdToEnroll;

        $eventToEnroll = $ef->getEvent($eventIdToEnroll);
        echo $eventToEnroll->getCurrentNumberOfParticipants() . "sadas<br>";
        echo $eventToEnroll->getMaxNoOfParticipant();

        if ($eventToEnroll->getCurrentNumberOfParticipants() < $eventToEnroll->getMaxNoOfParticipant()) {

            unset($_SESSION['lerr']);
            if ($user->joinEvent($eventIdToEnroll)) {
                unset($_SESSION['lerr']);
            } else {
                //TODO: Error message
                $e = $ef->getEvent($eventIdToEnroll);
                $_SESSION['lerr'] = 'Failed to enroll ' . $e->getTitle() . ' ' . $e->getPlace() . ' ' .
                    $e->getStartDate()->format('d M h:i') . ' ' . $e->getEndDate()->format('h:i');
            }
        } else {
            // TODO: Error message stating that event is full
            $e = $ef->getEvent($eventIdToEnroll);
            $_SESSION['lerr'] = 'Event ' . $e->getTitle() . ' ' . $e->getPlace() . ' ' .
                $e->getStartDate()->format('d M h:i') . ' ' . $e->getEndDate()->format('h:i') . ' is full';
        }
        header("Refresh:0");
    }

    ?>
    <?php if (isset($_SESSION['lerr'])): ?>

        <div class="notification is-danger is-light">
            <?= htmlspecialchars($_SESSION['lerr'], ENT_HTML5 | ENT_QUOTES); ?>
        </div>

    <?php endif; ?>
    <?php
    echo $engine->render('eventsPCRTests', [
        'enrolledevent' => $data_participate,
        "notenrolledevent" => $data_not_participate
    ]);
    ?>
</div>

</body>
</html>