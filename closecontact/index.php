<?php
    require_once(__DIR__."/../config.php");
    require_once(rootDirectory()."/util/NavBar.php");
    // require_once(rootDirectory() . "/util/UserFactory.php");
    startDefaultSessionWith();
    $pagename = '/closecontact';
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Close Contacts</title>

    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">

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
        // for testing
        $std = new Student();

        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory().'/templates'),
        ));
        $navbar = new NavBar(Student::TABLE_NAME, $pagename);
        echo $navbar->draw();

        $imgsource ="../srcs/default_profile_pic.jpg";

        // TODO: this array will be fetched from the database using user
        $contact_list = [
            ["imgsource" => $imgsource,"name" => "name 1", "id" => 1],
            ["imgsource" => $imgsource,"name"=>"name 2", "id"=>2]
        ];

        // using session array for test purposes
        if (isset($_SESSION["third"])) {
            $contact_list[] = ["imgsource" => $imgsource,"name" => "added", "id" => $_SESSION["third"]];
        }

        echo $m->render("contactlist", ["person" => $contact_list]);


        echo $m->render('pasteventlist',
            ['event' => [
                ['courseCode' => 'Math-123', 'lectureDate' => '1.1.2020'],
                ['courseCode' => 'Math-123', 'lectureDate' => '1.1.2020']            ]
            ]);

        // if the input is numeric try to add this id
        if(isset($_POST['closeContact'])) {
            if (is_numeric($_POST['closeContact'])) {
                $contactIdToAdd = intval($_POST['closeContact']);

                if ($std->addCloseContact($contactIdToAdd)) {
                    // testing
                    $_SESSION["third"] = $contactIdToAdd;
                    $successMass = <<<EOF
<h2> SUCESS added: </h2>
EOF;
                    echo $successMass;
                    echo $contactIdToAdd;
                } else {
                    $alertScript = <<<EOF
                        <script> alert("Close contact id not entered is not valid!") </script>
                    EOF;
                    echo $alertScript;
                }
            } else {
                $alertScript = <<<EOF
                    <script> alert("Given id is not valid") </script>
                EOF;
                echo $alertScript;
            }

        }

        // close contact component
        echo $m->render("addclosecontact");
    }


    ?>
    <!--
    <div class = "component">
        <h2>Add Close Contact by ID</h2>
        <div>
            <form method="post">
                <input type="text" name="HESCode" class="input" id="HESCode" min="1" required>
                <input type="submit" class="button" value="Add">
            </form>
        </div>
    </div>
    !-->
    <!--
    <div class="centerwrapper">
        <div class="centerdiv">
            <br><br>
            <h2>Close Contact Page</h2>
            <br>
            <form action="see"><div class="form-group">
                    <input type="submit" class="button button_submit" value="See Event Details">
                    <input type="hidden" name="eventid" id="eventid" value="CS101ab12as">
                </div>
            </form>
        </div>
    </div>
    !-->

</div>


</body>
</html>