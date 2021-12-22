<?php
include_once("../config.php");
require_once(rootDirectory() . "/util/UserFactory.php");
require_once(rootDirectory() . "/util/NavBar.php");
startDefaultSessionWith();
$pagename = "/events";
// ob_start();
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
    $user = $uf->makeUserById($conn, $usertype, $_SESSION["id"]);

    $navbar = new NavBar($usertype);
    echo $navbar->draw();

    $m = new Mustache_Engine(array(
        'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
    ));

    $lectures = $user->getEventsIParticipate();
    $sports = $user->getEventsIParticipate();

    // print_r($lectures);

    $lecture_data = [];
    foreach ($lectures as $lecture) {
        $lecture_data[] = ["firstEl"=>$lecture->getTitle(), "secondEl"=>$lecture->getPlace(),
        "value"=>"Leave", "hiddenValue"=>$lecture->getEventId()];
    }

    $sports_data_enrolled = [];
    $sports_data_not_enrolled = [];

    foreach ($sports as $sport) {
        if ($user->doIParticipate($sport->getEventId())) {
            $sports_data_enrolled[] = ["place"=>$sport->getPlace(), "dayslot"=>$sport->getStartDate(), "timeslot"=>$sport->getStartDate(), "eventId"=>$sport->getEventId(),
                "value"=>"Leave"];
        } else {
            $sports_data_not_enrolled[] = ["place"=>$sport->getPlace(), "dayslot"=>$sport->getStartDate(), "timeslot"=>$sport->getStartDate(), "eventId"=>$sport->getEventId(),
                "value"=>"Leave"];
        }
    }

    print_r($sports_data_enrolled);


    // RENDER RALATED PARTS
    echo $title;
    echo $m->render("listWith3ColumnsAndForm", ["row" => $lecture_data,
            "title" => "Enrolled Courses", "column1" => "Course Code", "column2" => "Place", "column3" => "Leave Course"]
    );


    echo $m->render('eventssports', [
        'enrolledevent' => $sports_data_enrolled,
        "notenrolledevent" => $sports_data_not_enrolled
    ]);


    // implementation of leaving an sports event
    if (isset($_POST["action"])) {

        $activityId = $_POST["action"];

        if ($user->cancelSportsAppointment($activityId)) {
            echo "LEAVED ACTIVITY WITH ID " . $activityId;
        } else {
            echo "Did not manage to leave the sports activity";
        }
    }
    ?>
</div>

</body>
</html>