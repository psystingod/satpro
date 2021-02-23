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
    AND codigoCliente NOT IN (
		/**Todos Los Clientes Suspendidos**/
		/**clientes de solo cable ya suspendidos*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
		/**fin clientes de solo cable ya suspendidos*/
		UNION
		/**clientes de solo internet ya suspendidos*/
		SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
		/**Fin clientes de solo internet ya suspendidos*/
		UNION
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		/**Todos Los Clientes Suspendidos**/
    ) group by `codigoCliente` HAVING COUNT(*) >= 2
    /*--fin Internet en general*/
    UNION
    	/**Todos Los Clientes Suspendidos**/
		/**clientes de solo cable ya suspendidos*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
		/**fin clientes de solo cable ya suspendidos*/
		UNION
		/**clientes de solo internet ya suspendidos*/
		SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
		/**Fin clientes de solo internet ya suspendidos*/
		UNION
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		/**Todos Los Clientes Suspendidos**/
) group by `codigoCliente` HAVING COUNT(*) >= 2";
          $resultado = $mysqli->query($query) ;
        }else{//cable y cobrador espercifico
           $query = "SELECT codigoCliente, nombre, direccion, tipoServicio,'C' as filtro, COUNT(*) as cantidadDeFacturasVencidas, fechaVencimiento, SUM(cuotaCable + totalImpuesto) as totalDeuda
FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
AND codigoCliente NOT IN (
	/*--Internet en general*/
	SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
    AND codigoCliente NOT IN (
			/**Todos Los Clientes Suspendidos**/
			/**clientes de solo cable ya suspendidos*/
			SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
			/**fin clientes de solo cable ya suspendidos*/
			UNION
			/**clientes de solo internet ya suspendidos*/
			SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
			/**Fin clientes de solo internet ya suspendidos*/
			UNION
			/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
			SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
			/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
			/**Todos Los Clientes Suspendidos**/
    ) group by `codigoCliente` HAVING COUNT(*) >= 2
    /*--fin Internet en general*/
    UNION
    	/**Todos Los Clientes Suspendidos**/
		/**clientes de solo cable ya suspendidos*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
		/**fin clientes de solo cable ya suspendidos*/
		UNION
		/**clientes de solo internet ya suspendidos*/
		SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
		/**Fin clientes de solo internet ya suspendidos*/
		UNION
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		/**Todos Los Clientes Suspendidos**/
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
			/**Todos Los Clientes Suspendidos**/
			/**clientes de solo cable ya suspendidos*/
			SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
			/**fin clientes de solo cable ya suspendidos*/
			UNION
			/**clientes de solo internet ya suspendidos*/
			SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
			/**Fin clientes de solo internet ya suspendidos*/
			UNION
			/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
			SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
			/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
			/**Todos Los Clientes Suspendidos**/
    )group by `codigoCliente` HAVING COUNT(*) >= 2
	/*--fin Cable en general*/
	UNION
		/**Todos Los Clientes Suspendidos**/
		/**clientes de solo cable ya suspendidos*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
		/**fin clientes de solo cable ya suspendidos*/
		UNION
		/**clientes de solo internet ya suspendidos*/
		SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
		/**Fin clientes de solo internet ya suspendidos*/
		UNION
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		/**Todos Los Clientes Suspendidos**/
) group by `codigoCliente` HAVING COUNT(*) >= 2";
          $resultado = $mysqli->query($query) ;
        }else{//INTERNET y cobrador espercifico
           $query = "SELECT codigoCliente, nombre, direccion, tipoServicio,'I' as filtro, COUNT(*) as cantidadDeFacturasVencidas, fechaVencimiento, SUM(cuotaInternet + totalImpuesto) as totalDeuda
FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
AND codigoCliente NOT IN (
	/*--Cable en general*/
	SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
    AND codigoCliente NOT IN (
			/**Todos Los Clientes Suspendidos**/
			/**clientes de solo cable ya suspendidos*/
			SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
			/**fin clientes de solo cable ya suspendidos*/
			UNION
			/**clientes de solo internet ya suspendidos*/
			SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
			/**Fin clientes de solo internet ya suspendidos*/
			UNION
			/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
			SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
			/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
			/**Todos Los Clientes Suspendidos**/
    ) group by `codigoCliente` HAVING COUNT(*) >= 2
	/*--fin Cable en general*/
    UNION
		/**Todos Los Clientes Suspendidos**/
		/**clientes de solo cable ya suspendidos*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
		/**fin clientes de solo cable ya suspendidos*/
		UNION
		/**clientes de solo internet ya suspendidos*/
		SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
		/**Fin clientes de solo internet ya suspendidos*/
		UNION
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		/**Todos Los Clientes Suspendidos**/
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
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Internet en general*/
					UNION
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
			/*---FIN SOLO CABLE TODOS LOS COBRADORES*/
              UNION
			/*---SOLO INTERNET TODOS LOS COBRADORES*/
						SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
			AND codigoCliente NOT IN (
				/*--Cable en general*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
				AND codigoCliente  NOT IN(
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
				)group by `codigoCliente` HAVING COUNT(*) >= 2
				/*--fin Cable en general*/
				UNION
					/**Todos Los Clientes Suspendidos**/
					/**clientes de solo cable ya suspendidos*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
					/**fin clientes de solo cable ya suspendidos*/
					UNION
					/**clientes de solo internet ya suspendidos*/
					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
					/**Fin clientes de solo internet ya suspendidos*/
					UNION
					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
					/**Todos Los Clientes Suspendidos**/
			) group by `codigoCliente` HAVING COUNT(*) >= 2
			/*---FIN SOLO INTERNET TODOS LOS COBRADORES*/
            UNION
					/**Todos Los Clientes Suspendidos**/
					/**clientes de solo cable ya suspendidos*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
					/**fin clientes de solo cable ya suspendidos*/
					UNION
					/**clientes de solo internet ya suspendidos*/
					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
					/**Fin clientes de solo internet ya suspendidos*/
					UNION
					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
					/**Todos Los Clientes Suspendidos**/
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
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Internet en general*/
					UNION
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
			/*---FIN SOLO CABLE TODOS LOS COBRADORES*/
              UNION
			/*---SOLO INTERNET TODOS LOS COBRADORES*/
						SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
			AND codigoCliente NOT IN (
				/*--Cable en general*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
				AND codigoCliente  NOT IN(
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
				)group by `codigoCliente` HAVING COUNT(*) >= 2
				/*--fin Cable en general*/
				UNION
					/**Todos Los Clientes Suspendidos**/
					/**clientes de solo cable ya suspendidos*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
					/**fin clientes de solo cable ya suspendidos*/
					UNION
					/**clientes de solo internet ya suspendidos*/
					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
					/**Fin clientes de solo internet ya suspendidos*/
					UNION
					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
					/**Todos Los Clientes Suspendidos**/
			) group by `codigoCliente` HAVING COUNT(*) >= 2
			/*---FIN SOLO INTERNET TODOS LOS COBRADORES*/
            UNION
					/**Todos Los Clientes Suspendidos**/
					/**clientes de solo cable ya suspendidos*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
					/**fin clientes de solo cable ya suspendidos*/
					UNION
					/**clientes de solo internet ya suspendidos*/
					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
					/**Fin clientes de solo internet ya suspendidos*/
					UNION
					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
					/**Todos Los Clientes Suspendidos**/
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
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Internet en general*/
					UNION
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
			/*---FIN SOLO CABLE TODOS LOS COBRADORES*/
              UNION
			/*---SOLO INTERNET TODOS LOS COBRADORES*/
						SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
			AND codigoCliente NOT IN (
				/*--Cable en general*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
				AND codigoCliente  NOT IN(
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
				)group by `codigoCliente` HAVING COUNT(*) >= 2
				/*--fin Cable en general*/
				UNION
					/**Todos Los Clientes Suspendidos**/
					/**clientes de solo cable ya suspendidos*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
					/**fin clientes de solo cable ya suspendidos*/
					UNION
					/**clientes de solo internet ya suspendidos*/
					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
					/**Fin clientes de solo internet ya suspendidos*/
					UNION
					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
					/**Todos Los Clientes Suspendidos**/
			) group by `codigoCliente` HAVING COUNT(*) >= 2
			/*---FIN SOLO INTERNET TODOS LOS COBRADORES*/
            UNION
					/**Todos Los Clientes Suspendidos**/
					/**clientes de solo cable ya suspendidos*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
					/**fin clientes de solo cable ya suspendidos*/
					UNION
					/**clientes de solo internet ya suspendidos*/
					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
					/**Fin clientes de solo internet ya suspendidos*/
					UNION
					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
					/**Todos Los Clientes Suspendidos**/
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
            $query = "SELECT codigoCliente, nombre, direccion, tipoServicio,'P' as filtro, COUNT(*) as cantidadDeFacturasVencidas, fechaVencimiento, SUM(cuotaCable + cuotaInternet + totalImpuesto) as totalDeuda
FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
            AND codigoCliente NOT IN (
              /*---SOLO CABLE POR COBRADOR ESPECIFICO*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
				AND codigoCliente NOT IN (
					/*--Internet en general*/
					SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
					AND codigoCliente NOT IN (
							/**Todos Los Clientes Suspendidos**/
							/**clientes de solo cable ya suspendidos*/
							SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
							/**fin clientes de solo cable ya suspendidos*/
							UNION
							/**clientes de solo internet ya suspendidos*/
							SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
							/**Fin clientes de solo internet ya suspendidos*/
							UNION
							/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
							SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
							/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
							/**Todos Los Clientes Suspendidos**/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Internet en general*/
					UNION
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
              /*---FIN SOLO CABLE POR COBRADOR ESPECIFICO*/
              UNION
              /*---SOLO INTERNET POR COBRADOR ESPECIFICO*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
				AND codigoCliente NOT IN (
					/*--Cable en general*/
					SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
					AND codigoCliente NOT IN (
							/**Todos Los Clientes Suspendidos**/
							/**clientes de solo cable ya suspendidos*/
							SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
							/**fin clientes de solo cable ya suspendidos*/
							UNION
							/**clientes de solo internet ya suspendidos*/
							SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
							/**Fin clientes de solo internet ya suspendidos*/
							UNION
							/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
							SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
							/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
							/**Todos Los Clientes Suspendidos**/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Cable en general*/
					UNION
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
			  /*---FIN SOLO INTERNET POR COBRADOR ESPECIFICO*/
               UNION
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
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
									/**Todos Los Clientes Suspendidos**/
									/**clientes de solo cable ya suspendidos*/
									SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
									/**fin clientes de solo cable ya suspendidos*/
									UNION
									/**clientes de solo internet ya suspendidos*/
									SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
									/**Fin clientes de solo internet ya suspendidos*/
									UNION
									/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
									SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
									/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
									/**Todos Los Clientes Suspendidos**/
							) group by `codigoCliente` HAVING COUNT(*) >= 2
							/*--fin Internet en general*/
							UNION
								/**Todos Los Clientes Suspendidos**/
								/**clientes de solo cable ya suspendidos*/
								SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
								/**fin clientes de solo cable ya suspendidos*/
								UNION
								/**clientes de solo internet ya suspendidos*/
								SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
								/**Fin clientes de solo internet ya suspendidos*/
								UNION
								/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
								SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
								/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
								/**Todos Los Clientes Suspendidos**/
						) group by `codigoCliente` HAVING COUNT(*) >= 2
					  /*---FIN SOLO CABLE POR COBRADOR ESPECIFICO*/
					  UNION
					  /*---SOLO INTERNET POR COBRADOR ESPECIFICO*/
						SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
						AND codigoCliente NOT IN (
							/*--Cable en general*/
							SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
							AND codigoCliente NOT IN (
									/**Todos Los Clientes Suspendidos**/
									/**clientes de solo cable ya suspendidos*/
									SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
									/**fin clientes de solo cable ya suspendidos*/
									UNION
									/**clientes de solo internet ya suspendidos*/
									SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
									/**Fin clientes de solo internet ya suspendidos*/
									UNION
									/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
									SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
									/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
									/**Todos Los Clientes Suspendidos**/
							) group by `codigoCliente` HAVING COUNT(*) >= 2
							/*--fin Cable en general*/
							UNION
								/**Todos Los Clientes Suspendidos**/
								/**clientes de solo cable ya suspendidos*/
								SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
								/**fin clientes de solo cable ya suspendidos*/
								UNION
								/**clientes de solo internet ya suspendidos*/
								SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
								/**Fin clientes de solo internet ya suspendidos*/
								UNION
								/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
								SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
								/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
								/**Todos Los Clientes Suspendidos**/
						) group by `codigoCliente` HAVING COUNT(*) >= 2
					  /*---FIN SOLO INTERNET POR COBRADOR ESPECIFICO*/
					   UNION
								/**Todos Los Clientes Suspendidos**/
								/**clientes de solo cable ya suspendidos*/
								SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
								/**fin clientes de solo cable ya suspendidos*/
								UNION
								/**clientes de solo internet ya suspendidos*/
								SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
								/**Fin clientes de solo internet ya suspendidos*/
								UNION
								/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
								SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
								/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
								/**Todos Los Clientes Suspendidos**/
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
							/**Todos Los Clientes Suspendidos**/
							/**clientes de solo cable ya suspendidos*/
							SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
							/**fin clientes de solo cable ya suspendidos*/
							UNION
							/**clientes de solo internet ya suspendidos*/
							SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
							/**Fin clientes de solo internet ya suspendidos*/
							UNION
							/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
							SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
							/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
							/**Todos Los Clientes Suspendidos**/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Internet en general*/
					UNION
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
              /*---FIN SOLO CABLE POR COBRADOR ESPECIFICO*/
              UNION
              /*---SOLO INTERNET POR COBRADOR ESPECIFICO*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
				AND codigoCliente NOT IN (
					/*--Cable en general*/
					SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
					AND codigoCliente NOT IN (
							/**Todos Los Clientes Suspendidos**/
							/**clientes de solo cable ya suspendidos*/
							SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
							/**fin clientes de solo cable ya suspendidos*/
							UNION
							/**clientes de solo internet ya suspendidos*/
							SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
							/**Fin clientes de solo internet ya suspendidos*/
							UNION
							/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
							SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
							/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
							/**Todos Los Clientes Suspendidos**/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Cable en general*/
					UNION
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
			  /*---FIN SOLO INTERNET POR COBRADOR ESPECIFICO*/
               UNION
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
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
		/**Todos Los Clientes Suspendidos**/
		/**clientes de solo cable ya suspendidos*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
		/**fin clientes de solo cable ya suspendidos*/
		UNION
		/**clientes de solo internet ya suspendidos*/
		SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
		/**Fin clientes de solo internet ya suspendidos*/
		UNION
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		/**Todos Los Clientes Suspendidos**/
    ) group by `codigoCliente` HAVING COUNT(*) >= 2
    /*--fin Internet en general*/
    UNION
    	/**Todos Los Clientes Suspendidos**/
		/**clientes de solo cable ya suspendidos*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
		/**fin clientes de solo cable ya suspendidos*/
		UNION
		/**clientes de solo internet ya suspendidos*/
		SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
		/**Fin clientes de solo internet ya suspendidos*/
		UNION
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		/**Todos Los Clientes Suspendidos**/
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
			/**Todos Los Clientes Suspendidos**/
			/**clientes de solo cable ya suspendidos*/
			SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
			/**fin clientes de solo cable ya suspendidos*/
			UNION
			/**clientes de solo internet ya suspendidos*/
			SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
			/**Fin clientes de solo internet ya suspendidos*/
			UNION
			/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
			SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
			/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
			/**Todos Los Clientes Suspendidos**/
    )group by `codigoCliente` HAVING COUNT(*) >= 2
	/*--fin Cable en general*/
	UNION
		/**Todos Los Clientes Suspendidos**/
		/**clientes de solo cable ya suspendidos*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
		/**fin clientes de solo cable ya suspendidos*/
		UNION
		/**clientes de solo internet ya suspendidos*/
		SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
		/**Fin clientes de solo internet ya suspendidos*/
		UNION
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		/**Todos Los Clientes Suspendidos**/
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
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Internet en general*/
					UNION
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
			/*---FIN SOLO CABLE TODOS LOS COBRADORES*/
              UNION
			/*---SOLO INTERNET TODOS LOS COBRADORES*/
						SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
			AND codigoCliente NOT IN (
				/*--Cable en general*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
				AND codigoCliente  NOT IN(
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
				)group by `codigoCliente` HAVING COUNT(*) >= 2
				/*--fin Cable en general*/
				UNION
					/**Todos Los Clientes Suspendidos**/
					/**clientes de solo cable ya suspendidos*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
					/**fin clientes de solo cable ya suspendidos*/
					UNION
					/**clientes de solo internet ya suspendidos*/
					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
					/**Fin clientes de solo internet ya suspendidos*/
					UNION
					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
					/**Todos Los Clientes Suspendidos**/
			) group by `codigoCliente` HAVING COUNT(*) >= 2
			/*---FIN SOLO INTERNET TODOS LOS COBRADORES*/
            UNION
					/**Todos Los Clientes Suspendidos**/
					/**clientes de solo cable ya suspendidos*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
					/**fin clientes de solo cable ya suspendidos*/
					UNION
					/**clientes de solo internet ya suspendidos*/
					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
					/**Fin clientes de solo internet ya suspendidos*/
					UNION
					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
					/**Todos Los Clientes Suspendidos**/
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
     						/**Todos Los Clientes Suspendidos**/
     						/**clientes de solo cable ya suspendidos*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     						/**fin clientes de solo cable ya suspendidos*/
     						UNION
     						/**clientes de solo internet ya suspendidos*/
     						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     						/**Fin clientes de solo internet ya suspendidos*/
     						UNION
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						/**Todos Los Clientes Suspendidos**/
     					) group by `codigoCliente` HAVING COUNT(*) >= 2
     					/*--fin Internet en general*/
     					UNION
     						/**Todos Los Clientes Suspendidos**/
     						/**clientes de solo cable ya suspendidos*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     						/**fin clientes de solo cable ya suspendidos*/
     						UNION
     						/**clientes de solo internet ya suspendidos*/
     						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     						/**Fin clientes de solo internet ya suspendidos*/
     						UNION
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						/**Todos Los Clientes Suspendidos**/
     				) group by `codigoCliente` HAVING COUNT(*) >= 2
     			/*---FIN SOLO CABLE TODOS LOS COBRADORES*/
                   UNION
     			/*---SOLO INTERNET TODOS LOS COBRADORES*/
     						SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
     			AND codigoCliente NOT IN (
     				/*--Cable en general*/
     				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
     				AND codigoCliente  NOT IN(
     						/**Todos Los Clientes Suspendidos**/
     						/**clientes de solo cable ya suspendidos*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     						/**fin clientes de solo cable ya suspendidos*/
     						UNION
     						/**clientes de solo internet ya suspendidos*/
     						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     						/**Fin clientes de solo internet ya suspendidos*/
     						UNION
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						/**Todos Los Clientes Suspendidos**/
     				)group by `codigoCliente` HAVING COUNT(*) >= 2
     				/*--fin Cable en general*/
     				UNION
     					/**Todos Los Clientes Suspendidos**/
     					/**clientes de solo cable ya suspendidos*/
     					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     					/**fin clientes de solo cable ya suspendidos*/
     					UNION
     					/**clientes de solo internet ya suspendidos*/
     					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     					/**Fin clientes de solo internet ya suspendidos*/
     					UNION
     					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     					/**Todos Los Clientes Suspendidos**/
     			) group by `codigoCliente` HAVING COUNT(*) >= 2
     			/*---FIN SOLO INTERNET TODOS LOS COBRADORES*/
                 UNION
     					/**Todos Los Clientes Suspendidos**/
     					/**clientes de solo cable ya suspendidos*/
     					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     					/**fin clientes de solo cable ya suspendidos*/
     					UNION
     					/**clientes de solo internet ya suspendidos*/
     					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     					/**Fin clientes de solo internet ya suspendidos*/
     					UNION
     					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     					/**Todos Los Clientes Suspendidos**/
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
     						/**Todos Los Clientes Suspendidos**/
     						/**clientes de solo cable ya suspendidos*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     						/**fin clientes de solo cable ya suspendidos*/
     						UNION
     						/**clientes de solo internet ya suspendidos*/
     						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     						/**Fin clientes de solo internet ya suspendidos*/
     						UNION
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						/**Todos Los Clientes Suspendidos**/
     					) group by `codigoCliente` HAVING COUNT(*) >= 2
     					/*--fin Internet en general*/
     					UNION
     						/**Todos Los Clientes Suspendidos**/
     						/**clientes de solo cable ya suspendidos*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     						/**fin clientes de solo cable ya suspendidos*/
     						UNION
     						/**clientes de solo internet ya suspendidos*/
     						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     						/**Fin clientes de solo internet ya suspendidos*/
     						UNION
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						/**Todos Los Clientes Suspendidos**/
     				) group by `codigoCliente` HAVING COUNT(*) >= 2
     			/*---FIN SOLO CABLE TODOS LOS COBRADORES*/
                   UNION
     			/*---SOLO INTERNET TODOS LOS COBRADORES*/
     						SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now()
     			AND codigoCliente NOT IN (
     				/*--Cable en general*/
     				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now()
     				AND codigoCliente  NOT IN(
     						/**Todos Los Clientes Suspendidos**/
     						/**clientes de solo cable ya suspendidos*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     						/**fin clientes de solo cable ya suspendidos*/
     						UNION
     						/**clientes de solo internet ya suspendidos*/
     						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     						/**Fin clientes de solo internet ya suspendidos*/
     						UNION
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						/**Todos Los Clientes Suspendidos**/
     				)group by `codigoCliente` HAVING COUNT(*) >= 2
     				/*--fin Cable en general*/
     				UNION
     					/**Todos Los Clientes Suspendidos**/
     					/**clientes de solo cable ya suspendidos*/
     					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     					/**fin clientes de solo cable ya suspendidos*/
     					UNION
     					/**clientes de solo internet ya suspendidos*/
     					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     					/**Fin clientes de solo internet ya suspendidos*/
     					UNION
     					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     					/**Todos Los Clientes Suspendidos**/
     			) group by `codigoCliente` HAVING COUNT(*) >= 2
     			/*---FIN SOLO INTERNET TODOS LOS COBRADORES*/
                 UNION
     					/**Todos Los Clientes Suspendidos**/
     					/**clientes de solo cable ya suspendidos*/
     					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     					/**fin clientes de solo cable ya suspendidos*/
     					UNION
     					/**clientes de solo internet ya suspendidos*/
     					SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     					/**Fin clientes de solo internet ya suspendidos*/
     					UNION
     					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     					SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     					/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     					/**Todos Los Clientes Suspendidos**/
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
			/**Todos Los Clientes Suspendidos**/
			/**clientes de solo cable ya suspendidos*/
			SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
			/**fin clientes de solo cable ya suspendidos*/
			UNION
			/**clientes de solo internet ya suspendidos*/
			SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
			/**Fin clientes de solo internet ya suspendidos*/
			UNION
			/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
			SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
			/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
			/**Todos Los Clientes Suspendidos**/
    ) group by `codigoCliente` HAVING COUNT(*) >= 2
    /*--fin Internet en general*/
    UNION
    	/**Todos Los Clientes Suspendidos**/
		/**clientes de solo cable ya suspendidos*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
		/**fin clientes de solo cable ya suspendidos*/
		UNION
		/**clientes de solo internet ya suspendidos*/
		SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
		/**Fin clientes de solo internet ya suspendidos*/
		UNION
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		/**Todos Los Clientes Suspendidos**/
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
			/**Todos Los Clientes Suspendidos**/
			/**clientes de solo cable ya suspendidos*/
			SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
			/**fin clientes de solo cable ya suspendidos*/
			UNION
			/**clientes de solo internet ya suspendidos*/
			SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
			/**Fin clientes de solo internet ya suspendidos*/
			UNION
			/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
			SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
			/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
			/**Todos Los Clientes Suspendidos**/
    ) group by `codigoCliente` HAVING COUNT(*) >= 2
	/*--fin Cable en general*/
    UNION
		/**Todos Los Clientes Suspendidos**/
		/**clientes de solo cable ya suspendidos*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
		/**fin clientes de solo cable ya suspendidos*/
		UNION
		/**clientes de solo internet ya suspendidos*/
		SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
		/**Fin clientes de solo internet ya suspendidos*/
		UNION
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
		/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
		/**Todos Los Clientes Suspendidos**/
) group by `codigoCliente` HAVING COUNT(*) >= 2
/*----FIN SOLO INTERNET*/
UNION
/*----PAQUETES*/
SELECT codigoCliente, nombre, direccion, tipoServicio,'P' as filtro, COUNT(*) as cantidadDeFacturasVencidas, fechaVencimiento, SUM(cuotaCable + cuotaInternet + totalImpuesto) as totalDeuda
FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
            AND codigoCliente NOT IN (
              /*---SOLO CABLE POR COBRADOR ESPECIFICO*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
				AND codigoCliente NOT IN (
					/*--Internet en general*/
					SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
					AND codigoCliente NOT IN (
							/**Todos Los Clientes Suspendidos**/
							/**clientes de solo cable ya suspendidos*/
							SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
							/**fin clientes de solo cable ya suspendidos*/
							UNION
							/**clientes de solo internet ya suspendidos*/
							SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
							/**Fin clientes de solo internet ya suspendidos*/
							UNION
							/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
							SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
							/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
							/**Todos Los Clientes Suspendidos**/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Internet en general*/
					UNION
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
              /*---FIN SOLO CABLE POR COBRADOR ESPECIFICO*/
              UNION
              /*---SOLO INTERNET POR COBRADOR ESPECIFICO*/
				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
				AND codigoCliente NOT IN (
					/*--Cable en general*/
					SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
					AND codigoCliente NOT IN (
							/**Todos Los Clientes Suspendidos**/
							/**clientes de solo cable ya suspendidos*/
							SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
							/**fin clientes de solo cable ya suspendidos*/
							UNION
							/**clientes de solo internet ya suspendidos*/
							SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
							/**Fin clientes de solo internet ya suspendidos*/
							UNION
							/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
							SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
							/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
							/**Todos Los Clientes Suspendidos**/
					) group by `codigoCliente` HAVING COUNT(*) >= 2
					/*--fin Cable en general*/
					UNION
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
				) group by `codigoCliente` HAVING COUNT(*) >= 2
			  /*---FIN SOLO INTERNET POR COBRADOR ESPECIFICO*/
               UNION
						/**Todos Los Clientes Suspendidos**/
						/**clientes de solo cable ya suspendidos*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
						/**fin clientes de solo cable ya suspendidos*/
						UNION
						/**clientes de solo internet ya suspendidos*/
						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
						/**Fin clientes de solo internet ya suspendidos*/
						UNION
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
						/**Todos Los Clientes Suspendidos**/
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
     									/**Todos Los Clientes Suspendidos**/
     									/**clientes de solo cable ya suspendidos*/
     									SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     									/**fin clientes de solo cable ya suspendidos*/
     									UNION
     									/**clientes de solo internet ya suspendidos*/
     									SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     									/**Fin clientes de solo internet ya suspendidos*/
     									UNION
     									/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     									SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     									/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     									/**Todos Los Clientes Suspendidos**/
     							) group by `codigoCliente` HAVING COUNT(*) >= 2
     							/*--fin Internet en general*/
     							UNION
     								/**Todos Los Clientes Suspendidos**/
     								/**clientes de solo cable ya suspendidos*/
     								SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     								/**fin clientes de solo cable ya suspendidos*/
     								UNION
     								/**clientes de solo internet ya suspendidos*/
     								SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     								/**Fin clientes de solo internet ya suspendidos*/
     								UNION
     								/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     								SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     								/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     								/**Todos Los Clientes Suspendidos**/
     						) group by `codigoCliente` HAVING COUNT(*) >= 2
     					  /*---FIN SOLO CABLE POR COBRADOR ESPECIFICO*/
     					  UNION
     					  /*---SOLO INTERNET POR COBRADOR ESPECIFICO*/
     						SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
     						AND codigoCliente NOT IN (
     							/*--Cable en general*/
     							SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
     							AND codigoCliente NOT IN (
     									/**Todos Los Clientes Suspendidos**/
     									/**clientes de solo cable ya suspendidos*/
     									SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     									/**fin clientes de solo cable ya suspendidos*/
     									UNION
     									/**clientes de solo internet ya suspendidos*/
     									SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     									/**Fin clientes de solo internet ya suspendidos*/
     									UNION
     									/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     									SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     									/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     									/**Todos Los Clientes Suspendidos**/
     							) group by `codigoCliente` HAVING COUNT(*) >= 2
     							/*--fin Cable en general*/
     							UNION
     								/**Todos Los Clientes Suspendidos**/
     								/**clientes de solo cable ya suspendidos*/
     								SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     								/**fin clientes de solo cable ya suspendidos*/
     								UNION
     								/**clientes de solo internet ya suspendidos*/
     								SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     								/**Fin clientes de solo internet ya suspendidos*/
     								UNION
     								/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     								SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     								/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     								/**Todos Los Clientes Suspendidos**/
     						) group by `codigoCliente` HAVING COUNT(*) >= 2
     					  /*---FIN SOLO INTERNET POR COBRADOR ESPECIFICO*/
     					   UNION
     								/**Todos Los Clientes Suspendidos**/
     								/**clientes de solo cable ya suspendidos*/
     								SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     								/**fin clientes de solo cable ya suspendidos*/
     								UNION
     								/**clientes de solo internet ya suspendidos*/
     								SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     								/**Fin clientes de solo internet ya suspendidos*/
     								UNION
     								/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     								SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     								/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     								/**Todos Los Clientes Suspendidos**/
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
     							/**Todos Los Clientes Suspendidos**/
     							/**clientes de solo cable ya suspendidos*/
     							SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     							/**fin clientes de solo cable ya suspendidos*/
     							UNION
     							/**clientes de solo internet ya suspendidos*/
     							SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     							/**Fin clientes de solo internet ya suspendidos*/
     							UNION
     							/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     							SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     							/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     							/**Todos Los Clientes Suspendidos**/
     					) group by `codigoCliente` HAVING COUNT(*) >= 2
     					/*--fin Internet en general*/
     					UNION
     						/**Todos Los Clientes Suspendidos**/
     						/**clientes de solo cable ya suspendidos*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     						/**fin clientes de solo cable ya suspendidos*/
     						UNION
     						/**clientes de solo internet ya suspendidos*/
     						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     						/**Fin clientes de solo internet ya suspendidos*/
     						UNION
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						/**Todos Los Clientes Suspendidos**/
     				) group by `codigoCliente` HAVING COUNT(*) >= 2
                   /*---FIN SOLO CABLE POR COBRADOR ESPECIFICO*/
                   UNION
                   /*---SOLO INTERNET POR COBRADOR ESPECIFICO*/
     				SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='I' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
     				AND codigoCliente NOT IN (
     					/*--Cable en general*/
     					SELECT codigoCliente FROM tbl_cargos WHERE estado ='pendiente' AND anulada=0 AND tipoServicio='C' AND fechaVencimiento < now() AND codigoCobrador=".$_POST["susCobrador"]."
     					AND codigoCliente NOT IN (
     							/**Todos Los Clientes Suspendidos**/
     							/**clientes de solo cable ya suspendidos*/
     							SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     							/**fin clientes de solo cable ya suspendidos*/
     							UNION
     							/**clientes de solo internet ya suspendidos*/
     							SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     							/**Fin clientes de solo internet ya suspendidos*/
     							UNION
     							/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     							SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     							/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     							/**Todos Los Clientes Suspendidos**/
     					) group by `codigoCliente` HAVING COUNT(*) >= 2
     					/*--fin Cable en general*/
     					UNION
     						/**Todos Los Clientes Suspendidos**/
     						/**clientes de solo cable ya suspendidos*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     						/**fin clientes de solo cable ya suspendidos*/
     						UNION
     						/**clientes de solo internet ya suspendidos*/
     						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     						/**Fin clientes de solo internet ya suspendidos*/
     						UNION
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						/**Todos Los Clientes Suspendidos**/
     				) group by `codigoCliente` HAVING COUNT(*) >= 2
     			  /*---FIN SOLO INTERNET POR COBRADOR ESPECIFICO*/
                    UNION
     						/**Todos Los Clientes Suspendidos**/
     						/**clientes de solo cable ya suspendidos*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 3
     						/**fin clientes de solo cable ya suspendidos*/
     						UNION
     						/**clientes de solo internet ya suspendidos*/
     						SELECT cod_cliente as codigoCliente FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null) and sin_servicio='T' AND estado_cliente_in = 2
     						/**Fin clientes de solo internet ya suspendidos*/
     						UNION
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						SELECT cod_cliente FROM clientes WHERE servicio_suspendido='T' AND sin_servicio='F' AND estado_cliente_in = 2
     						/**CLIENTES DE PAQUETE YA SUSPENDIDOS*/
     						/**Todos Los Clientes Suspendidos**/
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

    $codigos = array();

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

      echo '<!DOCTYPE html>
      <html lang="es" dir="ltr">
          <head>
              <meta charset="utf-8">
              <title>Suspensiones automáticas</title>
              <link rel="shortcut icon" href="../images/Cablesat.png" />
              <!-- Bootstrap Core CSS -->
              <link href="../../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

              <!-- MetisMenu CSS -->
              <link href="../../../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

              <!-- Font awesome CSS -->
              <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">

              <!-- Custom CSS -->
              <link href="../../dist/css/sb-admin-2.css" rel="stylesheet">
              <link rel="stylesheet" href="../../../dist/css/custom-principal.css">
              <link rel="stylesheet" href="../../js/menu.css">

              <!-- Morris Charts CSS -->
              <link href="../../vendor/morrisjs/morris.css" rel="stylesheet">

              <!-- Custom Fonts -->
              <link href="../../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
          </head>
          <body style="background-color: #EEEEEE;">
              <div class="row-fluid">
                  <div class="col-md-12">
                      <div class="page-header">
                          <h1 class="text-center"><b>Suspensiones automáticas</b></h1>
                      </div>
                      <a class="btn btn-danger pull-right" href="#" data-toggle="modal" data-target="#automaticas">Suspender</a>
                      <table id="facturacion" style="background-color:white; border:1px solid #BDBDBD" class="table table-responsive table-hover">
                          <thead>
                              <th>Código cliente</th>
                              <th>Nombre</th>
                              <th>Direccion</th>
                              <th>Fecha vencimiento</th>
                              <th>Tipo servicio</th>
                          </thead>
                          <tbody>';

        while($row = $resultado->fetch_assoc())
    	  {
              array_push($codigos,$row['codigoCliente']);
              echo "<tr><td>";
              echo "<span style='font-size:13px;' class='label label-primary'>".$row['codigoCliente']."</span></td><td>";
              echo utf8_decode($row['nombre'])."</td><td>";
              echo utf8_decode($row['direccion'])."</td><td>";
              echo $row['fechaVencimiento']."</td><td>";
              if ($row['tipoServicio'] == 'C') {
                  echo "<span style='font-size:13px;' class='label label-primary'>".$row['tipoServicio']."</span></td></tr>";
              }else {
                  echo "<span style='font-size:13px;' class='label label-success'>".$row['tipoServicio']."</span></td></tr>";
              }
    	  }
          //var_dump($codigos);
          echo '</tbody>
      </table>
  </div>
</div>
<!-- Modal AUTOMATICAS -->
<div id="automaticas" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg">

  <!-- Modal content-->
  <div class="modal-content">
    <div style="background-color: #b71c1c; color:white;" class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Suspensiones automáticas</h4>
    </div>
    <form id="frmAutomaticas" action="automaticas.php" method="POST" target="_blank">
    <div class="modal-body">
      <div class="row">
          <div class="col-md-4">
              <label for="fechaElaborada">Fecha elaborada</label>
              <input class="form-control" type="text" id="fechaElaborada" name="fechaElaborada" value="'.date("Y-m-d").'" required>
          </div>
          <div class="col-md-4">
              <label for="motivo">Motivo</label>
              <select class="form-control" type="text" id="motivo" name="motivo" required>
                  <option value="2" selected>Por mora de 2 meses</option>
              </select>
          </div>
          <div class="col-md-4">
              <label for="ordena">Ordena suspensión</label>
              <select class="form-control" type="text" id="ordena" name="ordena" required>
                  <option value="oficina" selected>Oficina</option>
              </select>
          </div>
      </div>
      <div class="row">
          <div class="col-md-12">
              <label for="observaciones">Observaciones</label>
              <input class="form-control" type="text" id="observaciones" name="observaciones">
              <input class="form-control" type="hidden" id="serial" name="serial" value='.serialize($codigos).'>
          </div>
      </div>
    </div>
    <div class="modal-footer">
        <div class="row">
            <div class="col-md-6">
                <input type="submit" class="btn btn-danger btn-md btn-block" name="submit" value="SUSPENDER TODOS">
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-default btn-md btn-block" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </form>
    </div>
  </div>
</div>
</div><!-- Fin Modal VENTAS MANUALES -->
<!-- jQuery -->
<script src="../../../vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="../../../vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="../../../vendor/metisMenu/metisMenu.min.js"></script>

<!-- DataTables JavaScript -->
<script src="../../../vendor/datatables/js/dataTables.bootstrap.js"></script>
<script src="../../../vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="../../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
<script src="../../../vendor/datatables-responsive/dataTables.responsive.js"></script>
<!-- Custom Theme JavaScript -->
<script src="../../dist/js/sb-admin-2.js"></script>
<!-- Page-Level Demo Scripts - Tables - Use for reference -->

<script>
  $(document).ready(function() {
      $("#facturacion").DataTable({
          responsive: true,
          "paging": true,
          "language": {
          "lengthMenu": "Mostrar _MENU_ registros por página",
          "zeroRecords": "No se encontró ningún registro",
          "info": "Mostrando _TOTAL_ de _MAX_ Registros",
          "infoEmpty": "No se encontró ningún registro",
          "search": "Buscar: ",
          "searchPlaceholder": "",
          "infoFiltered": "(de un total de _MAX_ registros)",
          "paginate": {
           "previous": "Anterior",
           "next": "Siguiente",

          }
      }

      });

  });
</script>
</body>
</html>';
/*var_dump($codigos);
if (isset($_POST["automaticas"])) {
    if ($_POST["automaticas"]) {

        foreach ($codigos as $codigo) {
            $queryInsert
            $mysqli->query($queryInsert);
        }
    }
}*/


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
	  //$pdf->Output();

  }


listaImpagos();

?>
