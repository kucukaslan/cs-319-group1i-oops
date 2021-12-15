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
        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory().'/templates'),
        ));

        // todo sessiondan al
        $usertype = $_SESSION['usertype'];
        $navbar = new NavBar($usertype, $pagename);
        echo $navbar->draw();

        $imgsource ="../srcs/default_profile_pic.jpg";
        echo $m->render("contactlist", ["person" => [
                ["imgsource" => $imgsource,"name" => "name 1", "id" => 123],
                ["imgsource" => $imgsource,"name"=>"name 2", "id"=>333]
        ]]);


        echo $m->render('pasteventlist',
            ['event' => [
                ['courseCode' => 'Math-123', 'lectureDate' => '1.1.2020'],
                ['courseCode' => 'Math-123', 'lectureDate' => '1.1.2020']            ]
            ]);

        // close contact compo
        echo $m->render("addclosecontact");



        //echo "<h3> <abbr title='Your Majesties, Your Excellencies, Your Highnesses'>Hey</abbr> " . $_SESSION['firstname'] . " </h3>";
        //echo "<i> Welcome asdsa to the <abbr title='arguably'>smallest</abbr> ... University Contact Tracing Service, <abbr title='of course by us'> <b>ever</b></abbr>!</i>";

    }

    ?>
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