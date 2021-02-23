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
$tipoServicio = $_POST['lServicio'];
$nDesde = $_POST['lDesde'];
$nHasta = $_POST['lHasta'];
$cobrador = $_POST['susCobrador'];
$Tipocliente = $_POST['lTipocliente'];
//var_dump($Tipocliente);
//$codigo = $_GET['id'];
if ($cobrador != 'todos') {
  $sql = "SELECT nombreCobrador FROM tbl_cobradores WHERE codigoCobrador='$cobrador'";
$resultado = $mysqli->query($sql);
while ($row = $resultado->fetch_assoc()){
  $nombreCobrador = $row['nombreCobrador'];
}
}else{
  $nombreCobrador = 'TODOS LOS COBRADORES';
}


$pdf = new FPDF();
 $pdf->AddPage('L','Letter');
  $pdf->SetFont('Arial','',6);
  $pdf->Cell(260,3,utf8_decode("Página ".str_pad($pdf->pageNo(),4,"0",STR_PAD_LEFT)),0,1,'R');
  $pdf->Ln(0);
  date_default_timezone_set('America/El_Salvador');
 $pdf->Cell(260,4,utf8_decode("GENERADO POR: ".strtoupper($_SESSION['nombres'])." ".strtoupper($_SESSION['apellidos']). " ".date('d/m/Y h:i:s')),"",1,'R');
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(260,3,utf8_decode("CABLE VISION POR SATELITE, S.A DE C.V "),0,1,'C');
  $pdf->Image('../../../images/logo.png',10,10, 20, 18);

  $pdf->Ln(1);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(260,6,utf8_decode("INFORME DE FACTURACION PENDIENTE"),0,1,'C');
  if ($tipoServicio == 'C') {
    $servicio = 'CABLE TV';
  }elseif ($tipoServicio == 'I'){
    $servicio = 'INTERNET';
  }elseif ($tipoServicio == 'T'){
    $servicio = 'CABLE E INTERNET';
  }
  $pdf->Cell(260,6,utf8_decode("SERVICIO: ".$servicio),0,1,'C');
  $pdf->Cell(260,6,utf8_decode("COBRADOR: ".$nombreCobrador),0,1,'C');
  $pdf->SetFont('Arial','',6);
  $pdf->Ln(1);

 putenv("LANG='es_ES.UTF-8'");
      setlocale(LC_ALL, 'es_ES.UTF-8');
      $pdf->SetFont('Arial','B',8);
      $pdf->Ln(1);
      $pdf->Cell(10,6,utf8_decode('N°'),1,0,'L');
      $pdf->Cell(55,6,utf8_decode('Cliente'),1,0,'L');
      $pdf->Cell(97,6,utf8_decode('Direccion'),1,0,'L');
      $pdf->Cell(15,6,utf8_decode('Servicio'),1,0,'C');
      $pdf->Cell(24,6,utf8_decode('Mes cargo'),1,0,'C');
      $pdf->Cell(28,6,utf8_decode('N° Factura'),1,0,'C');
      $pdf->Cell(15,6,utf8_decode('Saldo'),1,0,'C');
      $pdf->Cell(24,6,utf8_decode('Vencimiento'),1,0,'C');
      $pdf->Ln(8);
/*
estados
1=activo
2=suspendido
3=sin servicio

inter $query = "SELECT * FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null or servicio_suspendido='') and sin_servicio='T' AND estado_cliente_in = 1 AND cod_cliente<>'00000'";

cable $query = "SELECT * FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null or servicio_suspendido='') AND sin_servicio='F' AND estado_cliente_in = 3 AND cod_cliente<>'00000'";

ambos $query = "SELECT * FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null or servicio_suspendido='') and sin_servicio='F' AND estado_cliente_in = 1 AND cod_cliente<>'00000'";
*/
$contadorDeFilas=1;
$counter1=1;
///////////////////////////
// selector por cobrador //
//////////////////////////
if ($cobrador == 'todos') {
///////////////////////////
// selector por cobrador //
//////////////////////////
if ($tipoServicio == 'T') {
        $sql1 = "SELECT codigoCliente, COUNT(codigoCliente) FROM tbl_cargos WHERE fechaFactura BETWEEN '$nDesde' AND '$nHasta' AND estado='pendiente' and anulada='0' GROUP by codigoCliente HAVING COUNT(*)>3";

$resultado1 = $mysqli->query($sql1);
 while ($row1 = $resultado1->fetch_assoc()){
  //var_dump($row1['codigoCliente']);
  if ($row1['COUNT(codigoCliente)'] >= '3') {
    $codigoValido = $row1['codigoCliente'];
    }
   // var_dump($codigoValido);
 $sql = "SELECT codigoCliente, nombre, direccion, tipoServicio, mesCargo, numeroFactura, cuotaInternet, cuotaCable, fechaVencimiento FROM tbl_cargos WHERE fechaFactura BETWEEN '$nDesde' AND '$nHasta' AND estado='pendiente' and anulada='0' AND codigoCliente= '$codigoValido'";


 $resultado = $mysqli->query($sql);
/// orientacion de pagina P vertical L horizontal



      date_default_timezone_set('America/El_Salvador');

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
      
        $pdf->SetFont('Arial','',6);
        $pdf->Cell(13,6,utf8_decode($row['tipoServicio']),0,0,'C');
        $pdf->Cell(24,6,utf8_decode($row['mesCargo']),0,0,'C');
        

        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 28;
        $pdf->MultiCell(28,6,utf8_decode($row['numeroFactura']),0,'C');
        $pdf->SetXY($current_x + $cell_width, $current_y);
        if ($row['tipoServicio']=='I') {
        $pdf->Cell(15,6,utf8_decode('$'.$row['cuotaInternet']),0,0,'C');
        ////////////////// consulta de saldo en factura
        }elseif ($row['tipoServicio']=='C') {
        $pdf->Cell(15,6,utf8_decode('$'.$row['cuotaCable']),0,0,'C');
        }
        $pdf->Cell(24,6,utf8_decode($row['fechaVencimiento']),0,0,'C');

        
        $pdf->Ln(4);
        
$counter1++;
       }
       if ($counter1 > 16){
  $pdf->AddPage('L','Letter');
  $pdf->SetFont('Arial','',6);
  $pdf->Cell(260,3,utf8_decode("Página ".str_pad($pdf->pageNo(),4,"0",STR_PAD_LEFT)),0,1,'R');
  $pdf->Ln(0);
  date_default_timezone_set('America/El_Salvador');
  $pdf->Cell(260,4,utf8_decode("GENERADO POR: ".strtoupper($_SESSION['nombres'])." ".strtoupper($_SESSION['apellidos']). " ".date('d/m/Y h:i:s')),"",1,'R');
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(260,3,utf8_decode("CABLE VISION POR SATELITE, S.A DE C.V "),0,1,'C');
  $pdf->Image('../../../images/logo.png',10,10, 20, 18);

  $pdf->Ln(1);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(260,6,utf8_decode("INFORME DE FACTURACION PENDIENTE"),0,1,'C');
  if ($tipoServicio == 'C') {
    $servicio = 'CABLE TV';
  }elseif ($tipoServicio == 'I'){
    $servicio = 'INTERNET';
  }elseif ($tipoServicio == 'T'){
    $servicio = 'CABLE E INTERNET';
  }
  
  $pdf->Cell(260,6,utf8_decode("SERVICIO: ".$servicio),0,1,'C');
  $pdf->Cell(260,6,utf8_decode("COBRADOR: ".$nombreCobrador),0,1,'C');
  $pdf->SetFont('Arial','',6);
  $pdf->Ln(1);

 putenv("LANG='es_ES.UTF-8'");
      setlocale(LC_ALL, 'es_ES.UTF-8');
      $pdf->SetFont('Arial','B',8);
      $pdf->Ln(1);
      $pdf->Cell(10,6,utf8_decode('N°'),1,0,'L');
      $pdf->Cell(55,6,utf8_decode('Cliente'),1,0,'L');
      $pdf->Cell(97,6,utf8_decode('Direccion'),1,0,'L');
      $pdf->Cell(15,6,utf8_decode('Servicio'),1,0,'C');
      $pdf->Cell(24,6,utf8_decode('Mes cargo'),1,0,'C');
      $pdf->Cell(28,6,utf8_decode('N° Factura'),1,0,'C');
      $pdf->Cell(15,6,utf8_decode('Saldo'),1,0,'C');
      $pdf->Cell(24,6,utf8_decode('Vencimiento'),1,0,'C');
      $pdf->Ln(8);
        $counter1=1;
       }
        
       }
        //////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////
}else {
        $sql1 = "SELECT codigoCliente, tipoServicio, COUNT(codigoCliente) FROM tbl_cargos WHERE fechaFactura BETWEEN '$nDesde' AND '$nHasta' AND estado='pendiente' and anulada='0' AND tipoServicio='$tipoServicio' GROUP by codigoCliente HAVING COUNT(*)>1";

$resultado1 = $mysqli->query($sql1);
 while ($row1 = $resultado1->fetch_assoc()){
  //var_dump($row1['codigoCliente']);
  if ($row1['COUNT(codigoCliente)'] >= '2') {
    $codigoValido = $row1['codigoCliente'];
    }
   // var_dump($codigoValido);
 $sql = "SELECT codigoCliente, nombre, direccion, tipoServicio, mesCargo, numeroFactura, cuotaInternet, fechaVencimiento FROM tbl_cargos WHERE fechaFactura BETWEEN '$nDesde' AND '$nHasta' AND estado='pendiente' and anulada='0' AND tipoServicio='$tipoServicio' AND codigoCliente= '$codigoValido'";


 $resultado = $mysqli->query($sql);
/// orientacion de pagina P vertical L horizontal



      date_default_timezone_set('America/El_Salvador');

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
      
        $pdf->SetFont('Arial','',6);
        $pdf->Cell(13,6,utf8_decode($row['tipoServicio']),0,0,'C');
        $pdf->Cell(24,6,utf8_decode($row['mesCargo']),0,0,'C');
        

        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 28;
        $pdf->MultiCell(28,6,utf8_decode($row['numeroFactura']),0,'C');
        $pdf->SetXY($current_x + $cell_width, $current_y);
        $pdf->Cell(15,6,utf8_decode('$'.$row['cuotaInternet']),0,0,'C');
        $pdf->Cell(24,6,utf8_decode($row['fechaVencimiento']),0,0,'C');

        
        $pdf->Ln(4);
        
$counter1++;
       }
       if ($counter1 > 18){
  $pdf->AddPage('L','Letter');
  $pdf->SetFont('Arial','',6);
  $pdf->Cell(260,3,utf8_decode("Página ".str_pad($pdf->pageNo(),4,"0",STR_PAD_LEFT)),0,1,'R');
  $pdf->Ln(0);
  date_default_timezone_set('America/El_Salvador');
  $pdf->Cell(260,4,utf8_decode("GENERADO POR: ".strtoupper($_SESSION['nombres'])." ".strtoupper($_SESSION['apellidos']). " ".date('d/m/Y h:i:s')),"",1,'R');
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(260,3,utf8_decode("CABLE VISION POR SATELITE, S.A DE C.V "),0,1,'C');
  $pdf->Image('../../../images/logo.png',10,10, 20, 18);

  $pdf->Ln(1);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(260,6,utf8_decode("INFORME DE FACTURACION PENDIENTE"),0,1,'C');
  if ($tipoServicio == 'C') {
    $servicio = 'CABLE TV';
  }elseif ($tipoServicio == 'I'){
    $servicio = 'INTERNET';
  }elseif ($tipoServicio == 'T'){
    $servicio = 'CABLE E INTERNET';
  }
  
  $pdf->Cell(260,6,utf8_decode("SERVICIO: ".$servicio),0,1,'C');
  $pdf->Cell(260,6,utf8_decode("COBRADOR: ".$nombreCobrador),0,1,'C');
  $pdf->SetFont('Arial','',6);
  $pdf->Ln(1);

 putenv("LANG='es_ES.UTF-8'");
      setlocale(LC_ALL, 'es_ES.UTF-8');
      $pdf->SetFont('Arial','B',8);
      $pdf->Ln(1);
      $pdf->Cell(10,6,utf8_decode('N°'),1,0,'L');
      $pdf->Cell(55,6,utf8_decode('Cliente'),1,0,'L');
      $pdf->Cell(97,6,utf8_decode('Direccion'),1,0,'L');
      $pdf->Cell(15,6,utf8_decode('Servicio'),1,0,'C');
      $pdf->Cell(24,6,utf8_decode('Mes cargo'),1,0,'C');
      $pdf->Cell(28,6,utf8_decode('N° Factura'),1,0,'C');
      $pdf->Cell(15,6,utf8_decode('Saldo'),1,0,'C');
      $pdf->Cell(24,6,utf8_decode('Vencimiento'),1,0,'C');
      $pdf->Ln(8);
        $counter1=1;
       }
       
       }
        //////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////
}
///////////////////////////
// selector por cobrador //
//////////////////////////
}else{
///////////////////////////
// selector por cobrador //
//////////////////////////
if ($tipoServicio == 'T') {
        $sql1 = "SELECT codigoCliente, COUNT(codigoCliente) FROM tbl_cargos WHERE fechaFactura BETWEEN '$nDesde' AND '$nHasta' AND estado='pendiente' and anulada='0' and codigoCobrador='$cobrador' GROUP by codigoCliente HAVING COUNT(*)>3";

$resultado1 = $mysqli->query($sql1);
 while ($row1 = $resultado1->fetch_assoc()){
  //var_dump($row1['codigoCliente']);
  if ($row1['COUNT(codigoCliente)'] >= '3') {
    $codigoValido = $row1['codigoCliente'];
    }
   // var_dump($codigoValido);
 $sql = "SELECT codigoCliente, nombre, direccion, tipoServicio, mesCargo, numeroFactura, cuotaInternet, cuotaCable, fechaVencimiento FROM tbl_cargos WHERE fechaFactura BETWEEN '$nDesde' AND '$nHasta' AND estado='pendiente' and anulada='0'  and codigoCobrador='$cobrador'  AND codigoCliente= '$codigoValido'";


 $resultado = $mysqli->query($sql);
/// orientacion de pagina P vertical L horizontal



      date_default_timezone_set('America/El_Salvador');

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
      
        $pdf->SetFont('Arial','',6);
        $pdf->Cell(13,6,utf8_decode($row['tipoServicio']),0,0,'C');
        $pdf->Cell(24,6,utf8_decode($row['mesCargo']),0,0,'C');
        

        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 28;
        $pdf->MultiCell(28,6,utf8_decode($row['numeroFactura']),0,'C');
        $pdf->SetXY($current_x + $cell_width, $current_y);
        if ($row['tipoServicio']=='I') {
        $pdf->Cell(15,6,utf8_decode('$'.$row['cuotaInternet']),0,0,'C');
        ////////////////// consulta de saldo en factura
        }elseif ($row['tipoServicio']=='C') {
        $pdf->Cell(15,6,utf8_decode('$'.$row['cuotaCable']),0,0,'C');
        }
        $pdf->Cell(24,6,utf8_decode($row['fechaVencimiento']),0,0,'C');

        
        $pdf->Ln(4);
        
$counter1++;
       }
       if ($counter1 > 16){
  $pdf->AddPage('L','Letter');
  $pdf->SetFont('Arial','',6);
  $pdf->Cell(260,3,utf8_decode("Página ".str_pad($pdf->pageNo(),4,"0",STR_PAD_LEFT)),0,1,'R');
  $pdf->Ln(0);
  date_default_timezone_set('America/El_Salvador');
  $pdf->Cell(260,4,utf8_decode("GENERADO POR: ".strtoupper($_SESSION['nombres'])." ".strtoupper($_SESSION['apellidos']). " ".date('d/m/Y h:i:s')),"",1,'R');
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(260,3,utf8_decode("CABLE VISION POR SATELITE, S.A DE C.V "),0,1,'C');
  $pdf->Image('../../../images/logo.png',10,10, 20, 18);

  $pdf->Ln(1);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(260,6,utf8_decode("INFORME DE FACTURACION PENDIENTE"),0,1,'C');
  if ($tipoServicio == 'C') {
    $servicio = 'CABLE TV';
  }elseif ($tipoServicio == 'I'){
    $servicio = 'INTERNET';
  }elseif ($tipoServicio == 'T'){
    $servicio = 'CABLE E INTERNET';
  }
  
  $pdf->Cell(260,6,utf8_decode("SERVICIO: ".$servicio),0,1,'C');
  $pdf->Cell(260,6,utf8_decode("COBRADOR: ".$nombreCobrador),0,1,'C');
  $pdf->SetFont('Arial','',6);
  $pdf->Ln(1);

 putenv("LANG='es_ES.UTF-8'");
      setlocale(LC_ALL, 'es_ES.UTF-8');
      $pdf->SetFont('Arial','B',8);
      $pdf->Ln(1);
      $pdf->Cell(10,6,utf8_decode('N°'),1,0,'L');
      $pdf->Cell(55,6,utf8_decode('Cliente'),1,0,'L');
      $pdf->Cell(97,6,utf8_decode('Direccion'),1,0,'L');
      $pdf->Cell(15,6,utf8_decode('Servicio'),1,0,'C');
      $pdf->Cell(24,6,utf8_decode('Mes cargo'),1,0,'C');
      $pdf->Cell(28,6,utf8_decode('N° Factura'),1,0,'C');
      $pdf->Cell(15,6,utf8_decode('Saldo'),1,0,'C');
      $pdf->Cell(24,6,utf8_decode('Vencimiento'),1,0,'C');
      $pdf->Ln(8);
        $counter1=1;
       }
        
       }
        //////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////
}else {
        $sql1 = "SELECT codigoCliente, tipoServicio, COUNT(codigoCliente) FROM tbl_cargos WHERE fechaFactura BETWEEN '$nDesde' AND '$nHasta' AND estado='pendiente' and anulada='0' AND tipoServicio='$tipoServicio' and codigoCobrador='$cobrador'  GROUP by codigoCliente HAVING COUNT(*)>1";

$resultado1 = $mysqli->query($sql1);
 while ($row1 = $resultado1->fetch_assoc()){
  //var_dump($row1['codigoCliente']);
  if ($row1['COUNT(codigoCliente)'] >= '2') {
    $codigoValido = $row1['codigoCliente'];
    }
   // var_dump($codigoValido);
 $sql = "SELECT codigoCliente, nombre, direccion, tipoServicio, mesCargo, numeroFactura, cuotaInternet, fechaVencimiento FROM tbl_cargos WHERE fechaFactura BETWEEN '$nDesde' AND '$nHasta' AND estado='pendiente' and anulada='0' AND tipoServicio='$tipoServicio' and codigoCobrador='$cobrador'  AND codigoCliente= '$codigoValido'";


 $resultado = $mysqli->query($sql);
/// orientacion de pagina P vertical L horizontal



      date_default_timezone_set('America/El_Salvador');

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
      
        $pdf->SetFont('Arial','',6);
        $pdf->Cell(13,6,utf8_decode($row['tipoServicio']),0,0,'C');
        $pdf->Cell(24,6,utf8_decode($row['mesCargo']),0,0,'C');
        

        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 28;
        $pdf->MultiCell(28,6,utf8_decode($row['numeroFactura']),0,'C');
        $pdf->SetXY($current_x + $cell_width, $current_y);
        $pdf->Cell(15,6,utf8_decode('$'.$row['cuotaInternet']),0,0,'C');
        $pdf->Cell(24,6,utf8_decode($row['fechaVencimiento']),0,0,'C');

        
        $pdf->Ln(4);
        
$counter1++;
       }
       if ($counter1 > 18){
  $pdf->AddPage('L','Letter');
  $pdf->SetFont('Arial','',6);
  $pdf->Cell(260,3,utf8_decode("Página ".str_pad($pdf->pageNo(),4,"0",STR_PAD_LEFT)),0,1,'R');
  $pdf->Ln(0);
  date_default_timezone_set('America/El_Salvador');
  $pdf->Cell(260,4,utf8_decode("GENERADO POR: ".strtoupper($_SESSION['nombres'])." ".strtoupper($_SESSION['apellidos']). " ".date('d/m/Y h:i:s')),"",1,'R');
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(260,3,utf8_decode("CABLE VISION POR SATELITE, S.A DE C.V "),0,1,'C');
  $pdf->Image('../../../images/logo.png',10,10, 20, 18);

  $pdf->Ln(1);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(260,6,utf8_decode("INFORME DE FACTURACION PENDIENTE"),0,1,'C');
  if ($tipoServicio == 'C') {
    $servicio = 'CABLE TV';
  }elseif ($tipoServicio == 'I'){
    $servicio = 'INTERNET';
  }elseif ($tipoServicio == 'T'){
    $servicio = 'CABLE E INTERNET';
  }
  
  $pdf->Cell(260,6,utf8_decode("SERVICIO: ".$servicio),0,1,'C');
  $pdf->Cell(260,6,utf8_decode("COBRADOR: ".$nombreCobrador),0,1,'C');
  $pdf->SetFont('Arial','',6);
  $pdf->Ln(1);

 putenv("LANG='es_ES.UTF-8'");
      setlocale(LC_ALL, 'es_ES.UTF-8');
      $pdf->SetFont('Arial','B',8);
      $pdf->Ln(1);
      $pdf->Cell(10,6,utf8_decode('N°'),1,0,'L');
      $pdf->Cell(55,6,utf8_decode('Cliente'),1,0,'L');
      $pdf->Cell(97,6,utf8_decode('Direccion'),1,0,'L');
      $pdf->Cell(15,6,utf8_decode('Servicio'),1,0,'C');
      $pdf->Cell(24,6,utf8_decode('Mes cargo'),1,0,'C');
      $pdf->Cell(28,6,utf8_decode('N° Factura'),1,0,'C');
      $pdf->Cell(15,6,utf8_decode('Saldo'),1,0,'C');
      $pdf->Cell(24,6,utf8_decode('Vencimiento'),1,0,'C');
      $pdf->Ln(8);
        $counter1=1;
       }
       
       }
        //////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////
}
///////////////////////////
//////////////////////////

}

        $pdf->Ln(7);
        $pdf->SetFont('Arial','',10);
 
        
             mysqli_close($mysqli);
             $pdf->Output();
  ?>