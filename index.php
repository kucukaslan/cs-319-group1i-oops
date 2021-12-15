<?php

   // Why do we try to connect database before user is logged in? (talking specifically for this page)
    require_once("config.php");
    require_once(rootDirectory() . "/util/class.pdf2text.php");
    require_once(rootDirectory() . "/util/UserFactory.php");
    require_once(rootDirectory() . "/util/Vaccine.php");
    require_once(rootDirectory()."/util/NavBar.php");
    $conn = getDatabaseConnection();
    $pagename = '/';

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
    <header>
        <?php
            $navbar = new NavBar(Student::TABLE_NAME);
            echo $navbar->draw();
            ?>
    </header>
<div class="container">
    <p id="info">
        <?php

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
                }

                if(isset($_POST['vaccinecard'])) {
                    if ($_FILES['file']['type'] == "application/pdf") {
                        $a = new PDF2Text();
                        $a->setFilename($_FILES['file']['tmp_name']);
                        $a->decodePDF();

                        $vaccinecardasString = $a->output();

                        $pieces = explode(" ", $vaccinecardasString);

                        $date = $pieces[0];
                        $type = $pieces[1];

                        $date = str_replace(' ', '', $pieces[0]);
                        $date = str_replace("\n", '', $date);

                        $type = str_replace(' ', '', $pieces[1]);
                        $type = str_replace("\n", '', $type);



                        $vaccineInstance = new Vaccine($date,$type);






                        //echo $vaccineInstance->getVaccineName();
                        //echo $vaccineInstance->getDateApplied()->format(DATE_RFC3339);


                    }
                }
            }
        }
?>
<div class="block"></div>
<div class="tile is-ancestor notification is-primary">
    <div class="tile is-parent is-4">
        <div class="tile is-child box">
            <p class="title">
                <?php
                $engine = new Mustache_Engine(array(
                    'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),

                ));
                // render and print profile component sessiondan al name,email falan.
                echo $engine->render("profile", ["name" => $std->getFirstName(), "email" => $std->getEmail(), "id" => $std->getId(),
                    "allowance" => "Allowed", 'hescode' => $std->getHESCode()]);
                /*
                echo $engine->render('welcome', ['firstname' => $std->getFirstName(), 'lastname' => $std->getLastName()]);
                */


                ?>
            </p>
        </div>
    </div>
    <div class="tile is-parent is-vertical">
        <div class="tile is-child box">
            <div class="container">
                <?php
                // vaccine component
                echo $engine->render('vax',
                    ['vaccine' => [
                        ['vaccineDate' => 'bugun', 'vaccineType' => 'TURKOVAC'],
                        ['vaccineDate' => 'yarin', 'vaccineType' => 'TURKOVAC']
                    ]
                    ]);

                ?>
            </div>
        </div>
        <div class="tile is-child box">
            <p>
                <?php
                echo $engine->render("diagnosis",
                    ["diagnosis" => [
                        ["date" => "date 1"],
                        ["date" => "date 2"]
                    ]]);
                ?>
            </p>
        </div>
    </div>
    <div class="tile is-parent">
        <div class="tile is-child box">
            <p>
                <?php
                $pastTest = ["date" => "1.2.4.5", "result" => "negative"];
                $upcomingTest = ["date" => "2023"];

                // upcoming test are loaded first so that they appear on the top of the table.
                echo $engine->render("PCRtest", ["upcomingTest" => [
                    $upcomingTest, $upcomingTest
                ], "pastTest" => [
                    $pastTest, $pastTest
                ]]);


                ?>
            </p>
        </div>
    </div>
</div>
<div class="columns">
    <div class="column is-offset-5 is-two-fifths is-warning">
        <form method="post" action="/delete">
            <div class="form-group">
                <input type="submit" href="/delete" class="button is-danger is-6" value="!!! DELETE ACCOUNT !!!">
            </div>
        </form>
    </div>
</div>


<?php


/*


    echo $engine->render("diagnosis",
        ["diagnosis" => [
            ["date" => "date 1"],
            ["date" => "date 2"]
        ]]);
}

*/
?>
<!--
<form method='post' action="./delete">
    <div class="form-group">
        <input type="submit" href="/delete" class="button button_delete" value="!!! DELETE ACCOUNT !!!">
    </div>
</form>
</div>
<div class="">
<div class="centerdiv">
    <br><br>
    <h2>Some Titles/Tables in the Main Menu</h2>

    <br>
</div>


</div>
-->
</body>
</html>