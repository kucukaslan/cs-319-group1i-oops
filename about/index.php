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
    require_once rootDirectory() . '/vendor/autoload.php';

    $conn = getDatabaseConnection();

    $m = new Mustache_Engine(array(
        'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/../templates'),
    ));
    echo $m->render('navbar', ['links' => [
            ['href' => '../', 'title' => 'Main Menu'],
            ['href' => '../events', 'title' => 'Events'],
            ['href' => '../closecontact', 'title' => 'Close Contact'],
            ['href' => '../profile', 'title' => 'Profile'],
            (isset($_SESSION['id']) ?
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
            <h2>About</h2>
            <?php
            echo '<p>Our project is a pandemic manager system developed for managing COVID pandemic. The system is developed as a web application and can be accessible from personal computers and mobile devices that have access to the Internet. The main audience that the system serves is the students,instructors,administrators and other university members of Bilkent University.
                </p></br><p>To use the system, individuals need to register the web application using their Bilkent mails . After the verification process is completed, users can log in the system with their Bilkent University ID’s and passwords. The application provides different interfaces and features for different students,teachers and university administration.
                </p></br><p>The main feature system provides for the students is an interface for them  to submit their HES codes, PCR tests, uploading vaccination cards, and informing them about their campus allowance situation accordingly. The system tracks the HES code of the students and in case HES code situation is risky it changes the campus allowance status accordingly. Other features the system provides to students are enrolling in lectures , creating a list of close contact people and making reservations from Sports Hall. The system also provides information about the covid statistics related with both country and the university.
                </p></br><p>The features system provides for the instructors, administrators and other university staff such as Sports Hall staff includes the features it provides to students;however is not limited to them. The instructors can create courses they give throughout the semester, and along with the administrators can trace the allowance situation of the students enrolled in these courses. Similarly, the Sports Hall staff is provided the feature of tracing the list of students that made reservations and their allowance situations.
                </p>'
            ?>
            <br>
        </div>
    </div>


</div>

</body>
</html>