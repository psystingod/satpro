<?php
  require '../../../pdfs/fpdf.php';
  require_once("../../../php/config.php");
  require '../../../numLe/src/NumerosEnLetras.php';
    require_once('../../../php/connection.php');
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


  function listaImpagos(){
    global  $mysqli,$mysqliCobrador,$mysqliCableDePaquete,$mysqliInternetDePaquete;
    global $totalCantidadDeFacturasReporte,$totalDeudaReporte;
    global $totalCantidadDeFacturasSoloCable,$totalCantidadDeClientesSoloCable,$totalDeudaSoloCable;
    global $totalCantidadDeFacturasSoloInternet,$totalCantidadDeClientesSoloInternet,$totalDeudaSoloInternet;
    global $totalCantidadDeFacturasPaquete,$totalCantidadDeClientesPaquete;
    global $totalCantidadDefacturasCableDePaquete, $totalCantidadDeClientesCableDePaquete, $totalDeudaCableDePaquete;
    global $totalCantidadDefacturasInterDePaquete, $totalDeudaInternetDePaquete,$totalCantidadDeClientesInternetPaquete;

    $contadorDeFilas=1;
    $cobradorReporte="";
    $servicioReporte="";
   //Datos el cobrador par mostrar en el reporte
    if($_POST["susCobrador"]!="todos"){
      $queryCobrador="select * from tbl_cobradores where codigoCobrador='".$_POST["susCobrador"]."' LIMIT 1";
      $resultadoCobrador= $mysqliCobrador->query($queryCobrador) ;
      while($row = $resultadoCobrador->fetch_assoc()){
        $cobradorReporte=$row['codigoCobrador'].' '.$row['nombreCobrador'];
      }
    }else{
        $cobradorReporte="TODOS";
    }



      if ($_POST["susServicio"] == "C") {
        $servicioReporte="Cable";
        if($_POST["susCobrador"]=="todos"){//cable y todos los cobradores
          $query = "SELECT codigoCliente, nombre, direccion, tipoServicio,'C' as filtro, COUNT(*) as cantidadDeFacturasVencidas, fechaVencimiento, SUM(cuotaCable + totalImpuesto) as totalDeuda
FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
AND codigoCliente NOT IN (
	/*--Internet en general*/
	SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
    AND codigoCliente  NOT IN(
		/*-clientes de solo internet ya suspendidos*/
		SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
        /*-Fin clientes de solo internet ya suspendidos*/
    ) group by `codigoCliente` HAVING COUNT(*) >= 2
    /*--fin Internet en general*/
    UNION
    /*--clientes de solo cable ya suspendidos*/
    SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
    /*--fin clientes de solo cable ya suspendidos*/
) group by `codigoCliente` HAVING COUNT(*) >= 2";
          $resultado = $mysqli->query($query) ;
        }else{//cable y cobrador espercifico
           $query = "SELECT codigoCliente, nombre, direccion, tipoServicio,'C' as filtro, COUNT(*) as cantidadDeFacturasVencidas, fechaVencimiento, SUM(cuotaCable + totalImpuesto) as totalDeuda
FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
AND codigoCliente NOT IN (
	/*--Internet en general*/
	SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
    AND codigoCliente NOT IN (
		/*-clientes de solo internet ya suspendidos*/
		SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
        /*-Fin clientes de solo internet ya suspendidos*/
    ) group by `codigoCliente` HAVING COUNT(*) >= 2
    /*--fin Internet en general*/
    UNION
    /*--clientes de solo cable ya suspendidos*/
    SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
    /*--fin clientes de solo cable ya suspendidos*/
) group by `codigoCliente` HAVING COUNT(*) >= 2";
          $resultado = $mysqli->query($query) ;
        }
    }elseif ($_POST["susServicio"] == "I") {//internet
      $servicioReporte="Internet";
        if($_POST["susCobrador"]=="todos"){//internet y todos los cobradores
          $query = "SELECT codigoCliente, nombre, direccion, tipoServicio,'I' as filtro, COUNT(*) as cantidadDeFacturasVencidas, fechaVencimiento, SUM(cuotaInternet + totalImpuesto) as totalDeuda
FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
AND codigoCliente NOT IN (
	/*--Cable en general*/
	SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
    AND codigoCliente  NOT IN(
		/*-clientes de solo cable ya suspendidos*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
		/*-fin clientes de solo cable ya suspendidos*/
    )group by `codigoCliente` HAVING COUNT(*) >= 2
	/*--fin Cable en general*/
	UNION
	/*--clientes de solo internet ya suspendidos*/
	SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
	/*-Fin clientes de solo internet ya suspendidos*/
) group by `codigoCliente` HAVING COUNT(*) >= 2";
          $resultado = $mysqli->query($query) ;
        }else{//INTERNET y cobrador espercifico
           $query = "SELECT codigoCliente, nombre, direccion, tipoServicio,'I' as filtro, COUNT(*) as cantidadDeFacturasVencidas, fechaVencimiento, SUM(cuotaInternet + totalImpuesto) as totalDeuda
FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
AND codigoCliente NOT IN (
	/*--Cable en general*/
	SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
    AND codigoCliente NOT IN (
		/*-clientes de solo cable ya suspendidos*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
		/*-fin clientes de solo cable ya suspendidos*/
    ) group by `codigoCliente` HAVING COUNT(*) >= 2
	/*--fin Cable en general*/
    UNION
	/*--clientes de solo internet ya suspendidos*/
	SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
	/*-Fin clientes de solo internet ya suspendidos*/
) group by `codigoCliente` HAVING COUNT(*) >= 2";
          $resultado = $mysqli->query($query) ;
        }

    }else if($_POST["susServicio"]=="P") { //paquete
      $servicioReporte="Paquete";
       if($_POST["susCobrador"]=="todos"){//paquete y todos los cobradores
          $query = "SELECT codigoCliente, nombre, direccion, tipoServicio,'P' as filtro, COUNT(*) as cantidadDeFacturasVencidas, fechaVencimiento, SUM(cuotaCable + cuotaInternet + totalImpuesto) as totalDeuda
FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND fechaVencimiento < now()
AND codigoCliente NOT IN (
            /*---SOLO CABLE TODOS LOS COBRADORES*/
			SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
			AND codigoCliente NOT IN (
				/*--Internet en general*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
				AND codigoCliente NOT IN (
					/*-clientes de solo internet ya suspendidos*/
					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
					/*-Fin clientes de solo internet ya suspendidos*/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
				/*--fin Internet en general*/
				UNION
				/*--clientes de solo cable ya suspendidos*/
				SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
				/*--fin clientes de solo cable ya suspendidos*/
			) group by `codigoCliente` HAVING COUNT(*) >= 2
			/*---FIN SOLO CABLE TODOS LOS COBRADORES*/
              UNION
			/*---SOLO INTERNET TODOS LOS COBRADORES*/
			SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
			AND codigoCliente NOT IN (
				/*--Cable en general*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
				AND codigoCliente  NOT IN(
					/*-clientes de solo cable ya suspendidos*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
					/*-fin clientes de solo cable ya suspendidos*/
				)group by `codigoCliente` HAVING COUNT(*) >= 2
				/*--fin Cable en general*/
				UNION
				/*--clientes de solo internet ya suspendidos*/
				SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
				/*--Fin clientes de solo internet ya suspendidos*/
			) group by `codigoCliente` HAVING COUNT(*) >= 2
			/*---FIN SOLO INTERNET TODOS LOS COBRADORES*/
            UNION
            /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
            SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
            /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
) group by `codigoCliente` HAVING COUNT(*) >= 4";
          $resultado = $mysqli->query($query) ;


     //Contar cuantas facturas de cable hay en los paquetes
      $queryCableDelPaquete="select SUM(cantidadDeFacturasVencidas) as cantidadFacturasCableDePaquete, SUM(totalDeuda) as totalDeudaCableDePaquete FROM(
SELECT COUNT(*) as cantidadDeFacturasVencidas , SUM(cuotaCable + totalImpuesto) as totalDeuda FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
AND codigoCliente IN (
/*-----PAQUETES*/
SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND fechaVencimiento < now()
AND codigoCliente NOT IN (
            /*---SOLO CABLE TODOS LOS COBRADORES*/
			SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
			AND codigoCliente NOT IN (
				/*--Internet en general*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
				AND codigoCliente NOT IN (
					/*-clientes de solo internet ya suspendidos*/
					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
					/*-Fin clientes de solo internet ya suspendidos*/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
				/*--fin Internet en general*/
				UNION
				/*--clientes de solo cable ya suspendidos*/
				SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
				/*--fin clientes de solo cable ya suspendidos*/
			) group by `codigoCliente` HAVING COUNT(*) >= 2
			/*---FIN SOLO CABLE TODOS LOS COBRADORES*/
              UNION
			/*---SOLO INTERNET TODOS LOS COBRADORES*/
			SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
			AND codigoCliente NOT IN (
				/*--Cable en general*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
				AND codigoCliente  NOT IN(
					/*-clientes de solo cable ya suspendidos*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
					/*-fin clientes de solo cable ya suspendidos*/
				)group by `codigoCliente` HAVING COUNT(*) >= 2
				/*--fin Cable en general*/
				UNION
				/*--clientes de solo internet ya suspendidos*/
				SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
				/*--Fin clientes de solo internet ya suspendidos*/
			) group by `codigoCliente` HAVING COUNT(*) >= 2
			/*---FIN SOLO INTERNET TODOS LOS COBRADORES*/
            UNION
            /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
            SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
            /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
) group by `codigoCliente` HAVING COUNT(*) >= 4
/*-----FIN PAQUETES*/
)  group by `codigoCliente` HAVING COUNT(*) >= 2) as facturasCable;";
      $resultadoCableDelPaquete= $mysqliCableDePaquete->query($queryCableDelPaquete) ;
      while($row = $resultadoCableDelPaquete->fetch_assoc()){
        $totalCantidadDefacturasCableDePaquete=$row['cantidadFacturasCableDePaquete'];
        $totalDeudaCableDePaquete=$row['totalDeudaCableDePaquete'];
      }
      //_______fin de Contar cable del paquete

      //Contar cuantas facturas de Internet hay en los paquetes
      $queryInternetDelPaquete="select SUM(cantidadDeFacturasVencidas) as cantidadFacturasInternetDePaquete, SUM(totalDeuda) as totalDeudaInternetDePaquete FROM(
SELECT COUNT(*) as cantidadDeFacturasVencidas , SUM(cuotaInternet + totalImpuesto) as totalDeuda FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
AND codigoCliente IN (
/*-----PAQUETES*/
SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND fechaVencimiento < now()
AND codigoCliente NOT IN (
            /*---SOLO CABLE TODOS LOS COBRADORES*/
			SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
			AND codigoCliente NOT IN (
				/*--Internet en general*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
				AND codigoCliente NOT IN (
					/*-clientes de solo internet ya suspendidos*/
					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
					/*-Fin clientes de solo internet ya suspendidos*/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
				/*--fin Internet en general*/
				UNION
				/*--clientes de solo cable ya suspendidos*/
				SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
				/*--fin clientes de solo cable ya suspendidos*/
			) group by `codigoCliente` HAVING COUNT(*) >= 2
			/*---FIN SOLO CABLE TODOS LOS COBRADORES*/
              UNION
			/*---SOLO INTERNET TODOS LOS COBRADORES*/
			SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
			AND codigoCliente NOT IN (
				/*--Cable en general*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
				AND codigoCliente  NOT IN(
					/*-clientes de solo cable ya suspendidos*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
					/*-fin clientes de solo cable ya suspendidos*/
				)group by `codigoCliente` HAVING COUNT(*) >= 2
				/*--fin Cable en general*/
				UNION
				/*--clientes de solo internet ya suspendidos*/
				SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
				/*--Fin clientes de solo internet ya suspendidos*/
			) group by `codigoCliente` HAVING COUNT(*) >= 2
			/*---FIN SOLO INTERNET TODOS LOS COBRADORES*/
            UNION
            /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
            SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
            /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
) group by `codigoCliente` HAVING COUNT(*) >= 4
/*-----FIN PAQUETES*/
)  group by `codigoCliente` HAVING COUNT(*) >= 2) as facturasInternet;";
      $resultadoInternetDelPaquete= $mysqliInternetDePaquete->query($queryInternetDelPaquete) ;
      while($row = $resultadoInternetDelPaquete->fetch_assoc()){
        $totalCantidadDefacturasInterDePaquete = $row['cantidadFacturasInternetDePaquete'];
        $totalDeudaInternetDePaquete = $row['totalDeudaInternetDePaquete'];
      }
      //_______fin de Contar Internet del paquete


        }else{//paquete y cobrador espercifico
            $query = "SELECT codigoCliente, nombre, direccion, tipoServicio,'P' as filtro, COUNT(*) as cantidadDeFacturasVencidas, fechaVencimiento, SUM(cuotaCable + cuotaInternet + totalImpuesto) as totalDeuda FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
            AND codigoCliente NOT IN (
              /*---SOLO CABLE POR COBRADOR ESPECIFICO*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
				AND codigoCliente NOT IN (
					/*--Internet en general*/
					SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
					AND codigoCliente NOT IN (
						/*-clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/*-Fin clientes de solo internet ya suspendidos*/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Internet en general*/
					UNION
					/*--clientes de solo cable ya suspendidos*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
					/*--fin clientes de solo cable ya suspendidos*/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
              /*---FIN SOLO CABLE POR COBRADOR ESPECIFICO*/
              UNION
              /*---SOLO INTERNET POR COBRADOR ESPECIFICO*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
				AND codigoCliente NOT IN (
					/*--Cable en general*/
					SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
					AND codigoCliente NOT IN (
						/*-clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/*-fin clientes de solo cable ya suspendidos*/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Cable en general*/
					UNION
					/*--clientes de solo internet ya suspendidos*/
					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
					/*-Fin clientes de solo internet ya suspendidos*/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
			  /*---FIN SOLO INTERNET POR COBRADOR ESPECIFICO*/
               UNION
			  /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
				SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
			  /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
) group by `codigoCliente` HAVING COUNT(*) >= 4";
          $resultado = $mysqli->query($query) ;


     //Contar cuantas facturas de cable hay en los paquetes por cobrador
      $queryCableDelPaquete="select SUM(cantidadDeFacturasVencidas) as cantidadFacturasCableDePaquete, SUM(totalDeuda) as totalDeudaCableDePaquete FROM(
  SELECT COUNT(*) as cantidadDeFacturasVencidas , SUM(cuotaCable + totalImpuesto) as totalDeuda FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
    AND codigoCliente IN (
    /*todos los clientes que tienen los dos servicios y ya estan vencidas las 4 facturas*/
    SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
            AND codigoCliente NOT IN (
              /*---SOLO CABLE POR COBRADOR ESPECIFICO*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
				AND codigoCliente NOT IN (
					/*--Internet en general*/
					SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
					AND codigoCliente NOT IN (
						/*-clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/*-Fin clientes de solo internet ya suspendidos*/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Internet en general*/
					UNION
					/*--clientes de solo cable ya suspendidos*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
					/*--fin clientes de solo cable ya suspendidos*/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
              /*---FIN SOLO CABLE POR COBRADOR ESPECIFICO*/
              UNION
              /*---SOLO INTERNET POR COBRADOR ESPECIFICO*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
				AND codigoCliente NOT IN (
					/*--Cable en general*/
					SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
					AND codigoCliente NOT IN (
						/*-clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/*-fin clientes de solo cable ya suspendidos*/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Cable en general*/
					UNION
					/*--clientes de solo internet ya suspendidos*/
					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
					/*-Fin clientes de solo internet ya suspendidos*/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
			  /*---FIN SOLO INTERNET POR COBRADOR ESPECIFICO*/
               UNION
			  /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
				SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
			  /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
) group by `codigoCliente` HAVING COUNT(*) >= 4
    /*fin de todos los clientes que tienen los dos servicios y ya estan vencidas las 4 facturas*/
)  group by `codigoCliente` HAVING COUNT(*) >= 2) as facturasCable;";
      $resultadoCableDelPaquete= $mysqliCableDePaquete->query($queryCableDelPaquete) ;
      while($row = $resultadoCableDelPaquete->fetch_assoc()){
        $totalCantidadDefacturasCableDePaquete=$row['cantidadFacturasCableDePaquete'];
        $totalDeudaCableDePaquete=$row['totalDeudaCableDePaquete'];
      }
      //_______fin de Contar cable del paquete por cobrador

      //Contar cuantas facturas de Internet hay en los paquetes por cobrador
      $queryInternetDelPaquete="select SUM(cantidadDeFacturasVencidas) as cantidadFacturasInternetDePaquete, SUM(totalDeuda) as totalDeudaInternetDePaquete FROM(
  SELECT COUNT(*) as cantidadDeFacturasVencidas , SUM(cuotaInternet + totalImpuesto) as totalDeuda FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
    AND codigoCliente IN (
    /*todos los clientes que tienen los dos servicios y ya estan vencidas las 4 facturas*/
     SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
            AND codigoCliente NOT IN (
              /*---SOLO CABLE POR COBRADOR ESPECIFICO*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
				AND codigoCliente NOT IN (
					/*--Internet en general*/
					SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
					AND codigoCliente NOT IN (
						/*-clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/*-Fin clientes de solo internet ya suspendidos*/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Internet en general*/
					UNION
					/*--clientes de solo cable ya suspendidos*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
					/*--fin clientes de solo cable ya suspendidos*/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
              /*---FIN SOLO CABLE POR COBRADOR ESPECIFICO*/
              UNION
              /*---SOLO INTERNET POR COBRADOR ESPECIFICO*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
				AND codigoCliente NOT IN (
					/*--Cable en general*/
					SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
					AND codigoCliente NOT IN (
						/*-clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/*-fin clientes de solo cable ya suspendidos*/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Cable en general*/
					UNION
					/*--clientes de solo internet ya suspendidos*/
					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
					/*-Fin clientes de solo internet ya suspendidos*/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
			  /*---FIN SOLO INTERNET POR COBRADOR ESPECIFICO*/
               UNION
			  /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
				SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
			  /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
) group by `codigoCliente` HAVING COUNT(*) >= 4
    /*fin de todos los clientes que tienen los dos servicios y ya estan vencidas las 4 facturas*/
)  group by `codigoCliente` HAVING COUNT(*) >= 2) as facturasInternet;";
      $resultadoInternetDelPaquete= $mysqliInternetDePaquete->query($queryInternetDelPaquete) ;
      while($row = $resultadoInternetDelPaquete->fetch_assoc()){
        $totalCantidadDefacturasInterDePaquete = $row['cantidadFacturasInternetDePaquete'];
        $totalDeudaInternetDePaquete = $row['totalDeudaInternetDePaquete'];
      }
      //_______fin de Contar Internet del paquete por cobrador



        }//end else paquete y cobrador especifico

    }else if($_POST["susServicio"]=="A"){//Todos los servicios, cable, internet y paquetes

      $servicioReporte="TODOS";
       if($_POST["susCobrador"]=="todos"){//Todos los servicios y todos los cobradores
          $query = "/*----SOLO CABLE*/
SELECT codigoCliente, nombre, direccion, tipoServicio,'C' as filtro, COUNT(*) as cantidadDeFacturasVencidas, fechaVencimiento, SUM(cuotaCable + totalImpuesto) as totalDeuda
FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
AND codigoCliente NOT IN (
	/*--Internet en general*/
	SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
    AND codigoCliente NOT IN (
		/*-clientes de solo internet ya suspendidos*/
		SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
        /*-Fin clientes de solo internet ya suspendidos*/
    ) group by `codigoCliente` HAVING COUNT(*) >= 2
    /*--fin Internet en general*/
    UNION
    /*--clientes de solo cable ya suspendidos*/
    SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
    /*--fin clientes de solo cable ya suspendidos*/
) group by `codigoCliente` HAVING COUNT(*) >= 2
/*----FIN SOLO CABLE*/
UNION
/*----SOLO INTERNET*/
SELECT codigoCliente, nombre, direccion, tipoServicio,'I' as filtro, COUNT(*) as cantidadDeFacturasVencidas, fechaVencimiento, SUM(cuotaInternet + totalImpuesto) as totalDeuda
FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
AND codigoCliente NOT IN (
	/*--Cable en general*/
	SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
    AND codigoCliente  NOT IN(
		/*-clientes de solo cable ya suspendidos*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
		/*-fin clientes de solo cable ya suspendidos*/
    )group by `codigoCliente` HAVING COUNT(*) >= 2
	/*--fin Cable en general*/
	UNION
	/*--clientes de solo internet ya suspendidos*/
	SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
	/*-Fin clientes de solo internet ya suspendidos*/
) group by `codigoCliente` HAVING COUNT(*) >= 2
/*----FIN SOLO INTERNET*/
UNION
/*----PAQUETES */
SELECT codigoCliente, nombre, direccion, tipoServicio,'P' as filtro, COUNT(*) as cantidadDeFacturasVencidas, fechaVencimiento, SUM(cuotaCable + cuotaInternet + totalImpuesto) as totalDeuda
FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND fechaVencimiento < now()
AND codigoCliente NOT IN (
            /*---SOLO CABLE TODOS LOS COBRADORES*/
			SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
			AND codigoCliente NOT IN (
				/*--Internet en general*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
				AND codigoCliente NOT IN (
					/*-clientes de solo internet ya suspendidos*/
					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
					/*-Fin clientes de solo internet ya suspendidos*/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
				/*--fin Internet en general*/
				UNION
				/*--clientes de solo cable ya suspendidos*/
				SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
				/*--fin clientes de solo cable ya suspendidos*/
			) group by `codigoCliente` HAVING COUNT(*) >= 2
			/*---FIN SOLO CABLE TODOS LOS COBRADORES*/
              UNION
			/*---SOLO INTERNET TODOS LOS COBRADORES*/
			SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
			AND codigoCliente NOT IN (
				/*--Cable en general*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
				AND codigoCliente  NOT IN(
					/*-clientes de solo cable ya suspendidos*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
					/*-fin clientes de solo cable ya suspendidos*/
				)group by `codigoCliente` HAVING COUNT(*) >= 2
				/*--fin Cable en general*/
				UNION
				/*--clientes de solo internet ya suspendidos*/
				SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
				/*--Fin clientes de solo internet ya suspendidos*/
			) group by `codigoCliente` HAVING COUNT(*) >= 2
			/*---FIN SOLO INTERNET TODOS LOS COBRADORES*/
            UNION
            /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
            SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
            /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
) group by `codigoCliente` HAVING COUNT(*) >= 4
/*----FIN PAQUETES */";
          $resultado = $mysqli->query($query) ;


          //Contar cuantas facturas de cable hay en los paquetes
           $queryCableDelPaquete="select SUM(cantidadDeFacturasVencidas) as cantidadFacturasCableDePaquete, SUM(totalDeuda) as totalDeudaCableDePaquete FROM(
     SELECT COUNT(*) as cantidadDeFacturasVencidas , SUM(cuotaCable + totalImpuesto) as totalDeuda FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
     AND codigoCliente IN (
     /*-----PAQUETES*/
     SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND fechaVencimiento < now()
     AND codigoCliente NOT IN (
                 /*---SOLO CABLE TODOS LOS COBRADORES*/
     			SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
     			AND codigoCliente NOT IN (
     				/*--Internet en general*/
     				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
     				AND codigoCliente NOT IN (
     					/*-clientes de solo internet ya suspendidos*/
     					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     					/*-Fin clientes de solo internet ya suspendidos*/
     				) group by `codigoCliente` HAVING COUNT(*) >= 2
     				/*--fin Internet en general*/
     				UNION
     				/*--clientes de solo cable ya suspendidos*/
     				SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     				/*--fin clientes de solo cable ya suspendidos*/
     			) group by `codigoCliente` HAVING COUNT(*) >= 2
     			/*---FIN SOLO CABLE TODOS LOS COBRADORES*/
                   UNION
     			/*---SOLO INTERNET TODOS LOS COBRADORES*/
     			SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
     			AND codigoCliente NOT IN (
     				/*--Cable en general*/
     				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
     				AND codigoCliente  NOT IN(
     					/*-clientes de solo cable ya suspendidos*/
     					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     					/*-fin clientes de solo cable ya suspendidos*/
     				)group by `codigoCliente` HAVING COUNT(*) >= 2
     				/*--fin Cable en general*/
     				UNION
     				/*--clientes de solo internet ya suspendidos*/
     				SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     				/*--Fin clientes de solo internet ya suspendidos*/
     			) group by `codigoCliente` HAVING COUNT(*) >= 2
     			/*---FIN SOLO INTERNET TODOS LOS COBRADORES*/
                 UNION
                 /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
                 SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
                 /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     ) group by `codigoCliente` HAVING COUNT(*) >= 4
     /*-----FIN PAQUETES*/
     )  group by `codigoCliente` HAVING COUNT(*) >= 2) as facturasCable;";
           $resultadoCableDelPaquete= $mysqliCableDePaquete->query($queryCableDelPaquete) ;
           while($row = $resultadoCableDelPaquete->fetch_assoc()){
             $totalCantidadDefacturasCableDePaquete=$row['cantidadFacturasCableDePaquete'];
             $totalDeudaCableDePaquete=$row['totalDeudaCableDePaquete'];
           }
           //_______fin de Contar cable del paquete

           //Contar cuantas facturas de Internet hay en los paquetes
           $queryInternetDelPaquete="select SUM(cantidadDeFacturasVencidas) as cantidadFacturasInternetDePaquete, SUM(totalDeuda) as totalDeudaInternetDePaquete FROM(
     SELECT COUNT(*) as cantidadDeFacturasVencidas , SUM(cuotaInternet + totalImpuesto) as totalDeuda FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
     AND codigoCliente IN (
     /*-----PAQUETES*/
     SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND fechaVencimiento < now()
     AND codigoCliente NOT IN (
                 /*---SOLO CABLE TODOS LOS COBRADORES*/
     			SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
     			AND codigoCliente NOT IN (
     				/*--Internet en general*/
     				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
     				AND codigoCliente NOT IN (
     					/*-clientes de solo internet ya suspendidos*/
     					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     					/*-Fin clientes de solo internet ya suspendidos*/
     				) group by `codigoCliente` HAVING COUNT(*) >= 2
     				/*--fin Internet en general*/
     				UNION
     				/*--clientes de solo cable ya suspendidos*/
     				SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     				/*--fin clientes de solo cable ya suspendidos*/
     			) group by `codigoCliente` HAVING COUNT(*) >= 2
     			/*---FIN SOLO CABLE TODOS LOS COBRADORES*/
                   UNION
     			/*---SOLO INTERNET TODOS LOS COBRADORES*/
     			SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
     			AND codigoCliente NOT IN (
     				/*--Cable en general*/
     				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
     				AND codigoCliente  NOT IN(
     					/*-clientes de solo cable ya suspendidos*/
     					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     					/*-fin clientes de solo cable ya suspendidos*/
     				)group by `codigoCliente` HAVING COUNT(*) >= 2
     				/*--fin Cable en general*/
     				UNION
     				/*--clientes de solo internet ya suspendidos*/
     				SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     				/*--Fin clientes de solo internet ya suspendidos*/
     			) group by `codigoCliente` HAVING COUNT(*) >= 2
     			/*---FIN SOLO INTERNET TODOS LOS COBRADORES*/
                 UNION
                 /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
                 SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
                 /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     ) group by `codigoCliente` HAVING COUNT(*) >= 4
     /*-----FIN PAQUETES*/
     )  group by `codigoCliente` HAVING COUNT(*) >= 2) as facturasInternet;";
           $resultadoInternetDelPaquete= $mysqliInternetDePaquete->query($queryInternetDelPaquete) ;
           while($row = $resultadoInternetDelPaquete->fetch_assoc()){
             $totalCantidadDefacturasInterDePaquete = $row['cantidadFacturasInternetDePaquete'];
             $totalDeudaInternetDePaquete = $row['totalDeudaInternetDePaquete'];
           }
           //_______fin de Contar Internet del paquete


    }else{//todos los servicios y cobrador espercifico
            $query = "/*----CABLE*/
SELECT codigoCliente, nombre, direccion, tipoServicio,'C' as filtro, COUNT(*) as cantidadDeFacturasVencidas, fechaVencimiento, SUM(cuotaCable + totalImpuesto) as totalDeuda
FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
AND codigoCliente NOT IN (
	/*--Internet en general*/
	SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
    AND codigoCliente NOT IN (
		/*-clientes de solo internet ya suspendidos*/
		SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
        /*-Fin clientes de solo internet ya suspendidos*/
    ) group by `codigoCliente` HAVING COUNT(*) >= 2
    /*--fin Internet en general*/
    UNION
    /*--clientes de solo cable ya suspendidos*/
    SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
    /*--fin clientes de solo cable ya suspendidos*/
) group by `codigoCliente` HAVING COUNT(*) >= 2
/*----FIN CABLE*/
          UNION
/*----SOLO INTERNET*/
SELECT codigoCliente, nombre, direccion, tipoServicio,'I' as filtro, COUNT(*) as cantidadDeFacturasVencidas, fechaVencimiento, SUM(cuotaInternet + totalImpuesto) as totalDeuda
FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
AND codigoCliente NOT IN (
	/*--Cable en general*/
	SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
    AND codigoCliente NOT IN (
		/*-clientes de solo cable ya suspendidos*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
		/*-fin clientes de solo cable ya suspendidos*/
    ) group by `codigoCliente` HAVING COUNT(*) >= 2
	/*--fin Cable en general*/
    UNION
	/*--clientes de solo internet ya suspendidos*/
	SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
	/*--Fin clientes de solo internet ya suspendidos*/
) group by `codigoCliente` HAVING COUNT(*) >= 2
/*----SOLO INTERNET*/
UNION
/*----PAQUETES*/
SELECT codigoCliente, nombre, direccion, tipoServicio,'P' as filtro, COUNT(*) as cantidadDeFacturasVencidas, fechaVencimiento, SUM(cuotaCable + cuotaInternet + totalImpuesto) as totalDeuda FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
            AND codigoCliente NOT IN (
              /*---SOLO CABLE POR COBRADOR ESPECIFICO*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
				AND codigoCliente NOT IN (
					/*--Internet en general*/
					SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
					AND codigoCliente NOT IN (
						/*-clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/*-Fin clientes de solo internet ya suspendidos*/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Internet en general*/
					UNION
					/*--clientes de solo cable ya suspendidos*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
					/*--fin clientes de solo cable ya suspendidos*/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
              /*---FIN SOLO CABLE POR COBRADOR ESPECIFICO*/
              UNION
              /*---SOLO INTERNET POR COBRADOR ESPECIFICO*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
				AND codigoCliente NOT IN (
					/*--Cable en general*/
					SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
					AND codigoCliente NOT IN (
						/*-clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/*-fin clientes de solo cable ya suspendidos*/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Cable en general*/
					UNION
					/*--clientes de solo internet ya suspendidos*/
					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
					/*-Fin clientes de solo internet ya suspendidos*/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
			  /*---FIN SOLO INTERNET POR COBRADOR ESPECIFICO*/
               UNION
			  /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
				SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
			  /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
) group by `codigoCliente` HAVING COUNT(*) >= 4
/*----PAQUETES*/";
          $resultado = $mysqli->query($query) or die($mysqli->error) ;



          //Contar cuantas facturas de cable hay en los paquetes por cobrador
           $queryCableDelPaquete="select SUM(cantidadDeFacturasVencidas) as cantidadFacturasCableDePaquete, SUM(totalDeuda) as totalDeudaCableDePaquete FROM(
       SELECT COUNT(*) as cantidadDeFacturasVencidas , SUM(cuotaCable + totalImpuesto) as totalDeuda FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
         AND codigoCliente IN (
         /*todos los clientes que tienen los dos servicios y ya estan vencidas las 4 facturas*/
         SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
                 AND codigoCliente NOT IN (
                   /*---SOLO CABLE POR COBRADOR ESPECIFICO*/
             SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
             AND codigoCliente NOT IN (
               /*--Internet en general*/
               SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
               AND codigoCliente NOT IN (
                 /*-clientes de solo internet ya suspendidos*/
                 SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
                 /*-Fin clientes de solo internet ya suspendidos*/
               ) group by `codigoCliente` HAVING COUNT(*) >= 2
               /*--fin Internet en general*/
               UNION
               /*--clientes de solo cable ya suspendidos*/
               SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
               /*--fin clientes de solo cable ya suspendidos*/
             ) group by `codigoCliente` HAVING COUNT(*) >= 2
                   /*---FIN SOLO CABLE POR COBRADOR ESPECIFICO*/
                   UNION
                   /*---SOLO INTERNET POR COBRADOR ESPECIFICO*/
             SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
             AND codigoCliente NOT IN (
               /*--Cable en general*/
               SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
               AND codigoCliente NOT IN (
                 /*-clientes de solo cable ya suspendidos*/
                 SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
                 /*-fin clientes de solo cable ya suspendidos*/
               ) group by `codigoCliente` HAVING COUNT(*) >= 2
               /*--fin Cable en general*/
               UNION
               /*--clientes de solo internet ya suspendidos*/
               SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
               /*-Fin clientes de solo internet ya suspendidos*/
             ) group by `codigoCliente` HAVING COUNT(*) >= 2
             /*---FIN SOLO INTERNET POR COBRADOR ESPECIFICO*/
                    UNION
             /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
             SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
             /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     ) group by `codigoCliente` HAVING COUNT(*) >= 4
         /*fin de todos los clientes que tienen los dos servicios y ya estan vencidas las 4 facturas*/
     )  group by `codigoCliente` HAVING COUNT(*) >= 2) as facturasCable;";
           $resultadoCableDelPaquete= $mysqliCableDePaquete->query($queryCableDelPaquete) ;
           while($row = $resultadoCableDelPaquete->fetch_assoc()){
             $totalCantidadDefacturasCableDePaquete=$row['cantidadFacturasCableDePaquete'];
             $totalDeudaCableDePaquete=$row['totalDeudaCableDePaquete'];
           }
           //_______fin de Contar cable del paquete por cobrador

           //Contar cuantas facturas de Internet hay en los paquetes por cobrador
           $queryInternetDelPaquete="select SUM(cantidadDeFacturasVencidas) as cantidadFacturasInternetDePaquete, SUM(totalDeuda) as totalDeudaInternetDePaquete FROM(
       SELECT COUNT(*) as cantidadDeFacturasVencidas , SUM(cuotaInternet + totalImpuesto) as totalDeuda FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
         AND codigoCliente IN (
         /*todos los clientes que tienen los dos servicios y ya estan vencidas las 4 facturas*/
          SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
                 AND codigoCliente NOT IN (
                   /*---SOLO CABLE POR COBRADOR ESPECIFICO*/
             SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
             AND codigoCliente NOT IN (
               /*--Internet en general*/
               SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
               AND codigoCliente NOT IN (
                 /*-clientes de solo internet ya suspendidos*/
                 SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
                 /*-Fin clientes de solo internet ya suspendidos*/
               ) group by `codigoCliente` HAVING COUNT(*) >= 2
               /*--fin Internet en general*/
               UNION
               /*--clientes de solo cable ya suspendidos*/
               SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
               /*--fin clientes de solo cable ya suspendidos*/
             ) group by `codigoCliente` HAVING COUNT(*) >= 2
                   /*---FIN SOLO CABLE POR COBRADOR ESPECIFICO*/
                   UNION
                   /*---SOLO INTERNET POR COBRADOR ESPECIFICO*/
             SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
             AND codigoCliente NOT IN (
               /*--Cable en general*/
               SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
               AND codigoCliente NOT IN (
                 /*-clientes de solo cable ya suspendidos*/
                 SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
                 /*-fin clientes de solo cable ya suspendidos*/
               ) group by `codigoCliente` HAVING COUNT(*) >= 2
               /*--fin Cable en general*/
               UNION
               /*--clientes de solo internet ya suspendidos*/
               SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
               /*-Fin clientes de solo internet ya suspendidos*/
             ) group by `codigoCliente` HAVING COUNT(*) >= 2
             /*---FIN SOLO INTERNET POR COBRADOR ESPECIFICO*/
                    UNION
             /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
             SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
             /*---CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     ) group by `codigoCliente` HAVING COUNT(*) >= 4
         /*fin de todos los clientes que tienen los dos servicios y ya estan vencidas las 4 facturas*/
     )  group by `codigoCliente` HAVING COUNT(*) >= 2) as facturasInternet;";
           $resultadoInternetDelPaquete= $mysqliInternetDePaquete->query($queryInternetDelPaquete) ;
           while($row = $resultadoInternetDelPaquete->fetch_assoc()){
             $totalCantidadDefacturasInterDePaquete = $row['cantidadFacturasInternetDePaquete'];
             $totalDeudaInternetDePaquete = $row['totalDeudaInternetDePaquete'];
           }
           //_______fin de Contar Internet del paquete por cobrador




        }//end else todos los servicios y cobrador especifico

    }//end if ALL Services



	  $pdf = new FPDF();
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
      $pdf->Cell(260,6,utf8_decode("INFORME DE CLIENTES A SUSPENDER "),0,1,'L');
      //filtro cobrador del reporte
      $pdf->Cell(260,6,utf8_decode("Cobrador: ".$cobradorReporte),0,1,'L');
      //filtro servicio del reporte
      $pdf->Cell(260,6,utf8_decode("Servicio: ".$servicioReporte),0,1,'L');

	  $pdf->SetFont('Arial','B',11);

	  date_default_timezone_set('America/El_Salvador');

	  //echo strftime("El año es %Y y el mes es %B");
      putenv("LANG='es_ES.UTF-8'");
      setlocale(LC_ALL, 'es_ES.UTF-8');
	  $pdf->SetFont('Arial','B',8);
$pdf->Ln(6);
      $pdf->Cell(10,6,utf8_decode('N°'),1,0,'L');
      $pdf->Cell(80,6,utf8_decode('Cliente'),1,0,'L');
      $pdf->Cell(92,6,utf8_decode('Direccion'),1,0,'L');
      $pdf->Cell(22,6,utf8_decode('Tipo Servicio'),1,0,'L');
      $pdf->Cell(32,6,utf8_decode('Cant. Fact. Vencidas'),1,0,'L');
      $pdf->Cell(20,6,utf8_decode('Deuda Total'),1,0,'L');
      $pdf->Ln(6);

        while($row = $resultado->fetch_assoc())
    	  {
            $tipoServicioTemp;
            $totalCantidadDeFacturasReporte = $totalCantidadDeFacturasReporte + $row['cantidadDeFacturasVencidas'];
            $totalDeudaReporte = $totalDeudaReporte + $row['totalDeuda'];


              if($row['tipoServicio']=="C" && $row['filtro']=="C"){
               $tipoServicioTemp="Cable";
               $totalCantidadDeFacturasSoloCable = $totalCantidadDeFacturasSoloCable + $row['cantidadDeFacturasVencidas'];
               $totalDeudaSoloCable= $totalDeudaSoloCable + $row['totalDeuda'];
              }else if($row['tipoServicio']=="I" && $row['filtro']=="I"){
                $tipoServicioTemp="Internet";
                $totalCantidadDeFacturasSoloInternet = $totalCantidadDeFacturasSoloInternet + $row['cantidadDeFacturasVencidas'];
                $totalDeudaSoloInternet= $totalDeudaSoloInternet + $row['totalDeuda'];
              }else if($row['filtro']=="P"){//paquete
                $tipoServicioTemp="Paquete";
              }//endPaquete

                $pdf->Ln(3);
                $pdf->SetFont('Arial','',6);
                $pdf->Cell(10,3,utf8_decode($contadorDeFilas),0,0,'L');
                $contadorDeFilas++;
                $pdf->Cell(80,3,utf8_decode(strtoupper($row['codigoCliente']."  ".$row['nombre'])),0,0,'L');
                $pdf->Cell(92,3,utf8_decode($row['direccion']),0,0,'L');
                $pdf->Cell(22,3,utf8_decode($tipoServicioTemp),0,0,'L');
                $pdf->Cell(32,3,utf8_decode($row['cantidadDeFacturasVencidas']),0,0,'C');
                $pdf->Cell(20,3,utf8_decode("$ ".number_format($row['totalDeuda'],2)),0,0,'L');
                 $pdf->Ln(3);
    	  }



        $pdf->Cell(185,5,utf8_decode(''),0,0,'R');
        $pdf->Cell(75,5,utf8_decode(''),"",1,'R');

        //$pdf->AddPage('L','Letter');
        $pdf->SetFont('Arial','',8);

        //TOTAL Ventas
        $pdf->Cell(204,5,utf8_decode('TOTAL: '),0,0,'R');
        $pdf->Cell(32,5,$totalCantidadDeFacturasReporte,"T",0,'C');
        $pdf->Cell(20,5,"$ ".number_format($totalDeudaReporte,2),"T",0,'L');
        $pdf->Ln(10);

  putenv("LANG='es_ES.UTF-8'");
      setlocale(LC_ALL, 'es_ES.UTF-8');
    $pdf->SetFont('Arial','B',8);
      //Totales del reporte
      $pdf->Cell(15,5,utf8_decode(''),0,0,'L');
      $pdf->Cell(40,5,"CANTIDAD DE FACTURAS",0,0,'C');
      $pdf->Cell(35,5,"MONTO DEUDA",0,0,'C');
$pdf->Ln(5);
      $pdf->Cell(15,5,utf8_decode('CABLE'),"T",0,'L');
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(40,5,$totalCantidadDeFacturasSoloCable + $totalCantidadDefacturasCableDePaquete,"T",0,'C');
      $pdf->Cell(35,5,"$ ".number_format($totalDeudaSoloCable + $totalDeudaCableDePaquete,2),"T",0,'C');
$pdf->Ln(5);
$pdf->SetFont('Arial','B',8);
      $pdf->Cell(15,5,utf8_decode('INTERNET'),0,0,'L');
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(40,5,$totalCantidadDeFacturasSoloInternet + $totalCantidadDefacturasInterDePaquete,0,0,'C');
      $pdf->Cell(35,5,"$ ".number_format($totalDeudaSoloInternet + $totalDeudaInternetDePaquete,2),0,0,'C');
      $pdf->SetFont('Arial','B',8);
$pdf->Ln(5);
      $pdf->Cell(15,5,utf8_decode('TOTAL'),"T",0,'L');
      $pdf->Cell(40,5,$totalCantidadDeFacturasSoloCable + $totalCantidadDefacturasCableDePaquete + $totalCantidadDeFacturasSoloInternet + $totalCantidadDefacturasInterDePaquete,"T",0,'C');
      $pdf->Cell(35,5,"$ ".number_format($totalDeudaSoloCable + $totalDeudaCableDePaquete + $totalDeudaSoloInternet + $totalDeudaInternetDePaquete,2),"T",0,'C');
/*
      //TOTAL CABLE
      $pdf->Cell(190,5,utf8_decode('TOTAL CABLE: '),0,0,'R');
      $pdf->Cell(20,5,number_format($totalSoloCable,2),"T",0,'L');
      $pdf->Cell(20,5,number_format($totalAnticipoSoloCable,2),"T",0,'L');
      $pdf->Cell(15,5,number_format(($totalAnticipoImpuestoC+$totalImpuestoC),2),"T",0,'L');
      $pdf->Cell(20,5,number_format(($totalSoloCable+$totalAnticipoSoloCable+$totalAnticipoImpuestoC+$totalImpuestoC),2),"T",1,'L');

      //TOTAL IMPUESTO
      $pdf->Cell(190,5,utf8_decode('TOTAL GENERAL: '),"",0,'R');
      $pdf->Cell(20,5,number_format(($totalSoloCable+$totalSoloInter),2),"T",0,'L');
      $pdf->Cell(20,5,number_format(($totalAnticipoSoloCable+$totalAnticipoSoloInter),2),"T",0,'L');
      $pdf->Cell(15,5,utf8_decode(number_format(($totalAnticipoImpuestoC+$totalImpuestoC+$totalAnticipoImpuestoI+$totalImpuestoI),2)),"T",0,'L');
      $pdf->Cell(20,5,number_format(($totalSoloCable+$totalSoloInter+$totalAnticipoSoloCable+$totalAnticipoSoloInter+$totalAnticipoImpuestoC+$totalImpuestoC+$totalAnticipoImpuestoI+$totalImpuestoI),2),"T",1,'L');

	  /* close connection */
	  mysqli_close($mysqli);
	  $pdf->Output();

  }


listaImpagos();

?>
