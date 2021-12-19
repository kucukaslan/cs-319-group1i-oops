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
        $engine = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/administration/templates'),
        ));
        $usertype = $_SESSION['usertype'] ?? Student::TABLE_NAME;

        $uf = new UserFactory();
        $user = $uf->makeUserById($conn, $usertype, $_SESSION["id"]);

        $navbar = new NavBar($usertype);
        echo $navbar->draw();

        echo $engine->render("searchByIdHES");



    }

    ?>
    <div class="centerwrapper">
        <div class="centerdiv">
            <br><br>
            <h2>Administration Page</h2>
            <br>
        </div>
    </div>


</div>


</body>
</html>