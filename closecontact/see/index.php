<?php 
    include_once("../../config.php");

    startDefaultSessionWith();
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Details</title>

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
    } else {
        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory().'/templates'),
        ));
        echo $m->render('navbar', ['links' => [
                ['href' => '../../', 'title' => 'Main Menu'],
                ['href' => '../../events', 'title' => 'Events'],
                ['href' => '../../closecontact', 'title' => 'Close Contact'],
                ['href' => '../../profile', 'title' => 'Profile'],
                ['href' => '../../logout.php', 'title' => 'Logout', 'id' => 'logout']]]
        );

        echo "<h2>Event Details Page</h2>";

        /*echo "<h3> <abbr title='Your Majesties, Your Excellencies, Your Highnesses'>Hey</abbr> " . $_SESSION['firstname'] . " </h3>";
        echo "<i> Welcome to the <abbr title='arguably'>smallest</abbr> ... University Contact Tracing Service, <abbr title='of course by us'> <b>ever</b></abbr>!</i>";
        echo "<br> You are seeing the details of the contacts you have made with other students ".( (array_key_exists('eventid',$_GET)) ? "for the event " . $_GET['eventid']  :  "");
        */

        // render participants
        echo $m->render('lectureparticipants', [
            'addedStudent' => [
                ['name' => 'added student name'], ["name" => "added name 2"]],
            "nonAddedStudent" => [
                ['name' => 'nonadded student name']],
            "courseCode" => "example Course Code",
            "date" => "example datee"
            ]);
    }

    ?>
    <div class="centerwrapper">
        <div class="centerdiv">
            <form method='get' action="../"><div class="form-group">
                    <input type="submit" class="button button_submit" value="Return to Close Contact Page">
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>