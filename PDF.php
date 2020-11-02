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

    // Instanciation of inherited class
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times','',12);
    while($row = mysql_fetch_array($result)){
        //If the current row is the last one, create new page and print column title
        if ($i == $max){
            $pdf->AddPage();

            //print column titles for the current page
            $pdf->SetY($y_axis_initial);
            $pdf->SetX(25);
            $pdf->Cell(30,6,'CODE',1,0,'L',1);
            $pdf->Cell(100,6,'NAME',1,0,'L',1);
            $pdf->Cell(30,6,'PRICE',1,0,'R',1);
            
            //Go to next row
            $y_axis = $y_axis + $row_height;
            
            //Set $i variable to 0 (first row)
            $i = 0;
        }

        $user = $row['Code'];
        $sin = $row['Price'];
        $cos = $row['Code'];

        $pdf->SetY($y_axis);
        $pdf->SetX(25);
        $pdf->Cell(30,6,$code,1,0,'L',1);
        $pdf->Cell(100,6,$name,1,0,'L',1);
        $pdf->Cell(30,6,$price,1,0,'R',1);

        //Go to next row
        $y_axis = $y_axis + $row_height;
        $i = $i + 1;
    }
    $pdf->Output();
?>