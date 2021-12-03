<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main Page</title>
    <link rel="stylesheet" href="./styles.css">

    <meta name="author" content="CS319">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
<!--
<header class="navbar">
    <div class="navbar-text">Simple e-trade app <div class="navbar-text" style="text-align:right">Logout</div>
    </div>
</header>
-->
<div class="container">
    <header class="navbar">
        <a class="navbar-text" background_color="blue" href=".">Main Menu</a>
        <a class="navbar-text" href="./closecontact">Close Contact</a>
        <a class="navbar-text" href="./events">Events</a>
        <a class="navbar-text" href="profile" >Profile</a>
        <a class="navbar-text" href="logout.php" id="logout" >Logout</a>
    </header>

    <p id='info'>
    <?php
    // Why do we try to connect database before user is logged in? (talking specifically for this page)
    include("config.php");
    session_start();

    if( !isset($_SESSION['id']) || !isset($conn) ){ 
        header("location: ./login");
    }
    else{
        $id = $_SESSION['id'];  
        echo "<h3> <abbr title='Your Majesties, Your Excellencies, Your Highnesses'>Hey</abbr> ". $_SESSION['firstname']." ". $_SESSION['lastname']." </h3>";
        echo "<i> Welcome to the <abbr title='arguably'>smallest</abbr> ... University Contact Tracing Service, <abbr title='of course by us'> <b>ever</b></abbr>!</i>";

    }

    ?>
    </p>
    <div class="centerdiv">
        <div class="centerdiv">
            <br><br>
            <h2>Some Titles/Tables in the Main Menu</h2>
            
            <br>
        </div>
    </div>

    <form method='post' action="./profile"><div class="form-group">
            <input type="submit" class="button button_submit" value="Go to Profile Page">
        </div> </form>
</div>
</body>
</html>

