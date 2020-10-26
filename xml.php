<?php

    session_start();

    if ( isset( $_SESSION['user_id'] ) ) {
        // Grab user data from the database using the user_id
        // Let them access the "logged in only" pages

    } else {
        // Redirect them to the login page
        header("Location: index.php");
    }
    if (!$mysqli = mysqli_connect("127.0.0.1", "mario", "1234", "practicaPHP")) 
    {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    $query = "SELECT * FROM users WHERE user_id= '".  $_SESSION['userDrop'] ."'";
    $sinQuery = "SELECT * FROM userSin WHERE userId= '".  $_SESSION['userDrop'] ."'";
    $cosQuery = "SELECT * FROM usersCos WHERE userId= '".  $_SESSION['userDrop'] ."'";
    $sqlr = $mysqli->query($query);

    $objectSQLr = $sqlr->fetch_object();
   
        $xml = new SimpleXMLElement('<covid19/>');

        $usersXML = $xml->addchild('user');
        $usersXML->addAttribute('id',$objectSQLr->user_id);
        
        $user = $usersXML->addchild('nombre',$objectSQLr->user);
        
        if ($objectSQLr->covid == "1"){
            $sintomas = $usersXML->addchild('sintomes');
            $sinSQLR=$mysqli->query($sinQuery);

            while($sinRes = $sinSQLR->fetch_object()){
                $sin = $sintomas->addchild('sin',$sinRes->sinId);
            }
        }
        $costums = $usersXML->addchild('costums');
        $cosSQLR=$mysqli->query($cosQuery);

        while($cosRes = $cosSQLR->fetch_object()){
            $cos = $costums->addchild('cos',$cosRes->cosId);
        }

        $name = strftime($objectSQLr->hash . '_%m_%d_%Y.xml');
        header('Content-Disposition: attachment;filename=' . $name);
        header('Content-Type: text/xml');
    
        echo $xml->saveXML();
        

    mysqli_close($mysqli);

?>