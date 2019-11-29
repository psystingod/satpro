<?php
	require '../../../pdfs/fpdf.php';
  require_once("../../../php/config.php");

  $host = DB_HOST;
  $user = DB_USER;
  $password = DB_PASSWORD;
  $database = DB_NAME;
  $mysqli = new mysqli($host, $user, $password, $database);

  session_start();
  $codigo = $_GET['id'];

  $query = "SELECT nombre, numero_dui, numero_nit FROM clientes WHERE cod_cliente = ".$codigo;
	$resultado = $mysqli->query($query);
  $cantidad = 50;

	$pdf = new FPDF();
	$pdf->AliasNbPages();
	$pdf->AddPage('P','Letter');

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
    $pdf->SetFont('Arial','',12);
    $pdf->MultiCell(190,6,utf8_decode('Por este PAGARÉ me obligo a pagar incondicionalmente a la sociedad CABLE SAT, S.A. DE C.V., la cantidad de '.$cantidad.' Dólares de Estados Unidos de América, reconociendo en caso de mora el interés del 4% anual'),0,'L',0);
    $pdf->Ln();
    $pdf->MultiCell(190,6,utf8_decode('La cantidad antes mencionada se pagará en esta ciudad, en las oficinas administrativas de la sociedad acreedora, el día _____ de ______________________ del año ___________ En caso de acción judicial, señalo la ciudad de Usulután como domicilio especial en caso de ejecución; siendo a mi cargo, cualquier gasto que la sociedad acreedora antes mencionada hiciere en el cobro de la presente obligación, inclusive los llamados personales, y faculto a la sociedad acreedora para que designe al depositario judicial de los bienes que se embarguen, a quien relevo de la obligación de rendir fianza.'),0,'L',0);
    $pdf->Ln(3);

    $pdf->Cell(190,6,utf8_decode('Nombre del cliente: '.strtoupper($row['nombre'])),0,1,'L');

    $pdf->Ln(20);

    $pdf->Cell(190,6,utf8_decode('Firma: _______________________________'),0,1,'L');
    $pdf->Cell(190,6,utf8_decode('NIT: '.$row['numero_nit']),0,1,'L');
    $pdf->Cell(190,6,utf8_decode('DUI: '.$row['numero_dui']),0,1,'L');
	}
	$pdf->Output();
?>
