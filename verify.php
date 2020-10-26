
<?php

session_start();

if ( isset( $_SESSION['user_id'] ) ) {
    // Grab user data from the database using the user_id
    // Let them access the "logged in only" pages

} else {
    // Redirect them to the login page
    header("Location: index.php");
}
 
// connection to database 
if (!$mysqli = mysqli_connect("127.0.0.1", "mario", "1234", "practicaPHP")) 
{
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
if(isset($_POST['send']) && isset($_POST['hash']) && !empty($_POST['hash']))
{
    $result = $mysqli->query("SELECT * FROM users WHERE user_id='" . $_SESSION['user_id'] . "'");
    $sqlt = $result->fetch_object();

    if($_POST['hash']==$sqlt->hash){
        $mysqli->query("UPDATE users SET active = 1 WHERE user_id = '" . $_SESSION['user_id'] . "'");
        header("Location: user.php");
    }
}
mysqli_close($mysqli);
?>
<html>
<head>
<link href="style/reset.css" rel="stylesheet" type="text/css" />
    <link href="style/style.css" rel="stylesheet" type="text/css" />
</head>
<body class="verify">
    <h1>Introdueix el codi de seguretat</h1>
    <form name="f1"method="post" action="#" >
        <input type="text" name="hash"  > 
        <input type="submit" name="send" value="submit" > 
    </form>
</body>
</html>
