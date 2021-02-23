<?php
require '../../../pdfs/fpdf.php';
require_once("../../../php/config.php");
require '../../../numLe/src/NumerosEnLetras.php';
require_once('../../../php/connection.php');
require_once('../../modulo_administrar/php/getInfo2.php');
if(!isset($_SESSION))
{
  if(!isset($_SESSION))
  {
    session_start([
        'cookie_lifetime' => 86400,
    ]);
  }
}
$host = DB_HOST;
$user = DB_USER;
$password = DB_PASSWORD;
$database = $_SESSION['db'];
$mysqli = new mysqli($host, $user, $password, $database);
/*
$cobrador = $_POST['cobradorImp'];
$diaCobro = $_POST['diaImp'];
$fechaGenerada = $_POST['fechaImp'];
$tipoServicio = $_POST['tipoServicioImp'];
$tipoComprobante = $_POST['tipoComprobanteImp'];
*/
$cobrador = 'todos';
$diaCobro = '26';
$fechaGenerada = '2020-09-26';
$tipoServicio = 'C';
$tipoComprobante = '2';
//$codigo = $_GET['id'];
 //codigoCliente, nombre, direccion, fechaCobro, tipoServicio, numeroFactura, saldoCable, saldoInter, cuotaCable, cuotaInter 
$sql = "SELECT * FROM tbl_cargos WHERE SUBSTRING(fechaCobro, 9, 10) = '$diaCobro'  AND fechaFactura = '$fechaGenerada' AND tipoFactura ='$tipoComprobante' AND tipoServicio = '$tipoServicio'";

 $resultado = $mysqli->query($sql);
$contadorDeFilas=1;
$pdf = new FPDF();
 $pdf->AddPage('L','Letter');
 $pdf->SetFont('Arial','',6);
  $pdf->Cell(200,3,utf8_decode("Página ".str_pad($pdf->pageNo(),4,"0",STR_PAD_LEFT)),0,1,'R');
  $pdf->Ln(0);
  date_default_timezone_set('America/El_Salvador');
 $pdf->Cell(200,4,utf8_decode("GENERADO POR: ".strtoupper($_SESSION['nombres'])." ".strtoupper($_SESSION['apellidos']). " ".date('d/m/Y h:i:s')),"",1,'R');
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(200,3,utf8_decode("CABLE VISION POR SATELITE, S.A DE C.V "),0,1,'C');
  $pdf->Image('../../../images/logo.png',10,10, 20, 18);

  $pdf->Ln(1);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(200,6,utf8_decode("INFORME DE FACTURACION GENERADA"),0,1,'C');
  $pdf->SetFont('Arial','',6);
  $pdf->Ln(2);

 putenv("LANG='es_ES.UTF-8'");
      setlocale(LC_ALL, 'es_ES.UTF-8');
      $pdf->SetFont('Arial','B',8);
      $pdf->Ln(3);
      $pdf->Cell(10,6,utf8_decode('N°'),1,0,'L');
      $pdf->Cell(55,6,utf8_decode('Cliente'),1,0,'L');
      $pdf->Cell(97,6,utf8_decode('Direccion'),1,0,'L');

      $pdf->Cell(24,6,utf8_decode('Fecha cobro'),1,0,'C');

      $pdf->Cell(15,6,utf8_decode('Servicio'),1,0,'C');
      $pdf->Cell(28,6,utf8_decode('N° Factura'),1,0,'C');
      $pdf->Cell(15,6,utf8_decode('Valor'),1,0,'C');
      $pdf->Ln(8);
      while ($row = $resultado->fetch_assoc()){

        $pdf->Ln(4);
        $pdf->SetFont('Arial','',6);
        $pdf->Cell(10,6,utf8_decode($contadorDeFilas),0,0,'L');
        $contadorDeFilas++;
        $pdf->SetFont('Arial','',5);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 55;
        $pdf->MultiCell(55,6,utf8_decode(strtoupper(str_pad($row['codigoCliente'], 5, "0", STR_PAD_LEFT)."  ".$row['nombre'])),0,'L');
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 97;
        $pdf->SetFont('Arial','',5);
        $pdf->MultiCell(97,2,utf8_decode($row['direccion']),0,'J');
        $pdf->SetXY($current_x + $cell_width, $current_y);
       
/*
        $pdf->Cell(26,3,utf8_decode($colonia->getColonia($row['id_colonia'])),0,0,'L');
*/
        $pdf->SetFont('Arial','',6);
        $pdf->Cell(24,6,utf8_decode($row['fechaCobro']),0,0,'C');
        $pdf->Cell(13,6,utf8_decode($row['tipoServicio']),0,0,'C');

        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 28;
        $pdf->MultiCell(28,6,utf8_decode($row['numeroFactura']),0,'C');
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->Cell(15,6,utf8_decode('$'.$row['saldoCable']),0,0,'C');

        
        $pdf->Ln(4);
        

       }
        $pdf->Ln(7);
        $pdf->SetFont('Arial','',10);
 
        
             mysqli_close($mysqli);


$pdf -> Output();
?>