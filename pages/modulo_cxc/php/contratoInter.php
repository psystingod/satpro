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

  function getVelocidadById($idVelocidad)
  {
      try {
          global $mysqli;
          // SQL query para traer nombre de las categorías
          $query = "SELECT nombreVelocidad FROM tbl_velocidades WHERE idVelocidad=".$idVelocidad;
          // Preparación de sentencia
          $statement = $mysqli->query($query);
          while ($result = $statement->fetch_assoc()) {
    		  $velocidad = $result['nombreVelocidad'];
    	  }

          return $velocidad;

      } catch (Exception $e) {
          print "!Error¡: " . $e->getMessage() . "</br>";
          die();
      }
  }

  function contratoInter(){
	  global $codigo, $mysqli;
	  $query = "SELECT cod_cliente, nombre, direccion, numero_dui, cuota_in, num_registro, telefonos, lugar_exp, nacionalidad, fecha_nacimiento, tipo_comprobante, id_tipo_cliente, lugar_trabajo, tel_trabajo, numero_nit, nombre_conyuge, valor_cuota, tipo_servicio, periodo_contrato_ca, periodo_contrato_int, costo_instalacion_in, mac_modem, serie_modem, id_velocidad, tecnologia, entrega_calidad FROM clientes WHERE cod_cliente = ".$codigo;
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
      $pdf->Ln(5);
	  $tecnologia = "";
	  while($row = $resultado->fetch_assoc())
	  {
          if ($row['tipo_comprobante'] == 2) {
              $pdf->Image('../../../images/check.png',33,15, 5, 5);
          }

          if ($row['tecnologia'] == 1) {
              $tecnologia = "DOCSIS";
          }
          elseif ($row['tecnologia'] == 2) {
              $tecnologia = "FTTH";
          }
          elseif ($row['tecnologia'] == 3) {
              $tecnologia = "INALAMBRICO";
          }

    	  $pdf->SetFont('Courier','',10);

		  $pdf->Ln(-2);
          $pdf->Cell(130,6,utf8_decode(''),0,0,'L');
    	  $pdf->Cell(80,6,utf8_decode($row['cod_cliente']),0,1,'L');
    	  $pdf->Ln(-2);

    	  date_default_timezone_set('America/El_Salvador');

    	  //echo strftime("El año es %Y y el mes es %B");
    	  setlocale(LC_ALL,"es_ES");
    	  $pdf->SetFont('Courier','',10);
    	  $pdf->Cell(25,6,utf8_decode(''),0,0,'L');
          $pdf->Cell(70,6,utf8_decode(strtoupper($row['nombre'])),0,1,'L');
          $pdf->Cell(23,6,utf8_decode(''),0,0,'L');
          $pdf->Cell(150,6,utf8_decode(''),0,0,'L');
          $pdf->Cell(40,6,utf8_decode(strtoupper($row['num_registro'])),0,1,'L');

          $pdf->Cell(80,6,utf8_decode(''),0,0,'L');
          $pdf->Cell(78,6,utf8_decode($row['numero_dui']),0,0,'L');
          $pdf->Cell(70,6,utf8_decode($row['lugar_exp']),0,1,'L');
          $pdf->Cell(12,6,utf8_decode(''),0,0,'L');
          $pdf->Cell(63,6,utf8_decode($row['numero_nit']),0,0,'L');
          $pdf->Cell(84,6,utf8_decode($row['telefonos']),0,0,'L');
          $pdf->Cell(40,6,utf8_decode($row['nacionalidad']),0,1,'L');
          //$pdf->Cell(75,6,utf8_decode($row['lugar_exp']),0,0,'L');
          //$pdf->Cell(30,6,utf8_decode($row['fecha_nacimiento']),0,1,'R');

          /*$pdf->Cell(40,6,utf8_decode(''),0,0,'L');
          $pdf->Cell(65,6,utf8_decode(''),0,0,'L');
          $pdf->Cell(48,6,utf8_decode($row['num_registro']),0,0,'L');
          $pdf->Cell(40,6,utf8_decode($row['telefonos']),0,1,'L');

          $pdf->Cell(40,6,utf8_decode(''),0,0,'L');
          $pdf->Cell(65,6,utf8_decode($row['lugar_trabajo']),0,0,'L');
          $pdf->Cell(48,6,utf8_decode(''),0,0,'L');
          $pdf->Cell(40,6,utf8_decode($row['tel_trabajo']),0,1,'R');
    	  $pdf->Ln(5);*/

          $pdf->Cell(94,6,utf8_decode(''),0,0,'L');
          $pdf->Cell(70,6,utf8_decode(strtoupper($row['nombre_conyuge'])),0,1,'L');
          $pdf->Ln(15);
          $pdf->Cell(30,6,utf8_decode(''),0,0,'L');
          $pdf->MultiCell(150,6,utf8_decode(strtoupper($row['direccion'])),0,'L',0);
          $pdf->Ln(1);
          $pdf->Cell(30,6,utf8_decode(''),0,0,'L');
          $pdf->MultiCell(150,6,utf8_decode(strtoupper($row['direccion'])),0,'L',0);


          $pdf->Ln(6);
          $pdf->Image('../../../images/check.png',58,91, 5, 5);

          if ($row['id_tipo_cliente'] == 0001 || $row['id_tipo_cliente'] == 0002) {
              $pdf->Image('../../../images/check.png',58,96, 5, 5);
          }
          elseif ($row['id_tipo_cliente'] == 0003){
              $pdf->Image('../../../images/check.png',155,96, 5, 5);
          }

          $pdf->Ln(8);
          $pdf->Cell(22,6,utf8_decode(''),0,0,'L');
          $pdf->Cell(70,6,utf8_decode(strtoupper('INTERNET')),0,0,'L');
          $pdf->Cell(68,6,utf8_decode(strtoupper(getVelocidadById($row['id_velocidad']))),0,0,'L');
          $pdf->Cell(70,6,utf8_decode(strtoupper($tecnologia)),0,1,'L');

          $pdf->Cell(22,6,utf8_decode(''),0,0,'L');
          $pdf->Cell(75,6,utf8_decode(strtoupper($row['mac_modem'])),0,0,'L');
          $pdf->Cell(70,6,utf8_decode(strtoupper($row['serie_modem'])),0,0,'L');
          $pdf->Cell(70,6,utf8_decode(strtoupper($row['entrega_calidad'])),0,0,'L');
          $pdf->Ln(3);
          $pdf->Cell(49,6,utf8_decode(''),0,0,'L');
          //$pdf->Cell(115,6,utf8_decode($tipoServicio),0,0,'L');


	    $imp = substr((($row['cuota_in']/(1 + floatval($iva)))*$cesc),0,4);
  	    $imp = str_replace(',','.', $imp);
  	    //var_dump($imp);

  	    $cantidad = (doubleval($row['cuota_in']) + doubleval($imp));
        $pdf->Ln(3);
        $pdf->Cell(68,6,utf8_decode(''),0,0,'L');
        $pdf->Cell(74,6,utf8_decode($row['periodo_contrato_int']." MESES"),0,0,'L');
        $pdf->Cell(20,6,utf8_decode("$".$row['costo_instalacion_in']),0,1,'L');

        $pdf->Cell(60,6,utf8_decode(''),0,0,'L');
        $pdf->Cell(78,6,utf8_decode('$'.number_format($cantidad,2)),0,0,'L');
        $pdf->Cell(20,6,utf8_decode('INTERNET ILIMITADO'),0,1,'L');

	  }
	  /* close connection */
	  mysqli_close($mysqli);
	  $pdf->Output();

  }

  contratoInter();

?>
