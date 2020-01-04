<?php
  require '../../../pdfs/fpdf.php';
  require_once("../../../php/config.php");
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

  //$codigo = $_GET['id'];
  $desde = $_POST['lDesde'];
  $hasta = $_POST['lHasta'];
  $codigoCobrador = $_POST['lCobrador'];
  $colonia = $_POST['lColonia'];
  $tipoServicio = $_POST['lServicio'];

  function abonos(){
	  global $desde, $hasta, $codigoCobrador, $colonia, $tipoServicio, $mysqli;

      //SQL para todas las zonas de cobro
	  $query = "SELECT * FROM tbl_abonos WHERE cobradoPor= ".$codigoCobrador." AND idColonia= ".$colonia." AND tipoServicio= ".$tipoServicio." AND fechaAbonado BETWEEN ".$desde." AND ".$hasta;
	  $resultado = $mysqli->query($query);

	  // SQL query para traer datos del servicio de cable de la tabla clientes
	  $query = "SELECT valorImpuesto FROM tbl_impuestos WHERE siglasImpuesto = 'CESC'";
	  // Preparación de sentencia
	  $statement = $mysqli->query($query);
	  //$statement->execute();
	  while ($result = $statement->fetch_assoc()) {
		  $cesc = floatval($result['valorImpuesto']);
	  }

	  // SQL query para traer datos del servicio de cable de la tabla clientes
	  $query = "SELECT valorImpuesto FROM tbl_impuestos WHERE siglasImpuesto = 'IVA'";
	  // Preparación de sentencia
	  $statement = $mysqli->query($query);
	  //$statement->execute();
	  while ($result = $statement->fetch_assoc()) {
		  $iva = floatval($result['valorImpuesto']);
	  }


	  $pdf = new FPDF();
	  $pdf->AliasNbPages();
	  $pdf->AddPage('P','Letter');
	  $pdf->Image('../../../images/logo.png',10,10, 26, 24);
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
	  $pdf->Ln();
	  while($row = $resultado->fetch_assoc())
	  {

	    $imp = substr((($row['cuota_in']/(1 + floatval($iva)))*$cesc),0,4);
  	    $imp = str_replace(',','.', $imp);
  	    //var_dump($imp);

  	    $cantidad = doubleval((doubleval($row['cuota_in']) + doubleval($imp))*doubleval($row['periodo_contrato_int']));
		$pdf->SetFont('Arial','B',12);
		$pdf->MultiCell(190,6,utf8_decode('TOTAL: $'.number_format($cantidad,2)),0,'L',0);
		$numeroALetras = NumerosEnLetras::convertir($cantidad, '', true);
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
	  }

	  /* close connection */
	  mysqli_close($mysqli);
	  $pdf->Output();

  }

  abonos();

?>
