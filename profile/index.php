<?php
// Why do we try to connect database before user is logged in? (talking specifically for this page)
require_once("../config.php");
require_once(rootDirectory() . "/util/class.pdf2text.php");
require_once(rootDirectory() . "/util/UserFactory.php");
require_once(rootDirectory() . "/util/Vaccine.php");
require_once(rootDirectory() . "/util/VaccineFactory.php");
require_once(rootDirectory() . "/util/VaccineManager.php");
require_once(rootDirectory() . "/util/Test.php");
require_once(rootDirectory() . "/util/NavBar.php");

$conn = getDatabaseConnection();
startDefaultSessionWith();

if (!isset($_SESSION['id']) || !isset($conn)) {
    header("location: ../login");

}
$usertype = $_SESSION['usertype'] ?? Student::TABLE_NAME;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Page</title>
    <link rel="stylesheet" href="../styles.css">

    <meta name="author" content="Muhammed Can Küçükaslan">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
<header>
    <?php
    $navbar = new NavBar($usertype);
    echo $navbar->draw();
    ?>
</header>
<div class="container">
    <?php
    $uf = new UserFactory();
    $std = $uf->makeUserById($conn, $usertype, $_SESSION['id']);

    $m = new Mustache_Engine(array(
        'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
    ));

    $wrongVerPassword = '';
    if (isset($conn) && $_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['newName'])) {
            $newFName = $_POST['newName'];
            unset($_POST);

            $std->setFirstname($newFName);
            $std = $uf->makeUserById($conn, $usertype, $_SESSION['id']);
            $std->updateToDatabase();
            echo "new name: " . $newFName;
            echo "user id" . $_SESSION['id'];
            // header("Refresh:0");
        }
        if (isset($_POST['newsName'])) {
            $newSName = $_POST['newsName'];
            unset($_POST);

            $std->setLastname($newSName);
            $std = $uf->makeUserById($conn, $usertype, $_SESSION['id']);
            header("Refresh:0");
        }
        if (isset($_POST['newEmail'])) {
            $newEmail = $_POST['newEmail'];
            unset($_POST);

            $std->setEmail($newEmail);
            $std = $uf->makeUserById($conn, $usertype, $_SESSION['id']);
            header("Refresh:0");
        }
        if (isset($_POST['newP']) && isset($_POST['verP'])) {
            $currentPassword = $_POST['verP'];
            $std = $uf->makeUserById($conn, $usertype, $_SESSION['id']);
            $std->setPassword($currentPassword);
             try {
                if ( $std->verifyPassword()) {
                    $newPassword = $_POST['newP'];
                    $std->updatePassword($newPassword);
                    getConsoleLogger()->log("profile","Password changed");// np: $newPassword, cp: $currentPassword");
                } else {
                    $wrongVerPassword = 'Current password is invalid';
                }
            }
            catch (PasswordIsWrongException $e) {
                $wrongVerPassword = 'Current password is incorrect';
                getConsoleLogger()->log("profile","Password change failed");
            }
            catch (Exception $e) {
                $wrongVerPassword = 'Current password is invalid';
                getConsoleLogger()->log("profile","Password change failed");
            }
            unset($_POST);
            header("Refresh:0");
        }
    }
    echo $m->render('profilePage', [
        "name" => $std->getFirstName(),
        "email" => $std->getEmail(),
        "id" => $std->getId(),
        'WVPass' => $wrongVerPassword  
    ]);
    ?>
</div>
<?= $navbar->footer(); ?>
</body>
</html>