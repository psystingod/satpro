  <?php
    require '../../../pdfs/fpdf.php';
    require_once("../../../php/config.php");
    require '../../../numLe/src/NumerosEnLetras.php';
      require_once('../../../php/connection.php');
      require_once('../../modulo_administrar/php/getInfo2.php');
    if(!isset($_SESSION))
    {
    	session_start();
    }
    $host = DB_HOST;
    $user = DB_USER;
    $password = DB_PASSWORD;
    $database = $_SESSION['db'];
    $mysqli = new mysqli($host, $user, $password, $database);
    $mysqliCobrador = new mysqli($host, $user, $password, $database);
    $mysqliCableDePaquete = new mysqli($host, $user, $password, $database);
    $mysqliInternetDePaquete = new mysqli($host, $user, $password, $database);
    $mysqliFacturasPendientes  = new mysqli($host, $user, $password, $database);

    $colonia = new GetInfo2();

    $totalCantidadDeFacturasReporte=0;
    $totalDeudaReporte=0;

    $totalCantidadDeFacturasSoloCable=0;
    $totalCantidadDeFacturasSoloInternet=0;
    $totalCantidadDeFacturasPaquete=0;

    $totalCantidadDefacturasCableDePaquete=0;
    $totalCantidadDefacturasInterDePaquete=0;

    $totalDeudaSoloCable=0;
    $totalDeudaSoloInternet=0;

    $totalDeudaCableDePaquete=0;
    $totalDeudaInternetDePaquete=0;

    $totalCantidadDeClientesCableDePaquete=0;
    $totalCantidadDeClientesInternetPaquete=0;

    $totalCantidadDeClientesSoloCable=0;
    $totalCantidadDeClientesSoloInternet=0;
    $totalCantidadDeClientesPaquete=0;
    //__________INIT CLASSS_________________
    class FPDF2 extends FPDF{
      public function footer(){
        $this->SetFont('Arial','',5);
        $this->AliasnbPages();
        $this->Cell(260,3,utf8_decode("Página ".str_pad($this->pageNo(),0,"0",STR_PAD_LEFT)."/".str_pad("{nb}",0,"0",STR_PAD_LEFT)),0,1,'R');
      }

    }
    //____________end CLASS_________________


    function listaTodosLosClientesActivos(){
      $query="";
      global  $mysqli,$mysqliCobrador,$mysqliCableDePaquete,$mysqliInternetDePaquete, $mysqliFacturasPendientes;
      global $totalCantidadDeFacturasReporte,$totalDeudaReporte;
      global $totalCantidadDeFacturasSoloCable,$totalCantidadDeClientesSoloCable,$totalDeudaSoloCable;
      global $totalCantidadDeFacturasSoloInternet,$totalCantidadDeClientesSoloInternet,$totalDeudaSoloInternet;
      global $totalCantidadDeFacturasPaquete,$totalCantidadDeClientesPaquete;
      global $totalCantidadDefacturasCableDePaquete, $totalCantidadDeClientesCableDePaquete, $totalDeudaCableDePaquete;
      global $totalCantidadDefacturasInterDePaquete, $totalDeudaInternetDePaquete,$totalCantidadDeClientesInternetPaquete;
      global $colonia;

      //Auxiliares parea generar el query
      $filtroCobrador="";
      $FiltroColonia="";
      $FiltroServicio="";
      $filtroDiaDeCobro="";

      $filtroCobradorParaPendientes="";

      $contadorDeFilas=1;
      $cobradorReporte="";
      $coloniaReporte="";
      $servicioReporte="";
      $diaDeCobroReporte="";

      $queryTodos="";
      $queryCableParaTodos="";
      $queryInternetParaTodos="";

    	$pdf = new FPDF2();
      $pdf->AddPage('L','Letter');
      $pdf->SetFont('Arial','',6);
      $pdf->Cell(260,6,utf8_decode("Página ".str_pad($pdf->pageNo(),4,"0",STR_PAD_LEFT)),0,1,'R');
      $pdf->Ln(0);
      date_default_timezone_set('America/El_Salvador');
      $pdf->Cell(260,6,utf8_decode( date('Y/m/d g:i')),0,1,'R');
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(260,6,utf8_decode("CABLE VISION POR SATELITE, S.A DE C.V "),0,1,'C');
      $pdf->Image('../../../images/logo.png',10,10, 20, 18);
      $pdf->Ln(4);
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(260,6,utf8_decode("INFORME GENERAL DE CLIENTES. "),0,1,'C');

     //Datos el cobrador para mostrar en el reporte
      if($_POST["clCobrador"]!="todos"){//si es un cobrador especifico
        $queryCobrador="select * from tbl_cobradores where codigoCobrador='".$_POST["clCobrador"]."' LIMIT 1";
        $resultadoCobrador= $mysqliCobrador->query($queryCobrador) ;
        while($row = $resultadoCobrador->fetch_assoc()){
          $cobradorReporte=$row['codigoCobrador'].' '.$row['nombreCobrador'];
          $filtroCobrador= " AND cod_cobrador=".$_POST["clCobrador"];
          $filtroCobradorParaPendientes=" AND codigoCobrador=".$_POST["clCobrador"];
        }
        //Filtro Colonia
        if($_POST["clColonia"]=="todas"){
          $FiltroColonia="";
          $coloniaReporte="TODAS";
        }else{
          $FiltroColonia=" AND id_colonia=".$_POST["clColonia"];
          $coloniaReporte=$colonia->getColonia($_POST["clColonia"]);
        }

        //Filtro dia Cobro.
        if(isset($_POST['todosLosDias'])){
            $filtroDiaDeCobro="";
            $diaDeCobroReporte="TODOS";
        }else{
            $filtroDiaDeCobro=" AND dia_cobro=".$_POST["cldiaCobro"];
            $diaDeCobroReporte=$_POST["cldiaCobro"];
        }

        //Tipo de servicio
        switch ($_POST["clServicio"]) {
          case "C":
          $servicioReporte="CABLE";
          $FiltroServicio="/*clientes solo cable activos*/
         select cod_cliente, nombre, direccion_cobro, id_colonia, dia_cobro,'Cable' as servicio, telefonos, fecha_ult_pago, cod_cobrador
         from clientes WHERE (servicio_suspendido='F' OR servicio_suspendido is null)  AND sin_servicio='F' AND estado_cliente_in = 3 AND cod_cliente<>'00000'
          /*fin clientes solo cable activos*/";
            break;
          case "I":
          $servicioReporte="INTERNET";
          $FiltroServicio=" /*clientes solo Internet activos */
         select cod_cliente, nombre, direccion_cobro, id_colonia, (dia_corbo_in) as dia_cobro,'Internet' as servicio, telefonos, fecha_ult_pago, cod_cobrador
         from clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null or servicio_suspendido='') and sin_servicio='T' AND estado_cliente_in = 1 AND cod_cliente<>'00000'
          /*fin clientes solo Internet activos */";
              break;
          case "A":
          $servicioReporte="PAQUETE";
          $FiltroServicio="/*clientes paquete activos*/
         select cod_cliente, nombre, direccion_cobro, id_colonia, dia_cobro,'Paquete' as servicio, telefonos, fecha_ult_pago, cod_cobrador
         from clientes WHERE (servicio_suspendido='F' OR servicio_suspendido is null)  AND sin_servicio='F' AND estado_cliente_in = 1 AND cod_cliente<>'00000'
          /*fin clientes paquete activos*/";
                break;
          case "T":
          $servicioReporte="TODOS";
          $FiltroServicio="/*clientes solo cable activos*/
         select cod_cliente, nombre, direccion_cobro, id_colonia, dia_cobro,'Cable' as servicio, telefonos, fecha_ult_pago, cod_cobrador
         from clientes WHERE (servicio_suspendido='F' OR servicio_suspendido is null)  AND sin_servicio='F' AND estado_cliente_in = 3 AND cod_cliente<>'00000'
          /*fin clientes solo cable activos*/".$filtroCobrador.$FiltroColonia.$filtroDiaDeCobro." UNION "." /*clientes solo Internet activos */
         select cod_cliente, nombre, direccion_cobro, id_colonia, (dia_corbo_in) as dia_cobro,'Internet' as servicio, telefonos, fecha_ult_pago, cod_cobrador
         from clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null OR servicio_suspendido='') and sin_servicio='T' AND estado_cliente_in = 1 AND cod_cliente<>'00000'
          /*fin clientes solo Internet activos */".$filtroCobrador.$FiltroColonia.$filtroDiaDeCobro." UNION "."/*clientes paquete activos*/
         select cod_cliente, nombre, direccion_cobro, id_colonia, dia_cobro,'Paquete' as servicio, telefonos, fecha_ult_pago, cod_cobrador
         from clientes WHERE (servicio_suspendido='F' OR servicio_suspendido is nul OR servicio_suspendido='')  AND sin_servicio='F' AND estado_cliente_in = 1 AND cod_cliente<>'00000'
          /*fin clientes paquete activos*/".$filtroCobrador.$FiltroColonia.$filtroDiaDeCobro;
                      break;
          default:
          $FiltroServicio="";
            break;
        }

          if($_POST["clServicio"]=="T"){
            $query=$FiltroServicio;
          }else{
            $query=$FiltroServicio.$filtroCobrador.$FiltroColonia.$filtroDiaDeCobro;
          }

        $resultado = $mysqli->query($query) ;
        $pdf->SetFont('Arial','',6);
        //filtro servicio del reporte
        $pdf->Cell(260,4,utf8_decode("Servicio: ".$servicioReporte),0,1,'L');
        //filtro cobrador del reporte
        $pdf->Cell(260,4,utf8_decode("Cobrador: ".$cobradorReporte),0,1,'L');
        //filtro servicio del Colonia
        $pdf->Cell(260,4,utf8_decode("Barrio/Colonia: ".$coloniaReporte),0,1,'L');
        //filtro servicio del diaCobro
        $pdf->Cell(260,4,utf8_decode("Dia de cobro: ".$diaDeCobroReporte),0,1,'L');
        $pdf->SetFont('Arial','B',11);
        date_default_timezone_set('America/El_Salvador');
        //echo strftime("El año es %Y y el mes es %B");
        putenv("LANG='es_ES.UTF-8'");
        setlocale(LC_ALL, 'es_ES.UTF-8');
        $pdf->SetFont('Arial','B',8);
        $pdf->Ln(6);
        $pdf->Cell(10,6,utf8_decode('N°'),1,0,'L');
        $pdf->Cell(70,6,utf8_decode('Cliente'),1,0,'L');
        $pdf->Cell(100,6,utf8_decode('Direccion de cobro'),1,0,'L');
        $pdf->Cell(26,6,utf8_decode('Colonia'),1,0,'L');

        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 12;
        $pdf->MultiCell(12,3,utf8_decode('Dia Cobro'),1,'C');
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 13;
        $pdf->MultiCell(13,3,utf8_decode('Tipo Servicio'),1,'C');
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->Cell(13,6,utf8_decode('Telefono'),1,0,'C');

        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 15;
        $pdf->MultiCell(15,3,utf8_decode('Ult. mes pagado'),1,'C');
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->Ln(8);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(100,5,utf8_decode($cobradorReporte),0,0,'L');

        $pdf->Ln(3);
        $pdf->SetFont('Arial','',6);
        $fechaActualParaCondicion=date('Y-m-d');
        while($row = $resultado->fetch_assoc())
            {
              $pdf->Ln(3);
              $pdf->SetFont('Arial','',6);
              $pdf->Cell(10,3,utf8_decode($contadorDeFilas),0,0,'L');
              $contadorDeFilas++;

              $current_y = $pdf->GetY();
              $current_x = $pdf->GetX();
              $cell_width = 70;
              $pdf->MultiCell(70,3,utf8_decode(strtoupper($row['cod_cliente']."  ".$row['nombre'])),0,'L');
              $pdf->SetXY($current_x + $cell_width, $current_y);

              $current_y = $pdf->GetY();
              $current_x = $pdf->GetX();
              $cell_width = 100;
              $pdf->SetFont('Arial','',5);
              $pdf->MultiCell(100,2,utf8_decode($row['direccion_cobro']),0,'L');
              $pdf->SetXY($current_x + $cell_width, $current_y);

              $pdf->Cell(26,3,utf8_decode($colonia->getColonia($row['id_colonia'])),0,0,'L');
              $pdf->SetFont('Arial','',6);
              $pdf->Cell(12,3,utf8_decode($row['dia_cobro']),0,0,'C');
              $pdf->Cell(13,3,utf8_decode($row['servicio']),0,0,'C');

              $current_y = $pdf->GetY();
              $current_x = $pdf->GetX();
              $cell_width = 13;
              $pdf->MultiCell(13,2,utf8_decode($row['telefonos']),0,'C');
              $pdf->SetXY($current_x + $cell_width, $current_y);

              $pdf->Cell(15,3,utf8_decode($row['fecha_ult_pago']),0,0,'C');
              $pdf->Ln(3);
            }
      }else{//end if de todos los cobradores
          $cobradorReporte="TODOS";
          $filtroCobrador="";
        //Filtro Colonia
        if($_POST["clColonia"]=="todas"){
        $FiltroColonia="";
        $coloniaReporte="TODAS";
        }else{
        $FiltroColonia=" AND id_colonia=".$_POST["clColonia"];
        $coloniaReporte=$colonia->getColonia($_POST["clColonia"]);
        }
        //Filtro dia Cobro.
        if(isset($_POST['todosLosDias'])){
          $filtroDiaDeCobro="";
          $diaDeCobroReporte="TODOS";
        }else{
          $filtroDiaDeCobro=" AND dia_cobro=".$_POST["cldiaCobro"];
          $diaDeCobroReporte=$_POST["cldiaCobro"];
        }

        $mostrarEncabezados=true;
        $queryCobrador="select * from tbl_cobradores;";
        $resultadoCobrador= $mysqliCobrador->query($queryCobrador) ;
        while($row = $resultadoCobrador->fetch_assoc()){
          $cobradorReporte=$row['codigoCobrador'].' '.$row['nombreCobrador'];
          $filtroCobrador= " AND cod_cobrador=".$row['codigoCobrador'];


          //para armar la consulta de todos los servicios
          //Tipo de servicio
          switch ($_POST["clServicio"]) {
          case "C":
          $servicioReporte="CABLE";
          $FiltroServicio="/*clientes solo cable activos*/
          select cod_cliente, nombre, direccion_cobro, id_colonia, dia_cobro,'Cable' as servicio, telefonos, fecha_ult_pago, cod_cobrador
          from clientes WHERE (servicio_suspendido='F' OR servicio_suspendido is null)  AND sin_servicio='F' AND estado_cliente_in = 3 AND cod_cliente<>'00000'
          /*fin clientes solo cable activos*/";
            break;
          case "I":
          $servicioReporte="INTERNET";
          $FiltroServicio=" /*clientes solo Internet activos */
          select cod_cliente, nombre, direccion_cobro, id_colonia, (dia_corbo_in) as dia_cobro,'Internet' as servicio, telefonos, fecha_ult_pago, cod_cobrador
          from clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null or servicio_suspendido='') and sin_servicio='T' AND estado_cliente_in = 1 AND cod_cliente<>'00000'
          /*fin clientes solo Internet activos */";
          //$query = "SELECT count(*) FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null or servicio_suspendido='') and sin_servicio='T' AND estado_cliente_in = 1";
              break;
          case "A":
          $servicioReporte="PAQUETE";
          $FiltroServicio="/*clientes paquete activos*/
          select cod_cliente, nombre, direccion_cobro, id_colonia, dia_cobro,'Paquete' as servicio, telefonos, fecha_ult_pago, cod_cobrador
          from clientes WHERE (servicio_suspendido='F' OR servicio_suspendido is null)  AND sin_servicio='F' AND estado_cliente_in = 1 AND cod_cliente<>'00000'
          /*fin clientes paquete activos*/";
                break;
          case "T":
                $servicioReporte="TODOS";
                $FiltroServicio="/*clientes solo cable activos*/
               select cod_cliente, nombre, direccion_cobro, id_colonia, dia_cobro,'Cable' as servicio, telefonos, fecha_ult_pago, cod_cobrador
               from clientes WHERE (servicio_suspendido='F' OR servicio_suspendido is null)  AND sin_servicio='F' AND estado_cliente_in = 3 AND cod_cliente<>'00000'
                /*fin clientes solo cable activos*/".$filtroCobrador.$FiltroColonia.$filtroDiaDeCobro." UNION "." /*clientes solo Internet activos */
               select cod_cliente, nombre, direccion_cobro, id_colonia, (dia_corbo_in) as dia_cobro,'Internet' as servicio, telefonos, fecha_ult_pago, cod_cobrador
               from clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null OR servicio_suspendido='') and sin_servicio='T' AND estado_cliente_in = 1 AND cod_cliente<>'00000'
                /*fin clientes solo Internet activos */".$filtroCobrador.$FiltroColonia.$filtroDiaDeCobro." UNION "."/*clientes paquete activos*/
               select cod_cliente, nombre, direccion_cobro, id_colonia, dia_cobro,'Paquete' as servicio, telefonos, fecha_ult_pago, cod_cobrador
               from clientes WHERE (servicio_suspendido='F' OR servicio_suspendido is null OR servicio_suspendido='')  AND sin_servicio='F' AND estado_cliente_in = 1 AND cod_cliente<>'00000'
                /*fin clientes paquete activos*/".$filtroCobrador.$FiltroColonia.$filtroDiaDeCobro;
                break;
          default:
              $FiltroServicio="";
            break;
          }

          if($_POST["clServicio"]=="T"){
            $query=$FiltroServicio;
          }else{
          $query=$FiltroServicio.$filtroCobrador.$FiltroColonia.$filtroDiaDeCobro;
        }
          //echo($query);
          $resultado = $mysqli->query($query) or die($mysqli->error);
          if($mostrarEncabezados){
            $pdf->SetFont('Arial','',6);
            //filtro servicio del reporte
            $pdf->Cell(260,4,utf8_decode("Servicio: ".$servicioReporte),0,1,'L');
            //filtro cobrador del reporte
            $pdf->Cell(260,4,utf8_decode("Cobrador: TODOS"),0,1,'L');
            //filtro servicio del Colonia
            $pdf->Cell(260,4,utf8_decode("Barrio/Colonia: ".$coloniaReporte),0,1,'L');
            //filtro servicio del diaCobro
            $pdf->Cell(260,4,utf8_decode("Dia de cobro: ".$diaDeCobroReporte),0,1,'L');
            $pdf->SetFont('Arial','B',11);
            date_default_timezone_set('America/El_Salvador');
            //echo strftime("El año es %Y y el mes es %B");
            putenv("LANG='es_ES.UTF-8'");
            setlocale(LC_ALL, 'es_ES.UTF-8');
            $pdf->SetFont('Arial','B',8);
            $pdf->Ln(6);
            $pdf->Cell(10,6,utf8_decode('N°'),1,0,'L');
            $pdf->Cell(70,6,utf8_decode('Cliente'),1,0,'L');
            $pdf->Cell(100,6,utf8_decode('Direccion de cobro'),1,0,'L');
            $pdf->Cell(26,6,utf8_decode('Colonia'),1,0,'L');

            $current_y = $pdf->GetY();
            $current_x = $pdf->GetX();
            $cell_width = 12;
            $pdf->MultiCell(12,3,utf8_decode('Dia Cobro'),1,'C');
            $pdf->SetXY($current_x + $cell_width, $current_y);

            $current_y = $pdf->GetY();
            $current_x = $pdf->GetX();
            $cell_width = 13;
            $pdf->MultiCell(13,3,utf8_decode('Tipo Servicio'),1,'C');
            $pdf->SetXY($current_x + $cell_width, $current_y);

            $pdf->Cell(13,6,utf8_decode('Telefono'),1,0,'C');

            $current_y = $pdf->GetY();
            $current_x = $pdf->GetX();
            $cell_width = 15;
            $pdf->MultiCell(15,3,utf8_decode('Ult. mes pagado'),1,'C');
            $pdf->SetXY($current_x + $cell_width, $current_y);
            $mostrarEncabezados=false;

            $pdf->SetFont('Arial','B',6);
            $pdf->Ln(6);
            $pdf->SetFont('Arial','',6);
            $fechaActualParaCondicion=date('Y-m-d');
          }//endMostrar encabezados

        $mostrarEncabezadoCobrador=true;
        while($row = $resultado->fetch_assoc())
          {
          if($mostrarEncabezadoCobrador){
            $pdf->Ln(3);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(100,5,utf8_decode($cobradorReporte),0,0,'L');
            $pdf->Ln(3);
            $mostrarEncabezadoCobrador=false;
            }

          $pdf->Ln(3);
          $pdf->SetFont('Arial','',6);
          $pdf->Cell(10,3,utf8_decode($contadorDeFilas),0,0,'L');
          $contadorDeFilas++;

          $current_y = $pdf->GetY();
          $current_x = $pdf->GetX();
          $cell_width = 70;
          $pdf->MultiCell(70,3,utf8_decode(strtoupper($row['cod_cliente']."  ".$row['nombre'])),0,'L');
          $pdf->SetXY($current_x + $cell_width, $current_y);

          $current_y = $pdf->GetY();
          $current_x = $pdf->GetX();
          $cell_width = 100;
          $pdf->SetFont('Arial','',5);
          $pdf->MultiCell(100,2,utf8_decode($row['direccion_cobro']),0,'L');
          $pdf->SetXY($current_x + $cell_width, $current_y);

          $pdf->Cell(26,3,utf8_decode($colonia->getColonia($row['id_colonia'])),0,0,'L');
          $pdf->SetFont('Arial','',6);
          $pdf->Cell(12,3,utf8_decode($row['dia_cobro']),0,0,'C');
          $pdf->Cell(13,3,utf8_decode($row['servicio']),0,0,'C');

          $current_y = $pdf->GetY();
          $current_x = $pdf->GetX();
          $cell_width = 13;
          $pdf->MultiCell(13,2,utf8_decode($row['telefonos']),0,'C');
          $pdf->SetXY($current_x + $cell_width, $current_y);

          $pdf->Cell(15,3,utf8_decode($row['fecha_ult_pago']),0,0,'C');
          $pdf->Ln(3);
        }
      }//while de cobradores
    }//end else de cobrador

    $pdf->Cell(185,5,utf8_decode(''),0,0,'R');
    $pdf->Cell(75,5,utf8_decode(''),"",1,'R');
	  mysqli_close($mysqli);
	  $pdf->Output();
}//endFunction
  listaTodosLosClientesActivos();

  ?>
