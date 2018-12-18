<?php
 
// menangkap isi variabel dari index.php
$nama = $_GET['nama'];
$perusahaan = $_GET['perusahaan'];
$profil = $_GET['profil'];
 
// memasukkan pustaka TCPDF utama
require_once('tcpdf_include.php');
 
// membuat dokumen PDF baru
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
 
// menambahkan informasi dokumen
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Aditya Rizki');
$pdf->SetTitle('Cetak Formulir dengan TCPDF');
$pdf->SetSubject('Cetak Formulir dengan TCPDF');
$pdf->SetKeywords('TCPDF, PDF, contoh, formulir, cetak');
 
// mengeset bahasa
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
  require_once(dirname(__FILE__).'/lang/eng.php');
  $pdf->setLanguageArray($l);
}
 
// ---------------------------------------------------------
 
// mengeset font default untuk moda subsetting
$pdf->setFontSubsetting(true);
 
// mengeset font
$pdf->SetFont('dejavusans', '', 14, '', true);
 
// menambahkan halaman baru, terdapat beberapa opsi, dapat dicek di dokumentasi
$pdf->AddPage();
 
// mengeset efek teks bayangan
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
// mencetak konten ke dalam PDF
$html = <<<EOD
<h1>$nama</h1>
<i>- $perusahaan</i><br><br>
<b>Profil</b>
<p>$profil</p>
EOD;
 
// mencetak teks menggunakan writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
 
// ---------------------------------------------------------
 
// Menutup dan mengeluarkan dokumen PDF
$pdf->Output('example_001.pdf', 'I');
 
//============================================================+
// END OF FILE
//============================================================+