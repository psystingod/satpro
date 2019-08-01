<?php

    session_start();
    require("php/getData.php");
    require("php/GetAllInfo.php");
    $dataInfo = new GetAllInfo();
    $arrMunicipios = $dataInfo->getData('tbl_municipios_cxc');
    $data = new OrdersInfo();
    //$client = new GetClient();
    $arrayTecnicos = $data->getTecnicos();
    $arrayVendedores = $data->getVendedores();
    $arrayActividadesC = $data->getActividadesCable();
    $arrayActividadesI = $data->getActividadesInter();
    $arrayVelocidades = $data->getVelocidades();

    //include database connection
    require_once('../../php/connection.php');
    $precon = new ConectionDB();
    $con = $precon->ConectionDB();
    /**************************************************/
    if (isset($_GET['codigoCliente'])) {

        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id=isset($_GET['codigoCliente']) ? $_GET['codigoCliente'] : die('ERROR: Record no encontrado.');

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT cod_cliente, nombre, telefonos, id_municipio, saldo_actual, telefonos, dire_cable, dia_cobro, dire_internet, mactv, mac_modem, serie_modem, id_velocidad, dire_telefonia, recep_modem, trans_modem, ruido_modem, colilla, marca_modem, tecnologia, saldoCable, saldoInternet FROM clientes WHERE cod_cliente = ? LIMIT 0,1";
            $stmt = $con->prepare( $query );

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            /****************** DATOS GENERALES ***********************/
            date_default_timezone_set('America/El_Salvador');
            $fechaOrdenTrabajo = date_format(date_create(date('Y-m-d')), 'd/m/Y');
            $idOrdenTrabajo = "";
            $tipoOrden = "Técnica";
            $diaCobro = $row["dia_cobro"];
            $telefonos = $row["telefonos"];
            $codigoCliente = $row["cod_cliente"];
            $nombreCliente = $row['nombre'];
            $idMunicipio = $row["id_municipio"];
            $saldoCable = $row["saldoCable"];
            $saldoInter = $row["saldoInternet"];
            $direccionCable = $row["dire_cable"];
            $mactv = $row['mactv'];
            $direccionInter = $row["dire_internet"];
            $macModem = $row['mac_modem'];
            $serieModem = $row['serie_modem'];
            $idVelocidad = $row['id_velocidad'];
            $rx = $row['recep_modem'];
            $tx = $row['trans_modem'];
            $snr = $row['ruido_modem'];
            $velocidad = $row['id_velocidad'];
            $colilla = $row['colilla'];
            $marcaModelo = $row['marca_modem'];
            $tecnologia = $row['tecnologia'];
            $fechaTrabajo = "";
            $hora = "";
            $fechaProgramacion = "";
            $coordenadas = "";
            $observaciones = "";
            $nodo = $row['dire_telefonia'];
            $idVendedor = "";
            $recepcionTv = "";

        }

        // show error
        catch(PDOException $exception){
            die('ERROR: ' . $exception->getMessage());
        }
    }else if(isset($_GET['nOrden'])){
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id=isset($_GET['nOrden']) ? $_GET['nOrden'] : die('ERROR: Record no encontrado.');

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT idOrdenTrabajo, codigoCliente, fechaOrdenTrabajo, tipoOrdenTrabajo, diaCobro, nombreCliente, telefonos, idMunicipio, actividadCable, saldoCable, direccionCable, actividadInter, saldoInter, direccionInter, saldoInter, direccionInter, macModem, serieModem, velocidad, rx, tx, snr, colilla, fechaTrabajo, hora, fechaProgramacion, idTecnico, mactv, coordenadas, observaciones, nodo, marcaModelo, tecnologia, idVendedor, recepcionTv, tipoServicio, creadoPor  FROM tbl_ordenes_trabajo WHERE idOrdenTrabajo = ? LIMIT 0,1";
            $stmt = $con->prepare( $query );

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            /****************** DATOS GENERALES ***********************/
            $idOrdenTrabajo = $row["idOrdenTrabajo"];
            $fechaOrdenTrabajo = date_format(date_create($row["fechaOrdenTrabajo"]), 'd/m/Y');
            var_dump($fechaOrdenTrabajo);
            $tipoOrdenTrabajo = $row["tipoOrdenTrabajo"];
            $diaCobro = $row["diaCobro"];
            $codigoCliente = $row["codigoCliente"];
            if ($codigoCliente === "00000") {
                $codigoCliente = "SC";
            }
            $nombreCliente = $row['nombreCliente'];
            $telefonos = $row["telefonos"];
            $idMunicipio = $row["idMunicipio"];
            $idActividadCable = $row["actividadCable"];
            $saldoCable = $row["saldoCable"];
            $direccionCable = $row["direccionCable"];
            $idActividadInter = $row["actividadInter"];
            $saldoInter = $row["saldoInter"];
            $direccionInter = $row["direccionInter"];
            $macModem = $row['macModem'];
            $serieModem = $row['serieModem'];
            $velocidad = $row['velocidad'];
            $rx = $row['rx'];
            $tx = $row['tx'];
            $snr = $row['snr'];
            $colilla = $row['colilla'];
            $fechaTrabajo = date_format(date_create($row["fechaTrabajo"]), 'd/m/Y');
            $hora = $row['hora'];
            $fechaProgramacion = date_format(date_create($row["fechaProgramacion"]), 'd/m/Y');
            $idTecnico = $row['idTecnico'];
            $mactv = $row['mactv'];
            $coordenadas = $row['coordenadas'];
            $observaciones = $row['observaciones'];
            $nodo = $row['nodo'];
            $marcaModelo = $row['marcaModelo'];
            $tecnologia = $row['tecnologia'];
            $idVendedor = $row['idVendedor'];
            $recepcionTv = $row['recepcionTv'];
            $tipoServicio = $row['tipoServicio'];
            $creadoPor = $row['creadoPor'];
            //creadoPor
        }
        catch(PDOException $exception){
            die('ERROR: ' . $exception->getMessage());
        }
    }else {
        $fechaOrdenTrabajo = "";
        $idOrdenTrabajo = "";
        $codigoCliente = "";
        $nombreCliente = "";
        $telefonos = "";
        $idMunicipio = "";
        $diaCobro = "";
        $saldoCable = "";
        $direccionCable = "";
        $saldoInter = "";
        $direccionInter = "";
        $mactv = "";
        $macModem = "";
        $serieModem = "";
        $velocidad = "";
        $rx = "";
        $tx = "";
        $snr = "";
        $colilla = "";
        $hora="";
        $fechaTrabajo="";
        $coordenadas="";
        $observaciones="";
        $fechaProgramacion="";
        $idVendedor="";
        $tecnologia="";
        $marcaModelo="";
        $nodo="";
        $recepcionTv="";

    }

 ?>
<!DOCTYPE html>
<html lang="es">

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

</head>

<body>

    <?php
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
                          <div class="panel-heading"><b>Orden de trabajo</b> <span id="nombreOrden" class="label label-danger"></span></div>
                          <form id="ordenTrabajo" action="" method="POST">
                          <div class="panel-body">
                              <div class="col-md-12">
                                  <button class="btn btn-default btn-sm" id="nuevaOrdenId" onclick="nuevaOrden()" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Nueva orden"><i class="far fa-file"></i></button>
                                  <button class="btn btn-default btn-sm" id="editar" onclick="editarOrden()" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Editar orden"><i class="far fa-edit"></i></button>
                                  <button class="btn btn-default btn-sm" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Ver cliente"><i class="far fa-eye"></i></button>
                                  <button class="btn btn-default btn-sm" type="button" id="guardar" name="btn_nuevo" onclick="guardarOrden()" data-toggle="tooltip" data-placement="bottom" title="Guardar orden" disabled><i class="far fa-save"></i></button>
                                  <?php echo '<input style="display: none;" type="submit" id="guardar2" value="">'; ?>
                                  <button class="btn btn-default btn-sm" type="button" name="btn_nuevo" data-placement="bottom" title="Buscar orden" data-toggle="modal" data-target="#buscarOrden"><i class="fas fa-search"></i></button>
                                  <button class="btn btn-default btn-sm" id="imprimir" onclick="imprimirOrden()" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Imprimir orden" ><i class="fas fa-print"></i></button>
                                  <div class="pull-right">

                                      <button class="btn btn-default btn-sm" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Estado de cuenta"><i class="far fa-file-alt"></i></button>
                                      <button id="btn-cable" class="btn btn-default btn-sm" onclick="ordenCable()" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Orden de cable" disabled><i class="fas fa-tv"></i></button>
                                      <button id="btn-internet" class="btn btn-default btn-sm" onclick="ordenInternet()" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Orden de internet" disabled><i class="fas fa-wifi"></i></button>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-3">
                                      <br>
                                      <?php
                                      if (isset($_GET['nOrden'])) {
                                         echo "<input id='creadoPor' class='form-control input-sm' type='hidden' name='creadoPor' value='{$creadoPor}'>";
                                         echo "<input id='tipoServicio' class='form-control input-sm' type='hidden' name='tipoServicio' value='{$tipoServicio}' readonly>";
                                      }
                                      else{
                                         echo '<input id="creadoPor" class="form-control input-sm" type="hidden" name="creadoPor" value="'.$_SESSION['nombres'] . " " . $_SESSION['apellidos'].'"' . '>';
                                         echo '<input id="tipoServicio" class="form-control input-sm" type="hidden" name="tipoServicio" value="" readonly>';
                                      }
                                      ?>
                                      <label for="fechaOrden">Fecha de orden</label>
                                      <input id="fechaOrden" class="form-control input-sm" type="text" name="fechaOrden" value="<?php echo $fechaOrdenTrabajo ?>" readonly>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="numeroOrden">Número de orden</label>
                                      <input id="numeroOrden" class="form-control input-sm" type="text" name="numeroOrden" value="<?php echo $idOrdenTrabajo ?>" readonly>
                                  </div>
                                  <div class="col-md-3">
                                      <br>
                                      <label for="codigoCliente">Código de cliente</label>
                                      <input id="codigoCliente" class="form-control input-sm" type="text" name="codigoCliente" value="<?php echo $codigoCliente; ?>" readonly>
                                  </div>

                                  <div class="col-md-3">
                                      <br>
                                      <label for="tipoOrden">Tipo de orden</label>
                                      <input id="tipoOrden" class="form-control input-sm" type="text" name="tipoOrden" value="Técnica" readonly>
                                  </div>
                                  <div class="col-md-1">
                                      <br>
                                      <label for="diaCobro">Día c</label>
                                      <input class="form-control input-sm" type="text" name="diaCobro" value="<?php echo $diaCobro; ?>" readonly>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-6">
                                      <br>
                                      <label for="nombreCliente">Nombre del cliente</label>
                                      <input class="form-control input-sm input-sm" type="text" name="nombreCliente" value="<?php echo strtoupper($nombreCliente); ?>" readonly>
                                  </div>
                                  <div class="col-md-3">
                                      <br>
                                      <label for="telefonos">Teléfonos</label>
                                      <input class="form-control input-sm input-sm" type="text" name="telefonos" value="<?php echo $telefonos; ?>" readonly>
                                  </div>
                                  <div class="col-md-3">
                                      <br>
                                      <label for="municipio">Municipio</label>
                                      <select class="form-control input-sm" name="municipio" disabled>
                                          <option value="" selected>Seleccionar</option>
                                          <?php
                                          foreach ($arrMunicipios as $key) {
                                              if ($key['idMunicipio'] == $idMunicipio) {
                                                  echo "<option value=".$key['idMunicipio']." selected>".$key['nombreMunicipio']."</option>";
                                              }
                                              else {
                                                  echo "<option value=".$key['idMunicipio'].">".$key['nombreMunicipio']."</option>";
                                              }

                                          }
                                           ?>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-6">
                                      <br>
                                      <h4 class="alert alert-info cable"><strong>Cable</strong></h4>
                                      <div class="row">
                                          <div class="col-md-8">
                                              <label for="tipoActividadCable">Tipo de actividad</label>
                                              <select id="tipoActividadCable" class="form-control input-sm cable" name="tipoActividadCable" disabled>
                                                  <option value="" selected>Seleccionar</option>
                                                  <?php

                                                  foreach ($arrayActividadesC as $key) {
                                                      if ($key['nombreActividad'] == strval($idActividadCable)) {
                                                          echo "<option value='".$key['nombreActividad']."' selected>".$key['nombreActividad']."</option>";
                                                      }else {
                                                          echo "<option value='".$key['nombreActividad']."'>".$key['nombreActividad']."</option>";
                                                      }

                                                  }
                                                  ?>
                                              </select>
                                          </div>
                                          <div class="col-md-4">
                                              <label for="saldoCable">Saldo</label>
                                              <input id="saldoCable" class="form-control input-sm cable" type="text" name="saldoCable" value="<?php echo $saldoCable; ?>" readonly="true">
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-md-12">
                                              <label for="direccionCable">Dirección</label>
                                              <textarea id="direccionCable" class="form-control input-sm cable" name="direccionCable" rows="2" cols="40" readonly><?php echo strtoupper($direccionCable); ?></textarea>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <br>
                                      <h4 class="alert alert-info"><strong>Internet</strong></h4>
                                      <div class="row">
                                          <div class="col-md-8">
                                              <label for="tipoActividadInternet">Tipo de actividad</label>
                                              <select id="tipoActividadInter" class="form-control input-sm internet" name="tipoActividadInternet" disabled>
                                                  <option value="" selected>Seleccionar</option>
                                                  <?php
                                                  foreach ($arrayActividadesI as $key) {
                                                      if ($key['nombreActividad'] == $idActividadInter) {
                                                          echo "<option value='".$key['nombreActividad']."' selected>".$key['nombreActividad']."</option>";
                                                      }else {
                                                          echo "<option value='".$key['nombreActividad']."'>".$key['nombreActividad']."</option>";
                                                      }
                                                  }
                                                  ?>
                                              </select>
                                          </div>
                                          <div class="col-md-4">
                                              <label for="saldoInternet">Saldo</label>
                                              <input id="saldoInternet" class="form-control input-sm internet" type="text" name="saldoInternet" value="<?php echo $saldoInter; ?>" readonly>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-md-12">
                                              <label for="direccionInternet">Dirección</label>
                                              <textarea id="direccionInternet" class="form-control input-sm internet" name="direccionInternet" rows="2" cols="40" readonly><?php echo strtoupper($direccionInter); ?></textarea>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-3">
                                      <br>
                                      <label for="macModem">MAC del modem</label>
                                      <input id="macModem" class="form-control input-sm internet" type="text" name="macModem" value="<?php echo $macModem; ?>" readonly>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="serieModem">Serie del modem</label>
                                      <input id="serieModem" class="form-control input-sm internet" type="text" name="serieModem" value="<?php echo $serieModem; ?>" readonly>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="velocidad">Velocidad</label>
                                      <select id="velocidad" class="form-control input-sm internet" name="velocidad" disabled>
                                          <option value="" selected>Seleccionar</option>
                                          <?php
                                          foreach ($arrayVelocidades as $key) {
                                              if ($key['idVelocidad'] == $velocidad) {
                                                  echo "<option value=".$key['idVelocidad']." selected>".strtoupper($key['nombreVelocidad'])."</option>";
                                              }else {
                                                  echo "<option value=".$key['idVelocidad'].">".strtoupper($key['nombreVelocidad'])."</option>";
                                              }

                                          }
                                          ?>
                                      </select>
                                  </div>
                                  <div class="col-md-1">
                                      <br>
                                      <label for="rx">Rx</label>
                                      <input id="rx" class="form-control input-sm internet" type="text" name="rx" value="<?php echo $rx; ?>" readonly>
                                  </div>
                                  <div class="col-md-1">
                                      <br>
                                      <label for="tx">Tx</label>
                                      <input id="tx" class="form-control input-sm internet" type="text" name="tx" value="<?php echo $tx; ?>" readonly>
                                  </div>
                                  <div class="col-md-1">
                                      <br>
                                      <label for="snr">SNR</label>
                                      <input id="snr" class="form-control input-sm internet" type="text" name="snr" value="<?php echo $snr; ?>" readonly>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="colilla">Colilla</label>
                                      <input id="colilla" class="form-control input-sm internet" type="text" name="colilla" value="<?php echo $colilla; ?>" readonly>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-2">
                                      <br>
                                      <label for="fechaTrabajo">Fecha de trabajo</label>
                                      <input class="form-control input-sm" type="text" name="fechaTrabajo" value="<?php echo $fechaTrabajo; ?>" readonly>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="hora">Hora</label>
                                      <input id="hora" class="form-control input-sm" type="text" name="hora" value="<?php echo $hora; ?>" readonly>
                                  </div>
                                  <div class="col-md-3">
                                      <br>
                                      <label for="fechaProgramacion">Fecha de programación</label>
                                      <input class="form-control input-sm" type="text" name="fechaProgramacion" value="<?php echo $fechaProgramacion; ?>" readonly>
                                  </div>
                                  <div class="col-md-5">
                                      <br>
                                      <label for="responsable">Responsable</label>
                                      <select class="form-control input-sm" name="responsable" disabled>
                                          <option value="" selected>Seleccionar</option>
                                          <?php
                                          foreach ($arrayTecnicos as $key) {
                                              if ($key['idTecnico'] == $idTecnico) {
                                                  echo "<option value=".$key['idTecnico']." selected>".strtoupper($key['nombreTecnico'])."</option>";
                                              }
                                              echo "<option value=".$key['idTecnico'].">".strtoupper($key['nombreTecnico'])."</option>";
                                          }
                                          ?>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-3">
                                      <br>
                                      <label for="mactv">MAC TV</label>
                                      <input id="mactv" class="form-control input-sm cable" type="text" name="mactv" value="<?php echo $mactv ?>" readonly>
                                  </div>
                                  <div class="col-md-9">
                                      <br>
                                      <label for="coordenadas">Coordenadas/otros datos</label>
                                      <input class="form-control input-sm internet" type="text" name="coordenadas" value="<?php echo $coordenadas; ?>" readonly>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-12">
                                      <br>
                                      <label for="observaciones">Observaciones</label>
                                      <textarea class="form-control input-sm" name="observaciones" rows="2" cols="40" readonly><?php echo $observaciones; ?></textarea>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-2">
                                      <br>
                                      <label for="nodo">Nodo</label>
                                      <input id="nodo" class="form-control input-sm" type="text" name="nodo" value="<?php echo $nodo ?>" readonly>
                                  </div>
                                  <div class="col-md-3">
                                      <br>
                                      <label for="marcaModelo">Marca/modelo</label>
                                      <input id="marcaModelo" class="form-control input-sm internet" type="text" name="marcaModelo" value="<?php echo $marcaModelo ?>" readonly>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="tecnologia">Tecnología</label>
                                      <input id="tecnologia" class="form-control input-sm" type="text" name="tecnologia" value="<?php echo $tecnologia ?>" readonly>
                                  </div>
                                  <div class="col-md-3">
                                      <br>
                                      <label for="vendedor">Vendedor</label>
                                      <select id="vendedor" class="form-control input-sm" name="vendedor" disabled>
                                          <option value="" selected>Seleccionar</option>
                                          <?php
                                          foreach ($arrayVendedores as $key) {
                                              if ($key['idVendedor'] == $idVendedor) {
                                                  echo "<option value='".$key['idVendedor']."' selected>".$key['nombresVendedor']." ".$key['apellidosVendedor']."</option>";
                                              }else {
                                                  echo "<option value='".$key['idVendedor']."'>".$key['nombresVendedor']." ".$key['apellidosVendedor']."</option>";
                                              }
                                          }
                                          ?>
                                      </select>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="recepcionTv">Rx TV</label>
                                      <input class="form-control input-sm cable" type="text" name="recepcionTv" value="<?php echo $recepcionTv; ?>" readonly>
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
        <!-- Modal -->
        <div id="buscarOrden" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Buscar orden de trabajo</h4>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-md-12">
                          <input class="form-control" type="text" name="caja_busqueda" id="caja_busqueda" placeholder="N°orden, Fecha orden, Código cliente, Nombre, Dirección, Observaciones, Mac, Serial">
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12">
                          <div id="datos">

                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              </div>
            </div>

          </div>
        </div>
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
    <script src="js/ordenTrabajo.js"></script>
    <script src="js/searchot.js"></script>
    <script type="text/javascript">
        // Get the input field
        var cod = document.getElementById("codigoCliente");

        $('#ordenTrabajo').on('keyup keypress', function(e) {
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
        var codValue = document.getElementById("codigoCliente").value
        // Trigger the button element with a click
        window.location="ordenTrabajo.php?codigoCliente="+codValue;
        }
        });
    </script>
    <?php
    if (isset($_GET['codigoCliente'])) {
        echo "<script>
            document.getElementById('ordenTrabajo').action = 'php/nuevaOrdenTrabajo.php';
            document.getElementById('btn-cable').disabled = false;
            document.getElementById('btn-internet').disabled = false;
            document.getElementById('guardar').disabled = false;
            document.getElementById('editar').disabled = true;
            document.getElementById('imprimir').disabled = true;
            var inputs = document.getElementsByClassName('input-sm');
            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].readOnly == true) {
                    inputs[i].readOnly = false;
                }
                else if (inputs[i].disabled == true) {
                    inputs[i].disabled = false;
                }
            }
            var time = new Date();

            var seconds = time.getSeconds();
            var minutes = time.getMinutes();
            var hour = time.getHours();
            time = hour + ':' + minutes + ':' + seconds;
            document.getElementById('hora').value = time;
        </script>";
    }
    if (isset($_GET['nOrden'])) {
        echo "<script>
        var tipoServicio = document.getElementById('tipoServicio').value
        if (tipoServicio == 'C') {
            document.getElementById('nombreOrden').innerHTML = 'CABLE';
        }else if (tipoServicio == 'I') {
            document.getElementById('nombreOrden').innerHTML = 'INTERNET';
        }
        </script>";

    }
    ?>

</body>

</html>
