<?php
include_once("../config.php");
require_once(rootDirectory() . "/util/NavBar.php");
startDefaultSessionWith();

require_once rootDirectory() . '/vendor/autoload.php';

$conn = getDatabaseConnection();

if (!isset($_SESSION['id']) || !isset($conn)) {
    header("location: ../login");
}
$usertype  = $_SESSION['usertype'] ?? Student::TABLE_NAME;
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
        $title = <<<EOF
        <div class="centerwrapper">
        <div class="centerdiv">
            <p class="title">Events Page</p>
        </div>
    </div>
    EOF;
        $navbar = new NavBar($usertype);
        echo $navbar->draw();

        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/../templates'),
        ));
        
        echo $title;
        echo $m->render("eventslecture", ["event" => [["courseCode" => "cs123", "lectureDate" => "1.2.3444"]]]);
        // echo $m->render("eventssports", ["event" => [["courseCode"=>"cs123", "lectureDate"=>"1.2.3444"]]]);
        ?>
        <div class="block"></div>
        <?php
        echo $m->render('eventssports', [
            'enrolledevent' => [
                ['place' => 'place1', "dayslot" => "day1", "timeslot" => "13.30"]],
            "notenrolledevent" => [
                ['place' => 'place1', "dayslot" => "day2", "timeslot" => "13.30"]]
        ]);
    ?>
</div>

</body>
</html>