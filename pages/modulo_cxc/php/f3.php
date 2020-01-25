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

  $codigo = $_GET['id'];
  $codigoCobrador = $_POST['codigoCobrador'];

  function f3(){
	  global $codigo, $mysqli;
	  $query = "SELECT nombre, numero_dui, numero_nit, cuota_in, periodo_contrato_int FROM clientes WHERE cod_cliente = ".$codigo;
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
	  $pdf->Cell(190,6,'F-3',0,1,'R');
	  $pdf->Ln();
	  $pdf->Cell(190,6,'PAGARÉ SIN PROTESTO',0,1,'C');
	  $pdf->Ln(10);

	  $pdf->SetFont('Arial','B',12);

	  date_default_timezone_set('America/El_Salvador');

	  //echo strftime("El año es %Y y el mes es %B");
      putenv("LANG='es_ES.UTF-8'");
      setlocale(LC_ALL, 'es_ES.UTF-8');
	  $pdf->SetFont('Arial','B',12);
	  $pdf->Cell(190,6,"Ciudad de Usulután, ".utf8_decode(strftime('%A %e de %B de %G')),0,1,'L');
	  $pdf->Ln();
	  while($row = $resultado->fetch_assoc())
	  {

	    $imp = substr((($row['cuota_in']/(1 + floatval($iva)))*$cesc),0,4);
  	    $imp = str_replace(',','.', $imp);
  	    //var_dump($imp);

  	    $cantidad = doubleval((doubleval($row['cuota_in']) + doubleval($imp))*doubleval($row['periodo_contrato_int']));
		$pdf->SetFont('Arial','B',12);
		$pdf->MultiCell(190,6,'TOTAL: $'.number_format($cantidad,2),0,'L',0);
		$numeroALetras = NumerosEnLetras::convertir(number_format($cantidad,2), 'dólares', false, 'centavos');
		$pdf->SetFont('Arial','',12);
		$pdf->MultiCell(190,6,'Por este PAGARÉ me obligo a pagar incondicionalmente a la sociedad CABLE SAT, S.A. DE C.V., la cantidad de '.$numeroALetras.' Dólares de Estados Unidos de América, reconociendo en caso de mora el interés del 4% anual',0,'L',0);
		$pdf->Ln();
		$pdf->MultiCell(190,6,'La cantidad antes mencionada se pagará en esta ciudad, en las oficinas administrativas de la sociedad acreedora, el día _____ de ______________________ del año ___________ En caso de acción judicial, señalo la ciudad de Usulután como domicilio especial en caso de ejecución; siendo a mi cargo, cualquier gasto que la sociedad acreedora antes mencionada hiciere en el cobro de la presente obligación, inclusive los llamados personales, y faculto a la sociedad acreedora para que designe al depositario judicial de los bienes que se embarguen, a quien relevo de la obligación de rendir fianza.',0,'L',0);
		$pdf->Ln(3);

		$pdf->Cell(190,6,'Nombre del cliente: '.strtoupper($row['nombre']),0,1,'L');

		$pdf->Ln(20);

		$pdf->Cell(190,6,'Firma: _______________________________',0,1,'L');
		$pdf->Cell(190,6,'NIT: '.$row['numero_nit'],0,1,'L');
		$pdf->Cell(190,6,'DUI: '.$row['numero_dui'],0,1,'L');
	  }

	  /* close connection */
	  mysqli_close($mysqli);
	  $pdf->Output();

  }

  f3();

?>
