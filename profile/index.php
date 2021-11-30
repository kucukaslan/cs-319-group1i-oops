<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Page of <?php echo $_SESSION['cname']?></title>
    <link rel="stylesheet" href="../styles.css">

    <meta name="author" content="Muhammed Can Küçükaslan">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
<div class=\"container\">

<?php
    include "../config.php";
    session_start();

    if(! isset($_SESSION['id'] )) {
        echo"<div class='centerwrapper'> <div class = 'centerdiv'>"
        . "You haven't logged in";
         echo "<form method='get' action=\"..\"><div class=\"form-group\">
                        <input type=\"submit\" class=\"button button_submit\" value=\"Go to Login Page\">
                    </div> </form>";
            echo "</div> </div>";
    }
    else { 
        

        echo '<header class="navbar">
        <a class="navbar-text" background_color="blue" href="..">Main Menu</a>
        <a class="navbar-text" href="../closecontact">Close Contact</a>
        <a class="navbar-text" href="../events">Events</a>
        <a class="navbar-text" href="../profile" >Profile</a>
        <a class="navbar-text" href="../logout.php" id="logout" >Logout</a>
        </header class="navbar">';

        echo "<h3> <abbr title='Your Majesties, Your Excellencies, Your Highnesses'>Hey</abbr> ". $_SESSION['firstname']." </h3>";
        echo "<i> Welcome to the <abbr title='arguably'>smallest</abbr> ... University Contact Tracing Service, <abbr title='of course by us'> <b>ever</b></abbr>!</i>";
    
    }

?>
 <div class="centerwrapper">
        <div class="centerdiv">
            <br><br>
            <h2>User Profile Page</h2>
            <br>
        </div>
    </div>


</div>


</body>
</html>