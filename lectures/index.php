<?php
include_once("../config.php");
require_once(rootDirectory()."/util/NavBar.php");
require_once(rootDirectory() . "/util/UserFactory.php");
startDefaultSessionWith();
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
    } else {
        $engine = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
        ));
        $usertype  = $_SESSION['usertype'] ?? Student::TABLE_NAME;
        $uf = new UserFactory();
        $user = $uf->makeUserById($conn,$usertype, $_SESSION["id"]);

        $navbar = new NavBar($usertype);
        echo $navbar->draw();

        $counter = 0;
        /*$buttonNames = [];
        for ($i = 0; $i < 2; $i++) { // $user->get no of participants of the event $i++) {
            // set button names
            $buttonNames[] = "button" . ($i + 1);
        }*/
        // render upcoming lectures
        echo $engine->render("listWith3ColumnsAndButton", ["row" => [
        ['firstEl' => 'Math123', 'secondEl' => '1.1.2100', "buttonName"=>"Manage", "buttonLink"=>"../../lectures/manage"],
            ['firstEl' => 'Math123', 'secondEl' => '1.1.2100', "buttonName"=>"Manage", "buttonLink"=>"../../lectures/manage"],
        ], "title"=>"Upcoming Lectures",
        "column1"=>"Course Code", "column2"=>"Lecture Date", "column3"=>"Manage Lecture"]);

        // render past lectures
        echo $engine->render("pastlectures", ["event" => [["courseCode"=>"code1", "lectureDate"=>"1.2.344"], ["courseCode"=>"code1", "lectureDate"=>"1.2.344"]]]);

        // create lecture
        $createLectureButton = <<<EOF
<form method="POST"> <input type="submit" class="button" name="" value="Create Lecture"> </form>
EOF;
        echo $createLectureButton;

    }
    ?>

    <!--
    <div class="centerwrapper">
        <div class="centerdiv">
            <br><br>
            <h2>Lectures Page</h2>
            <br>
            <form action="manage">
                <div class="form-group">
                    <input type="submit" class="button button_submit" value="See Course Details">
                    <input type="hidden" name="courseid" id="courseid" value="CS101">
                </div>
            </form>
        </div>
    </div>
    !-->
</div>
</body>
</html>