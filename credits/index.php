<?php 
    include_once("../config.php");
    startDefaultSessionWith();
?>    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About For thy Health</title>
    <link rel="stylesheet" href="../styles.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
<div class="container">

<?php
    require_once rootDirectory().'/vendor/autoload.php';

    $conn = getDatabaseConnection();

    $m = new Mustache_Engine(array(
        'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/../templates'),
    ));
    echo $m->render('navbar', ['links' => [
            ['href' => '../', 'title' => 'Main Menu'],
            ['href' => '../events', 'title' => 'Events'],
            ['href' => '../closecontact', 'title' => 'Close Contact'],
            ['href' => '../profile', 'title' => 'Profile'],
            ( isset($_SESSION['id']) ?
                ['href' => '../login', 'title' => 'Login'] 
                :
                ['href' => '../logout.php', 'title' => 'Logout', 'id' => 'logout']
            )
            ]]
    );
   
   
    ?>
    <div class="centerwrapper">
        <div class="centerdiv">
            <br><br>
            <h2>Credits</h2>
            <?php
                echo '<h3>ForThyHealth</h3>
                </h4>Group 1-I - OOPS Members: </h4>
                </br><abbr title="master of some cool stuff that no other man can comprehend">Giray Akyol</abbr>
                </br><abbr title="his majesty known as the easter egg troll">Muhammed Can Küçükaslan</abbr>
                </br><abbr title="the one I wouldn\'t dare to troll"> Muhammet Hikmet Şimşir</abbr>
                </br><abbr title="the one I wouldn\'t dare to troll"> Mustafa Utku Aydoğdu</abbr>
                </br><abbr title="the one I wouldn\'t dare to troll"> Mustafa Yasir Altunhan</abbr>'
            ?>
            <br>
        </div>
    </div>


</div>

</body>
</html>