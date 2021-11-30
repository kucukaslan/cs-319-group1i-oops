<?php
 
    include("../config.php");
    include("../util/Student.php");

    session_start();
    $username ="";
    $password ="";
    if(isset($conn) && $_SERVER["REQUEST_METHOD"] == "POST") {
        // username and password sent from the form
        $username = $_POST['username'];
        $password =$_POST['password'];

        //set parameters
        $entered_username = $username;
        $entered_password = $password;


        
        // var_dump($std);
        // echo '<br>';

        try {
            $std = new Student($conn, $entered_username, $entered_password);
               // session_start();
                $_SESSION['firstname'] = $std->getFirstName();
                $_SESSION['lastname'] = $std->getLastName();
                $_SESSION['id'] = $std->getId();
                $_SESSION['email'] = $std->getEmail();
                header("location: .."); //redirect to main page
      
        }
        catch (Exception $e) {
            echo "<script type='text/javascript'>alert('".$e->getMessage()."');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../styles.css">
    <style>

    </style>
</head>
<body>

<div class="container">
    <nav class="navbar">
        <h4 class="navbar-text">ForThyHealth</h4>
    </nav>

    <div class="centerwrapper">
        <div class="centerdiv">
            <br><br>
            <h2>Login For Thy Health</h2>
            <p>Please enter your E-mail and password to login.</p>

            <form id="loginForm" action="" method="post">


                <div class="form-group">
                    <label for="username">E-mail</label> <br>
                    <input type="text" name="username" class="form-control" id="username" required='true'>

                </div>
                <div class="form-group">
                    <label for="password">Password</label><br>
                    <input type="password" name="password" class="form-control" id="password" required='true'>

                </div>
                <div class="form-group">
                    <input type="submit"  class="button button_submit" value="Login">
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
