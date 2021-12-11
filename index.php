<?php
    // Why do we try to connect database before user is logged in? (talking specifically for this page)
    include("config.php");
    startDefaultSessionWith();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main Page</title>
    <link rel="stylesheet" href="./styles.css">

    <meta name="author" content="CS319">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
<!--
<header class="navbar">
    <div class="navbar-text">Simple e-trade app <div class="navbar-text" style="text-align:right">Logout</div>
    </div>
</header>
-->
<div class="container">
    <p id='info'>

        <?php
      
        $conn = getDatabaseConnection();

        if (!isset($_SESSION['id']) || !isset($conn)) {
            header("location: ./login");
        } else {
            $id = $_SESSION['id'];
            require_once './vendor/autoload.php';
            $engine = new Mustache_Engine(array(
                    'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory(). '/templates'),

            ));
            echo $engine->render('navbar', ['links' => [
                    ['href' => './', 'title' => 'Main Menu'],
                    ['href' => './events', 'title' => 'Events'],
                    ['href' => './closecontact', 'title' => 'Close Contact'],
                    ['href' => './profile', 'title' => 'Profile'],
                    ['href' => './logout.php', 'title' => 'Logout', 'id' => 'logout']]]
            );

            echo $engine->render('welcome', ['firstname' => $_SESSION['firstname'], 'lastname' => $_SESSION['lastname']]
            );

            // render and print profile component sessiondan al name,email falan.
            echo $engine->render("profile", ["name"=>"Feridun", "email"=>"email@emailoglu", "id" => $id, "allowance"=>"Allowed"]);

            // vaccine component
            echo $engine->render("vax", ['vaccine' => [
                    ['vaccineDate' => 'bugun', 'vaccineType' => 'TURKOVAC'],
                    ['vaccineDate' => 'yarin', 'vaccineType' => 'TURKOVAC']]]
            );

            $pastTest = ["date" => "1.2.4.5", "result" => "negative"];
            $upcomingTest = ["date" => "2023"];

            // upcoming test are loaded first so that they appear on the top of the table.
            echo $engine->render("PCRtest", ["upcomingTest" => [
                $upcomingTest, $upcomingTest
            ], "pastTest" => [
                $pastTest, $pastTest
            ]]);

            echo $engine->render("diagnosis", ["diagnosis" =>[
                    ["date" => "date 1"],
                    ["date" =>"date 2"]]]);
        }


        ?>

      <!-- delete account button !-->
    <form method='post' action="./delete">
        <div class="form-group">
            <input type="submit" href="/delete" class="button button_delete" value="!!! DELETE ACCOUNT !!!">
        </div>
    </form>

<!--    </p>
    <div class="centerwrapper">
        <div class="centerdiv">
            <br><br>
            <h2>Some Titles/Tables in the Main Menu</h2>

            <br>
        </div>
    </div>

    <form method='post' action="./profile">
        <div class="form-group">
            <input type="submit" class="button button_submit" value="Go to Profile Page">
        </div>
    </form>
!-->
</div>
</body>
</html>

