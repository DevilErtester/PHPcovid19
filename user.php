<?php
session_start();
if ( isset( $_SESSION['user_id'] ) ) {
    
    // Grab user data from the database using the user_id
    // Let them access the "logged in only" pages

} else {
    // Redirect them to the login page
    header("Location: index.php");
    exit;
}
if (!$mysqli = mysqli_connect("127.0.0.1", "mario", "1234", "practicaPHP")) 
{
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
 //function to print all results of select
function printArray($tabla,$type){
    if (!$mysqli = mysqli_connect("127.0.0.1", "mario", "1234", "practicaPHP")) 
    {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
   
    $result = $mysqli->query("SELECT * FROM ". $tabla. "");
    while($row = $result->fetch_row()){
        $rows[] = $row;
    }
    foreach($rows as $row){
        // putting all results on a div of class simptoms for applying style
    
        echo  '<label for='. $type . $row[0] . '>'. $row[1] . '</label><input type="checkbox" id=' . $type . $row[0] . ' value=' . $row[0] . ' name="'.$type.'[]"></br>';
       
    }
    mysqli_close($mysqli);
}
if(isset($_POST['log_out'])){
    header("Location: index.php");
}
if(isset($_POST['send'])){

    if(isset($_POST['Covid']) && !empty($_POST['Covid'])){

        $radioVal = $_POST['Covid'];
        $userId = $_SESSION['user_id'];
        

        if(!empty($_POST['sin'])) {    
            foreach($_POST['sin'] as $value){
                $arraySin[$i] = $value; 
                $i++;
            }
        }
        if(!empty($_POST['cos'])) {    
            foreach($_POST['cos'] as $value){
                
                $arrayCos[$i] = $value; 
                $i++;
            }
        }
        if($radioVal === 'y'){

            $mysqli->query("UPDATE users SET covid = 1 WHERE user_id = '" . $_SESSION['user_id'] . "'");

            foreach($arrayCos as $cos){
                $mysqli->query("INSERT INTO usersCos (userId,cosId) VALUES (". ($_SESSION['user_id']) .", '" . ($cos) . "')");
            }
            foreach($arraySin as $sin){
                $mysqli->query("INSERT INTO userSin (userId,sinId) VALUES (". ($_SESSION['user_id']) .", '" . ($sin) . "')");
            }
        }
        else{
            foreach($arrayCos as $cos){
                $mysqli->query("INSERT INTO usersCos (userId,cosId) VALUES (". ($_SESSION['user_id']) .", '" . ($cos) . "')");
            }
        }
    }
}
mysqli_close($mysqli);

?>
<html>
    <head>
        <script src="scripts/scriptsUser.js"></script>
        <link href="style/reset.css" rel="stylesheet" type="text/css" />
        <link href="style/style.css" rel="stylesheet" type="text/css" />    
    </head>
    <body id="user" onload="functionEreaseForm()">
        <div>
            <form action="#" method="post" name="formCovid">
                <input type="submit" name="log_out" value="Log out">
                <p>Has passat el covid?</p>
                <input type="radio" id="y" name="Covid" value="y" onclick="functionDisplayForm(this.value)">
                <label for="y">Si</label>
                <input type="radio" id="n" name="Covid" value="n" onclick="functionDisplayForm(this.value)">
                <label for="n">No</label>
                

            
                <div id="sin">
                    <h1>Sintomes</h1>
                        <?php
                            printArray(sinCovid,sin);
                        ?>
                </div>

                <div id="cos">
                    <h1>Costums</h1>

                        <?php
                            printArray(cosCovid,cos);
                        ?>
                </div>
                <input type="submit" value="Submit" name="send">
            </form>  
        </div> 
    </body>
</html>