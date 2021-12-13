<?php
// Why do we try to connect database before user is logged in? (talking specifically for this page)
include("config.php");
startDefaultSessionWith();
/*
    <!--
    <header class="navbar">
        <div class="navbar-text">Simple e-trade app <div class="navbar-text" style="text-align:right">Logout</div>
        </div>
    </header>
    -->
    <div class="container">
*/

$conn = getDatabaseConnection();

if (!isset($_SESSION['id']) || !isset($conn)) {
    header("location: ./login");
} else {
    $id = $_SESSION['id'];
    require_once './vendor/autoload.php';
    $navbar = new Mustache_Engine(array(
        'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
    ));
    echo $navbar->render('navbar', [
            'title' => 'Main Page',
            'links' => [
                ['href' => '/events', 'title' => 'Events'],
                ['href' => '/closecontact', 'title' => 'Close Contact'],
                ['href' => '/profile', 'title' => 'Profile'],
            ],
        ]
    );
    /*
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

                         ['text' => 'Welcome to the', 'i' => true],
            ['text' => 'smallest', 'i' => true, 'b' => true],
            ['text' => ' ... University Contact Tracing Service, ', 'i' => true],
            ['text' => 'ever', 'i' => true, 'b' => true],
     * */
}

