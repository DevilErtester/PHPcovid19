<?php

session_start();
//if the session is not valid we destroy previus session
session_destroy();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    require 'libs/PHPMailer/src/Exception.php';
    require 'libs/PHPMailer/src/PHPMailer.php';
    require 'libs/PHPMailer/src/SMTP.php';

    function envia ($user, $hash) {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->CharSet="UTF-8";
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->Username = 'marioproves1@gmail.com';
        $mail->Password = 'NSvH2Aubg4KzWKr';
        $mail->SMTPAuth = true;
        $mail->SMTPDebug = 0;
        
        $mail->From = 'marioproves1@gmail.com';
        $mail->FromName = 'Mario Proves';
        
        
        $mail->AddAddress($user); //correu del client
        
        
        $mail->IsHTML(true);
        $mail->Subject = "Codi de seguretat";
        $mail->AltBody = "Codi de seguretat";
        $mail->Body = "Hola, aquest es el teu codi de verificacio, copia'l i enganxa'l a la pagina: " . $hash . ".";
        
        if(!$mail->Send()){
            echo "Mailer Error: " . $mail->ErrorInfo;
            return false;
        }else{
            return true;
        }
    }
    // connection to database 
    if (!$mysqli = mysqli_connect("127.0.0.1", "mario", "1234", "practicaPHP")) 
    {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

   
    if(isset($_POST['send']))
    {
        //button pressed
        if(isset($_POST['user']) && !empty($_POST['user']) && isset($_POST['pass']) && !empty($_POST['pass'])){

            $user=$_POST['user'];
            $pass=$_POST['pass'];
       
            
            $result = $mysqli->query("SELECT * FROM users WHERE user='" . $user . "'");
            $sqlt = $result->fetch_object();
            
        }
            if($sqlt->admin && $sqlt->active){
             // verification user ADMIN
                if(md5($pass)!=$sqlt->pass ){
                    echo "te has equivocado de pass crack";
                }
                else
                {
                    session_start();
                    //Once user is checkd proceed to validate the session.
                    $_SESSION['user_id'] = $sqlt->user_id;
                    $_SESSION['admin'] = $sqlt->admin;
                    //once checkd we redir to admin.php
                    header("Location: admin.php");
                }
            }
            // verification normal user
            else if((md5($pass)==$sqlt->pass))
            { 
                if($sqlt->active)  {
                    session_start();
                    $_SESSION['user_id'] = $sqlt->user_id;
                    header("Location: user.php");
                }
                else if(!$sqlt->active) {
                    session_start();
                    $_SESSION['user_id'] = $sqlt->user_id;
                    header("Location: verify.php");
                }
                else
                {
                    echo "te has equivocado de pass crack";
                }
            }
            // create new user using verification code
            else if(md5($pass)!=$sqlt->pass && !$sqlt->admin && !$sqlt->active)
            {
                $hashNewUser = hash('crc32',time().rand(0,100000));
                if(envia($user,$hashNewUser)){
                $mysqli->query("INSERT INTO users ( pass, user, hash) VALUES(
                    '". (hash('md5',$pass)) ."', 
                    '". ($user) ."', 
                    '". ($hashNewUser) ."') ") or die(mysql_error());

                    //We launch query again to search the newly created user and give him a unique session on verify.php
                    $result = $mysqli->query("SELECT * FROM users WHERE user='" . $user . "'");
                    $sqlt = $result->fetch_object();
                    session_start();
                    $_SESSION['user_id'] = $sqlt->user_id;
                    header("Location: verify.php");
                   }
            }
        }
        else
        {
            
        }
    
        mysqli_close($mysqli);
?>

<html>
    <head>
    <link href="style/reset.css" rel="stylesheet" type="text/css" />
    <link href="style/style.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="index">
        <form name="f1"method="post" >
        User:    <input type="text" name="user" > 
        Pass:    <input type="password" name="pass" > 
            <input type="submit" name="send" value="submit" > 
        </form>
    </body>
</html>