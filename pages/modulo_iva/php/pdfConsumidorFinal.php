<?php
  require '../../../pdfs/fpdf.php';
  require_once("../../../php/config.php");
  require_once("../getDatos.php");
  require '../../../numLe/src/NumerosEnLetras.php';
  if(!isset($_SESSION))
  {
  	session_start();
  }
  $host = DB_HOST;
  $user = DB_USER;
  $password = DB_PASSWORD;
  $database = $_SESSION['db'];
  $mysqli = new mysqli($host, $user, $password, $database);

  $mesGenerar = $_POST['mesGenerar']; //NECESARIO PARA LA CONSULTA
  $anoGenerar = $_POST['anoGenerar']; //NECESARIO PARA LA CONSULTA
  $tipoFacturaGenerar = $_POST['facturas']; //1Normal, 2Pequeñas, 3Anuladas, 4Todas
  $encabezados = null;
  $numPag = null;
  $libroDetallado = null;

  // SQL query para traer datos del servicio de cable de la tabla impuestos
  $query = "SELECT valorImpuesto FROM tbl_impuestos WHERE siglasImpuesto = 'IVA'";
  // Preparación de sentencia
  $statement = $mysqli->query($query);
  //$statement->execute();
  while ($result = $statement->fetch_assoc()) {
      $iva = floatval($result['valorImpuesto']);
  }

  // SQL query para traer datos del servicio de cable de la tabla impuestos
  $query = "SELECT valorImpuesto FROM tbl_impuestos WHERE siglasImpuesto = 'CESC'";
  // Preparación de sentencia
  $statement = $mysqli->query($query);
  //$statement->execute();
  while ($result = $statement->fetch_assoc()) {
      $cesc = floatval($result['valorImpuesto']);
  }

  $ex = new GetDatos();

  if (isset($_POST["encabezados"])) {
      $encabezados = 1;
  }
  if (isset($_POST["numPag"])) {
      $numPag = 1;
  }
  if (isset($_POST["libroDetallado"])) {
      $libroDetallado = 1;
  }

  //$filtroServicio = $_POST['filtroServicio'];

  $totalSinIva=0;
  $totalConIva=0;
  $totalSinIvaEx=0;
  $totalConIvaEx=0;
  $totalSoloIva=0;
  $totalSoloCesc=0;

  $totalSinIva1=0;
  $totalConIva1=0;
  $totalSinIvaEx1=0;
  $totalConIvaEx1=0;
  $totalSoloIva1=0;
  $totalSoloCesc1=0;

  $totalSinIva2=0;
  $totalConIva2=0;
  $totalSinIvaEx2=0;
  $totalConIvaEx2=0;
  $totalSoloIva2=0;
  $totalSoloCesc2=0;

  $totalSinIva3=0;
  $totalConIva3=0;
  $totalSinIvaEx3=0;
  $totalConIvaEx3=0;
  $totalSoloIva3=0;
  $totalSoloCesc3=0;

  function libroConsumidorFinal(){
	  global $mysqli, $mesGenerar, $anoGenerar, $tipoFacturaGenerar, $encabezados, $numPag, $libroDetallado, $ex, $iva, $cesc, $filtroServicio;
      global $totalSinIva,$totalConIva,$totalSinIvaEx,$totalConIvaEx,$totalSoloIva,$totalSoloCesc;
      global $totalSinIva1,$totalConIva1,$totalSinIvaEx1,$totalConIvaEx1,$totalSoloIva1,$totalSoloCesc1;
      global $totalSinIva2,$totalConIva2,$totalSinIvaEx2,$totalConIvaEx2,$totalSoloIva2,$totalSoloCesc2;
      global $totalSinIva3,$totalConIva3,$totalSinIvaEx3,$totalConIvaEx3,$totalSoloIva3,$totalSoloCesc3;
	  $pdf = new FPDF();
	  $pdf->AliasNbPages();

      /*********************************COMIENZO DE LA MASACRE***********************************/
        if ($libroDetallado == 1) {
            $pdf->AddPage('P','Letter');

            $pdf->SetFont('Times','B',10);
            $pdf->Cell(190,6,utf8_decode('LIBRO O REGISTRO DE OPERACIONES CON EMISION DE FACTURAS'),0,0,'C');
            $pdf->Ln(5);
            $pdf->SetFont('Times','B',8);
            $pdf->Cell(190,6,utf8_decode('VALORES EXPRESADOS EN US DOLARES'),0,0,'C');
            $pdf->Ln(10);

            $pdf->SetFont('Times','B',10);
            //FILA 1
            $pdf->Cell(25,6,utf8_decode(''),"TLR",0,'C');
            $pdf->SetFont('Times','B',5);
            $pdf->Cell(15,6,utf8_decode('N° DE'),"T",0,'C');
            $pdf->SetFont('Times','B',10);
            $pdf->Cell(60,6,utf8_decode(''),"TLR",0,'C');
            $pdf->Cell(60,6,utf8_decode('VENTAS'),1,0,'C');
            $pdf->Cell(40,6,utf8_decode(''),"TLR",1,'C');

            //FILA 2
            $pdf->Cell(25,6,utf8_decode(''),"LR",0,'C');
            $pdf->SetFont('Times','B',5);
            $pdf->Cell(15,6,utf8_decode('FORMULARIO'),"LR",0,'C');
            $pdf->SetFont('Times','B',10);
            $pdf->Cell(60,6,utf8_decode(''),"LR",0,'C');
            $pdf->Cell(20,6,utf8_decode(''),"LRT",0,'C');
            $pdf->Cell(40,6,utf8_decode('GRAVADAS'),1,0,'C');
            $pdf->Cell(40,6,utf8_decode(''),"BLR",1,'C');

            //FILA 3
            $pdf->SetFont('Times','B',5);
            $pdf->Cell(5,6,utf8_decode('DIA'),1,0,'C');
            $pdf->Cell(20,6,utf8_decode('N° DE FACTURA'),1,0,'C');
            $pdf->Cell(15,6,utf8_decode('UNICO'),"BLR",0,'C');
            $pdf->SetFont('Times','B',7);
            $pdf->Cell(60,6,utf8_decode('NOMBRE DEL CLIENTE'),1,0,'C');
            $pdf->Cell(20,6,utf8_decode('EXENTAS'),"BLR",0,'C');
            $pdf->Cell(20,6,utf8_decode('LOCALES'),1,0,'C');
            $pdf->Cell(20,6,utf8_decode('SERVICIO'/*'EXPORTACION'*/),1,0,'C');
            $pdf->SetFont('Times','B',5);
            $pdf->Cell(15,6,utf8_decode('SUB-TOTAL'),1,0,'C');
            $pdf->Cell(10,6,utf8_decode('CESC'),1,0,'C');
            $pdf->SetFont('Times','B',4);
            $pdf->Cell(15,6,utf8_decode('VENTAS TOTALES'),1,1,'C');
            $pdf->SetFont('Times','B',5);
      	  /*$pdf->Image('../../../images/logo.png',10,10, 26, 24);
      	  $pdf->Ln(35);
      	  $pdf->SetFont('Arial','B',13);
      	  $pdf->Cell(190,6,utf8_decode('F-3'),0,1,'R');
      	  $pdf->Ln();
      	  $pdf->Cell(190,6,utf8_decode('PAGARÉ SIN PROTESTO'),0,1,'C');
      	  $pdf->Ln(10);

      	  $pdf->SetFont('Arial','B',12);

      	  date_default_timezone_set('America/El_Salvador');

      	  //echo strftime("El año es %Y y el mes es %B");
      	  setlocale(LC_ALL,"es_ES");
      	  $pdf->SetFont('Arial','B',12);
      	  $pdf->Cell(190,6,utf8_decode(strftime('Ciudad de Usulután, %A %e de %B de %G')),0,1,'L');
      	  $pdf->Ln();*/
            $pdf->Ln(3);
            $pdf->SetFont('Times','',6.4);
            $counter = 1;
            $counter2 = 1;
            while ($counter <= 31) {

                if ($tipoFacturaGenerar == 1) {
                    //var_dump($counter2);

                    /*if ($filtroServicio == 'C'){
                        $sql = "SELECT * FROM tbl_cargos WHERE DAY(fechaFactura) =".$counter." AND MONTH(fechaFactura)=".$mesGenerar." AND YEAR(fechaFactura)=".$anoGenerar." AND tipoFactura = 2 AND anulada=0 AND tipoServicio = 'C'";
                    }elseif ($filtroServicio == 'I'){
                        $sql = "SELECT * FROM tbl_cargos WHERE DAY(fechaFactura) =".$counter." AND MONTH(fechaFactura)=".$mesGenerar." AND YEAR(fechaFactura)=".$anoGenerar." AND tipoFactura = 2 AND anulada=0 AND tipoServicio = 'I'";
                    }else{
                        $sql = "SELECT * FROM tbl_cargos WHERE DAY(fechaFactura) =".$counter." AND MONTH(fechaFactura)=".$mesGenerar." AND YEAR(fechaFactura)=".$anoGenerar." AND tipoFactura = 2 AND anulada=0";
                    }*/
                    $sql = "SELECT * FROM tbl_cargos WHERE DAY(fechaFactura) =".$counter." AND MONTH(fechaFactura)=".$mesGenerar." AND YEAR(fechaFactura)=".$anoGenerar." AND tipoFactura = 2 AND anulada=0";

                    $stmt = $mysqli->query($sql);

                    while ($result = $stmt->fetch_assoc()) {
                        if ($result["tipoServicio"] == "C") {
                            $montoCancelado1 = doubleval($result["cuotaCable"]);
                        }elseif ($result["tipoServicio"] == "I") {
                            $montoCancelado1 = doubleval($result["cuotaInternet"]);
                        }
                        //IVA
                        //$separado1 = (floatval($montoCancelado1)/(1 + floatval($iva)));
                        //var_dump($separado);
                        //$totalIva1 = round((floatval($separado1) * floatval($iva)),2);

                        //IVA
                        //var_dump($counter2);
                        $separado1 = (doubleval($montoCancelado1)/(1 + doubleval($iva)));
                        $totalIva1 = substr(doubleval($separado1) * doubleval($iva),0,7);

                        $pdf->Cell(5,1,utf8_decode(date("d", strtotime($result['fechaFactura']))),0,0,'L');
                        $pdf->Cell(20,1,utf8_decode($result['numeroFactura']),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(0),0,0,'L');
                        $pdf->Cell(60,1,utf8_decode($result["nombre"]),0,0,'L');
                        $sinIva1 = doubleval($montoCancelado1)-doubleval($totalIva1);
                        if ($ex->isExento($result["codigoCliente"])) {
                            if ($result["tipoServicio"] == 'C'){
                                $tipoServ = 'CABLE';
                            }elseif($result["tipoServicio"] == 'I'){
                                $tipoServ = 'INTERNET';
                            }
                            $pdf->Cell(20,1,utf8_decode($montoCancelado1),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode($tipoServ),0,0,'L');
                            $totalSinIvaEx1 = $totalSinIvaEx1 + $sinIva1;
                            $totalConIvaEx1 = $totalConIvaEx1 + $montoCancelado1;
                        }

                        else {
                            if ($result["tipoServicio"] == 'C'){
                                $tipoServ = 'CABLE';
                            }elseif($result["tipoServicio"] == 'I'){
                                $tipoServ = 'INTERNET';
                            }
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode(number_format($sinIva1,2)),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode($tipoServ),0,0,'L');
                            $totalSinIva1 = $totalSinIva1 +$sinIva1;
                            $totalConIva1 = $totalConIva1 + $montoCancelado1;
                        }

                        $totalSoloIva1 = $totalSoloIva1 + $totalIva1;
                        $totalSoloCesc1 = $totalSoloCesc1 + doubleval($result["totalImpuesto"]);
                        //$pdf->Cell(20,1,utf8_decode($montoCancelado),0,1,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format($montoCancelado1,2)),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format($result["totalImpuesto"],2)),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format(doubleval($montoCancelado1)+doubleval($result["totalImpuesto"]),2)),0,0,'L');
                        $pdf->Ln(4);

                        if($counter2 > 50){
                            //var_dump("llegamos aca");
                            // DESPUES DE TODOS LOS CICLOS
                            $pdf->AddPage('P','Letter');

                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(190,6,utf8_decode('LIBRO O REGISTRO DE OPERACIONES CON EMISION DE FACTURAS'),0,0,'C');
                            $pdf->Ln(5);
                            $pdf->SetFont('Times','B',8);
                            $pdf->Cell(190,6,utf8_decode('VALORES EXPRESADOS EN US DOLARES'),0,0,'C');
                            $pdf->Ln(10);

                            $pdf->SetFont('Times','B',10);
                            //FILA 1
                            $pdf->Cell(25,6,utf8_decode(''),"TLR",0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('N° DE'),"T",0,'C');
                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(60,6,utf8_decode(''),"TLR",0,'C');
                            $pdf->Cell(60,6,utf8_decode('VENTAS'),1,0,'C');
                            $pdf->Cell(40,6,utf8_decode(''),"TLR",1,'C');

                            //FILA 2
                            $pdf->Cell(25,6,utf8_decode(''),"LR",0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('FORMULARIO'),"LR",0,'C');
                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(60,6,utf8_decode(''),"LR",0,'C');
                            $pdf->Cell(20,6,utf8_decode(''),"LRT",0,'C');
                            $pdf->Cell(40,6,utf8_decode('GRAVADAS'),1,0,'C');
                            $pdf->Cell(40,6,utf8_decode(''),"BLR",1,'C');

                            //FILA 3
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(5,6,utf8_decode('DIA'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('N° DE FACTURA'),1,0,'C');
                            $pdf->Cell(15,6,utf8_decode('UNICO'),"BLR",0,'C');
                            $pdf->SetFont('Times','B',7);
                            $pdf->Cell(60,6,utf8_decode('NOMBRE DEL CLIENTE'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('EXENTAS'),"BLR",0,'C');
                            $pdf->Cell(20,6,utf8_decode('LOCALES'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('SERVICIO'/*'EXPORTACION'*/),1,0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('SUB-TOTAL'),1,0,'C');
                            $pdf->Cell(10,6,utf8_decode('CESC'),1,0,'C');
                            $pdf->SetFont('Times','B',4);
                            $pdf->Cell(15,6,utf8_decode('VENTAS TOTALES'),1,1,'C');
                            $counter2=1;
                            $pdf->SetFont('Times','',6.4);
                            $pdf->Ln(3);
                        }
                        $counter2++;
                    }
                }
                elseif ($tipoFacturaGenerar == 2) {

                    $sql = "SELECT * FROM tbl_ventas_manuales WHERE DAY(fechaComprobante) =".$counter." AND MONTH(fechaComprobante)=".$mesGenerar." AND YEAR(fechaComprobante)=".$anoGenerar." AND tipoComprobante = 2 ORDER BY idVenta ASC";

                    $stmt = $mysqli->query($sql);

                    while ($result = $stmt->fetch_assoc()) {

                        if ($result["montoCable"] > 0 && is_numeric($result["montoCable"])) {
                            $tipoServ='CABLE';
                            $montoCancelado2 = doubleval($result["montoCable"]);
                        }elseif ($result["montoInternet"] > 0 && is_numeric($result["montoInternet"])) {
                            $tipoServ='INTERNET';
                            $montoCancelado2 = doubleval($result["montoInternet"]);
                        }else {
                            $montoCancelado2 = 0;
                        }
                        //IVA
                        //$separado2 = (floatval($montoCancelado2)/(1 + floatval($iva)));
                        //var_dump($separado);
                        //$totalIva2 = round((floatval($separado2) * floatval($iva)),2);
                        //var_dump('inicio '.$counter2);
                        //IVA
                        $separado2 = (floatval($montoCancelado2)/(1 + floatval($iva)));
                        $totalIva2 = substr(floatval($separado2) * floatval($iva),0,4);

                        $pdf->Cell(5,1,utf8_decode(date("d", strtotime($result['fechaComprobante']))),0,0,'L');
                        $pdf->Cell(20,1,utf8_decode($result['prefijo'] . $result['numeroComprobante']),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(0),0,0,'L');
                        $pdf->Cell(60,1,utf8_decode($result["nombreCliente"]),0,0,'L');
                        $sinIva2 = doubleval($montoCancelado2)-doubleval($totalIva2);
                        if ($ex->isExento($result["codigoCliente"])) {
                            $pdf->Cell(20,1,utf8_decode($montoCancelado2),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode($tipoServ),0,0,'L');
                            $totalSinIvaEx2 = $totalSinIvaEx2 + $sinIva2;
                            $totalConIvaEx2 = $totalConIvaEx2 + $montoCancelado2;
                        }

                        else {
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode(number_format($montoCancelado2,2)),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode($tipoServ),0,0,'L');
                            $totalSinIva2 = $totalSinIva2 +$sinIva2;
                            $totalConIva2 = $totalConIva2 + $montoCancelado2;
                        }

                        $totalSoloIva2 = $totalSoloIva2 + $totalIva2;
                        $totalSoloCesc2 = $totalSoloCesc2 + doubleval($result["impuesto"]);
                        //$pdf->Cell(20,1,utf8_decode($montoCancelado),0,1,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format($montoCancelado2,2)),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format($result["impuesto"],2)),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format(doubleval($montoCancelado2)+doubleval($result["impuesto"]),2)),0,0,'L');
                        $pdf->Ln(4);

                        if($counter2 > 50){

                            //var_dump("llegamos aca");
                            // DESPUES DE TODOS LOS CICLOS
                            $pdf->AddPage('P','Letter');

                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(190,6,utf8_decode('LIBRO O REGISTRO DE OPERACIONES CON EMISION DE FACTURAS'),0,0,'C');
                            $pdf->Ln(5);
                            $pdf->SetFont('Times','B',8);
                            $pdf->Cell(190,6,utf8_decode('VALORES EXPRESADOS EN US DOLARES'),0,0,'C');
                            $pdf->Ln(10);

                            $pdf->SetFont('Times','B',10);
                            //FILA 1
                            $pdf->Cell(25,6,utf8_decode(''),"TLR",0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('N° DE'),"T",0,'C');
                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(60,6,utf8_decode(''),"TLR",0,'C');
                            $pdf->Cell(60,6,utf8_decode('VENTAS'),1,0,'C');
                            $pdf->Cell(40,6,utf8_decode(''),"TLR",1,'C');

                            //FILA 2
                            $pdf->Cell(25,6,utf8_decode(''),"LR",0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('FORMULARIO'),"LR",0,'C');
                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(60,6,utf8_decode(''),"LR",0,'C');
                            $pdf->Cell(20,6,utf8_decode(''),"LRT",0,'C');
                            $pdf->Cell(40,6,utf8_decode('GRAVADAS'),1,0,'C');
                            $pdf->Cell(40,6,utf8_decode(''),"BLR",1,'C');

                            //FILA 3
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(5,6,utf8_decode('DIA'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('N° DE FACTURA'),1,0,'C');
                            $pdf->Cell(15,6,utf8_decode('UNICO'),"BLR",0,'C');
                            $pdf->SetFont('Times','B',7);
                            $pdf->Cell(60,6,utf8_decode('NOMBRE DEL CLIENTE'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('EXENTAS'),"BLR",0,'C');
                            $pdf->Cell(20,6,utf8_decode('LOCALES'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('SERVICIO'/*'EXPORTACION'*/),1,0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('SUB-TOTAL'),1,0,'C');
                            $pdf->Cell(10,6,utf8_decode('CESC'),1,0,'C');
                            $pdf->SetFont('Times','B',4);
                            $pdf->Cell(15,6,utf8_decode('VENTAS TOTALES'),1,1,'C');
                            $counter2=1;
                            $pdf->SetFont('Times','',6);
                            $pdf->Ln(3);
                        }
                        $counter2++;
                        //var_dump('final '.$counter2);
                    }
                }
                elseif ($tipoFacturaGenerar == 3) {

                    $sql = "SELECT * FROM tbl_ventas_anuladas WHERE DAY(fechaComprobante) =".$counter." AND MONTH(fechaComprobante)=".$mesGenerar." AND YEAR(fechaComprobante)=".$anoGenerar." AND tipoComprobante = 2 ORDER BY numeroComprobante ASC";

                    $stmt = $mysqli->query($sql);
                    $counter2 = 1;
                    while ($result = $stmt->fetch_assoc()) {
                        if ($result["totalComprobante"] > 0 && is_numeric($result["totalComprobante"])) {
                            $montoCancelado3 = doubleval($result["totalComprobante"]);
                        }else {
                            $montoCancelado3 = 0;
                        }
                        //IVA
                        $separado3 = (floatval($montoCancelado3)/(1 + floatval($iva)));
                        //var_dump($separado);
                        //$totalIva3 = (floatval($separado3) * floatval($iva));
                        //$totalCesc3 = round((floatval($separado3) * floatval($cesc)),2);

                        $totalCesc3 = substr(((doubleval($montoCancelado3)/(1 + floatval($iva)))*$cesc),0,7);

                        //IVA
                        $separado3 = (floatval($montoCancelado3)/(1 + floatval($iva)));
                        $totalIva3 = (doubleval($separado3) * doubleval($iva));


                        $pdf->Cell(5,1,utf8_decode(date("d", strtotime($result['fechaComprobante']))),0,0,'L');
                        $pdf->Cell(20,1,utf8_decode($result['prefijo'] . $result['numeroComprobante']),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(0),0,0,'L');
                        $pdf->Cell(60,1,utf8_decode($result["nombreCliente"]),0,0,'L');
                        $sinIva3 = doubleval($montoCancelado3)-doubleval($totalIva3);
                        if ($ex->isExento($result["codigoCliente"])) {
                            $pdf->Cell(20,1,utf8_decode($montoCancelado3),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $totalSinIvaEx3 = $totalSinIvaEx3 + $sinIva3;
                            $totalConIvaEx3 = $totalConIvaEx3 + $montoCancelado3;
                        }

                        else {
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode(number_format($montoCancelado3,2)),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $totalSinIva3 = $totalSinIva3 +$sinIva3;
                            $totalConIva3 = $totalConIva3 + $montoCancelado3;
                        }

                        $totalSoloIva3 = $totalSoloIva3 + $totalIva3;
                        $totalSoloCesc3 = $totalSoloCesc3 + $totalCesc3;
                        //$pdf->Cell(20,1,utf8_decode($montoCancelado),0,1,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format($montoCancelado3,2)),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format($totalCesc3,2)),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format(doubleval($montoCancelado3)+doubleval($totalCesc3),2)),0,0,'L');
                        $pdf->Ln(4);

                        if($counter2 > 50){
                            //var_dump("llegamos aca");
                            // DESPUES DE TODOS LOS CICLOS
                            $pdf->AddPage('P','Letter');

                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(190,6,utf8_decode('LIBRO O REGISTRO DE OPERACIONES CON EMISION DE FACTURAS'),0,0,'C');
                            $pdf->Ln(5);
                            $pdf->SetFont('Times','B',8);
                            $pdf->Cell(190,6,utf8_decode('VALORES EXPRESADOS EN US DOLARES'),0,0,'C');
                            $pdf->Ln(10);

                            $pdf->SetFont('Times','B',10);
                            //FILA 1
                            $pdf->Cell(25,6,utf8_decode(''),"TLR",0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('N° DE'),"T",0,'C');
                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(60,6,utf8_decode(''),"TLR",0,'C');
                            $pdf->Cell(60,6,utf8_decode('VENTAS'),1,0,'C');
                            $pdf->Cell(40,6,utf8_decode(''),"TLR",1,'C');

                            //FILA 2
                            $pdf->Cell(25,6,utf8_decode(''),"LR",0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('FORMULARIO'),"LR",0,'C');
                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(60,6,utf8_decode(''),"LR",0,'C');
                            $pdf->Cell(20,6,utf8_decode(''),"LRT",0,'C');
                            $pdf->Cell(40,6,utf8_decode('GRAVADAS'),1,0,'C');
                            $pdf->Cell(40,6,utf8_decode(''),"BLR",1,'C');

                            //FILA 3
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(5,6,utf8_decode('DIA'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('N° DE FACTURA'),1,0,'C');
                            $pdf->Cell(15,6,utf8_decode('UNICO'),"BLR",0,'C');
                            $pdf->SetFont('Times','B',7);
                            $pdf->Cell(60,6,utf8_decode('NOMBRE DEL CLIENTE'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('EXENTAS'),"BLR",0,'C');
                            $pdf->Cell(20,6,utf8_decode('LOCALES'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('SERVICIO'/*'EXPORTACION'*/),1,0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('SUB-TOTAL'),1,0,'C');
                            $pdf->Cell(10,6,utf8_decode('CESC'),1,0,'C');
                            $pdf->SetFont('Times','B',4);
                            $pdf->Cell(15,6,utf8_decode('VENTAS TOTALES'),1,1,'C');
                            $counter2=1;
                            $pdf->SetFont('Times','',6);
                            $pdf->Ln(3);
                        }
                        $counter2++;
                    }

                }
                elseif ($tipoFacturaGenerar == 4) {
                    $sql = "SELECT * FROM tbl_cargos WHERE DAY(fechaFactura) =".$counter." AND MONTH(fechaFactura)=".$mesGenerar." AND YEAR(fechaFactura)=".$anoGenerar." AND tipoFactura = 2 AND anulada=0";

                    $stmt = $mysqli->query($sql);

                    while ($result = $stmt->fetch_assoc()) {
                        if ($result["tipoServicio"] == "C") {
                            $tipoServ = 'CABLE';
                            $montoCancelado1 = doubleval($result["cuotaCable"]);
                        }elseif ($result["tipoServicio"] == "I") {
                            $tipoServ = 'INTERNET';
                            $montoCancelado1 = doubleval($result["cuotaInternet"]);
                        }
                        //IVA
                        //$separado1 = (floatval($montoCancelado1)/(1 + floatval($iva)));
                        //var_dump($separado);
                        //$totalIva1 = round((floatval($separado1) * floatval($iva)),2);


                        $separado1 = (floatval($montoCancelado1)/(1 + floatval($iva)));
                        $totalIva1 = substr(doubleval($separado1) * doubleval($iva),0,7);

                        $pdf->Cell(5,1,utf8_decode(date("d", strtotime($result['fechaFactura']))),0,0,'L');
                        $pdf->Cell(20,1,utf8_decode($result['numeroFactura']),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(0),0,0,'L');
                        $pdf->Cell(60,1,utf8_decode($result["nombre"]),0,0,'L');
                        $sinIva1 = doubleval($montoCancelado1)-doubleval($totalIva1);
                        if ($ex->isExento($result["codigoCliente"])) {
                            $pdf->Cell(20,1,utf8_decode($montoCancelado1),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode($tipoServ),0,0,'L');
                            $totalSinIvaEx1 = $totalSinIvaEx1 + $sinIva1;
                            $totalConIvaEx1 = $totalConIvaEx1 + $montoCancelado1;
                        }

                        else {
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode(number_format($sinIva1,2)),0,0,'L');//APARENTEMENTE TIENE IVA Y FALTA CESC
                            $pdf->Cell(20,1,utf8_decode($tipoServ),0,0,'L');
                            $totalSinIva1 = $totalSinIva1 +$sinIva1;
                            $totalConIva1 = $totalConIva1 + $montoCancelado1;
                        }

                        $totalSoloIva1 = $totalSoloIva1 + $totalIva1;
                        $totalSoloCesc1 = $totalSoloCesc1 + doubleval($result["totalImpuesto"]);
                        //$pdf->Cell(20,1,utf8_decode($montoCancelado),0,1,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format($montoCancelado1,2)),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format($result["totalImpuesto"],2)),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format(doubleval($montoCancelado1)+doubleval($result["totalImpuesto"]),2)),0,0,'L');
                        $pdf->Ln(4);

                        if($counter2 > 50){

                            //var_dump("llegamos aca");
                            // DESPUES DE TODOS LOS CICLOS
                            $pdf->AddPage('P','Letter');

                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(190,6,utf8_decode('LIBRO O REGISTRO DE OPERACIONES CON EMISION DE FACTURAS'),0,0,'C');
                            $pdf->Ln(5);
                            $pdf->SetFont('Times','B',8);
                            $pdf->Cell(190,6,utf8_decode('VALORES EXPRESADOS EN US DOLARES'),0,0,'C');
                            $pdf->Ln(10);

                            $pdf->SetFont('Times','B',10);
                            //FILA 1
                            $pdf->Cell(25,6,utf8_decode(''),"TLR",0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('N° DE'),"T",0,'C');
                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(60,6,utf8_decode(''),"TLR",0,'C');
                            $pdf->Cell(60,6,utf8_decode('VENTAS'),1,0,'C');
                            $pdf->Cell(40,6,utf8_decode(''),"TLR",1,'C');

                            //FILA 2
                            $pdf->Cell(25,6,utf8_decode(''),"LR",0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('FORMULARIO'),"LR",0,'C');
                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(60,6,utf8_decode(''),"LR",0,'C');
                            $pdf->Cell(20,6,utf8_decode(''),"LRT",0,'C');
                            $pdf->Cell(40,6,utf8_decode('GRAVADAS'),1,0,'C');
                            $pdf->Cell(40,6,utf8_decode(''),"BLR",1,'C');

                            //FILA 3
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(5,6,utf8_decode('DIA'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('N° DE FACTURA'),1,0,'C');
                            $pdf->Cell(15,6,utf8_decode('UNICO'),"BLR",0,'C');
                            $pdf->SetFont('Times','B',7);
                            $pdf->Cell(60,6,utf8_decode('NOMBRE DEL CLIENTE'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('EXENTAS'),"BLR",0,'C');
                            $pdf->Cell(20,6,utf8_decode('LOCALES'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('SERVICIO'/*'EXPORTACION'*/),1,0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('SUB-TOTAL'),1,0,'C');
                            $pdf->Cell(10,6,utf8_decode('CESC'),1,0,'C');
                            $pdf->SetFont('Times','B',4);
                            $pdf->Cell(15,6,utf8_decode('VENTAS TOTALES'),1,1,'C');
                            $counter2=1;
                            $pdf->SetFont('Times','',6);
                            $pdf->Ln(3);
                        }
                        $counter2++;
                    }

                    $sql = "SELECT * FROM tbl_ventas_manuales WHERE DAY(fechaComprobante) =".$counter." AND MONTH(fechaComprobante)=".$mesGenerar." AND YEAR(fechaComprobante)=".$anoGenerar." AND tipoComprobante = 2 ORDER BY idVenta ASC";

                    $stmt = $mysqli->query($sql);

                    while ($result = $stmt->fetch_assoc()) {
                        if ($result["montoCable"] > 0 && is_numeric($result["montoCable"])) {
                            $tipoServ = 'CABLE';
                            $montoCancelado2 = doubleval($result["montoCable"]);
                        }elseif ($result["montoInternet"] > 0 && is_numeric($result["montoInternet"])) {
                            $tipoServ = 'INTERNET';
                            $montoCancelado2 = doubleval($result["montoInternet"]);
                        }else {
                            $montoCancelado2 = 0;
                        }
                        //IVA
                        //$separado2 = (floatval($montoCancelado2)/(1 + floatval($iva)));
                        //var_dump($separado);
                        //$totalIva2 = round((floatval($separado2) * floatval($iva)),2);
                        $separado2 = (floatval($montoCancelado2)/(1 + floatval($iva)));
                        $totalIva2 = substr(floatval($separado2) * floatval($iva),0,7);


                        $pdf->Cell(5,1,utf8_decode(date("d", strtotime($result['fechaComprobante']))),0,0,'L');
                        $pdf->Cell(20,1,utf8_decode($result['prefijo'] . $result['numeroComprobante']),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(0),0,0,'L');
                        $pdf->Cell(60,1,utf8_decode($result["nombreCliente"]),0,0,'L');
                        $sinIva2 = doubleval($montoCancelado2)-doubleval($totalIva2);
                        if ($ex->isExento($result["codigoCliente"])) {
                            $pdf->Cell(20,1,utf8_decode($montoCancelado2),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode($tipoServ),0,0,'L');
                            $totalSinIvaEx2 = $totalSinIvaEx2 + $sinIva2;
                            $totalConIvaEx2 = $totalConIvaEx2 + $montoCancelado2;
                        }

                        else {
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode(number_format($montoCancelado2,2)),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode($tipoServ),0,0,'L');
                            $totalSinIva2 = $totalSinIva2 +$sinIva2;
                            $totalConIva2 = $totalConIva2 + $montoCancelado2;
                        }

                        $totalSoloIva2 = $totalSoloIva2 + $totalIva2;
                        $totalSoloCesc2 = $totalSoloCesc2 + doubleval($result["impuesto"]);
                        //$pdf->Cell(20,1,utf8_decode($montoCancelado),0,1,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format($montoCancelado2,2)),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format($result["impuesto"],2)),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format(doubleval($montoCancelado2)+doubleval($result["impuesto"]),2)),0,0,'L');
                        $pdf->Ln(4);

                        if($counter2 > 50){

                            //var_dump("llegamos aca");
                            // DESPUES DE TODOS LOS CICLOS
                            $pdf->AddPage('P','Letter');

                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(190,6,utf8_decode('LIBRO O REGISTRO DE OPERACIONES CON EMISION DE FACTURAS'),0,0,'C');
                            $pdf->Ln(5);
                            $pdf->SetFont('Times','B',8);
                            $pdf->Cell(190,6,utf8_decode('VALORES EXPRESADOS EN US DOLARES'),0,0,'C');
                            $pdf->Ln(10);

                            $pdf->SetFont('Times','B',10);
                            //FILA 1
                            $pdf->Cell(25,6,utf8_decode(''),"TLR",0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('N° DE'),"T",0,'C');
                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(60,6,utf8_decode(''),"TLR",0,'C');
                            $pdf->Cell(60,6,utf8_decode('VENTAS'),1,0,'C');
                            $pdf->Cell(40,6,utf8_decode(''),"TLR",1,'C');

                            //FILA 2
                            $pdf->Cell(25,6,utf8_decode(''),"LR",0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('FORMULARIO'),"LR",0,'C');
                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(60,6,utf8_decode(''),"LR",0,'C');
                            $pdf->Cell(20,6,utf8_decode(''),"LRT",0,'C');
                            $pdf->Cell(40,6,utf8_decode('GRAVADAS'),1,0,'C');
                            $pdf->Cell(40,6,utf8_decode(''),"BLR",1,'C');

                            //FILA 3
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(5,6,utf8_decode('DIA'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('N° DE FACTURA'),1,0,'C');
                            $pdf->Cell(15,6,utf8_decode('UNICO'),"BLR",0,'C');
                            $pdf->SetFont('Times','B',7);
                            $pdf->Cell(60,6,utf8_decode('NOMBRE DEL CLIENTE'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('EXENTAS'),"BLR",0,'C');
                            $pdf->Cell(20,6,utf8_decode('LOCALES'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('SERVICIO'/*'EXPORTACION'*/),1,0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('SUB-TOTAL'),1,0,'C');
                            $pdf->Cell(10,6,utf8_decode('CESC'),1,0,'C');
                            $pdf->SetFont('Times','B',4);
                            $pdf->Cell(15,6,utf8_decode('VENTAS TOTALES'),1,1,'C');
                            $counter2=1;
                            $pdf->SetFont('Times','',6);
                            $pdf->Ln(3);
                        }
                        $counter2++;
                    }

                    $sql = "SELECT * FROM tbl_ventas_anuladas WHERE DAY(fechaComprobante) =".$counter." AND MONTH(fechaComprobante)=".$mesGenerar." AND YEAR(fechaComprobante)=".$anoGenerar." AND tipoComprobante = 2 ORDER BY idVenta ASC";

                    $stmt = $mysqli->query($sql);

                    while ($result = $stmt->fetch_assoc()) {
                        if ($result["totalComprobante"] > 0 && is_numeric($result["totalComprobante"])) {
                            $montoCancelado3 = doubleval($result["totalComprobante"]);
                        }else {
                            $montoCancelado3 = 0;
                        }
                        //IVA
                        //$separado3 = (floatval($montoCancelado3)/(1 + floatval($iva)));
                        //var_dump($separado);
                        //$totalIva3 = round((floatval($separado3) * floatval($iva)),2);
                        //$totalCesc3 = round((floatval($separado3) * floatval($cesc)),2);

                        $totalCesc3 = substr((($montoCancelado3/(1 + floatval($iva)))*$cesc),0,7);
                        //IVA
                        $separado3 = (floatval($montoCancelado3)/(1 + floatval($iva)));

                        //$totalIva3 = (doubleval($separado3) * doubleval($iva));
                        $totalIva3 = substr(doubleval($separado3) * doubleval($iva),0,7);

                        $pdf->Cell(5,1,utf8_decode(date("d", strtotime($result['fechaComprobante']))),0,0,'L');
                        $pdf->Cell(20,1,utf8_decode($result['prefijo'] . $result['numeroComprobante']),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(0),0,0,'L');
                        $pdf->Cell(60,1,utf8_decode($result["nombreCliente"]),0,0,'L');
                        $sinIva3 = doubleval($montoCancelado3)-doubleval($totalIva3);
                        if ($ex->isExento($result["codigoCliente"])) {
                            $pdf->Cell(20,1,utf8_decode($montoCancelado3),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $totalSinIvaEx3 = $totalSinIvaEx3 + $sinIva3;
                            $totalConIvaEx3 = $totalConIvaEx3 + $montoCancelado3;
                        }

                        else {
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode(number_format($montoCancelado3,2)),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $totalSinIva3 = $totalSinIva3 +$sinIva3;
                            $totalConIva3 = $totalConIva3 + $montoCancelado3;
                        }

                        $totalSoloIva3 = $totalSoloIva3 + $totalIva3;
                        $totalSoloCesc3 = $totalSoloCesc3 + $totalCesc3;
                        //$pdf->Cell(20,1,utf8_decode($montoCancelado),0,1,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format($montoCancelado3,2)),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format($result["impuesto"],2)),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format(doubleval($montoCancelado3)+doubleval($result["impuesto"]),2)),0,0,'L');
                        $pdf->Ln(4);

                        if($counter2 > 50){

                            //var_dump("llegamos aca");
                            // DESPUES DE TODOS LOS CICLOS
                            $pdf->AddPage('P','Letter');

                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(190,6,utf8_decode('LIBRO O REGISTRO DE OPERACIONES CON EMISION DE FACTURAS'),0,0,'C');
                            $pdf->Ln(5);
                            $pdf->SetFont('Times','B',8);
                            $pdf->Cell(190,6,utf8_decode('VALORES EXPRESADOS EN US DOLARES'),0,0,'C');
                            $pdf->Ln(10);

                            $pdf->SetFont('Times','B',10);
                            //FILA 1
                            $pdf->Cell(25,6,utf8_decode(''),"TLR",0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('N° DE'),"T",0,'C');
                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(60,6,utf8_decode(''),"TLR",0,'C');
                            $pdf->Cell(60,6,utf8_decode('VENTAS'),1,0,'C');
                            $pdf->Cell(40,6,utf8_decode(''),"TLR",1,'C');

                            //FILA 2
                            $pdf->Cell(25,6,utf8_decode(''),"LR",0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('FORMULARIO'),"LR",0,'C');
                            $pdf->SetFont('Times','B',10);
                            $pdf->Cell(60,6,utf8_decode(''),"LR",0,'C');
                            $pdf->Cell(20,6,utf8_decode(''),"LRT",0,'C');
                            $pdf->Cell(40,6,utf8_decode('GRAVADAS'),1,0,'C');
                            $pdf->Cell(40,6,utf8_decode(''),"BLR",1,'C');

                            //FILA 3
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(5,6,utf8_decode('DIA'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('N° DE FACTURA'),1,0,'C');
                            $pdf->Cell(15,6,utf8_decode('UNICO'),"BLR",0,'C');
                            $pdf->SetFont('Times','B',7);
                            $pdf->Cell(60,6,utf8_decode('NOMBRE DEL CLIENTE'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('EXENTAS'),"BLR",0,'C');
                            $pdf->Cell(20,6,utf8_decode('LOCALES'),1,0,'C');
                            $pdf->Cell(20,6,utf8_decode('SERVICIO'/*'EXPORTACION'*/),1,0,'C');
                            $pdf->SetFont('Times','B',5);
                            $pdf->Cell(15,6,utf8_decode('SUB-TOTAL'),1,0,'C');
                            $pdf->Cell(10,6,utf8_decode('CESC'),1,0,'C');
                            $pdf->SetFont('Times','B',4);
                            $pdf->Cell(15,6,utf8_decode('VENTAS TOTALES'),1,1,'C');
                            $counter2=1;
                            $pdf->SetFont('Times','',6);
                            $pdf->Ln(3);
                        }
                        $counter2++;

                    }
                }
                //$counter2++;
                $counter++;

            }



            $pdf->Ln(2);
            $pdf->SetFont('Times','B',7);
            $pdf->Cell(40,6,utf8_decode(''),0,0,'C');
            $pdf->Cell(60,6,utf8_decode('TOTALES DEL MES'),"T",0,'C');
            if ($tipoFacturaGenerar == 1) {
                $pdf->Cell(20,6,utf8_decode(number_format($totalSinIvaEx1,2)),"T",0,'C');
                $pdf->Cell(20,6,utf8_decode(number_format($totalSinIva1,2)),"T",0,'C');
                $pdf->Cell(20,6,utf8_decode('0.00'),"T",0,'C');
                $pdf->Cell(15,6,utf8_decode(number_format($totalConIva1,2)),"T",0,'C');
                $pdf->Cell(10,6,utf8_decode(number_format($totalSoloCesc1,2)),"T",0,'C');
                $pdf->Cell(15,6,utf8_decode(number_format(($totalConIva1 + $totalSoloCesc1),2)),"T",0,'C');

                $pdf->Ln(10);
                $pdf->SetFont('Times','B',10);
                $pdf->Cell(40,6,utf8_decode('RESUMEN'),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('Ventas exentas'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format($totalConIvaEx1,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('Ventas netas gravadas'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format($totalSinIva1,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('13% de IVA'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format($totalSoloIva1,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('5% CESC'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format($totalSoloCesc1,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('Exportaciones'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode('0.00'),0,1,'L');
                $pdf->Cell(60,6,utf8_decode(''),"T",1,'C');
                $pdf->Cell(40,6,utf8_decode("VENTAS TOTALES:"),"",0,'L');
                $pdf->Cell(20,6,utf8_decode(number_format(($totalConIvaEx1+$totalSinIva1+$totalSoloIva1+$totalSoloCesc1),2)),"",1,'L');

                $pdf->Ln(20);
                $pdf->Cell(70,3,utf8_decode(''),"T",1,'C');
                $pdf->Cell(40,1,utf8_decode("Nombre y firma del contador"),"",0,'L');
            }elseif ($tipoFacturaGenerar == 2) {
                $pdf->Cell(20,6,utf8_decode(number_format($totalSinIvaEx2,2)),"T",0,'C');
                $pdf->Cell(20,6,utf8_decode(number_format($totalSinIva2,2)),"T",0,'C');
                $pdf->Cell(20,6,utf8_decode('0.00'),"T",0,'C');
                $pdf->Cell(15,6,utf8_decode(number_format($totalConIva2,2)),"T",0,'C');
                $pdf->Cell(10,6,utf8_decode(number_format($totalSoloCesc2,2)),"T",0,'C');
                $pdf->Cell(15,6,utf8_decode(number_format(($totalConIva2 + $totalSoloCesc2),2)),"T",0,'C');

                $pdf->Ln(10);
                $pdf->SetFont('Times','B',10);
                $pdf->Cell(40,6,utf8_decode('RESUMEN'),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('Ventas exentas'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format($totalConIvaEx2,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('Ventas netas gravadas'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format($totalSinIva2,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('13% de IVA'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format($totalSoloIva2,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('5% CESC'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format($totalSoloCesc2,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('Exportaciones'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode('0.00'),0,1,'L');
                $pdf->Cell(60,6,utf8_decode(''),"T",1,'C');
                $pdf->Cell(40,6,utf8_decode("VENTAS TOTALES:"),"",0,'L');
                $pdf->Cell(20,6,utf8_decode(number_format(($totalConIvaEx2+$totalSinIva2+$totalSoloIva2+$totalSoloCesc2),2)),"",1,'L');

                $pdf->Ln(20);
                $pdf->Cell(70,3,utf8_decode(''),"T",1,'C');
                $pdf->Cell(40,1,utf8_decode("Nombre y firma del contador o contribuyente:"),"",0,'L');
            }elseif ($tipoFacturaGenerar == 3) {
                $pdf->Cell(20,6,utf8_decode(number_format($totalSinIvaEx3,2)),"T",0,'C');
                $pdf->Cell(20,6,utf8_decode(number_format($totalSinIva3,2)),"T",0,'C');
                $pdf->Cell(20,6,utf8_decode('0.00'),"T",0,'C');
                $pdf->Cell(15,6,utf8_decode(number_format($totalConIva3,2)),"T",0,'C');
                $pdf->Cell(10,6,utf8_decode(number_format($totalSoloCesc3,2)),"T",0,'C');
                $pdf->Cell(15,6,utf8_decode(number_format(($totalConIva3 + $totalSoloCesc3),2)),"T",0,'C');

                $pdf->Ln(5);
                $pdf->SetFont('Times','B',10);
                $pdf->Cell(40,6,utf8_decode('RESUMEN'),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('Ventas exentas'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format($totalConIvaEx3,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('Ventas netas gravadas'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format($totalSinIva3,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('13% de IVA'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format($totalSoloIva3,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('5% CESC'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format($totalSoloCesc3,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('Exportaciones'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode('0.00'),0,1,'L');
                $pdf->Cell(60,6,utf8_decode(''),"T",1,'C');
                $pdf->Cell(40,6,utf8_decode("VENTAS TOTALES:"),"",0,'L');
                $pdf->Cell(20,6,utf8_decode(number_format(($totalConIvaEx3+$totalSinIva3+$totalSoloIva3+$totalSoloCesc3),2)),"",1,'L');

                $pdf->Ln(30);
                $pdf->Cell(70,3,utf8_decode(''),"T",1,'C');
                $pdf->Cell(40,1,utf8_decode("Nombre y firma del contador:"),"",0,'L');
            }elseif ($tipoFacturaGenerar == 4) {
                $pdf->Cell(20,6,utf8_decode(number_format(($totalSinIvaEx1+$totalSinIvaEx2-$totalSinIvaEx3),2)),"T",0,'C');
                $pdf->Cell(20,6,utf8_decode(number_format(($totalSinIva1+$totalSinIva2-$totalSinIva3),2)),"T",0,'C');
                $pdf->Cell(20,6,utf8_decode('0.00'),"T",0,'C');
                $pdf->Cell(15,6,utf8_decode(number_format(($totalConIva1+$totalConIva2-$totalConIva3),2)),"T",0,'C');
                $pdf->Cell(10,6,utf8_decode(number_format(($totalSoloCesc1+$totalSoloCesc2-$totalSoloCesc3),2)),"T",0,'C');
                $pdf->Cell(15,6,utf8_decode(number_format(($totalConIva1 + $totalSoloCesc1 + $totalConIva2 + $totalSoloCesc2 - $totalConIva3 - $totalSoloCesc3),2)),"T",0,'C');

                $pdf->Ln(10);
                $pdf->SetFont('Times','B',10);
                $pdf->Cell(40,6,utf8_decode('RESUMEN'),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('Ventas exentas'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format(($totalConIvaEx1+$totalConIvaEx2-$totalConIvaEx3),2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('Ventas netas gravadas'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format(($totalSinIva1+$totalSinIva2-$totalSinIva3),2)),0,0,'L');
                $pdf->Cell(40,6,utf8_decode("- ".(number_format($totalSinIva3,2))." (anuladas)"),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('13% de IVA'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format(($totalSoloIva1+$totalSoloIva2-$totalSoloIva3),2)),0,0,'L');
                $pdf->Cell(40,6,utf8_decode("- ".(number_format($totalSoloIva3,2))." (anuladas)"),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('5% CESC'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format(($totalSoloCesc1+$totalSoloCesc2-$totalSoloCesc3),2)),0,0,'L');
                $pdf->Cell(40,6,utf8_decode("- ".(number_format($totalSoloCesc3,2))." (anuladas)"),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('Exportaciones'),0,0,'L');
                $pdf->Cell(40,6,utf8_decode('0.00'),0,1,'L');
                $pdf->Cell(60,6,utf8_decode(''),"T",1,'C');
                $pdf->Cell(40,6,utf8_decode("VENTAS TOTALES:"),"",0,'L');
                $pdf->Cell(20,6,utf8_decode(number_format(($totalConIvaEx1+$totalSinIva1+$totalSoloIva1+$totalSoloCesc1 + $totalConIvaEx2+$totalSinIva2+$totalSoloIva2+$totalSoloCesc2 - $totalConIvaEx3-$totalSinIva3-$totalSoloIva3-$totalSoloCesc3),2)),"",1,'L');

                $pdf->Ln(20);
                $pdf->Cell(70,3,utf8_decode(''),"T",1,'C');
                $pdf->Cell(40,1,utf8_decode("Nombre y firma del contador:"),"",0,'L');
            }


        }
        elseif ($libroDetallado == null) { // GENERAL
            $pdf->AddPage('P','Letter');

            $pdf->SetFont('Times','B',10);
            $pdf->Cell(190,6,utf8_decode('LIBRO O REGISTRO DE OPERACIONES CON EMISION DE FACTURAS'),0,0,'C');
            $pdf->Ln(5);
            $pdf->SetFont('Times','B',8);
            $pdf->Cell(190,6,utf8_decode('VALORES EXPRESADOS EN US DOLARES'),0,0,'C');
            $pdf->Ln(10);

            $pdf->SetFont('Times','B',10);
            //FILA 1
            $pdf->Cell(25,6,utf8_decode(''),"TLR",0,'C');
            $pdf->SetFont('Times','B',5);
            $pdf->SetFont('Times','B',10);
            $pdf->Cell(75,6,utf8_decode(''),"TLR",0,'C');
            $pdf->Cell(60,6,utf8_decode('VENTAS'),1,0,'C');
            $pdf->Cell(40,6,utf8_decode(''),"TLR",1,'C');

            //FILA 2
            $pdf->Cell(25,6,utf8_decode(''),"LR",0,'C');
            $pdf->SetFont('Times','B',5);
            $pdf->SetFont('Times','B',10);
            $pdf->Cell(75,6,utf8_decode(''),"LR",0,'C');
            $pdf->Cell(20,6,utf8_decode(''),"LRT",0,'C');
            $pdf->Cell(40,6,utf8_decode('GRAVADAS'),1,0,'C');
            $pdf->Cell(40,6,utf8_decode(''),"BLR",1,'C');

            //FILA 3
            $pdf->SetFont('Times','B',5);
            $pdf->Cell(5,6,utf8_decode('DIA'),1,0,'C');
            $pdf->SetFont('Times','B',7);
            $pdf->Cell(30,6,utf8_decode('DEL N° DE FACTURA'),1,0,'C');
            $pdf->Cell(30,6,utf8_decode('AL N° DE FACTURA'),1,0,'C');
            $pdf->Cell(35,6,utf8_decode('N° CAJA REGISTRADORA'),1,0,'C');
            $pdf->Cell(20,6,utf8_decode('EXENTAS'),"BLR",0,'C');
            $pdf->Cell(20,6,utf8_decode('LOCALES'),1,0,'C');
            $pdf->Cell(20,6,utf8_decode('EXPORTACION'),1,0,'C');
            $pdf->SetFont('Times','B',5);
            $pdf->Cell(15,6,utf8_decode('SUB-TOTAL'),1,0,'C');
            $pdf->Cell(10,6,utf8_decode('CESC'),1,0,'C');
            $pdf->SetFont('Times','B',4);
            $pdf->Cell(15,6,utf8_decode('VENTAS TOTALES'),1,1,'C');
            $pdf->SetFont('Times','B',5);
      	  /*$pdf->Image('../../../images/logo.png',10,10, 26, 24);
      	  $pdf->Ln(35);
      	  $pdf->SetFont('Arial','B',13);
      	  $pdf->Cell(190,6,utf8_decode('F-3'),0,1,'R');
      	  $pdf->Ln();
      	  $pdf->Cell(190,6,utf8_decode('PAGARÉ SIN PROTESTO'),0,1,'C');
      	  $pdf->Ln(10);

      	  $pdf->SetFont('Arial','B',12);

      	  date_default_timezone_set('America/El_Salvador');

      	  //echo strftime("El año es %Y y el mes es %B");
      	  setlocale(LC_ALL,"es_ES");
      	  $pdf->SetFont('Arial','B',12);
      	  $pdf->Cell(190,6,utf8_decode(strftime('Ciudad de Usulután, %A %e de %B de %G')),0,1,'L');
      	  $pdf->Ln();*/
            $pdf->Ln(3);
            $pdf->SetFont('Times','',6);
            $counter = 1;
            while ($counter <= 31) {
                if ($tipoFacturaGenerar == 1) {
                    $sql = "SELECT
                            (SELECT SUM(cuotaCable) from tbl_cargos where tipoServicio='C' and DAY(fechaFactura) =".$counter." AND MONTH(fechaFactura)=".$mesGenerar." AND YEAR(fechaFactura)=".$anoGenerar." AND tipoFactura = 2 AND anulada=0) as totalCuotaCable,
                            (SELECT SUM(cuotaInternet) from tbl_cargos where tipoServicio='I' and DAY(fechaFactura) =".$counter." AND MONTH(fechaFactura)=".$mesGenerar." AND YEAR(fechaFactura)=".$anoGenerar." AND tipoFactura = 2 AND anulada=0) as totalCuotaInter, SUM(totalImpuesto) as totalImp, MIN(numeroFactura) as inFact, MAX(numeroFactura) as finFact, DAY(fechaFactura) as dia FROM tbl_cargos
                            WHERE DAY(fechaFactura) =".$counter." AND MONTH(fechaFactura)=".$mesGenerar." AND YEAR(fechaFactura)=".$anoGenerar." AND tipoFactura = 2 AND anulada=0";

                            $stmt = $mysqli->query($sql);
                            //var_dump($stmt);

                            while ($result = $stmt->fetch_assoc()) {
                                $montoCancelado = doubleval($result["totalCuotaCable"]) + doubleval($result["totalCuotaInter"]);
                                //IVA
                                $separado = (floatval($montoCancelado)/(1 + floatval($iva)));
                                //var_dump($separado);
                                $totalIva = substr(floatval($separado) * floatval($iva),0,7);


                                $pdf->Cell(5,1,utf8_decode($counter),0,0,'L');
                                //$pdf->Cell(30,1,utf8_decode($montoCancelado),0,0,'L');
                                $pdf->Cell(30,1,utf8_decode($result["inFact"]),0,0,'L');
                                $pdf->Cell(30,1,utf8_decode($result["finFact"]),0,0,'L');
                                $pdf->Cell(35,1,utf8_decode(""),0,0,'L');
                                $sinIva = doubleval($montoCancelado)-doubleval($totalIva);
                                if ($ex->isExento("")) {
                                    $pdf->Cell(20,1,utf8_decode($montoCancelado),0,0,'L');
                                    $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                                    $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                                    $totalSinIvaEx = $totalSinIvaEx + $sinIva;
                                    $totalConIvaEx = $totalConIvaEx + $montoCancelado;
                                }

                                else {
                                    $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                                    $pdf->Cell(20,1,utf8_decode(number_format($sinIva,2)),0,0,'L');
                                    $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                                    $totalSinIva = $totalSinIva +$sinIva;
                                    $totalConIva = $totalConIva + $montoCancelado;
                                }

                                $totalSoloIva = $totalSoloIva + $totalIva;
                                $totalSoloCesc = $totalSoloCesc + doubleval($result["totalImp"]);
                                //$pdf->Cell(20,1,utf8_decode($montoCancelado),0,1,'L');
                                $pdf->Cell(15,1,utf8_decode(number_format($montoCancelado,2)),0,0,'L');
                                $pdf->Cell(15,1,utf8_decode(number_format($result["totalImp"],2)),0,0,'L');
                                $pdf->Cell(15,1,utf8_decode(number_format(doubleval($montoCancelado)+doubleval($result["totalImp"]),2)),0,0,'L');
                                $pdf->Ln(4);
                            }
                }
                elseif ($tipoFacturaGenerar == 2) {
                    $sql = "SELECT SUM(montoCable) as totalCable, SUM(montoInternet) as totalInter, SUM(impuesto) as totalImp, MIN(numeroComprobante) as inFact, MAX(numeroComprobante) as finFact, DAY(fechaComprobante) as dia FROM tbl_ventas_manuales
                            WHERE DAY(fechaComprobante) =".$counter." AND MONTH(fechaComprobante)=".$mesGenerar." AND YEAR(fechaComprobante)=".$anoGenerar." AND tipoComprobante = 2";

                    $stmt = $mysqli->query($sql);

                    while ($result = $stmt->fetch_assoc()) {
                        $montoCancelado = doubleval($result["totalCable"]) + doubleval($result["totalInter"]);
                        //IVA
                        $separado = (floatval($montoCancelado)/(1 + floatval($iva)));
                        //var_dump($separado);
                        $totalIva = substr(floatval($separado) * floatval($iva),0,7);

                        $pdf->Cell(5,1,utf8_decode($counter),0,0,'L');
                        $pdf->Cell(30,1,utf8_decode($result["inFact"]),0,0,'L');
                        $pdf->Cell(30,1,utf8_decode($result["finFact"]),0,0,'L');
                        $pdf->Cell(35,1,utf8_decode(""),0,0,'L');
                        $sinIva = doubleval($montoCancelado)-doubleval($totalIva);
                        if ($ex->isExento("")) {
                            $pdf->Cell(20,1,utf8_decode(number_format($montoCancelado,2)),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $totalSinIvaEx = $totalSinIvaEx + $sinIva;
                            $totalConIvaEx = $totalConIvaEx + $montoCancelado;
                        }

                        else {
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode(number_format($montoCancelado,2)),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $totalSinIva = $totalSinIva +$sinIva;
                            $totalConIva = $totalConIva + $montoCancelado;
                        }

                        $totalSoloIva = $totalSoloIva + $totalIva;
                        $totalSoloCesc = $totalSoloCesc + doubleval($result["totalImp"]);
                        //$pdf->Cell(20,1,utf8_decode($montoCancelado),0,1,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format($montoCancelado,2)),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format($result["totalImp"],2)),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format(doubleval($montoCancelado)+doubleval($result["totalImp"]),2)),0,0,'L');
                        $pdf->Ln(4);
                    }
                }
                elseif ($tipoFacturaGenerar == 3) {
                    $sql = "SELECT SUM(totalComprobante) as totalComprobante, MIN(numeroComprobante) as inFact, MAX(numeroComprobante) as finFact, DAY(fechaComprobante) as dia FROM tbl_ventas_anuladas
                            WHERE DAY(fechaComprobante) =".$counter." AND MONTH(fechaComprobante)=".$mesGenerar." AND YEAR(fechaComprobante)=".$anoGenerar." AND tipoComprobante = 2";

                    $stmt = $mysqli->query($sql);

                    while ($result = $stmt->fetch_assoc()) {
                        if (doubleval($result["totalComprobante"]) > 0 && is_numeric(doubleval($result["totalComprobante"]))) {
                            $montoCancelado = doubleval($result["totalComprobante"]);
                        }else {
                            $montoCancelado = 0;
                        }

                        //IVA
                        $totalCesc = substr(((doubleval($montoCancelado)/(1 + floatval($iva)))*$cesc),0,7);
                        //IVA
                        $separado = (doubleval($montoCancelado)/(1 + doubleval($iva)));
                        $totalIva = (doubleval($separado) * doubleval($iva));



                        $pdf->Cell(5,1,utf8_decode($counter),0,0,'L');
                        $pdf->Cell(30,1,utf8_decode($result["inFact"]),0,0,'L');
                        $pdf->Cell(30,1,utf8_decode($result["finFact"]),0,0,'L');
                        $pdf->Cell(35,1,utf8_decode(""),0,0,'L');
                        $sinIva = doubleval($montoCancelado)-doubleval($totalIva);
                        if ($ex->isExento("")) {
                            $pdf->Cell(20,1,utf8_decode(number_format($montoCancelado,2)),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $totalSinIvaEx = $totalSinIvaEx + $sinIva;
                            $totalConIvaEx = $totalConIvaEx + $montoCancelado;
                        }

                        else {
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode(number_format($montoCancelado,2)),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $totalSinIva = $totalSinIva +$sinIva;
                            $totalConIva = $totalConIva + $montoCancelado;
                        }

                        $totalSoloIva = $totalSoloIva + $totalIva;
                        $totalSoloCesc = $totalSoloCesc + $totalCesc;
                        //$pdf->Cell(20,1,utf8_decode($montoCancelado),0,1,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format($montoCancelado,2)),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format($totalCesc,2)),0,0,'L');
                        $pdf->Cell(15,1,utf8_decode(number_format(doubleval($montoCancelado + $totalCesc),2)),0,0,'L');
                        $pdf->Ln(4);
                    }
                }
                elseif ($tipoFacturaGenerar == 4) {
                    $sql = "SELECT
                            (SELECT SUM(cuotaCable) from tbl_cargos where tipoServicio='C' and DAY(fechaFactura) =".$counter." AND MONTH(fechaFactura)=".$mesGenerar." AND YEAR(fechaFactura)=".$anoGenerar." AND tipoFactura = 2 AND anulada=0) as totalCuotaCable,
                            (SELECT SUM(cuotaInternet) from tbl_cargos where tipoServicio='I' and DAY(fechaFactura) =".$counter." AND MONTH(fechaFactura)=".$mesGenerar." AND YEAR(fechaFactura)=".$anoGenerar." AND tipoFactura = 2 AND anulada=0) as totalCuotaInter, SUM(totalImpuesto) as totalImp, MIN(numeroFactura) as inFact, MAX(numeroFactura) as finFact, DAY(fechaFactura) as dia FROM tbl_cargos
                            WHERE DAY(fechaFactura) =".$counter." AND MONTH(fechaFactura)=".$mesGenerar." AND YEAR(fechaFactura)=".$anoGenerar." AND tipoFactura = 2 AND anulada=0";

                            $stmt = $mysqli->query($sql);
                            //var_dump($stmt);

                            while ($result = $stmt->fetch_assoc()) {
                                $montoCancelado1 = doubleval($result["totalCuotaCable"]) + doubleval($result["totalCuotaInter"]);
                                //IVA
                                $separado1 = (floatval($montoCancelado1)/(1 + floatval($iva)));
                                //var_dump($separado);
                                $totalIva1 = substr(floatval($separado1) * floatval($iva),0,7);

                                $pdf->Cell(5,1,utf8_decode($counter),0,0,'L');
                                $pdf->Cell(30,1,utf8_decode($result["inFact"]),0,0,'L');
                                $pdf->Cell(30,1,utf8_decode($result["finFact"]),0,0,'L');
                                $pdf->Cell(35,1,utf8_decode(""),0,0,'L');
                                $sinIva1 = doubleval($montoCancelado1)-doubleval($totalIva1);
                                if ($ex->isExento("")) {
                                    $pdf->Cell(20,1,utf8_decode($montoCancelado1),0,0,'L');
                                    $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                                    $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                                    $totalSinIvaEx1 = $totalSinIvaEx1 + $sinIva1;
                                    $totalConIvaEx1 = $totalConIvaEx1 + $montoCancelado1;
                                }

                                else {
                                    $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                                    $pdf->Cell(20,1,utf8_decode(number_format($sinIva1,2)),0,0,'L');
                                    $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                                    $totalSinIva1 = $totalSinIva1 +$sinIva1;
                                    $totalConIva1 = $totalConIva1 + $montoCancelado1;
                                }

                                $totalSoloIva1 = $totalSoloIva1 + $totalIva1;
                                $totalSoloCesc1 = $totalSoloCesc1 + doubleval($result["totalImp"]);
                                //$pdf->Cell(20,1,utf8_decode($montoCancelado),0,1,'L');
                                $pdf->Cell(15,1,utf8_decode(number_format($montoCancelado1,2)),0,0,'L');
                                $pdf->Cell(15,1,utf8_decode(number_format($result["totalImp"],2)),0,0,'L');
                                $pdf->Cell(15,1,utf8_decode(number_format(doubleval($montoCancelado1)+doubleval($result["totalImp"]),2)),0,0,'L');
                                $pdf->Ln(4);
                            }

                            //ACÁ COMIENZA EL NUMERO 2
                            $sql = "SELECT SUM(montoCable) as totalCable, SUM(montoInternet) as totalInter, SUM(impuesto) as totalImp, MIN(numeroComprobante) as inFact, MAX(numeroComprobante) as finFact, DAY(fechaComprobante) as dia FROM tbl_ventas_manuales
                                    WHERE DAY(fechaComprobante) =".$counter." AND MONTH(fechaComprobante)=".$mesGenerar." AND YEAR(fechaComprobante)=".$anoGenerar." AND tipoComprobante = 2";

                            $stmt = $mysqli->query($sql);

                            while ($result = $stmt->fetch_assoc()) {
                                $montoCancelado2 = doubleval($result["totalCable"]) + doubleval($result["totalInter"]);
                                //IVA
                                $separado2 = (floatval($montoCancelado2)/(1 + floatval($iva)));
                                //var_dump($separado);
                                $totalIva2 = substr(floatval($separado2) * floatval($iva),0,7);

                                /*$pdf->Cell(5,1,utf8_decode($counter),0,0,'L');
                                $pdf->Cell(30,1,utf8_decode($result["inFact"]),0,0,'L');
                                $pdf->Cell(30,1,utf8_decode($result["finFact"]),0,0,'L');
                                $pdf->Cell(35,1,utf8_decode(""),0,0,'L');*/
                                $sinIva2 = doubleval($montoCancelado2)-doubleval($totalIva2);
                                if ($ex->isExento("")) {
                                    /*$pdf->Cell(20,1,utf8_decode(number_format($montoCancelado,2)),0,0,'L');
                                    $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                                    $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');*/
                                    $totalSinIvaEx2 = $totalSinIvaEx2 + $sinIva2;
                                    $totalConIvaEx2 = $totalConIvaEx2 + $montoCancelado2;
                                }

                                else {
                                    /*$pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                                    $pdf->Cell(20,1,utf8_decode(number_format($montoCancelado2,2)),0,0,'L');
                                    $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');*/
                                    $totalSinIva2 = $totalSinIva2 +$sinIva2;
                                    $totalConIva2 = $totalConIva2 + $montoCancelado2;
                                }

                                $totalSoloIva2 = $totalSoloIva2 + $totalIva2;
                                $totalSoloCesc2 = $totalSoloCesc2 + doubleval($result["totalImp"]);
                                //$pdf->Cell(20,1,utf8_decode($montoCancelado),0,1,'L');
                                /*$pdf->Cell(15,1,utf8_decode(number_format($montoCancelado2,2)),0,0,'L');
                                $pdf->Cell(15,1,utf8_decode(number_format($result["totalImp"],2)),0,0,'L');
                                $pdf->Cell(15,1,utf8_decode(number_format(doubleval($montoCancelado2)+doubleval($result["totalImp"]),2)),0,0,'L');*/
                                //$pdf->Ln(4);
                            }

                            //COMIENZO NUMERO 3
                            $sql = "SELECT SUM(totalComprobante) as totalComprobante, MIN(numeroComprobante) as inFact, MAX(numeroComprobante) as finFact, DAY(fechaComprobante) as dia FROM tbl_ventas_anuladas
                                    WHERE DAY(fechaComprobante) =".$counter." AND MONTH(fechaComprobante)=".$mesGenerar." AND YEAR(fechaComprobante)=".$anoGenerar." AND tipoComprobante = 2";

                            $stmt = $mysqli->query($sql);

                            while ($result = $stmt->fetch_assoc()) {
                                if (doubleval($result["totalComprobante"]) > 0 && is_numeric(doubleval($result["totalComprobante"]))) {
                                    $montoCancelado3 = doubleval($result["totalComprobante"]);
                                }else {
                                    $montoCancelado3 = 0;
                                }

                                //IVA
                                $separado3 = (floatval($montoCancelado3)/(1 + floatval($iva)));
                                //var_dump($separado);
                                $totalIva3 = (doubleval($separado3) * doubleval($iva));
                                $totalCesc3 = $totalCesc = substr((($montoCancelado3/(1 + floatval($iva)))*$cesc),0,7);

                                /*$pdf->Cell(5,1,utf8_decode($counter),0,0,'L');
                                $pdf->Cell(30,1,utf8_decode($result["inFact"]),0,0,'L');
                                $pdf->Cell(30,1,utf8_decode($result["finFact"]),0,0,'L');
                                $pdf->Cell(35,1,utf8_decode(""),0,0,'L');*/
                                $sinIva3 = doubleval($montoCancelado3)-doubleval($totalIva3);
                                if ($ex->isExento("")) {
                                    /*$pdf->Cell(20,1,utf8_decode(number_format($montoCancelado3,2)),0,0,'L');
                                    $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                                    $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');*/
                                    $totalSinIvaEx3 = $totalSinIvaEx3 + $sinIva3;
                                    $totalConIvaEx3 = $totalConIvaEx3 + $montoCancelado3;
                                }

                                else {
                                    /*$pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                                    $pdf->Cell(20,1,utf8_decode(number_format($montoCancelado3,2)),0,0,'L');
                                    $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');*/
                                    $totalSinIva3 = $totalSinIva3 +$sinIva3;
                                    $totalConIva3 = $totalConIva3 + $montoCancelado3;
                                }

                                $totalSoloIva3 = $totalSoloIva3 + $totalIva3;
                                $totalSoloCesc3 = $totalSoloCesc3 + $totalCesc3;
                                //$pdf->Cell(20,1,utf8_decode($montoCancelado),0,1,'L');
                                /*$pdf->Cell(15,1,utf8_decode(number_format($montoCancelado3,2)),0,0,'L');
                                $pdf->Cell(15,1,utf8_decode(number_format($totalCesc3,2)),0,0,'L');
                                $pdf->Cell(15,1,utf8_decode(number_format(doubleval($montoCancelado3),2)),0,0,'L');*/
                                //$pdf->Ln(4);
                            }
                }

                $counter++;
            }

            // DESPUES DE TODOS LOS CICLOS
            /*$pdf->AddPage('P','Letter');

            $pdf->SetFont('Times','B',10);
            $pdf->Cell(190,6,utf8_decode('LIBRO O REGISTRO DE OPERACIONES CON EMISION DE FACTURAS'),0,0,'C');
            $pdf->Ln(5);
            $pdf->SetFont('Times','B',8);
            $pdf->Cell(190,6,utf8_decode('VALORES EXPRESADOS EN US DOLARES'),0,0,'C');
            $pdf->Ln(10);

            $pdf->SetFont('Times','B',10);
            //FILA 1
            $pdf->Cell(25,6,utf8_decode(''),"TLR",0,'C');
            $pdf->SetFont('Times','B',5);
            $pdf->Cell(15,6,utf8_decode('N° DE'),"T",0,'C');
            $pdf->SetFont('Times','B',10);
            $pdf->Cell(60,6,utf8_decode(''),"TLR",0,'C');
            $pdf->Cell(60,6,utf8_decode('VENTAS'),1,0,'C');
            $pdf->Cell(40,6,utf8_decode(''),"TLR",1,'C');

            //FILA 2
            $pdf->Cell(25,6,utf8_decode(''),"LR",0,'C');
            $pdf->SetFont('Times','B',5);
            $pdf->Cell(15,6,utf8_decode('FORMULARIO'),"LR",0,'C');
            $pdf->SetFont('Times','B',10);
            $pdf->Cell(60,6,utf8_decode(''),"LR",0,'C');
            $pdf->Cell(20,6,utf8_decode(''),"LRT",0,'C');
            $pdf->Cell(40,6,utf8_decode('GRAVADAS'),1,0,'C');
            $pdf->Cell(40,6,utf8_decode(''),"BLR",1,'C');

            //FILA 3
            $pdf->SetFont('Times','B',5);
            $pdf->Cell(5,6,utf8_decode('DIA'),1,0,'C');
            $pdf->Cell(20,6,utf8_decode('N° DE FACTURA'),1,0,'C');
            $pdf->Cell(15,6,utf8_decode('UNICO'),"BLR",0,'C');
            $pdf->SetFont('Times','B',7);
            $pdf->Cell(60,6,utf8_decode('NOMBRE DEL CLIENTE'),1,0,'C');
            $pdf->Cell(20,6,utf8_decode('EXENTAS'),"BLR",0,'C');
            $pdf->Cell(20,6,utf8_decode('LOCALES'),1,0,'C');
            $pdf->Cell(20,6,utf8_decode('EXPORTACION'),1,0,'C');
            $pdf->SetFont('Times','B',5);
            $pdf->Cell(15,6,utf8_decode('SUB-TOTAL'),1,0,'C');
            $pdf->Cell(10,6,utf8_decode('CESC'),1,0,'C');
            $pdf->SetFont('Times','B',4);
            $pdf->Cell(15,6,utf8_decode('VENTAS TOTALES'),1,1,'C');
            */
            $pdf->Ln(5);

            $pdf->SetFont('Times','B',7);
            /*$pdf->Cell(40,6,utf8_decode(''),0,0,'C');
            $pdf->Cell(60,6,utf8_decode('TOTALES DEL MES'),"T",0,'C');
            $pdf->Cell(20,6,utf8_decode(number_format($totalSinIvaEx,2)),"T",0,'C');
            $pdf->Cell(20,6,utf8_decode(number_format($totalSinIva,2)),"T",0,'C');
            $pdf->Cell(20,6,utf8_decode('0.00'),"T",0,'C');
            $pdf->Cell(15,6,utf8_decode(number_format($totalConIva,2)),"T",0,'C');
            $pdf->Cell(10,6,utf8_decode(''/*number_format($totalSoloCesc,2)),"T",0,'C');
            $pdf->Cell(15,6,utf8_decode(number_format(($totalConIva + $totalSoloCesc),2)),"T",0,'C');*/

            $pdf->Ln(10);
            $pdf->SetFont('Times','B',10);
            $pdf->Cell(40,6,utf8_decode('RESUMEN'),0,0,'L');
            $pdf->Cell(45,6,utf8_decode('FACTURAS GENERADAS'),0,0,'L');
            $pdf->Cell(45,6,utf8_decode('FACTURAS MANUALES'),0,0,'L');
            $pdf->Cell(45,6,utf8_decode('FACTURAS ANULADAS'),0,0,'L');
            $pdf->Cell(40,6,utf8_decode('TOTALES'),0,1,'L');
            $pdf->Cell(200,1,utf8_decode(''),"T",1,'L');
            if ($tipoFacturaGenerar == 1) {
                $pdf->Cell(40,6,utf8_decode('Ventas exentas'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalConIvaEx,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format($totalConIvaEx,2)),0,1,'L');

                $pdf->Cell(40,6,utf8_decode('Ventas netas gravadas'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSinIva,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSinIva,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('13% de IVA'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloIva,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloIva,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('5% CESC'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloCesc,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloCesc,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('Exportaciones'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,1,'L');

                $pdf->Cell(200,1,utf8_decode(''),"T",1,'L');

                $pdf->Cell(40,6,utf8_decode(""),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(($totalConIvaEx + $totalSinIva + $totalSoloIva + $totalSoloCesc),2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format(($totalConIvaEx + $totalSinIva + $totalSoloIva + $totalSoloCesc),2)),0,1,'L');

                $pdf->Ln(20);
                $pdf->Cell(70,3,utf8_decode(''),"T",1,'C');
                $pdf->Cell(40,1,utf8_decode("Nombre y firma del contador:"),"",0,'L');
            }elseif ($tipoFacturaGenerar == 2) {
                $pdf->Cell(40,6,utf8_decode('Ventas exentas'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalConIvaEx,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format($totalConIvaEx,2)),0,1,'L');

                $pdf->Cell(40,6,utf8_decode('Ventas netas gravadas'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSinIva,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSinIva,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('13% de IVA'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloIva,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloIva,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('5% CESC'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloCesc,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloCesc,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('Exportaciones'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,1,'L');

                $pdf->Cell(200,1,utf8_decode(''),"T",1,'L');

                $pdf->Cell(40,6,utf8_decode(""),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(($totalConIvaEx + $totalSinIva + $totalSoloIva + $totalSoloCesc),2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format(($totalConIvaEx + $totalSinIva + $totalSoloIva + $totalSoloCesc),2)),0,1,'L');

                $pdf->Ln(20);
                $pdf->Cell(70,3,utf8_decode(''),"T",1,'C');
                $pdf->Cell(40,1,utf8_decode("Nombre y firma del contador:"),"",0,'L');
            }elseif ($tipoFacturaGenerar == 3) {
                $pdf->Cell(40,6,utf8_decode('Ventas exentas'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalConIvaEx,2)),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format($totalConIvaEx,2)),0,1,'L');

                $pdf->Cell(40,6,utf8_decode('Ventas netas gravadas'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSinIva,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSinIva,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('13% de IVA'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloIva,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloIva,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('5% CESC'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloCesc,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloCesc,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('Exportaciones'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,1,'L');

                $pdf->Cell(200,1,utf8_decode(''),"T",1,'L');

                $pdf->Cell(40,6,utf8_decode(""),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(($totalConIvaEx + $totalSinIva + $totalSoloIva + $totalSoloCesc),2)),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format(($totalConIvaEx + $totalSinIva + $totalSoloIva + $totalSoloCesc),2)),0,1,'L');

                $pdf->Ln(20);
                $pdf->Cell(70,3,utf8_decode(''),"T",1,'C');
                $pdf->Cell(40,1,utf8_decode("Nombre y firma del contador:"),"",0,'L');
            }elseif ($tipoFacturaGenerar == 4) {
                $pdf->Cell(40,6,utf8_decode('Ventas exentas'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalConIvaEx1,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalConIvaEx2,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalConIvaEx3,2)),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format($totalConIvaEx1+$totalConIvaEx2+$totalConIvaEx3,2)),0,1,'L');

                $pdf->Cell(40,6,utf8_decode('Ventas netas gravadas'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSinIva1,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSinIva2,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSinIva3,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSinIva1+$totalSinIva2-$totalSinIva3,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('13% de IVA'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloIva1,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloIva2,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloIva3,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloIva1+$totalSoloIva2-$totalSoloIva3,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('5% CESC'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloCesc1,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloCesc2,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloCesc3,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format($totalSoloCesc1+$totalSoloCesc2-$totalSoloCesc3,2)),0,1,'L');
                $pdf->Cell(40,6,utf8_decode('Exportaciones'),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,1,'L');

                $pdf->Cell(200,1,utf8_decode(''),"T",1,'L');

                $pdf->Cell(40,6,utf8_decode(""),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(($totalConIvaEx1 + $totalSinIva1 + $totalSoloIva1 + $totalSoloCesc1),2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(($totalConIvaEx2 + $totalSinIva2 + $totalSoloIva2 + $totalSoloCesc2),2)),0,0,'L');
                $pdf->Cell(45,6,utf8_decode(number_format(($totalConIvaEx3 + $totalSinIva3 + $totalSoloIva3 + $totalSoloCesc3),2)),0,0,'L');
                $pdf->Cell(40,6,utf8_decode(number_format(($totalConIvaEx1 + $totalSinIva1 + $totalSoloIva1 + $totalSoloCesc1 + $totalConIvaEx2 + $totalSinIva2 + $totalSoloIva2 + $totalSoloCesc2 - $totalConIvaEx3 - $totalSinIva3 - $totalSoloIva3 - $totalSoloCesc3),2)),0,1,'L');

                $pdf->Ln(20);
                $pdf->Cell(70,3,utf8_decode(''),"T",1,'C');
                $pdf->Cell(40,1,utf8_decode("Nombre y firma del contador:"),"",0,'L');
            }
            /*$pdf->Cell(40,6,utf8_decode('Ventas exentas'),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
            $pdf->Cell(40,6,utf8_decode(number_format($totalConIvaEx,2)),0,1,'L');

            $pdf->Cell(40,6,utf8_decode('Ventas netas gravadas'),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format($totalSinIva,2)),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format($totalSinIva,2)),0,1,'L');
            $pdf->Cell(40,6,utf8_decode('13% de IVA'),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format($totalSoloIva,2)),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format($totalSoloIva,2)),0,1,'L');
            $pdf->Cell(40,6,utf8_decode('5% CESC'),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format($totalSoloCesc,2)),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format($totalSoloCesc,2)),0,1,'L');
            $pdf->Cell(40,6,utf8_decode('Exportaciones'),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,1,'L');

            $pdf->Cell(200,1,utf8_decode(''),"T",1,'L');

            $pdf->Cell(40,6,utf8_decode(""),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format(($totalConIvaEx + $totalSinIva + $totalSoloIva + $totalSoloCesc),2)),0,0,'L');
            $pdf->Cell(45,6,utf8_decode(number_format(0,2)),0,0,'L');
            $pdf->Cell(40,6,utf8_decode(number_format(($totalConIvaEx + $totalSinIva + $totalSoloIva + $totalSoloCesc),2)),0,1,'L');*/
        }

	  /* close connection */
	  mysqli_close($mysqli);
	  $pdf->Output();

  }

  libroConsumidorFinal();

?>
