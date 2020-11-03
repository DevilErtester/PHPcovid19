<?php
require('libs/fpdf/fpdf.php');
session_start();
if ( isset( $_SESSION['admin']) ) {
    
    // Grab user data from the database using the user_id
    // Let them access the "logged in only" pages

} else {
    
    header("Location: index.php");
    exit;
    // Redirect them to the login page
}

    
    class PDF extends FPDF
    {
    // Page header
    function Header()
    {
        // Logo
         $this->Image('img/logo.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30,10,'Covid19',1,0,'C');
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}   
if (!$mysqli = mysqli_connect("127.0.0.1", "mario", "1234", "practicaPHP")) 
{
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
    
   
    // Instanciation of inherited class
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->SetFont('Times','',12);
    
    //set initial y axis position per page
    $y_axis_initial = 25;
    //print column titles
    $pdf->SetFillColor(232,232,232);
    $pdf->SetFont('Arial','B',12);
    $pdf->SetY($y_axis_initial);
    $pdf->SetX(25);
    $y_axis = 0;
    //Set Row Height
    $row_height = 6;
    //initialize counter
    $i = 0;
    //Set maximum rows per page
    $max = 25;
    $queryUSER = "SELECT user_id,user,covid FROM users WHERE admin=0";
    $ResUser = $mysqli->query($queryUSER);
    while( $sqlARRusr = $ResUser->fetch_array()){
        $pdf->AddPage();
        $covid = $sqlARRusr['covid'];   
        $userID = $sqlARRusr['user_id'];
        //fetching sintomas
        $querySIN = "SELECT sintoma FROM userSin JOIN sinCovid WHERE sinId=idSin AND userid='".$userID."'";

        //fetching costums
        $queryCos = "SELECT costum FROM usersCos JOIN cosCovid WHERE cosId=idCost AND userid='".$userID."'";
        
        $ResCos = $mysqli->query($queryCos);
        $pdf->Cell(1,20,'User '.$sqlARRusr['user'],0,1);
        while($sqlARRcos = $ResCos->fetch_array()){
            $pdf->Cell(0,10,'Costum:  '.$sqlARRcos['costum'],0,1);
        }
        $ResSin = $mysqli->query($querySIN);
        while($sqlARRsin = $ResSin->fetch_array()){
            $pdf->Cell(0,10,'Sintoma:  '.$sqlARRsin['sintoma'],0,1);
        }
        
        
    }
    $pdf->Output();
?>