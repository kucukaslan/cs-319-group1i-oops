<?php
require_once(__DIR__ . "/../config.php");
require_once(rootDirectory() . "/util/NavBar.php");
require_once(rootDirectory() . "/util/UserFactory.php");
startDefaultSessionWith();
$pagename = '/closecontact';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Close Contacts</title>

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
        $uf = new UserFactory();
        $user = $uf->makeUserById($conn, $usertype, $_SESSION["id"]);

        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
        ));

        $navbar = new NavBar($usertype, $pagename);
        echo $navbar->draw();

        $imgSource = "../srcs/default_profile_pic.jpg";

        $buttonNames = [];
        $contact_list = [];
        $contactIds = $user->getCloseContacts();

        for ($i = 0; $i < $user->getNoOfCloseContacts(); $i++) {
            // set button names
            $buttonNames[] = "button" . ($i + 1);

            // add close contact to contact list
            $contact_list[] = ["imgsource" => $imgSource, "buttonname" => $buttonNames[$i], "name" => $user->giveName($contactIds[$i]), "id" => $contactIds[$i]];
        }

        // render close contacts
        echo $m->render("contactlist", ["person" => $contact_list]);

        // render past events
        echo $m->render('listWith3ColumnsAndButton',
            ['row' => [
                ['firstEl' => 'Math-123', 'secondEl' => '1.1.2020', "buttonLink"=>"../../closecontact/see", "buttonName"=>"see"],
                ['firstEl' => 'Math-123', 'secondEl' => '1.1.2020', "buttonLink"=>"../../closecontact/see", "buttonName"=>"see"]]
            , "title"=>"Past Events", "column1"=>"Course Code", "column2"=>"Lecture Date", "column3"=>"See Participants"]);
        // close contact component
        echo $m->render("addclosecontact");


        // implementation of add close contact by id
        // if the input is numeric try to add this id
        if (isset($_POST['closeContact'])) {
            if (is_numeric($_POST['closeContact'])) {
                $contactIdToAdd = intval($_POST['closeContact']);

                if ($user->addCloseContact($contactIdToAdd)) {
                    $successMass = <<<EOF
<h2> SUCESS added: </h2>
EOF;
                    echo $successMass;
                    echo $contactIdToAdd;
                } else {
                    $alertScript = <<<EOF
<script> alert("Cannot add given ID") </script>
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

        // implementation of deleting user from the close contact list
        // check if a button is pressed for any user in the table
        for ($i = 0; $i < $user->getNoOfCloseContacts(); $i++) {
            $buttonName = "button" . ($i + 1);
            if (isset($_POST[$buttonName])) {
                // delete the user at the ($i + 1)th row in the contact list
                $idToDeleteFromContactList = $contact_list[$i]["id"];
                if ($user->deleteCloseContact($idToDeleteFromContactList)) {
                    echo "DELETED USER WITH ID: " . $idToDeleteFromContactList;
                } else {
                    echo "DID NOT MANAGE TO DELETE " . $idToDeleteFromContactList;
                }

                break;
            }
        }


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