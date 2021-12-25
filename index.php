<?php
// Why do we try to connect database before user is logged in? (talking specifically for this page)
require_once("config.php");
require_once(rootDirectory() . "/util/class.pdf2text.php");
require_once(rootDirectory() . "/util/UserFactory.php");
require_once(rootDirectory() . "/util/Vaccine.php");
require_once(rootDirectory() . "/util/VaccineFactory.php");
require_once(rootDirectory() . "/util/VaccineManager.php");
require_once(rootDirectory() . "/util/Test.php");
require_once(rootDirectory() . "/util/NavBar.php");
require_once(rootDirectory() . "/util/Diagnosis.php");

$conn = getDatabaseConnection();
$pagename = '/';
startDefaultSessionWith();

if (!isset($_SESSION['id']) || !isset($conn)) {
    header("location: ./login");

}
$usertype = $_SESSION['usertype'] ?? Student::TABLE_NAME;
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
    $navbar = new NavBar($usertype);
    echo $navbar->draw();
    ?>
</header>
<div class="container">
    <p id="info">
        <?php


        $id = $_SESSION['id'];


        $uf = new UserFactory();//$usertype);
        $std = $uf->makeUserById($conn, $usertype, $_SESSION['id']);

        //----
        if (isset($conn) && $_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['HESCode'])) {

                $hescode = $_POST['HESCode'];
                $std->updateHESCode($hescode);
                $std = $uf->makeUserById($conn, $usertype, $_SESSION['id']);
            }

            if (isset($_POST['vaccinecard'])) {
                if ($_FILES['file']['type'] == "application/pdf") {
                    $a = new PDF2Text();
                    $a->setFilename($_FILES['file']['tmp_name']);
                    $a->decodePDF();

                    $vaccinecardasString = $a->output();

                    $pieces = explode(" ", $vaccinecardasString);

                    $date = $pieces[0];
                    $cvx = $pieces[1];

                    $date = str_replace(' ', '', $pieces[0]);
                    $date = str_replace("\n", '', $date);

                    $cvx = str_replace(' ', '', $pieces[1]);
                    $cvx = str_replace("\n", '', $cvx);

                    $vf = new VaccineFactory();

                    $vaccineInstance = $vf->makeVaccineByCvxCode(getDatabaseConnection(), 208);
                    $vacmanager = new VaccineManager(getDatabaseConnection(), $_SESSION['id']);
                    $vacmanager->insertVaccination($vaccineInstance, new DateTime($date));
                }
            }


            if (isset($_POST['diagnosis'])) {
                if ($_FILES['file']['type'] == "application/pdf") {
                    $a1 = new PDF2Text();
                    $a1->setFilename($_FILES['file']['tmp_name']);
                    $a1->decodePDF();

                    $diagnosisasString = $a1->output();

                    $pieces1 = explode(" ", $diagnosisasString);

                    $date = $pieces1[0];
                    $type = $pieces1[1];
                    $result = $pieces1[2];


                    $date1 = str_replace(' ', '', $pieces1[0]);
                    $date1 = str_replace("\n", '', $date);

                    $type1 = str_replace(' ', '', $pieces1[1]);
                    $type1 = str_replace("\n", '', $type1);

                    $currentId = $_SESSION['id'];


                    $diagnosisInstance = new Diagnosis();

                    //$date2 = datetime::createFromFormat(DateTimeInterface::RFC3339,$date1);
                    $date2 = DateTime::createFromFormat('Y-m-d', $date1);

                    $diagnosisInstance->setDiagnosisDate($date2);
                    $diagnosisInstance->setResult($result);
                    $diagnosisInstance->setType($type1);

                    echo $diagnosisInstance;
                    $diagnosisInstance->updateDiagnosisesOfUser($conn);


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
                    echo $engine->render("profile", [
                        "name" => $std->getFirstName(),
                        "email" => $std->getEmail(),
                        "id" => $std->getId(),
                        "allowance" => "Allowed",
                        'hescode' => $std->getHESCode()
                    ]);

                    ?>
                </p>
            </div>
        </div>
        <div class="tile is-parent is-vertical">
            <div class="tile is-child box">
                <div class="container">
                    <?php

                    $vaccineManager = new VaccineManager($conn, $_SESSION['id']);
                    $myVaccines = $vaccineManager->getUserVaccines();


                    $abc = [];
                    foreach ($myVaccines as $vaccine) {
                        $myVaccineType = $vaccine->getVaccineType();
                        $myVaccineDate = $vaccine->getVaccineDate();
                        $abc[] = ['vaccineDate' => $myVaccineDate->format('d M Y'), 'vaccineType' => $myVaccineType];
                    }

                    echo $engine->render('vax',
                        ['vaccine' => $abc]);

                    ?>
                </div>
            </div>
            <div class="tile is-child box">
                <?php

                /*
                $diagnosisTest = new Diagnosis();
                $diagnosisTest->setType("hadi bakalim");
                $diagnosisTest->setResult(3);
                $diagnosisTest->setDiagnosisId(24);
                $diagnosisTest->setUserId($_SESSION['id']);
                $DateAndTime1 = new DateTime('NOW');
                $diagnosisTest->setDiagnosisDate($DateAndTime1);
                */
                //$diagnosisTest->updateDiagnosisesOfUser($conn);
                $diagnosises = Diagnosis::getDiagnosisesOfUser($_SESSION['id'], $conn);
                $dTable = [];
                foreach ($diagnosises as $diagnosise) {
                    $dTable[] = [
                        'date' => $diagnosise->getDiagnosisDate()->format('d M Y'),
                        'type' => $diagnosise->getType()
                    ];
                }

                echo $engine->render("diagnosis",
                    ["diagnosis" => $dTable]);

                ?>
            </div>
        </div>
        <div class="tile is-parent">
            <div class="tile is-child box">
                <p>
                    <?php
                    //$pastTest = ["date" => "1.2.4.5", "result" => "negative"];
                    //$upcomingTest = ["date" => "2023"];
                    $pastTests = Test::getTestsOfUserPast($_SESSION['id'], $conn);
                    $futureTests = Test::getTestsOfUserFuture($_SESSION['id'], $conn);

                    $pastArr = array();
                    $futureArr = array();
                    foreach ($pastTests as $p) {
                        $pastArr[] = array("date" => $p->getTestDate()->format('r'), "result" => $p->getResult());
                    }
                    foreach ($futureTests as $p) {
                        $futureArr[] = array("date" => $p->getTestDate()->format('r'));
                    }

                    echo $engine->render("PCRtest", ['upcomingTest' => $futureArr,
                        'pastTest' => $pastArr
                    ]);

                    ?>
                </p>
            </div>
        </div>
    </div>
</body>
</html>