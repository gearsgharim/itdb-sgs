<?PHP
    require_once('tcpdf.php');
     
    $pdf = new tcpdf();
     
    $orientation = 'L';
    $format = 'A4';
    $keepmargins = false;
    $tocpage = false;
     
    $pdf->AddPage($orientation, $format, $keepmargins, $tocpage);
     
    $family = 'dejavusans';
    $style = '';
    $size = '12';
     
    $pdf->SetFont($family, $style, $size);
     
    $html = '
    <img src="sgs.png" width="100" /><br />
    <table border="1">
        <tr bgcolor="#009900">
            <td align="center">Nim</td>
            <td align="center">Nama</td>
        </tr>
        <tr>
            <td>J3C205003</td>
            <td>Aditya Wicaksono</td>
        </tr>
        <tr>
            <td>J3C105018</td>
            <td>Bryan Nurjayanti</td>
        </tr>
    </table>';
     
    $ln = true;
    $fill = false;
    $reseth = false;
    $cell = false;
    $align = '';
     
    $pdf->writeHTML($html, $ln, $fill, $reseth, $cell, $align);
     
    $pdf->Output();
     
    $name = 'pdf.pdf';
    $dest = 'F';
     
    $pdf->Output($name, $dest);
?>