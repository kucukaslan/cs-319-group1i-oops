<?php
    include_once("../config.php");
    require_once(rootDirectory() . "/util/NavBar.php");
    require_once(rootDirectory() . "/util/UserFactory.php");
    startDefaultSessionWith();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administration System</title>

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
        $my_engine = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/administration/templates'),
        ));
        $engine = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
        ));

        $usertype = $_SESSION['usertype'] ?? Student::TABLE_NAME;

        $uf = new UserFactory();
        $user = $uf->makeUserById($conn, $usertype, $_SESSION["id"]);

        $navbar = new NavBar($usertype);
        echo $navbar->draw();
?>
        <h2>Admin Page</h2>
        <?php

        echo $my_engine->render("searchByIdHES");

        // render sports events
        echo $engine->render("list5ColButton", ["row" => [
            ['firstEl' => 'Main Sprots Hall', 'secondEl' => '12.2', "thirdEl"=>"13-12", "fourthEl"=>"10/40","buttonName"=>"See", "buttonLink"=>"../../reservations/see"],
            ['firstEl' => 'Main Sprots Hall', 'secondEl' => '12.2', "thirdEl"=>"13-12", "fourthEl"=>"10/40","buttonName"=>"See", "buttonLink"=>"../../reservations/see"]],
            "title"=>"Sports Events",
            "column1"=>"Place", "column2"=>"Day Slot", "column3"=>"Time Slot", "column4"=>"Quota", "column5"=>"See Participants"]);

        // render courses
        echo $engine->render("listWith3ColumnsAndButton", ["row" => [
            ['firstEl' => 'Math123', 'secondEl' => 'SBZ-18', "buttonName"=>"Manage", "buttonLink"=>"../../lectures/manage"],
            ['firstEl' => 'Math123', 'secondEl' => 'SBZ-18', "buttonName"=>"Manage", "buttonLink"=>"../../lectures/manage"]],
            "title"=>"Lectures",
            "column1"=>"Course Code", "column2"=>"Place", "column3"=>"Manage Lecture"]);

        // render search by course form
        echo $my_engine->render("searchLectureByCode", ["courseCodes"=>[
                ["courseCode"=>"Math123"], ["courseCode"=>"cs319"]]]);

        if (isset($_POST["lectureCodes"])) {
            echo $_POST["lectureCodes"];
        }

    }

    ?>
</div>


</body>
</html>