<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <style>

    </style>
</head>
<body>
<?php
// Why do we try to connect database before user is logged in? (talking specifically for this page)
include_once('config.php');

include_once('util/NavBar.php');

startDefaultSessionWith();


$conn = getDatabaseConnection();

if (!isset($_SESSION['id']) || !isset($conn)) {
    header("location: ./login");
} else {
    require_once 'vendor/autoload.php';

    $id = $_SESSION['id'];
    $navbar = new NavBar(Student::TABLE_NAME);

    //echo 'dwdwdd';
    echo $navbar->draw();

    $welcome = new Mustache_Engine(array(
        'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
    ));
    echo $welcome->render('welcome', ['firstname' => $_SESSION['firstname'], 'lastname' => $_SESSION['lastname']]
    );
    echo $welcome->render('markup', [
            'i' => true,
            'top' => 'Welcome to the',
            'markup' => ['top' => 'smallest', 'bm' => false, 'b' => true, 'markup' => false, 'bot' => false
            ], 'bot' => ' ... University Contact Tracing Service, ',
            'bm' => ['top' => 'dwdw', 'bm' => false]]

    );

}
?>
</body>
</html>

