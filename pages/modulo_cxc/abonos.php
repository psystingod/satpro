<?php

    session_start();
    // get passed parameter value, in this case, the record ID
    // isset() is a PHP function used to verify if a value is there or not
    if (isset($_GET['codigoCliente'])) {
        $id=isset($_GET['codigoCliente']) ? $_GET['codigoCliente'] : die('ERROR: Record no encontrado.');

        //include database connection
        include '../../php/connection.php';
        require_once 'php/getSaldoReal.php';
        $precon = new ConectionDB();
        $con = $precon->ConectionDB();
        // read current record's data
        try {
            $getSaldoReal = new GetSaldoReal();
            $saldoRealCable = $getSaldoReal->getSaldoCable($id);
            $saldoRealInter = $getSaldoReal->getSaldoInter($id);

            // prepare select query
            $query = "SELECT * FROM clientes WHERE cod_cliente = ? LIMIT 0,1";
            $stmt = $con->prepare( $query );

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            /****************** DATOS GENERALES ***********************/
            $estado_cable = $row['servicio_suspendido']; // F o T
            $estado_internet = $row['estado_cliente_in']; // 1, 2, 3
            $codigo = $row["cod_cliente"];
            $nContrato = $row["numero_contrato"];
            $cuotaCable = $row["valor_cuota"];
            $cuotaInter = $row["cuota_in"];
            $nFactura = $row["num_factura"];
            $nombre = trim(ucwords(strtolower($row['nombre'])));
            //$empresa = $row["empresa"];
            $nRegistro = $row["num_registro"];
            $dui = trim(ucwords(strtolower($row['numero_dui'])));
            $lugarExp = trim(ucwords(strtolower($row['lugar_exp'])));
            $nit = trim(ucwords(strtolower($row['numero_nit'])));
            $fechaNacimiento = $row['fecha_nacimiento'];
            $direccion = $row["direccion"];
            $departamento = $row["id_departamento"];
            $municipio = $row["id_municipio"];
            $colonia = $row["id_colonia"];
            $direccionCobro = $row["direccion_cobro"];
            $telefonos = $row['telefonos'];
            $telTrabajo = $row['tel_trabajo'];
            $ocupacion = $row['profesion'];
            $cuentaContable = $row['id_cuenta'];
            $formaFacturar = $row['forma_pago']; //Contado o al crédito
            $saldoCable = $row['saldoCable']; //SALDO CABLE
            $saldoInter = $row['saldoInternet']; //SALDO INTERNET
            //$saldoActual = $row['saldo_actual'];
            $diasCredito = $row['dias_credito'];
            $limiteCredito = $row['limite_credito'];
            $tipoComprobante = $row['tipo_comprobante']; //Credito fiscal o consumidor final
            $facebook = $row['facebook'];
            $correo = $row['correo_electronico'];

            /****************** OTROS DATOS ***********************/
            $cobrador = $row['cod_cobrador'];
            $contacto1 = $row['contactos'];
            $contacto2 = $row['contacto2'];
            $contacto3 = $row['contacto3'];
            $telCon1 = $row['telcon1'];
            $telCon2 = $row['telcon2'];
            $telCon3 = $row['telcon3'];
            $paren1 = $row['paren1'];
            $paren2 = $row['paren2'];
            $paren3 = $row['paren3'];
            $dir1 = $row['dir1'];
            $dir2 = $row['dir2'];
            $dir3 = $row['dir3'];

            /****************** DATOS CABLE ***********************/
            $fechaInstalacion = date_format(date_create($row['fecha_instalacion']), "d/m/Y");
            $fechaPrimerFactura = date_format(date_create($row['fecha_primer_factura']), "d/m/Y");
            $exento = $row['exento'];
            $diaCobro = $row['dia_cobro'];
            $cortesia = $row['servicio_cortesia'];
            $prepago = $row['prepago'];
            $tipoComprobante = $row['tipo_comprobante'];
            $tipoServicio = $row['tipo_servicio'];
            $mactv = $row['mactv'];
            $periodoContratoCable = $row['periodo_contrato_ca'];
            $vencimientoCable = $row['vencimiento_ca'];
            //var_dump($row['fecha_instalacion']);
            if (strlen($row['fecha_instalacion']) < 8) {
                $fechaInstalacion = "";
            }else {
                $fechaInstalacion = DateTime::createFromFormat('Y-m-d', $row['fecha_instalacion']);
                $fechaInstalacion = $fechaInstalacion->format('d/m/Y');
            }

            if (strlen($row['fecha_primer_factura']) < 8) {
                $fechaPrimerFactura = "";
            }else {
                $fechaPrimerFactura = DateTime::createFromFormat('Y-m-d', $row['fecha_primer_factura']);
                $fechaPrimerFactura = $fechaPrimerFactura->format('d/m/Y');
            }

            if (strlen($row['fecha_suspencion']) < 8) {
                $fechaSuspensionCable = "";
            }else {
                $fechaSuspensionCable = DateTime::createFromFormat('Y-m-d', $row['fecha_suspencion']);
                $fechaSuspensionCable = $fechaSuspensionCable->format('d/m/Y');
            }

            if (strlen($row['fecha_reinstalacion']) < 8) {
                $fechaReinstalacionCable = "";
            }else {
                $fechaReinstalacionCable = DateTime::createFromFormat('Y-m-d', $row['fecha_reinstalacion']);
                $fechaReinstalacionCable = $fechaReinstalacionCable->format('d/m/Y');
            }
            $tecnicoCable = $row['id_tecnico'];
            $direccionCable = $row['dire_cable'];
            $nDerivaciones = $row['numero_derivaciones'];

            /****************** DATOS INTERNET ***********************/
            if (strlen($row['fecha_instalacion_in']) < 8) {
                $fechaInstalacionInter = "";
            }else {
                $fechaInstalacionInter = DateTime::createFromFormat('Y-m-d', $row['fecha_instalacion_in']);
                $fechaInstalacionInter = $fechaInstalacionInter->format('d/m/Y');
            }

            if (strlen($row['fecha_primer_factura_in']) < 8) {
                $fechaPrimerFacturaInter = "";
            }else {
                $fechaPrimerFacturaInter = DateTime::createFromFormat('Y-m-d', $row['fecha_primer_factura_in']);
                $fechaPrimerFacturaInter = $fechaPrimerFacturaInter->format('d/m/Y');
            }

            $tipoServicioInternet = $row['tipo_servicio_in'];
            $periodoContratoInternet = $row['periodo_contrato_int'];
            $diaCobroInter = $row['dia_corbo_in'];
            $velocidadInter = $row['id_velocidad'];
            $cuotaMensualInter = $row['cuota_in'];
            $tipoClienteInter = $row['id_tipo_cliente'];
            $tecnologia = $row['tecnologia'];
            $nContratoInter = $row['no_contrato_inter'];
            if (strlen($row['vencimiento_in']) < 8) {
                $vencimientoInternet = "";
            }else {
                $vencimientoInternet = DateTime::createFromFormat('Y-m-d', $row['vencimiento_in']);
                $vencimientoInternet = $vencimientoInternet->format('d/m/Y');
            }

            if (strlen($row['ult_ren_in']) < 8) {
                $ultimaRenovacionInternet = "";
            }else {
                $ultimaRenovacionInternet = DateTime::createFromFormat('Y-m-d', $row['ult_ren_in']);
                $ultimaRenovacionInternet = $ultimaRenovacionInternet->format('d/m/Y');
            }

            if (strlen($row['fecha_suspencion_in']) < 8) {
                $fechaSuspencionInternet = "";
            }else {
                $fechaSuspencionInternet = DateTime::createFromFormat('Y-m-d', $row['fecha_suspencion_in']);
                $fechaSuspencionInternet = $fechaSuspencionInternet->format('d/m/Y');
            }

            if (strlen($row['fecha_reconexion_in']) < 8) {
                $fechaReconexionInternet = "";
            }else {
                $fechaReconexionInternet = DateTime::createFromFormat('Y-m-d', $row['fecha_reconexion_in']);
                $fechaReconexionInternet = $fechaReconexionInternet->format('d/m/Y');
            }

            $promocion = $row['id_promocion'];
            if (strlen($row['dese_promocion_in']) < 8) {
                $promocionDesde = "";
            }else {
                $promocionDesde = DateTime::createFromFormat('Y-m-d', $row['dese_promocion_in']);
                $promocionDesde = $promocionDesde->format('d/m/Y');
            }

            if (strlen($row['hasta_promocion_in']) < 8) {
                $promocionHasta = "";
            }else {
                $promocionHasta = DateTime::createFromFormat('Y-m-d', $row['hasta_promocion_in']);
                $promocionHasta = $promocionHasta->format('d/m/Y');
            }
            $cuotaPromocion = $row['cuota_promocion'];
            $direccionInternet = $row['dire_internet'];
            $tecnicoInternet = $row['id_tecnico_in'];
            $colilla = $row['colilla'];
            $modelo = $row['marca_modem'];
            $recepcion = $row['recep_modem'];
            $wanip = $row['wanip'];
            $mac = $row['mac_modem'];
            $transmision = $row['trans_modem'];
            $coordenadas = $row['coordenadas'];
            $serie = $row['serie_modem'];
            $ruido = $row['ruido_modem'];
            $nodo = $row['dire_telefonia'];
            $wifiClave = $row['clave_modem'];
        }

        // show error
        catch(PDOException $exception){
            die('ERROR: ' . $exception->getMessage());
        }
    }
    else {
        /****************** DATOS GENERALES ***********************/
        $estado_cable = ""; // 0 o 1
        $estado_internet = ""; // 1, 2, 3
        $codigo = "";
        $nContrato = "";
        $nRecibo = "";
        $nombre = "";
        $empresa = "";
        $cuotaCable = "";
        $cuotaInter = "";
        $nRegistro = "";
        $dui = "";
        $lugarExp = "";
        $nit = "";
        $fechaNacimiento = "";
        $direccion = "";
        $departamento = "";
        $municipio = "";
        $colonia = "";
        $direccionCobro = "";
        $telefonos = "";
        $telTrabajo = "";
        $ocupacion = "";
        $cuentaContable = "";
        $formaFacturar = ""; //Contado o al crédito
        $saldoActual = "";
        $diasCredito = "";
        $limiteCredito = "";
        $tipoFacturacion = ""; //Credito fiscal o consumidor final
        $facebook = "";
        $correo = "";
        /****************** OTROS DATOS ***********************/
        $cobrador = "";

        /****************** DATOS CABLE ***********************/
        $fechaInstalacion = "";
        $fechaPrimerFactura = "";
        $fechaSuspensionCable = "";
        $exento = "F";
        $diaCobro = "";
        $cortesia = "F";
        $cuotaMensualCable = "";
        $prepago = "";
        $tipoComprobante = "";
        $tipoServicio = "";
        $mactv = "";
        $periodoContratoCable = "";
        $vencimientoCable = "";
        $fechaInstalacionCable = "";
        $fechaReinstalacionCable = "";
        $tecnicoCable = "";
        $direccionCable = "";
        $nDerivaciones = "";

        /****************** DATOS INTERNET ***********************/
        $fechaInstalacionInter = "";
        $fechaPrimerFacturaInter = "";
        $tipoServicioInternet = "";
        $nodo = "";
        $periodoContratoInternet = "";
        $diaCobroInter = "";
        $velocidadInter = "";
        $cuotaMensualInter = "";
        $tipoClienteInter = "";
        $tecnologia = "";
        $nContratoInter = "";
        $vencimientoInternet = "";
        $ultimaRenovacionInternet = "";
        $fechaSuspencionInternet = "";
        $fechaReconexionInternet = "";
        $promocion = "";
        $promocionDesde = "";
        $promocionHasta = "";
        $cuotaPromocion = "";
        $direccionInternet = "";
        $colilla = "";
        $modelo = "";
        $recepcion = "";
        $wanip = "";
        $mac = "";
        $transmision = "";
        $coordenadas = "";
        $serie = "";
        $ruido = "";
        $wifiClave = "";
    }
 ?>
 <?php
 require_once 'php/GetAllInfo.php';
 require_once 'php/getData.php';
 $data = new GetAllInfo();
 $data2 = new OrdersInfo();
 $arrDepartamentos = $data->getData('tbl_departamentos_cxc');
 $arrMunicipios = $data->getData('tbl_municipios_cxc');
 $arrColonias = $data->getData('tbl_colonias_cxc');
 $arrFormaFacturar = $data->getData('tbl_forma_pago');
 $arrCobradores = $data->getData('tbl_cobradores');
 $arrComprobantes = $data->getData('tbl_tipo_comprobante');
 $arrServicioCable = $data->getData('tbl_servicios_cable');
 $arrServicioInter = $data->getData('tbl_servicios_inter');
 $arrVelocidad = $data->getData('tbl_velocidades');
 $arrTecnicos = $data->getData('tbl_tecnicos_cxc');
 $arrTecnologias = $data->getData('tbl_tecnologias');
 $arrTiposClientes = $data->getData('tbl_tipos_clientes');
 if (isset($_GET['tipoServicio'])) {
      $tipoServicio = strtoupper($_GET['tipoServicio']);
 }

 $arrCargos = $data->getDataCargos2('tbl_cargos', $codigo, $tipoServicio, "pendiente");
 //Array de ordenes de trabajo por cliente
 //$arrOrdenesTrabajo = $data->getDataOrders('tbl_ordenes_trabajo', $codigo);
 //$arrOrdenesSuspension = $data->getDataOrders('tbl_ordenes_suspension', $codigo);
 //$arrOrdenesReconex = $data->getDataOrders('tbl_ordenes_reconexion', $codigo);
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cablesat</title>

    <link rel="shortcut icon" href="../../images/cablesat.png" />
    <!-- Bootstrap Core CSS -->
    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Font awesome CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link href="../../dist/css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="../../dist/css/custom-principal.css">

    <!-- Morris Charts CSS -->
    <link href="../../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style media="screen">
    .form-control {
        color: #01579B;
        font-size: 15px;
        font-weight: bold;

    }
    </style>
</head>

<body>

    <?php
         // session_start();
         if(!isset($_SESSION["user"])) {
             header('Location: ../login.php');
         }
     ?>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Cablesat</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <?php echo "<i class='far fa-user'></i>"." ".$_SESSION['nombres']." ".$_SESSION['apellidos'] ?> <i class="fas fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="perfil.php"><i class="fas fa-user-circle"></i> Perfil</a>
                        </li>
                        <li><a href="config.php"><i class="fas fa-cog"></i> Configuración</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="../php/logout.php"><i class="fas fa-sign-out-alt"></i></i> Salir</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href='../index.php'><i class='fas fa-home'></i> Principal</a>
                        </li>
                        <?php
                        require('../../php/contenido.php');
                        require('../../php/modulePermissions.php');

                        if (setMenu($_SESSION['permisosTotalesModulos'], ADMINISTRADOR)) {
                            echo "<li><a href='../modulo_administrar/administrar.php'><i class='fas fa-key'></i> Administrar</a></li>";
                        }else {
                            echo "";
                        }
                        if (setMenu($_SESSION['permisosTotalesModulos'], CONTABILIDAD)) {
                            echo "<li><a href='../modulo_contabilidad/contabilidad.php'><i class='fas fa-money-check-alt'></i> Contabilidad</a></li>";
                        }else {
                            echo "";
                        }
                        if (setMenu($_SESSION['permisosTotalesModulos'], PLANILLA)) {
                            echo "<li><a href='../modulo_planillas/planillas.php'><i class='fas fa-file-signature'></i> Planillas</a></li>";
                        }else {
                            echo "";
                        }
                        if (setMenu($_SESSION['permisosTotalesModulos'], ACTIVOFIJO)) {
                            echo "<li><a href='../modulo_activoFijo/activoFijo.php'><i class='fas fa-building'></i> Activo fijo</a></li>";
                        }else {
                            echo "";
                        }
                        if (setMenu($_SESSION['permisosTotalesModulos'], INVENTARIO)) {
                            echo "<li><a href='../moduloInventario.php'><i class='fas fa-scroll'></i> Inventario</a></li>";
                        }else {
                            echo "";
                        }
                        if (setMenu($_SESSION['permisosTotalesModulos'], IVA)) {
                            echo "<li><a href='../modulo_iva/iva.php'><i class='fas fa-file-invoice-dollar'></i> IVA</a></li>";
                        }else {
                            echo "";
                        }
                        if (setMenu($_SESSION['permisosTotalesModulos'], BANCOS)) {
                            echo "<li><a href='../modulo_bancos/bancos.php'><i class='fas fa-university'></i> Bancos</a></li>";
                        }else {
                            echo "";
                        }
                        if (setMenu($_SESSION['permisosTotalesModulos'], CXC)) {
                            echo "<li><a href='cxc.php'><i class='fas fa-hand-holding-usd'></i> Cuentas por cobrar</a></li>";
                        }else {
                            echo "";
                        }
                        if (setMenu($_SESSION['permisosTotalesModulos'], CXP)) {
                            echo "<li><a href='../modulo_cxp/cxp.php'><i class='fas fa-money-bill-wave'></i> Cuentas por pagar</a></li>";
                        }else {
                            echo "";
                        }
                        ?>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <br>
                        <div class="panel panel-primary">
                          <div class="panel-heading">Abonos</div>
                          <form id="frAbonos" action="php/aplicarAbonos.php" method="POST">
                          <div class="panel-body" style="color">
                              <div class="col-md-12">
                                  <?php
                                  if (isset($_GET['abonado'])) {
                                      if ($_GET['abonado'] == 'yes') {
                                          echo "<br>";
                                          echo '<span class="alert alert-info">Abono ingresado con exito. Para ingresar otro abono coloque el código de cliente y el tipo de servicio.</span>';
                                          echo "<br>";
                                      }
                                      elseif ($_GET['abonado'] == 'no') {
                                          echo "<br>";
                                          echo '<span class="alert alert-danger">No se pudo ingresar el abono.</span>';
                                          echo "<br>";
                                      }
                                  }
                                  ?>
                                  <div class="pull-right">
                                      <button class="btn btn-default btn-sm" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Estado de cuenta">Estado de cuenta</button>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <?php
                                  if ($tipoComprobante == 1) {
                                      echo '<div class="col-md-4">
                                          <label for="creditoFiscal">Crédito fiscal</label>
                                          <input class="form-control input-sm" type="checkbox" name="creditoFiscal" value="'.$tipoComprobante.'" checked readonly>
                                      </div>
                                      <div class="col-md-4">
                                          <label for="consumidorFinal">Consumidor final</label>
                                          <input class="form-control input-sm" type="checkbox" name="consumidorFinal" value="" readonly>
                                      </div>';
                                  }elseif ($tipoComprobante == 2) {
                                      echo '<div class="col-md-4">
                                          <label for="creditoFiscal">Crédito fiscal</label>
                                          <input class="form-control input-sm" type="checkbox" name="creditoFiscal" readonly>
                                      </div>
                                      <div class="col-md-4">
                                          <label for="consumidorFinal">Consumidor final</label>
                                          <input class="form-control input-sm" type="checkbox" name="consumidorFinal" value="'.$tipoComprobante.'" checked readonly>
                                      </div>';
                                  }

                                  if ($estado_cable == "T") {
                                      echo '<div class="col-md-4">
                                              <label for="servicioSuspendido">Servicio suspendido</label>
                                              <input class="form-control input-sm" type="checkbox" name="servicioSuspendido" value="'.$estado_cable.'" checked readonly>
                                            </div>';
                                  }elseif ($estado_cable == "F") {
                                      echo '<div class="col-md-4">
                                              <label for="servicioSuspendido">Servicio suspendido</label>
                                              <input class="form-control input-sm" type="checkbox" name="servicioSuspendido" value="'.$estado_cable.'" readonly>
                                            </div>';
                                  }
                                  ?>
                              </div>

                              <div class="form-row">
                                  <div class="col-md-2">
                                    <!-- readonly -->
                                      <label for="claseOrden">Fecha del abono</label>
                                      <input class="form-control input-sm input-sm" name="fechaAbono" type="text" value= "<?php date_default_timezone_set('America/El_Salvador'); echo date('d/m/Y'); ?>" >
                                  </div>
                                  <!--<div class="col-md-2">
                                      <label for="nRecibo">N° de recibo</label>
                                      <input class="form-control input-sm" type="text" name="nRecibo" value="<?php echo $nRecibo; ?>">
                                  </div>-->
                                  <div class="col-md-5">

                                      <label for="numeroOrden">Zona</label>
                                      <select class="form-control input-sm" name="zona" required>
                                          <?php
                                          foreach ($arrCobradores as $key) {
                                              if ($key['codigoCobrador'] == "000") {
                                                  echo "<option value=".$key['codigoCobrador'].">".$key['nombreCobrador']."</option>";
                                              }
                                              else {
                                                  echo "<option value=".$key['codigoCobrador'].">".$key['nombreCobrador']."</option>";
                                              }

                                          }
                                           ?>
                                      </select>
                                  </div>
                                  <div class="col-md-5">

                                      <label for="numeroOrden">Cobrador</label>
                                      <select class="form-control input-sm" name="cobrador" required>
                                          <?php
                                          foreach ($arrCobradores as $key) {
                                              if ($key['codigoCobrador'] == $cobrador) {
                                                  echo "<option value=".$key['codigoCobrador']." selected>".$key['nombreCobrador']."</option>";
                                              }
                                              else {
                                                  echo "<option value=".$key['codigoCobrador'].">".$key['nombreCobrador']."</option>";
                                              }

                                          }
                                           ?>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-2">
                                    <!-- readonly -->
                                      <label for="codigoCliente">Código</label>
                                      <input id="codigoCliente" class="form-control input-sm input-sm" type="text" name="codigoCliente" value="<?php echo $codigo; ?>">
                                  </div>
                                  <div class="col-md-4">
                                      <label for="nombreCliente">Nombre del cliente</label>
                                      <input class="form-control input-sm input-sm" type="text" name="nombreCliente" value="<?php echo $nombre; ?>">
                                  </div>
                                  <div class="col-md-2">
                                      <!-- readonly -->
                                      <label for="nrc">NRC</label>
                                      <input class="form-control input-sm input-sm" type="text" name="nrc" value="<?php echo $nRegistro; ?>">
                                  </div>
                                  <div class="col-md-2">

                                      <label for="servicio">Servicio</label>
                                      <select id="servicio" class="form-control input-sm" onchange='getValorCuota(this)' name="servicio">
                                          <?php
                                          if ($_GET['tipoServicio'] == "c") {
                                              echo "<option value='c' selected>Cable</option>";
                                              echo "<option value='i'>Internet</option>";
                                          }elseif ($_GET['tipoServicio'] == "i") {
                                              echo "<option value='i' selected>Internet</option>";
                                              echo "<option value='c'>Cable</option>";
                                          }else {
                                              echo "<option value='c' selected>Cable</option>";
                                              echo "<option value='i'>Internet</option>";
                                          }

                                          ?>
                                      </select>
                                  </div>
                                  <div class="col-md-2">
                                      <label for="valorCuota">Valor de la cuota</label>
                                      <input id="cuotaCable" type="hidden" name="cuotaCable" value="<?php echo $cuotaCable; ?>">
                                      <input id="cuotaInter" type="hidden" name="cuotaInter" value="<?php echo $cuotaInter; ?>">
                                      <input id="valorCuota" class="form-control input-sm alert-info" type="text" name="valorCuota" value="0.00" style="font-weight: bold;">
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-12">
                                      <label for="direccion">Dirección</label>
                                      <input class="form-control input-sm" type="text" name="direccion" value="<?php echo $direccion; ?>">
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-3">
                                      <label for="formaPago">Forma de pago</label>
                                      <select class="form-control input-sm" type="text" name="formaPago">
                                          <option value="efectivo">Efectivo</option>
                                      </select>
                                  </div>
                                  <div class="col-md-2">
                                      <label for="totalPagar">Total a pagar</label>
                                      <input class="form-control input-sm" type="text" id="totalPagar" name="totalPagar" value="">
                                  </div>
                                  <div class="col-md-2">
                                      <label for="porImp">% CESC</label>
                                      <input id="cesc" class="form-control input-sm" type="text" name="porImp" value="">
                                  </div>
                                  <div class="col-md-2">
                                      <label for="impSeg">Impuesto seg</label>
                                      <input type="text" class="form-control input-sm" id="impSeg" name="impSeg" value="">
                                  </div>
                                  <!--<div class="col-md-3">

                                      <label for="totalAbono">Total a abonar</label>
                                      <input class="form-control input-sm" type="text" name="totalAbono">
                                  </div>-->
                                  <div class="col-md-3">
                                      <label for="totalAbonoImpSeg">Con Impuesto de seguridad</label>
                                      <input type="text" class="form-control input-sm alert-danger" id="totalAbonoImpSeg" name="totalAbonoImpSeg" value="0.00" style="color:red; font-weight:bold;">
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-7">

                                      <label for="concepto">Concepto</label>
                                      <input class="form-control input-sm" type="text" name="concepto" value="">
                                  </div>
                                  <div class="col-md-2">
                                      <label for="diaCobro">Día cobro</label>
                                      <input class="form-control input-sm" type="text" name="diaCobro" value="<?php echo $diaCobro; ?>">
                                  </div>
                                  <div class="col-md-3">
                                      <label for="cuotaImpuesto">Cuota/solo impuesto</label>
                                      <select class="form-control input-sm" type="text" name="cuotaImpuesto" value="">
                                          <option value="cuota" selected>Cuota</option>
                                          <option value="impuesto">Solo impuesto</option>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-9">
                                      <input id="aplicarCesc" class="" onclick="getCesc()" type="radio" name="aplicarCesc" value="0.05">
                                      <label for="5">5%</label>
                                      <input class="" type="radio" onclick="getCesc()" name="aplicarCesc" value="0.10">
                                      <label for="5">10%</label>
                                      <input class="" type="radio" onclick="getCesc()" name="aplicarCesc" value="0">
                                      <label for="5">Excento</label>
                                  </div>
                                  <div class="col-md-3">
                                      <input class="" type="checkbox" name="anularComp">
                                      <label for="5">Anular comprobante</label>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-12">
                                      <table class="table table-bordered table-hover table-striped">
                                          <tr class="">
                                              <th class="bg-success">Abonar?</th>
                                              <th class="bg-success"></th>
                                              <th class="bg-success"></th>
                                              <th class="bg-success">N° recibo</th>
                                              <th class="bg-success">Mes de servicio</th>
                                              <th class="bg-success">Cuota</th>
                                              <th class="bg-success">Vencimiento</th>
                                          </tr>

                                          <?php
                                          $counter = 1;
                                          foreach ($arrCargos as $key) {
                                              if (isset($_GET['tipoServicio'])) {
                                                  if ($_GET['tipoServicio'] == "c") {
                                                      $cesc = 0.05;
                                                      $saldoActualSinIva = substr((floatVal($saldoRealCable)/1.13), 0,5);
                                                      //echo var_dump($saldoActualSinIva);
                                                      $impSeg = substr($saldoActualSinIva * $cesc, 0,4);
                                                      //echo var_dump($impSeg);
                                                      //echo var_dump($saldoCable);
                                                      $saldoActual = substr((floatVal($saldoRealCable) + floatVal($impSeg)), 0,5);
                                                      //echo var_dump($saldoActual. "Mira");
                                                      echo "<input type='hidden' id='saldoActual' name='saldoActual' value='".$saldoActual. "' readonly>";
                                                      echo "<input type='hidden' id='saldoActual0' value='".$saldoRealCable. "' readonly>";

                                                  }
                                                  elseif ($_GET['tipoServicio'] == "i") {
                                                      $cesc = 0.05;
                                                      $saldoActualSinIva = substr((floatVal($saldoRealInter)/1.13), 0,5);
                                                      //echo var_dump($saldoActualSinIva);
                                                      $impSeg = substr($saldoActualSinIva * $cesc, 0,4);
                                                      //echo var_dump($impSeg);
                                                      $saldoActual = substr((floatVal($saldoRealInter) + floatVal($impSeg)), 0,5);
                                                      //echo var_dump($saldoActual);
                                                      echo "<input type='hidden' id='saldoActual' name='saldoActual' value='".$saldoActual . "' readonly>";
                                                      echo "<input type='hidden' id='saldoActual0' value='".$saldoRealInter. "' readonly>";
                                                  }
                                              }

                                              echo "<tr><td>";
                                              echo "<input name='mesx{$counter}' type='checkbox' id='mesx{$counter}' onchange='getMesesPagar()' value=''>"."</td>";
                                              echo "<input type='hidden' name='idFacturax{$counter}' value='".$key["idFactura"] . "' readonly>"."</td><td>";
                                              echo "<input type='hidden' name='fechaCobrox{$counter}' value='".$key["fechaCobro"] . "' readonly>"."</td><td>";
                                              echo "<input type='hidden' name='fechaFacturax{$counter}' value='".$key["fechaFactura"] . "' readonly>"."</td><td>";
                                              echo "<input class='form-control input-sm' type='text' name='nFacturax{$counter}' value='".$key["numeroRecibo"] . "' readonly>"."</td><td>";
                                              echo "<input class='form-control input-sm' type='text' id='mesCargo{$counter}' name='mesCargo{$counter}' value='".$key["mesCargo"] . "' readonly>"."</td><td>";
                                              if ($key["tipoServicio"] == 'C') {
                                                  echo "<input class='form-control input-sm' type='text' id='mesx{$counter}value' name='cuotaCable{$counter}' value='".$key["cuotaCable"] . "' readonly>"."</td><td>";
                                              }elseif ($key["tipoServicio"] == 'I') {
                                                  echo "<input class='form-control input-sm' type='text' id='mesx{$counter}value' name='cuotaInternet{$counter}' value='".$key["cuotaInternet"] . "' readonly>"."</td><td>";
                                              }
                                              echo "<input class='form-control type='text' name='vencimientox{$counter}' value='".date_format(date_create($key["fechaVencimiento"]), "d/m/Y") . "' readonly>"."</td><tr>";
                                              $counter++;
                                          }
                                          ?>
                                      </table>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-4">
                                      <label for="meses">TOTAL (INCLUYE IMPUESTOS)</label>
                                  </div>
                                  <div class="col-md-2">
                                      <input id="total" class="form-control input-sm alert-danger" type="text" name="total" value="0.00" style="color:red; font-weight:bold;">
                                  </div>
                                  <div class="col-md-3">
                                      <label for="meses">PENDIENTE (SIN CESC)</label>
                                  </div>
                                  <?php
                                  if (isset($_GET['tipoServicio'])) {
                                      if ($_GET['tipoServicio'] == "c"){
                                          $saldoReal = $saldoRealCable;
                                      }elseif ($_GET['tipoServicio'] == "i") {
                                          $saldoReal = $saldoRealInter;
                                      }
                                  }
                                  else {
                                      $saldoReal = "";
                                  }
                                  ?>
                                  <div class="col-md-2">
                                      <input class="form-control input-sm alert-danger" type="text" id="pendiente" name="pendiente" value="<?php echo $saldoReal ?>" style="color:red; font-weight:bold;">
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-8">
                                      <label for="meses">Meses</label>
                                      <textarea id="meses" class="form-control" name="meses" rows="2" cols="40"></textarea>
                                  </div>
                                  <div class="col-md-4">
                                      <label for="meses" style="color: brown;"></label>
                                      <button class="btn btn-success btn-md btn-block" type="submit" name="button" style="margin-bottom: 6px; margin-top: 0px;"><i class="fas fa-check" style="color: white;"></i> Aplicar abonos</button>
                                      <a href="cxc.php" style="text-decoration: none;"><button class="btn btn-danger btn-md btn-block" type="button" name="button"><i class="fas fa-sign-out-alt" style="color: white;"></i> Salir</button></a>
                                  </div>
                              </div>
                          </div>
                          </form>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../../dist/js/sb-admin-2.js"></script>
    <script src="js/abonos.js"></script>
    <script type="text/javascript">
        // Get the input field
        var cod = document.getElementById("codigoCliente");

        $('#frAbonos').on('keyup keypress', function(e) {
          var keyCode = e.keyCode || e.which;
          if (keyCode === 13) {
            e.preventDefault();
            return false;
          }
        });

        // Execute a function when the user releases a key on the keyboard
        cod.addEventListener("keyup", function(event) {
        // Number 13 is the "Enter" key on the keyboard
        if (event.keyCode === 13) {
        // Cancel the default action, if needed
        event.preventDefault();
        var codValue = document.getElementById("codigoCliente").value;
        var servicio = document.getElementById("servicio").value;
        // Trigger the button element with a click
        window.location="abonos.php?codigoCliente="+codValue+"&tipoServicio="+servicio;
        }
        });
    </script>

    <?php
    if (isset($_GET['codigoCliente'])) {
        echo "<script>
            //document.getElementById('ordenTrabajo').action = 'php/nuevaOrdenTrabajo.php';
            //document.getElementById('guardar').disabled = false;
            //document.getElementById('editar').disabled = true;
            //document.getElementById('imprimir').disabled = true;
            //var inputs = document.getElementsByClassName('input-sm');
        </script>";
    }
    ?>

</body>

</html>
