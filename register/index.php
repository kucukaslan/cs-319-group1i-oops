<?php 
    include_once("../config.php");
    startDefaultSessionWith();
?>    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Page <?php echo isset($_SESSION['firstname']) ? " of ".$_SESSION['firstname'] : "" ?></title>
    <link rel="stylesheet" href="../styles.css">

    <meta name="author" content="Muhammed Can Küçükaslan">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
<div class="container">

<?php
    require_once rootDirectory().'/vendor/autoload.php';

    $conn = getDatabaseConnection();

    if (isset($_SESSION['id'])) {
        header("location: ../");
    } else {
        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/../templates'),
        ));
        echo $m->render('navbar', ['links' => [
                ['href' => '../login', 'title' => 'Login']
                ]]
        );


    }

    ?>
    <div class="centerwrapper">
        <div class="centerdiv">
            <br>
            <h3>Registration form etc.</h3>
            <br>
        </div>
    </div>


</div>

</body>
</html>