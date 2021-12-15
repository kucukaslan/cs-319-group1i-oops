<link rel="stylesheet" href="./styles.css">
<?php
    require_once './vendor/autoload.php';
    $engine = new Mustache_Engine(array(
        'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/templates'),
    ));

    // putting mustache elements
    // $mustacheElts = array("id"=>21333, "name"=>"Feridun", "email"=>"email@emailoglu", "allowance"=>"Not Allowed!!!");


    // render and display the html
    echo $engine->render("vax", ['vaccine' => [
            ['vaccineDate' => 'bugun', 'vaccineType' => 'TURKOVAC'],
            ['vaccineDate' => 'yarin', 'vaccineType' => 'TURKOVAC']]]
    );

?>