<?php
require('libs/fpdf/fpdf.php');
    
    if (!$mysqli = mysqli_connect("127.0.0.1", "mario", "1234", "practicaPHP")) 
    {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    
    class PDF extends FPDF
    {
    // Page header
    function Header()
    {
        // Logo
        // $this->Image('img/logo.png',10,6,30);
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

    // Instanciation of inherited class
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times','',12);
    //fetching user
    $queryUSER = "SELECT user_id,user,covid FROM user WHERE admin=0";
    $ResUser = $mysqli->query($queryUSER);

    while($sqlARRusr = $ResUser->fetch_object()){
        $userID = $sqlARRusr['user_id'];
        //fetching sintomas
        $querySIN = "SELECT sintoma FROM usersin JOIN sincovid WHERE sinID=IDsin AND userid='" .$userID. "'";
    
        //fetching costums
        $queryCos = "SELECT costum FROM userscos JOIN coscovid WHERE cosID=IDcost AND userid='" .$userID. "'";

        $covid = $sqlARRusr['covid'];
        if ($i == $max){
            $pdf->AddPage();

            //print column titles for the current page
            $pdf->SetY($y_axis_initial);
            $pdf->SetX(25);
            $pdf->Cell(30,6,'User',1,0,'L',1);
            $pdf->Cell(100,6,'Costums',1,0,'L',1);
            if($covid==1){
                $pdf->Cell(30,6,'Sintomes',1,0,'R',1);
            }
            
            //Go to next row
            $y_axis = $y_axis + $row_height;
            
            //Set $i variable to 0 (first row)
            $i = 0;
        }

        $user = $sqlARRusr['user'];
        $ResCos = $mysqli->query($queryCos);
        while($cos = $ResCos->fetch_object()){
            $costum = $cos->costum;
        }  
        if($covid==1){
            $ResSin = $mysqli->query($querySIN);
            while($sin = $ResSin->fetch_object()){
                $sintoma = $sin->sintoma;
                $pdf->Cell(100,6,$sintoma,1,0,'L',1);
            }
        }
        $pdf->SetY($y_axis);
        $pdf->SetX(25);
        $pdf->Cell(30,6,$user,1,0,'L',1);
        $pdf->Cell(30,6,$cos,1,0,'R',1);

        //Go to next row
        $y_axis = $y_axis + $row_height;
        $i = $i + 1;
    }
    $pdf->Output();

?>