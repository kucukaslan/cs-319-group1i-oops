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
    $user = $uf->makeUserById($conn, $usertype, $_SESSION['id']);

    $m = new Mustache_Engine(array(
        'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
    ));

    if (isset($conn) && $_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['newName'])) {
            $newFName = $_POST['newName'];
            unset($_POST);

            $user = $uf->makeUserById($conn, $usertype, $_SESSION['id']);
            $user->setFirstname($newFName);
            $user->updateToDatabase();

            header("Refresh:0");
        }
        if (isset($_POST['newsName'])) {
            $newSName = $_POST['newsName'];
            unset($_POST);

            $user = $uf->makeUserById($conn, $usertype, $_SESSION['id']);
            $user->setLastname($newSName);
            $user->updateToDatabase();

            header("Refresh:0");
        }
        if (isset($_POST['newEmail'])) {
            $newEmail = $_POST['newEmail'];
            unset($_POST);

            $user = $uf->makeUserById($conn, $usertype, $_SESSION['id']);
            $user->setEmail($newEmail);
            $user->updateToDatabase();
            header("Refresh:0");
        }
        if (isset($_POST['conP']) && isset($_POST['newP']) && isset($_POST['verP'])) {
            if (0 == strcmp($_POST['conP'], $_POST['newP'])) {
                $_SESSION['CPE'] = '';
                $currentPassword = $_POST['verP'];
                $user = $uf->makeUserById($conn, $usertype, $_SESSION['id']);
                $user->setPassword($currentPassword);
                try {
                    if ($user->verifyPassword()) {
                        $newPassword = $_POST['newP'];
                        $user->updatePassword($newPassword);
                        $_SESSION['NPE'] = '';
                        getConsoleLogger()->log("profile", "Password changed");// np: $newPassword, cp: $currentPassword");
                    } else {
                        $_SESSION['NPE'] = 'Current password is invalid';
                    }
                } catch (PasswordIsWrongException $e) {
                    $_SESSION['NPE'] = 'Current password is incorrect';
                    getConsoleLogger()->log("profile", "Password change failed");
                } catch (Exception $e) {
                    $_SESSION['NPE'] = 'Current password is invalid';
                    getConsoleLogger()->log("profile", "Password change failed");
                }
            } else {
                $_SESSION['NPE'] = '';
                $_SESSION['CPE'] = 'Passwords do not match';
                getConsoleLogger()->log("profile", "Passwords are different");
            }
            unset($_POST);
            header("Refresh:0");
        }
    }
    echo $m->render('profilePage', [
        "name" => $user->getFirstName() . " " . $user->getLastName(),
        "email" => $user->getEmail(),
        "id" => $user->getId(),
        'WVPass' => $_SESSION['NPE'],
        'WCPass' => $_SESSION['CPE'],
    ]);
    ?>
</div>
<?= $navbar->footer(); ?>
</body>
</html>