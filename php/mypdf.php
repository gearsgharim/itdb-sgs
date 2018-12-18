<?php
	require("../init.php");
    require_once("tcpdf/tcpdf.php");

    $pdf = & new TCPDF("P", "mm", "A4", true, "UTF-8", false);

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetAutoPageBreak(false);

    $con = mysql_connect('localhost','root','');

    if (!$con)
    {   
        die('Could not connect: ' . mysql_error()); 
    }

    $sql="SELECT items.id,model,sn,sn3,itemtypeid,dnsname,ipv4,ipv6,label, agents.title as agtitle FROM items,agents ".
     " WHERE agents.id=items.manufacturerid AND items.id in ($ids) order by items.id";
	$sth=db_execute($dbh,$sql);
	$idx=0;

    $pdf->SetMargins(15, 20, 15);

    $pdf->AddPage();    
    $pdf->SetFont('FreeSerif', 'B', 16);    
    $pdf->SetFillColor(171, 255, 205);

    $pdf->Cell(180, $row_height, 'Employees', 0, 1, 'C', 1);
    $pdf->Ln(4);
    $pdf->SetFont('FreeSerif', 'B', 14);
    $pdf->SetFillColor(1, 254, 83);
    $pdf->Cell(9, $row_height, 'ID', 0, 0, 'L', 1);
    $pdf->Cell(73, $row_height, 'First Name', 0, 0, 'L', 1);
    $pdf->Cell(73, $row_height, 'Last Name', 0, 0, 'L', 1);
    $pdf->Cell(25, $row_height, 'DOB', 0, 1, 'L', 1);

    $i = 0;
    $id = 0;
    $max = 30;
    $row_height = 6;

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

            $pdf->Cell(9, $row_height, 'ID', 0, 0, 'L', 1);
            $pdf->Cell(73, $row_height, 'First Name', 0, 0, 'L', 1);
            $pdf->Cell(73, $row_height, 'Last Name', 0, 0, 'L', 1);
            $pdf->Cell(25, $row_height, 'DOB', 0, 1, 'L', 1);

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

        $first_width = $pdf->GetStringWidth($first);
        $last_width = $pdf->GetStringWidth($last);

        if ($first_width > 71 || $last_width > 71)
        {
            $pdf->SetFont('FreeSerif', '', 12);
            $pdf->MultiCell(9, $row_height*2, $id, 0, 'L', 1, 0);
            $pdf->MultiCell(73, $row_height*2, wordwrap($first, 30, "\n"), 0, 'L', 1, 0);
            $pdf->MultiCell(73, $row_height*2, wordwrap($last, 30, "\n"), 0, 'L', 1, 0);
            $pdf->MultiCell(25, $row_height*2, $dob, 0, 'L', 1, 1);

            $i=$i+2;
        }
        else
        {
            $pdf->SetFont('FreeSerif', '', 12);
            $pdf->Cell(9, $row_height, $id, 0, 0, 'L', 1);
            $pdf->Cell(73, $row_height, $first, 0, 0, 'L', 1);
            $pdf->Cell(73, $row_height, $last, 0, 0, 'L', 1);
            $pdf->Cell(25, $row_height, $dob, 0, 1, 'L', 1);

            $i++;
        }
    }

    mysql_close($con);

    ob_end_clean();

    $pdf->Output('mypdf.pdf', 'I');
?>