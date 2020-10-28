<?php

session_start();
if ( isset( $_SESSION['admin']) ) {
    
    // Grab user data from the database using the user_id
    // Let them access the "logged in only" pages

} else {
    
    header("Location: index.php");
    exit;
    // Redirect them to the login page
}
//creating a dropdown menu to select the user that we will use for creating the XML
function dropdownXML(){
    if (!$mysqli = mysqli_connect("127.0.0.1", "mario", "1234", "practicaPHP")) 
    {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    $query = "SELECT * FROM users";
    $sqlr = $mysqli->query($query);
    $objectSQLr = $sqlr->fetch_object();

    echo "<select name='userDrop' id='userDrop'";

    while($objectSQLr = $sqlr->fetch_object()){
        echo '<option value='. $objectSQLr->user_id .' >'. $objectSQLr->user .'</option>';
    }

    echo "</select>";
    mysqli_close($mysqli);
}


if(isset($_POST['sendXML']) &&  isset($_POST['userDrop'])){
    $_SESSION['userDrop']=$_POST['userDrop'];
    header("Location: xml.php");
}

?>
<html>
    <head>
        <link href="style/reset.css" rel="stylesheet" type="text/css" />
        <link href="style/style.css" rel="stylesheet" type="text/css" />
        <script src="libs/canvasjs.min.js"></script>
    </head>
    <body class="admin">
        <form action="#" method="post">
            <p>De quin usuari vols crear un XML?</p>
            <?php
                dropdownXML(); 
            ?>
            <input type="submit" name="sendXML" id="xml" value="crear XML">
            
        </form>

    </body>
</html>
