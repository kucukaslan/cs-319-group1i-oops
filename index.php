<?php

// Why do we try to connect database before user is logged in? (talking specifically for this page)
require_once("config.php");
require_once('util/NavBar.php');
require_once(rootDirectory() . "/util/UserFactory.php");

startDefaultSessionWith();
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <style>

    </style>
</head>
<body>


<div class="container">
    <p id="info">
        <?php
        $conn = getDatabaseConnection();

        
        if (!isset($_SESSION['id']) || !isset($conn)) {
            header("location: ./login");
        } else {
            $id = $_SESSION['id'];
            $uf = new UserFactory(Student::TABLE_NAME);
            $std = $uf->makeUserById($conn, $_SESSION['id']);
    
            //----
            if (isset($conn) && $_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['HESCode'])) {

                    $hescode = $_POST['HESCode'];
                    $std->updateHESCode($hescode);
                    $std = $uf->makeUserById($conn, $_SESSION['id']);
                }  // userid and password sent from the form

            }

            $navbar = new NavBar(Student::TABLE_NAME);
            echo $navbar->draw();
            $engine = new Mustache_Engine(array(
                'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),

            ));
            echo $engine->render('welcome', ['firstname' => $std->getFirstName(), 'lastname' => $std->getLastName()]);


            // render and print profile component sessiondan al name,email falan.
            echo $engine->render("profile", ["name" => $std->getFirstName(), "email" => $std->getEmail(), "id" => $std->getId(),
                "allowance" => "Allowed", 'hescode' => $std->getHESCode()]);


            // vaccine component
            echo $engine->render('vax',
                ['vaccine' => [
                    ['vaccineDate' => 'bugun', 'vaccineType' => 'TURKOVAC'],
                    ['vaccineDate' => 'yarin', 'vaccineType' => 'TURKOVAC']
                ]
                ]);

            $pastTest = ["date" => "1.2.4.5", "result" => "negative"];
            $upcomingTest = ["date" => "2023"];

            // upcoming test are loaded first so that they appear on the top of the table.
            echo $engine->render("PCRtest", ["upcomingTest" => [
                $upcomingTest, $upcomingTest
            ], "pastTest" => [
                $pastTest, $pastTest
            ]]);

            echo $engine->render("diagnosis", ["diagnosis" => [
                ["date" => "date 1"],
                ["date" => "date 2"]]]);
        }


        ?>

        <!-- delete account button !-->
    <form method='post' action="./delete">
        <div class="form-group">
            <input type="submit" href="/delete" class="button button_delete" value="!!! DELETE ACCOUNT !!!">
        </div>
    </form>
    </p>
</div>
<div class="">
    <div class="centerdiv">
        <br><br>
        <h2>Some Titles/Tables in the Main Menu</h2>

        <br>
    </div>


    <form method="post" action="./profile">
        <div class="form-group">
            <input type="submit" class="button button_submit" value="Go to Profile Page">
        </div>
    </form>

</div>
</body>
</html>

