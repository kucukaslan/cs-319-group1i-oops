<?php
// Why do we try to connect database before user is logged in? (talking specifically for this page)
require_once("config.php");
require_once(rootDirectory() . "/util/class.pdf2text.php");
require_once(rootDirectory() . "/util/UserFactory.php");
require_once(rootDirectory() . "/util/EventFactory.php");
require_once(rootDirectory() . "/util/Vaccine.php");
require_once(rootDirectory() . "/util/VaccineFactory.php");
require_once(rootDirectory() . "/util/VaccineManager.php");
require_once(rootDirectory() . "/util/RiskStatusManager.php");
require_once(rootDirectory() . "/util/Test.php");
require_once(rootDirectory() . "/util/NavBar.php");
require_once(rootDirectory() . "/util/Diagnosis.php");
require_once(rootDirectory() . "/util/AllowanceFacade.php");

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
<div class="container has-text-centered">
    <?php


        $id = $_SESSION['id'];

        $hesErr = '';
        $uf = new UserFactory();//$usertype);
        $user = $uf->makeUserById($conn, $usertype, $_SESSION['id']);

        //----
        if (isset($conn) && $_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['HESCode'])) {
                $formattedHESCode = RiskStatusManager::formatHESCode($_POST['HESCode']);
                if ($formattedHESCode != "") {
                    if (!$user->updateHESCode($formattedHESCode)) {
                        $hesErr = "Failed to update HES Code";
                    } else {
                        header("Refresh:0");
                    }
                } else {
                    $hesErr = "Given value is not of the form of a HES code!";
                }
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
                    $af = new AllowanceFacade($conn, Student::TABLE_NAME, $user->getId());
                    if ($af->getIsAllowed())
                        $isAllowed = "Allowed";
                    else
                        $isAllowed = "";
                    // render and print profile component sessiondan al name,email falan.
                    echo $engine->render("profile", [
                        "name" => $user->getFirstName() . " " . $user->getLastName(),
                        "email" => $user->getEmail(),
                        "id" => $user->getId(),
                        'allowance' => $isAllowed,
                        'hescode' => $user->getHESCode(),
                        'hesErr' => $hesErr
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
                        $vaccineManufacturer = $vaccine->getVaccineName();
                        $abc[] = ['vaccineDate' => $myVaccineDate->format('d M Y'), 'vaccineManufacturer' => $vaccineManufacturer];
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
                        'type' => $diagnosise->getType(),
                        'result' => $diagnosise->getResultAsString()
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
                    $ef = new EventFactory($conn);

                    $pastTests = Test::getTestsOfUserPast($_SESSION['id'], $conn);

                    $pastArr = array();
                    $futureArr = array();
                    foreach ($pastTests as $p) {
                        $pastArr[] = array("date" => $p->getTestDate()->format('Y M d h:i'), "result" => $p->getResult());
                    }

                    $appointments = $user->getEventsIParticipate(TestAppointmentEvent::TABLE_NAME);
                    $appointment_data = array();
                    foreach ($appointments as $appointment) {
                        $appointment_data[] = ["date"=>$appointment->getStartDate()->format("Y M d h:i"), "place"=>$appointment->getPlace()];
                    }


                    echo $engine->render("PCRtest", ['upcomingTest' => $appointment_data,
                        'pastTest' => $pastArr
                    ]);

                    ?>
                </p>
            </div>
        </div>
    </div>
    <?= $navbar->footer() ?>
</body>
</html>
