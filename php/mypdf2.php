<?php
	require("../init.php");
    require_once("../tcpdf.php");

    $pdf = & new TCPDF("P", "mm", "A4", true, "UTF-8", false);

    $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
    $pdf->SetAutoPageBreak(false);

    $con = mysql_connect('localhost','root','');

    if (!$con)
    {   
        die('Could not connect: ' . mysql_error()); 
    }

    mysql_select_db('emp', $con);
    mysql_query("SET NAMES utf8");

    $result = mysql_query('SELECT ID,FirstName,LastName,DOB FROM employee ORDER BY LastName ASC', $con);

    $pdf->SetMargins(15, 20, 15);

    $pdf->AddPage();    
    $pdf->SetFont('FreeSerif', 'B', 16);    
    $pdf->SetFillColor(171, 255, 205);

    $pdf->Cell(180, $row_height, 'Employees', 0, 1, 'C', 1);
    $pdf->Ln(5);
    $pdf->SetFont('FreeSerif', 'B', 14);
    $pdf->SetFillColor(1, 254, 83);
    $pdf->SetDrawColor(255,255,255);
    $pdf->Cell(9, $row_height, 'ID', 1, 0, 'L', 1);
    $pdf->Cell(73, $row_height, 'First Name', 1, 0, 'L', 1);
    $pdf->Cell(73, $row_height, 'Last Name', 1, 0, 'L', 1);
    $pdf->Cell(25, $row_height, 'DOB', 1, 1, 'L', 1);

    $i = 0;
    $id = 0;
    $max = 46;
    $row_height = 5;

    while($row = mysql_fetch_array($result))
    {
        $id++;
        $first = $row['FirstName'];
        $last = $row['LastName'];
        $dob = $row['DOB'];

        if ($i > $max)
        {
            $pdf->AddPage();
            $pdf->SetFont('FreeSerif', 'B', 14);
            $pdf->SetFillColor(1, 254, 83);

            $pdf->Cell(9, $row_height, 'ID', 1, 0, 'L', 1);
            $pdf->Cell(73, $row_height, 'First Name', 1, 0, 'L', 1);
            $pdf->Cell(73, $row_height, 'Last Name', 1, 0, 'L', 1);
            $pdf->Cell(25, $row_height, 'DOB', 1, 1, 'L', 1);

            $i = 0;
        }

        if ($id%2 == 0)
        {
            $pdf->SetFillColor(203, 255, 206);
        }
        else
        {
            $pdf->SetFillColor(238, 255, 237);
        }

        $pdf->SetFont('FreeSerif', '', 10);
        $first_line = $pdf->getNumLines($first, 73); 
        $last_line = $pdf->getNumLines($last, 73);      
        $linecount = max($first_line, $last_line);

        $pdf->SetTextColor(30,30,100);
        $pdf->MultiCell(9, $row_height*$linecount, $id, 1, 'L', 1, 0);
        $pdf->SetTextColor(0,0,0);
        $pdf->MultiCell(73, $row_height*$linecount, $first, 1, 'L', 1, 0);          
        $pdf->MultiCell(73, $row_height*$linecount, $last, 1, 'L', 1, 0);           
        $pdf->MultiCell(25, $row_height*$linecount, $dob, 1, 'L', 1, 1);

        $i=$i+$linecount;
    }

    mysql_close($con);

    ob_end_clean();

    $pdf->Output('mypdf.pdf', 'I');
?>