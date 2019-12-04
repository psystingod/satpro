<?php
require '../../../pdfs/fpdf.php';
require_once("../../../php/config.php");

$host = DB_HOST;
$user = DB_USER;
$password = DB_PASSWORD;
$database = DB_NAME;
$mysqli = new mysqli($host, $user, $password, $database);

session_start();
$cobrador = $_POST['cobradorImp'];
$diaCobro = $_POST['diaImp'];
$fechaGenerada = $_POST['fechaImp'];
$tipoServicio = $_POST['tipoServicioImp'];
//$codigo = $_GET['id'];

if (isset($_POST['desdeImp']) && isset($_POST['hastaImp'])) {
    $nDesde = $_POST['desdeImp'];
    $nHasta = $_POST['hastaImp'];
    if ($cobrador == "todos") {
        $query = "SELECT * FROM tbl_cargos WHERE SUBSTRING(fechaCobro, 9, 10) = '".$diaCobro."' AND fechaFactura = '".$fechaGenerada."' AND tipoServicio = '".$tipoServicio."'";
    }else {
        $query = "SELECT * FROM tbl_cargos WHERE codigoCobrador = ".$cobrador." AND diaCobro = ".$diaCobro." AND fechaFactura = ".$fechaGenerada." AND tipoServicio = ".$tipoServicio;
    }
}else {
    $query = "SELECT * FROM tbl_cargos WHERE codigoCobrador = ".$codigo;
}
  $resultado = $mysqli->query($query);
  //var_dump($query);
//*****************************************************************F3
  $pdf = new FPDF();
  date_default_timezone_set('America/El_Salvador');

  while($row = $resultado->fetch_assoc())
  {
    $pdf->AliasNbPages();
    $pdf->AddPage('P','Letter');
    setlocale(LC_ALL,"es_ES");
    $pdf->SetFont('Arial','',11);

    $pdf->Cell(190,6,utf8_decode('Número de factura: '.strtoupper($row['numeroFactura'])),0,1,'L');

  }

  /* close connection */
  mysqli_close($mysqli);
  $pdf->Output();
?>
