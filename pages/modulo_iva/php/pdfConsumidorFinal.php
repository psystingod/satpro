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

  $totalSinIva=0;
  $totalConIva=0;
  $totalSinIvaEx=0;
  $totalConIvaEx=0;
  $totalSoloIva=0;
  $totalSoloCesc=0;

  function f3(){
	  global $mysqli, $mesGenerar, $anoGenerar, $tipoFacturaGenerar, $encabezados, $numPag, $libroDetallado, $ex, $iva;
      global $totalSinIva,$totalConIva,$totalSinIvaEx,$totalConIvaEx,$totalSoloIva,$totalSoloCesc;
	  $pdf = new FPDF();
	  $pdf->AliasNbPages();
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
      /*********************************COMIENZO DE LA MASACRE***********************************/
        if ($libroDetallado == 1) {
            $counter = 1;
            while ($counter <= 31) {
                if ($tipoFacturaGenerar = 1) {
                    $sql = "SELECT * FROM tbl_cargos WHERE DAY(fechaFactura) =".$counter." AND MONTH(fechaFactura)=".$mesGenerar." AND YEAR(fechaFactura)=".$anoGenerar." AND tipoFactura = 2";
                }
                elseif ($tipoFacturaGenerar = 2) {
                    // code...
                }
                elseif ($tipoFacturaGenerar = 3) {
                    // code...
                }
                elseif ($tipoFacturaGenerar = 4) {
                    // code...
                }

                $stmt = $mysqli->query($sql);
                //var_dump($stmt);

                while ($result = $stmt->fetch_assoc()) {
                    if ($result["tipoServicio"] == "C") {
                        $montoCancelado = doubleval($result["cuotaCable"]);
                    }elseif ($result["tipoServicio"] == "I") {
                        $montoCancelado = doubleval($result["cuotaInternet"]);
                    }
                    //IVA
                    $separado = (floatval($montoCancelado)/(1 + floatval($iva)));
                    //var_dump($separado);
                    $totalIva = round((floatval($separado) * floatval($iva)),2);

                    $pdf->Cell(5,1,utf8_decode(date("d", strtotime($result['fechaFactura']))),0,0,'L');
                    $pdf->Cell(20,1,utf8_decode($result['numeroFactura']),0,0,'L');
                    $pdf->Cell(15,1,utf8_decode(0),0,0,'L');
                    $pdf->Cell(60,1,utf8_decode($result["nombre"]),0,0,'L');
                    $sinIva = doubleval($montoCancelado)-doubleval($totalIva);
                    if ($ex->isExento($result["codigoCliente"])) {
                        $pdf->Cell(20,1,utf8_decode($montoCancelado),0,0,'L');
                        $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                        $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                        $totalSinIvaEx = $totalSinIvaEx + $sinIva;
                        $totalConIvaEx = $totalConIvaEx + $montoCancelado;
                    }

                    else {
                        $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                        $pdf->Cell(20,1,utf8_decode($sinIva),0,0,'L');
                        $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                        $totalSinIva = $totalSinIva +$sinIva;
                        $totalConIva = $totalConIva + $montoCancelado;
                    }

                    $totalSoloIva = $totalSoloIva + $totalIva;
                    $totalSoloCesc = $totalSoloCesc + doubleval($result["totalImpuesto"]);
                    //$pdf->Cell(20,1,utf8_decode($montoCancelado),0,1,'L');
                    $pdf->Cell(15,1,utf8_decode($montoCancelado),0,0,'L');
                    $pdf->Cell(15,1,utf8_decode($result["totalImpuesto"]),0,0,'L');
                    $pdf->Cell(15,1,utf8_decode(number_format(doubleval($montoCancelado)+doubleval($result["totalImpuesto"]),2)),0,0,'L');
                    $pdf->Ln(4);
                }
                $counter++;
            }

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
            $pdf->Cell(20,6,utf8_decode('EXPORTACION'),1,0,'C');
            $pdf->SetFont('Times','B',5);
            $pdf->Cell(15,6,utf8_decode('SUB-TOTAL'),1,0,'C');
            $pdf->Cell(10,6,utf8_decode('CESC'),1,0,'C');
            $pdf->SetFont('Times','B',4);
            $pdf->Cell(15,6,utf8_decode('VENTAS TOTALES'),1,1,'C');

            $pdf->Ln(5);
            $pdf->SetFont('Times','B',7);
            $pdf->Cell(40,6,utf8_decode(''),0,0,'C');
            $pdf->Cell(60,6,utf8_decode('TOTALES DEL MES'),"T",0,'C');
            $pdf->Cell(20,6,utf8_decode(number_format($totalSinIvaEx,2)),"T",0,'C');
            $pdf->Cell(20,6,utf8_decode(number_format($totalSinIva,2)),"T",0,'C');
            $pdf->Cell(20,6,utf8_decode('0.00'),"T",0,'C');
            $pdf->Cell(15,6,utf8_decode(number_format($totalConIva,2)),"T",0,'C');
            $pdf->Cell(10,6,utf8_decode(number_format($totalSoloCesc,2)),"T",0,'C');
            $pdf->Cell(15,6,utf8_decode(number_format(($totalConIva + $totalSoloCesc),2)),"T",0,'C');

            $pdf->Ln(10);
            $pdf->SetFont('Times','B',10);
            $pdf->Cell(40,6,utf8_decode('RESUMEN'),0,1,'L');
            $pdf->Cell(40,6,utf8_decode('Ventas exentas'),0,0,'L');
            $pdf->Cell(40,6,utf8_decode(number_format($totalConIvaEx,2)),0,1,'L');
            $pdf->Cell(40,6,utf8_decode('Ventas netas gravadas'),0,0,'L');
            $pdf->Cell(40,6,utf8_decode(number_format($totalConIva,2)),0,1,'L');
            $pdf->Cell(40,6,utf8_decode('13% de IVA'),0,0,'L');
            $pdf->Cell(40,6,utf8_decode(number_format($totalSoloIva,2)),0,1,'L');
            $pdf->Cell(40,6,utf8_decode('5% CESC'),0,0,'L');
            $pdf->Cell(40,6,utf8_decode(number_format($totalSoloCesc,2)),0,1,'L');
            $pdf->Cell(40,6,utf8_decode('Exportaciones'),0,0,'L');
            $pdf->Cell(40,6,utf8_decode('0.00'),0,1,'L');
        }
        elseif ($libroDetallado == null) {
            // code...
        }
	  /*while($row = $resultado->fetch_assoc())
	  {

	    $imp = substr((($row['cuota_in']/(1 + floatval($iva)))*$cesc),0,4);
  	    $imp = str_replace(',','.', $imp);
  	    //var_dump($imp);

  	    $cantidad = doubleval((doubleval($row['cuota_in']) + doubleval($imp))*doubleval($row['periodo_contrato_int']));
		$pdf->SetFont('Arial','B',12);
		$pdf->MultiCell(190,6,utf8_decode('TOTAL: $'.number_format($cantidad,2)),0,'L',0);
		$numeroALetras = NumerosEnLetras::convertir(number_format($cantidad,2), 'dólares', false, 'centavos');
		$pdf->SetFont('Arial','',12);
		$pdf->MultiCell(190,6,utf8_decode('Por este PAGARÉ me obligo a pagar incondicionalmente a la sociedad CABLE SAT, S.A. DE C.V., la cantidad de '.$numeroALetras.' Dólares de Estados Unidos de América, reconociendo en caso de mora el interés del 4% anual'),0,'L',0);
		$pdf->Ln();
		$pdf->MultiCell(190,6,utf8_decode('La cantidad antes mencionada se pagará en esta ciudad, en las oficinas administrativas de la sociedad acreedora, el día _____ de ______________________ del año ___________ En caso de acción judicial, señalo la ciudad de Usulután como domicilio especial en caso de ejecución; siendo a mi cargo, cualquier gasto que la sociedad acreedora antes mencionada hiciere en el cobro de la presente obligación, inclusive los llamados personales, y faculto a la sociedad acreedora para que designe al depositario judicial de los bienes que se embarguen, a quien relevo de la obligación de rendir fianza.'),0,'L',0);
		$pdf->Ln(3);

		$pdf->Cell(190,6,utf8_decode('Nombre del cliente: '.strtoupper($row['nombre'])),0,1,'L');

		$pdf->Ln(20);

		$pdf->Cell(190,6,utf8_decode('Firma: _______________________________'),0,1,'L');
		$pdf->Cell(190,6,utf8_decode('NIT: '.$row['numero_nit']),0,1,'L');
		$pdf->Cell(190,6,utf8_decode('DUI: '.$row['numero_dui']),0,1,'L');
    }*/

	  /* close connection */
	  mysqli_close($mysqli);
	  $pdf->Output();

  }

  f3();

?>
