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
if(isset($_POST['sendPDF'])){
    header("Location: PDF.php");
}

    if (!$mysqli = mysqli_connect("127.0.0.1", "mario", "1234", "practicaPHP")) 
    {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    $queryCos = "SELECT costum,count(cosId) as number FROM userscos JOIN coscovid WHERE cosId=idCost group by costum";
    $sqlrCos = $mysqli->query($queryCos);
    $querySin = "SELECT sintoma,count(sinId) as number FROM usersin JOIN sincovid WHERE sinId=idsin group by sintoma";
    $sqlrSin = $mysqli->query($querySin);
?>
<html>
    <head>
        <link href="style/reset.css" rel="stylesheet" type="text/css" />
        <link href="style/style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">  
           google.charts.load('current', {'packages':['corechart']});  
           google.charts.setOnLoadCallback(costums);
           google.charts.setOnLoadCallback(sintomes);   
           function costums()  
           {  
                var data = google.visualization.arrayToDataTable([  
                          ['costum', 'number'],  
                          <?php  
                          while($row = mysqli_fetch_array($sqlrCos))  
                          {  
                               echo "['".$row["costum"]."', ".$row["number"]."],";  
                          }  
                          ?>  
                     ]);  
                var options = {  
                      title: 'Percentatge de costums per tots el usuaris',  
                      //is3D:true,  
                      pieHole: 0.4  
                     };  
                var chart = new google.visualization.PieChart(document.getElementById('costums'));  
                chart.draw(data, options);  
           }  
           function sintomes()  
           {  
                var data = google.visualization.arrayToDataTable([  
                          ['sintoma', 'number'],  
                          <?php  
                          while($row = mysqli_fetch_array($sqlrSin))  
                          {  
                               echo "['".$row["sintoma"]."', ".$row["number"]."],";  
                          }  
                          ?>  
                     ]);  
                var options = {  
                      title: 'Percentatge de sintomes per tots el usuaris',  
                      //is3D:true,  
                      pieHole: 0.4  
                     };  
                var chart = new google.visualization.PieChart(document.getElementById('sintomes'));  
                chart.draw(data, options);  
           }
           </script>  
    </head>
    <body class="admin">
        <form action="#" method="post">
            <p>De quin usuari vols crear un XML?</p>
            <?php
                dropdownXML(); 
            ?>
            <input type="submit" name="sendXML" id="xml" value="crear XML">    
        </form>
        <form action="#" method="post">
           <label for="sendPDF">Crear un PDF amb resum dels usuaris</label>
           <input type="submit" name="sendPDF" value=" crear PDF">
        </form>

        <div id="costums" style="width: 600px; height: 300px;"></div>
        <div id="sintomes" style="width: 600px; height: 300px;"></div>

    </body>
</html>
