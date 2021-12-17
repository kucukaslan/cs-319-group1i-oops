<?php
require_once(__DIR__ . "/../../config.php");
require_once(rootDirectory() . "/util/NavBar.php");
startDefaultSessionWith();
$pagename = '/closecontact/see';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Details</title>


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
        echo '<header>';
        $navbar = new NavBar($usertype, $pagename);
        echo $navbar->draw();
        echo "<h2>Event Details Page</h2>";
        echo '</header>';


        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
        ));
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
            <div class="columns is-centered">
                <form method='get' class="box" action="../">
                    <div class="form-group">
                        <input type="submit" class="button button_submit" value="Return to Close Contact Page">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>