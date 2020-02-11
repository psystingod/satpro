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

  if (isset($_GET['nOrden'])) {

      // get passed parameter value, in this case, the record ID
      // isset() is a PHP function used to verify if a value is there or not
      $id=isset($_GET['nOrden']) ? $_GET['nOrden'] : die('ERROR: Record no encontrado.');
  }

  function f3(){


	  global $id, $mysqli, $data;
	  $query = "SELECT * FROM tbl_ordenes_suspension WHERE idOrdenSuspension = ".$id;
	  $resultado = $mysqli->query($query);

	  $pdf = new FPDF();
	  $pdf->AliasNbPages();
	  $pdf->AddPage('P','Letter');
	  //$pdf->Image('../../../images/logo.png',10,10, 26, 24);



	  date_default_timezone_set('America/El_Salvador');

	  //echo strftime("El año es %Y y el mes es %B");
      putenv("LANG='es_ES.UTF-8'");
      setlocale(LC_ALL, 'es_ES.UTF-8');
      $pdf->Ln(16);
	  while($row = $resultado->fetch_assoc())
	  {


        if ($row["tipoServicio"] == "I") {
            // SQL query para traer datos del servicio de cable de la tabla clientes
      	  $query1 = "SELECT nombreTecnico FROM tbl_tecnicos_cxc WHERE idTecnico = ".$row['idTecnico'];
      	  // Preparación de sentencia
      	  $statement1 = $mysqli->query($query1);
      	  //$statement->execute();
      	  while ($result1 = $statement1->fetch_assoc()) {
      		  $tecnico = $result1['nombreTecnico'];
      	  }

          // SQL query para traer datos del servicio de cable de la tabla clientes
    	  $query2 = "SELECT nombreVelocidad FROM tbl_velocidades WHERE idVelocidad= ".$row['velocidad'];
    	  // Preparación de sentencia
    	  $statement2 = $mysqli->query($query2);
    	  //$statement->execute();
    	  while ($result2 = $statement2->fetch_assoc()) {
    		  $velocidad = $result2['nombreVelocidad'];
    	  }

          // SQL query para traer datos del servicio de cable de la tabla clientes
    	  $query3 = "SELECT nombreActividad FROM tbl_actividades_susp WHERE idActividadSusp= ".$row["actividadInter"];
    	  // Preparación de sentencia
    	  $statement3 = $mysqli->query($query3);
    	  //$statement->execute();
    	  while ($result3 = $statement3->fetch_assoc()) {
    		  $actividadInter = $result3['nombreActividad'];
    	  }
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(190,6,'SUSPENSION DE SERVICIO',0,1,'C');
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(175,6,'Dia de cobro: '.$row["diaCobro"],0,0,'L');
            $pdf->Cell(20,6,utf8_decode('N° ').$row["idOrdenSuspension"],1,1,'C');
            $pdf->Ln(12);
            $pdf->SetFont('Arial','UB',9);
            $pdf->Cell(40,3,'Fecha: '.$row["fechaOrden"],0,0,'L');
            $pdf->Cell(50,3,'Codigo de cliente: '.$row["codigoCliente"],0,0,'L');
            $pdf->Cell(120,3,'Nombre: '.utf8_decode($row["nombreCliente"]),0,1,'L');
            $pdf->Ln(3);
            //$pdf->Cell(190,3,'Direccion: '.$row["direccionCable"],0,1,'L');
            $pdf->MultiCell(190,6,'Direccion: '.utf8_decode($row["direccion"]),0,'L',0);
            $pdf->Ln(3);
            //$pdf->Cell(190,3,'Direccion: '.$row["direccionCable"],0,1,'L');
            //$pdf->MultiCell(190,6,'Direccion a trasladar: '.utf8_decode($row["direccionTraslado"]),0,'L',0);
            //$pdf->Ln(5);

            $pdf->Cell(70,3,'Tecnico: '.utf8_decode($tecnico),0,1,'L');
            $pdf->Ln(3);

            $pdf->Cell(40,3,'MAC: '.$row["macModem"],0,0,'L');
            $pdf->Cell(40,3,'Colilla: '.$row["colilla"],0,1,'L');
            $pdf->Ln(3);
            $pdf->Cell(190,3,'Motivo de la suspension: '.$actividadInter,0,1,'L');
            $pdf->Ln(3);
            $pdf->SetFont('Arial','B',9);
            if ($row["ordenaSuspension"] == "administracion") {
                $pdf->Cell(35,3,'Ordena suspension: ',0,0,'L');
                $pdf->Cell(25,3,'Administracion',0,0,'L');
                $pdf->Cell(20,3,'X',1,0,'L');
                $pdf->Cell(20,3,'Oficina',0,0,'R');
                $pdf->Cell(20,3,'',1,0,'L');
                $pdf->Cell(20,3,'El cliente',0,0,'R');
                $pdf->Cell(20,3,'',1,1,'L');
            }elseif ($row["ordenaSuspension"] == "oficina") {
                $pdf->Cell(35,3,'Ordena suspension: ',0,0,'L');
                $pdf->Cell(25,3,'Administracion',0,0,'L');
                $pdf->Cell(20,3,'',1,0,'L');
                $pdf->Cell(20,3,'Oficina',0,0,'R');
                $pdf->Cell(20,3,'X',1,0,'C');
                $pdf->Cell(20,3,'El cliente',0,0,'R');
                $pdf->Cell(20,3,'',1,1,'L');
            }elseif ($row["ordenaSuspension"] == "cliente") {
                $pdf->Cell(35,3,'Ordena suspension: ',0,0,'L');
                $pdf->Cell(25,3,'Administracion',0,0,'L');
                $pdf->Cell(20,3,'',1,0,'L');
                $pdf->Cell(20,3,'Oficina',0,0,'R');
                $pdf->Cell(20,3,'',1,0,'C');
                $pdf->Cell(20,3,'El cliente',0,0,'R');
                $pdf->Cell(20,3,'X',1,1,'L');
            }
            $pdf->Ln(3);
            $pdf->MultiCell(190,6,'Observaciones: '.utf8_decode($row["observaciones"]),0,'L',0);
            $pdf->Ln(3);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(70,6,'Fecha de realizacion: ',1,1,'L');
            $pdf->Ln(3);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(60,6,'Cliente: ',1,0,'L');
            $pdf->Cell(70,6,'Tecnico: ',1,0,'L');
            $pdf->Cell(65,6,'Autorizacion: ',1,1,'L');

            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(95,8,'Creado por: '.utf8_decode($row["creadoPor"]),0,0,'L');
            $pdf->Cell(95,8,'Tipo de servicio: '.$row["tipoServicio"],0,1,'R');

        }elseif($row["tipoServicio"] == "C") {
            // SQL query para traer datos del servicio de cable de la tabla clientes
      	  $query1 = "SELECT nombreTecnico FROM tbl_tecnicos_cxc WHERE idTecnico = ".$row['idTecnico'];
      	  // Preparación de sentencia
      	  $statement1 = $mysqli->query($query1);
      	  //$statement->execute();
      	  while ($result1 = $statement1->fetch_assoc()) {
      		  $tecnico = $result1['nombreTecnico'];
      	  }

          // SQL query para traer datos del servicio de cable de la tabla clientes
    	  $query3 = "SELECT nombreActividad FROM tbl_actividades_susp WHERE idActividadSusp= ".$row["actividadCable"];
    	  // Preparación de sentencia
    	  $statement3 = $mysqli->query($query3);
    	  //$statement->execute();
    	  while ($result3 = $statement3->fetch_assoc()) {
    		  $actividadCable = $result3['nombreActividad'];
    	  }
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(190,6,'SUSPENSION DE SERVICIO',0,1,'C');
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(175,6,'Dia de cobro: '.$row["diaCobro"],0,0,'L');
            $pdf->Cell(20,6,utf8_decode('N° ').$row["idOrdenSuspension"],1,1,'C');
            $pdf->Ln(12);
            $pdf->SetFont('Arial','UB',9);
            $pdf->Cell(40,3,'Fecha: '.$row["fechaOrden"],0,0,'L');
            $pdf->Cell(50,3,'Codigo de cliente: '.$row["codigoCliente"],0,0,'L');
            $pdf->Cell(120,3,'Nombre: '.utf8_decode($row["nombreCliente"]),0,1,'L');
            $pdf->Ln(3);
            //$pdf->Cell(190,3,'Direccion: '.$row["direccionCable"],0,1,'L');
            $pdf->MultiCell(190,6,'Direccion: '.utf8_decode($row["direccion"]),0,'L',0);
            $pdf->Ln(3);
            $pdf->Cell(70,3,'Tecnico: '.$tecnico,0,1,'L');
            $pdf->Ln(5);
            $pdf->Cell(50,3,'MACTV: '.$row["mactv"],0,0,'L');
            $pdf->Cell(50,3,'Colilla: '.$row["colilla"],0,1,'L');

            $pdf->Ln(3);
            $pdf->Cell(190,3,'Motivo de la suspension: '.$actividadCable,0,1,'L');
            $pdf->Ln(3);
            $pdf->SetFont('Arial','B',9);
            if ($row["ordenaSuspension"] == "administracion") {
                $pdf->Cell(35,3,'Ordena suspension: ',0,0,'L');
                $pdf->Cell(25,3,'Administracion',0,0,'L');
                $pdf->Cell(20,3,'X',1,0,'L');
                $pdf->Cell(20,3,'Oficina',0,0,'R');
                $pdf->Cell(20,3,'',1,0,'L');
                $pdf->Cell(20,3,'El cliente',0,0,'R');
                $pdf->Cell(20,3,'',1,1,'L');
            }elseif ($row["ordenaSuspension"] == "oficina") {
                $pdf->Cell(35,3,'Ordena suspension: ',0,0,'L');
                $pdf->Cell(25,3,'Administracion',0,0,'L');
                $pdf->Cell(20,3,'',1,0,'L');
                $pdf->Cell(20,3,'Oficina',0,0,'R');
                $pdf->Cell(20,3,'X',1,0,'C');
                $pdf->Cell(20,3,'El cliente',0,0,'R');
                $pdf->Cell(20,3,'',1,1,'L');
            }elseif ($row["ordenaSuspension"] == "cliente") {
                $pdf->Cell(35,3,'Ordena suspension: ',0,0,'L');
                $pdf->Cell(25,3,'Administracion',0,0,'L');
                $pdf->Cell(20,3,'',1,0,'L');
                $pdf->Cell(20,3,'Oficina',0,0,'R');
                $pdf->Cell(20,3,'',1,0,'C');
                $pdf->Cell(20,3,'El cliente',0,0,'R');
                $pdf->Cell(20,3,'X',1,1,'L');
            }
            $pdf->Ln(3);
            $pdf->SetFont('Arial','Bu',9);
            $pdf->MultiCell(190,6,'Observaciones: '.utf8_decode($row["observaciones"]),0,'L',0);
            $pdf->Ln(3);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(70,6,'Fecha de realizacion: ',1,1,'L');
            $pdf->Ln(3);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(60,6,'Cliente: ',1,0,'L');
            $pdf->Cell(70,6,'Tecnico: ',1,0,'L');
            $pdf->Cell(65,6,'Autorizacion: ',1,1,'L');

            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(95,8,'Creado por: '.$row["creadoPor"],0,0,'L');
            $pdf->Cell(95,8,'Tipo de servicio: '.$row["tipoServicio"],0,1,'R');
        }


	  }

	  /* close connection */
	  mysqli_close($mysqli);
	  $pdf->Output();

  }

  f3();

?>
