<?php

    session_start();

    // get passed parameter value, in this case, the record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record no encontrado.');

    //include database connection
    include '../../php/connection.php';
    $precon = new ConectionDB();
    $con = $precon->ConectionDB();
    // read current record's data
    try {
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
        $estado_cable = $row['servicio_suspendido']; // 0 o 1
        $estado_internet = $row['estado_cliente_in']; // 1, 2, 3
        $codigo = $row["cod_cliente"];
        $nContrato = $row["numero_contrato"];
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
        $saldoActual = $row['saldo_actual'];
        $diasCredito = $row['dias_credito'];
        $limiteCredito = $row['limite_credito'];
        $tipoFacturacion = $row['tipo_facturacion']; //Credito fiscal o consumidor final
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
        $cuotaMensualCable = $row['valor_cuota'];
        $prepago = $row['prepago'];
        $tipoComprobante = $row['tipo_comprobante'];
        $tipoServicio = $row['tipo_servicio'];
        $periodoContratoCable = $row['periodo_contrato_ca'];
        $vencimientoCable = $row['vencimiento_ca'];
        if (strlen($row['fecha_reinstalacion']) < 8) {
            $fechaInstalacionCable = "";
        }else {
            $fechaInstalacionCable = date_format(date_create($row['fecha_instalacion']), "d/m/Y");
        }
        if (strlen($row['fecha_suspencion']) < 8) {
            $fechaSuspensionCable = "";
        }else {
            $fechaSuspensionCable = date_format(date_create($row['fecha_suspencion']), "d/m/Y");
        }

        if (strlen($row['fecha_reinstalacion']) < 8) {
            $fechaReinstalacionCable = "";
        }else {
            $fechaReinstalacionCable = date_format(date_create($row['fecha_reinstalacion']), "d/m/Y");
        }
        $tecnicoCable = $row['id_tecnico'];
        $direccionCable = $row['dire_cable'];
        $nDerivaciones = $row['numero_derivaciones'];

        /****************** DATOS INTERNET ***********************/
        if (strlen($row['fecha_instalacion_in']) < 8) {
            $fechaInstalacionInter = "";
        }else {
            $fechaInstalacionInter = date_format(date_create($row['fecha_instalacion_in']), "d/m/Y");
        }

        if (strlen($row['fecha_primer_factura_in']) < 8) {
            $fechaPrimerFacturaInter = "";
        }else {
            $fechaPrimerFacturaInter = date_format(date_create($row['fecha_primer_factura_in']), "d/m/Y");
        }

        $tipoServicioInternet = $row['tipo_servicio_in'];
        $nodo = $row['dire_telefonia'];
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
            $vencimientoInternet = date_format(date_create($row['vencimiento_in']), "d/m/Y");
        }

        if (strlen($row['ult_ren_in']) < 8) {
            $ultimaRenovacionInternet = "";
        }else {
            $ultimaRenovacionInternet = date_format(date_create($row['ult_ren_in']), "d/m/Y");
        }

        if (strlen($row['fecha_suspencion_in']) < 8) {
            $fechaSuspencionInternet = "";
        }else {
            $fechaSuspencionInternet = date_format(date_create($row['fecha_suspencion_in']), "d/m/Y");
        }

        if (strlen($row['fecha_reconexion_in']) < 8) {
            $fechaReconexionInternet = "";
        }else {
            $fechaReconexionInternet = date_format(date_create($row['fecha_reconexion_in']), "d/m/Y");
        }

        $promocion = $row['id_promocion'];
        if (strlen($row['dese_promocion_in']) < 8) {
            $promocionDesde = "";
        }else {
            $promocionDesde = date_format(date_create($row['dese_promocion_in']), "d/m/Y");
        }

        if (strlen($row['hasta_promocion_in']) < 8) {
            $promocionHasta = "";
        }else {
            $promocionHasta = date_format(date_create($row['hasta_promocion_in']), "d/m/Y");
        }
        $cuotaPromocion = $row['cuota_promocion'];
        $direccionInternet = $row['dire_internet'];
        $colilla = $row['colilla'];
        $modelo = $row['marca_modem'];
        $recepcion = $row['recep_modem'];
        $wanip = $row['wanip'];
        $mac = $row['mac_modem'];
        $transmision = $row['trans_modem'];
        //$coordenadas = $row['coordenadas'];
        $serie = $row['serie_modem'];
        $ruido = $row['ruido_modem'];
        $nodo = "";
        $wifiClave = $row['clave_modem'];
    }

    // show error
    catch(PDOException $exception){
        die('ERROR: ' . $exception->getMessage());
    }
 ?>
 <?php
 require_once 'php/GetAllInfo.php';
 $data = new GetAllInfo();
 $arrDepartamentos = $data->getData('tbl_departamentos_cxc');
 $arrMunicipios = $data->getData('tbl_municipios_cxc');
 $arrColonias = $data->getData('tbl_colonias_cxc');
 $arrFormaFacturar = $data->getData('tbl_forma_pago');
 $arrCobradores = $data->getData('tbl_cobradores');
 $arrComprobantes = $data->getData('tbl_tipo_comprobante');
 $arrServicioCable = $data->getData('tbl_servicios_cable');
 $arrServicioInter = $data->getData('tbl_servicios_inter');
 $arrVelocidad = $data->getData('tbl_velocidades');
 $arrTecnologias = $data->getData('tbl_tecnologias');
 $arrTiposClientes = $data->getData('tbl_tipos_clientes');
 //Array de ordenes de trabajo por cliente
 $arrOrdenesTrabajo = $data->getDataOrders('tbl_ordenes_trabajo', $codigo);
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
    <link rel="shortcut icon" href="../images/cablesat.png" />
    <!-- Bootstrap Core CSS -->
    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="../../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Font awesome CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link href="../../dist/css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="../../dist/css/custom-principal.css">
    <link rel="stylesheet" href="../../dist/css/switches.css">

    <!-- Morris Charts CSS -->
    <link href="../../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- DataTables CSS -->
    <!--<link rel="stylesheet" href="../vendor/datatables/css/dataTables.bootstrap.min.css"> -->
    <!--<link rel="stylesheet" href="../vendor/datatables/css/jquery.dataTables.min.css"> -->
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

            <ul class="nav navbar-top-links navbar-left">
                <!-- /.dropdown -->
                <li class="dropdown procesos">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Procesos <i class="fas fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="ordenTrabajo.php" target="_blank">Ordenes de trabajo</a>
                        </li>
                        <li><a href="ordenSuspension.php" target="_blank">Ordenes de suspensión</a>
                        </li>
                        <li><a href="ordenReconexion.php" target="_blank">Ordenes de reconexión</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

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
                        <li><a href="../../php/logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a>
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
                            echo "<li><a href='../modulo_cxc/cxc.php'><i class='fas fa-hand-holding-usd'></i> Cuentas por cobrar</a></li>";
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
        <div class="" id="page-wrapper">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-responsive">
                        <tr>
                            <td><button class="btn btn-danger btn-block"><i class="fas fa-sign-out-alt fa-2x"></i></button></td>
                            <td><button class="btn btn-success btn-block"><i class="fas fa-print fa-2x"></i></button></td>
                            <td><button class="btn btn-warning btn-block"><i class="far fa-edit fa-2x"></i></button></td>
                            <td><button class="btn btn-primary btn-block" data-toggle="modal" data-target="#buscarCliente"><i class="fas fa-user-plus fa-2x"></i></button></td>
                        </tr>

                        <tr>
                            <td><button class="btn btn-primary btn-block" style="font-size: 16px;">Contrato de cable</button></td>
                            <td><button class="btn btn-primary btn-block" style="font-size: 16px;">Contrato de internet</button></td>
                            <td><button class="btn btn-primary btn-block" style="font-size: 16px;">Estado de cuenta</button></td>
                            <td><button class="btn btn-success btn-block"><i class="fas fa-user-edit fa-2x"></i></button></td>
                        </tr>
                        <tr>
                            <td><button class="btn btn-info btn-block" style="font-size: 16px; ;">Reporte</button></td>
                            <td colspan="2"><button class="btn btn-info btn-block" style="font-size: 16px; ;">Compromiso de internet</button></td>
                            <td><button class="btn btn-danger btn-block"><i class="fas fa-save fa-2x"></i></button></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-bordered table-responsive table-condensed">
                        <th></th>
                        <th>Activo</th>
                        <th>Susp</th>
                        <th>Sin serv</th>
                        <?php
                        if ($estado_cable == "F" OR $estado_cable == "") {
                            echo "<tr class='info'>
                                <th>TV</th>
                                <td><label class='switch'><input type='radio' name ='cable' checked disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input type='radio' name ='cable' disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input type='radio' name ='cable' disabled><span class='slider round'></span></label></td>
                            </tr>";
                        }
                        else if ($estado_cable == "T") {
                            echo "<tr class='info'>
                                <th>TV</th>
                                <td><label class='switch'><input type='radio' name ='cable' disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input type='radio' name ='cable' checked disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input type='radio' name ='cable' disabled><span class='slider round'></span></label></td>
                            </tr>";
                        }
                        else {
                            echo "<tr class='info'>
                                <th>TV</th>
                                <td><label class='switch'><input type='radio' name ='cable' disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input type='radio' name ='cable' disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input type='radio' name ='cable' checked disabled><span class='slider round'></span></label></td>
                            </tr>";
                        }
                        if ($estado_internet == 1) {
                            echo "<tr class='success'>
                                <th>Internet</th>
                                <td><label class='switch'><input type='radio' name='internet' checked disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input type='radio' name='internet' disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input type='radio' name='internet' disabled><span class='slider round'></span></label></td>
                            </tr>";
                        }
                        elseif ($estado_internet == 2) {
                            echo "<tr class='success'>
                                <th>Internet</th>
                                <td><label class='switch'><input type='radio' name='internet' disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input type='radio' name='internet' checked disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input type='radio' name='internet' disabled><span class='slider round'></span></label></td>
                            </tr>";
                        }
                        else {
                            echo "<tr class='success'>
                                <th>Internet</th>
                                <td><label class='switch'><input type='radio' name='internet' disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input type='radio' name='internet' disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input type='radio' name='internet' checked disabled><span class='slider round'></span></label></td>
                            </tr>";
                        }
                        echo "<tr class='warning'>
                            <th>Teléfono</th>
                            <td><label class='switch'><input type='radio' name='telefono' disabled><span class='slider round'></span></label></td>
                            <td><label class='switch'><input type='radio' name='telefono' disabled><span class='slider round'></span></label></td>
                            <td><label class='switch'><input type='radio' name='telefono' checked disabled><span class='slider round'></span></label></td>
                        </tr>";
                        ?>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span style="font-size:15px;" class="label label-danger"><?php echo $codigo; ?></span> <span><?php echo strtoupper($nombre); ?></span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-pills nav-justified">
                                <li class="active"><a href="#datos-generales" data-toggle="tab">Datos generales</a>
                                </li>
                                <li><a href="#otros-datos" data-toggle="tab">Otros datos</a>
                                </li>
                                <li><a href="#servicios" data-toggle="tab">Servicios</a>
                                </li>
                                <li><a href="#ordenes-tecnicas" data-toggle="tab">Ordenes técnicas</a>
                                </li>
                                <li><a href="#notificaciones-traslados" data-toggle="tab">Traslados</a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="datos-generales">
                                    <br>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="codigo">Código del cliente</label>
                                            <input class="form-control" type="text" name="codigo" value="<?php echo $codigo; ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="contrato">Número de contrato</label>
                                            <input class="form-control" type="text" name="contrato" value="<?php echo $nContrato; ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="factura">Número de factura</label>
                                            <input class="form-control" type="text" name="factura" value="<?php echo $nFactura; ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="nombre">Nombre</label>
                                            <input class="form-control" type="text" name="nombre" value="<?php echo $nombre; ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="empresa">Empresa</label>
                                            <input class="form-control" type="text" name="empresa" value="">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="ncr">Número de registro</label>
                                            <input class="form-control" type="text" name="ncr" value="<?php echo $nRegistro; ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="dui">DUI</label>
                                            <input class="form-control" type="text" name="dui" value="<?php echo $dui; ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="expedicion">Lugar y fecha de expedición</label>
                                            <input class="form-control" type="text" name="expedicion" value="<?php echo $lugarExp; ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="nit">NIT</label>
                                            <input class="form-control" type="text" name="nit" value="<?php echo $nit; ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="fechaNacimiento">Fecha de nacimiento</label>
                                            <input class="form-control" type="text" name="fechaNacimiento" value="<?php echo $fechaNacimiento; ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="direccion">Dirección</label>
                                            <textarea class="form-control" name="direccion" rows="2" cols="40"><?php echo $direccion; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="departamento">Departamento</label>
                                            <select class="form-control" name="departamento">
                                                <option value="" selected>Seleccionar</option>
                                                <?php
                                                foreach ($arrDepartamentos as $key) {
                                                    if ($key['idDepartamento'] == $departamento) {
                                                        echo "<option value=".$key['idDepartamento']." selected>".$key['nombreDepartamento']."</option>";
                                                    }
                                                    else {
                                                        echo "<option value=".$key['idDepartamento'].">".$key['nombreDepartamento']."</option>";
                                                    }

                                                }
                                                 ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="municipio">Municipio</label>
                                            <select class="form-control" name="municipio">
                                                <option value="" selected>Seleccionar</option>
                                                <?php
                                                foreach ($arrMunicipios as $key) {
                                                    if ($key['idMunicipio'] == $municipio) {
                                                        echo "<option value=".$key['idMunicipio']." selected>".$key['nombreMunicipio']."</option>";
                                                    }
                                                    else {
                                                        echo "<option value=".$key['idMunicipio'].">".$key['nombreMunicipio']."</option>";
                                                    }

                                                }
                                                 ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="colonia">Barrio o colonia</label>
                                            <select class="form-control" name="municipio">
                                                <option value="" selected>Seleccionar</option>
                                                <?php
                                                foreach ($arrColonias as $key) {
                                                    if ($key['idColonia'] == $colonia) {
                                                        echo "<option value=".$key['idColonia']." selected>".$key['nombreColonia']."</option>";
                                                    }
                                                    else {
                                                        echo "<option value=".$key['idColonia'].">".$key['nombreColonia']."</option>";
                                                    }

                                                }
                                                 ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="direccionCobro">Dirección de cobro</label>
                                            <textarea class="form-control" name="direccionCobro" rows="2" cols="40"><?php echo $direccionCobro; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="telefono">Teléfono</label>
                                            <input class="form-control" type="text" name="telefono" value="<?php echo $telefonos; ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="telefonoTrabajo">Teléfono de trabajo</label>
                                            <input class="form-control" type="text" name="telefonoTrabajo" value="<?php echo $telTrabajo; ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="ocupacion">Ocupación</label>
                                            <input class="form-control" type="text" name="ocupacion" value="<?php echo $ocupacion; ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="cuentaContable">Cuenta contable</label>
                                            <input class="form-control" type="text" name="cuentaContable" value="<?php echo $cuentaContable; ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="formaFacturar">Forma al facturar</label>
                                            <select class="form-control" name="formaFacturar">
                                                <option value="" selected>Seleccionar</option>
                                                <?php
                                                foreach ($arrFormaFacturar as $key) {
                                                    if ($key['idFormaPago'] == $formaFacturar) {
                                                        echo "<option value=".$key['idFormaPago']." selected>".$key['nombreFormaPago']."</option>";
                                                    }
                                                    else {
                                                        echo "<option value=".$key['idFormaPago'].">".$key['nombreFormaPago']."</option>";
                                                    }

                                                }
                                                 ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="saldoActual">Saldo actual</label>
                                            <input class="form-control" type="text" name="saldoActual" value="<?php echo $saldoActual; ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="limiteCredito">Días de crédito</label>
                                            <input class="form-control" type="text" name="diasCredito" value="<?php echo $diasCredito; ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="limiteCredito">Límite de crédito</label>
                                            <input class="form-control" type="text" name="limiteCredito" value="<?php echo $limiteCredito; ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!--<div class="col-md-3">
                                            <label for="tipoFacturacion">Tipo de facturación</label>
                                            <input class="form-control" type="text" name="tipoFacturacion" value="<?php echo $tipoFacturacion; ?>">
                                        </div>-->
                                        <div class="col-md-12">
                                            <label for="facebook">Cuenta de Facebook</label>
                                            <input class="form-control" type="text" name="facebook" value="<?php echo $facebook; ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="correo">Correo electrónico</label>
                                            <input class="form-control" type="text" name="correo" value="<?php echo $correo; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="otros-datos">
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="cobrador">Cobrador que lo atiende</label>
                                            <select class="form-control" name="cobrador">
                                                <option value="" selected>Seleccionar</option>
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
                                    <br><br>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="rp1_nombre">Referencia personal #1</label>
                                            <input class="form-control" type="text" name="rf1_nombre" value="<?php echo $contacto1; ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="rp1_telefono">Teléfono</label>
                                            <input class="form-control" type="text" name="rp1_telefono" value="<?php echo $telCon1; ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="rp1_direccion">Dirección</label>
                                            <input class="form-control" type="text" name="rp1_direccion" value="<?php echo $dir1; ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="rp1_parentezco">Parentezco</label>
                                            <input class="form-control" type="text" name="rp1_parentezco" value="<?php echo $paren1; ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="rp2_nombre">Referencia personal #2</label>
                                            <input class="form-control" type="text" name="rf2_nombre" value="<?php echo $contacto2; ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="rp2_telefono">Teléfono</label>
                                            <input class="form-control" type="text" name="rp2_telefono" value="<?php echo $telCon2; ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="rp2_direccion">Dirección</label>
                                            <input class="form-control" type="text" name="rp2_direccion" value="<?php echo $dir2; ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="rp2_parentezco">Parentezco</label>
                                            <input class="form-control" type="text" name="rp2_parentezco" value="<?php echo $paren2; ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="rp3_nombre">Referencia personal #3</label>
                                            <input class="form-control" type="text" name="rf3_nombre" value="<?php echo $contacto3; ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="rp3_telefono">Teléfono</label>
                                            <input class="form-control" type="text" name="rp3_telefono" value="<?php echo $telCon3; ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="rp3_direccion">Dirección</label>
                                            <input class="form-control" type="text" name="rp3_direccion" value="<?php echo $dir3; ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="rp3_parentezco">Parentezco</label>
                                            <input class="form-control" type="text" name="rp3_parentezco" value="<?php echo $paren3; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade in" id="servicios">
                                    <!--Accordion wrapper-->
                                    <div class="accordion md-accordion" id="accordionEx1" role="tablist" aria-multiselectable="true">

                                      <!-- Accordion card -->
                                      <div class="card">

                                        <!-- Card header -->
                                        <div class="card-header" role="tab" id="headingTwo1">
                                          <a style="text-decoration:none;" class="collapsed" data-toggle="collapse" data-parent="#accordionEx1" href="#collapseTwo1"
                                            aria-expanded="false" aria-controls="collapseTwo1">
                                            <h5 class="mb-0 alert bg-info">
                                              TV POR CABLE <i class="fas fa-angle-down rotate-icon"></i>
                                            </h5>
                                          </a>
                                        </div>

                                        <!-- Card body -->
                                        <div id="collapseTwo1" class="collapse" role="tabpanel" aria-labelledby="headingTwo1" data-parent="#accordionEx1">
                                          <div class="card-body">
                                              <div class="row">
                                                  <div class="col-md-2">
                                                      <label for="fechaInstalacionCable">Fecha de instalación</label>
                                                      <input class="form-control" type="text" name="fechaInstalacionCable" value="<?php echo $fechaInstalacion; ?>">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="fechaPrimerFacturaCable">Fecha de primer factura</label>
                                                      <input class="form-control" type="text" name="fechaPrimerFacturaCable" value="<?php echo $fechaPrimerFactura; ?>">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="fechaSuspensionCable">Fecha suspension</label>
                                                      <input class="form-control" type="text" name="fechaSuspensionCable" value="<?php echo $fechaSuspensionCable; ?>">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="exento">Exento</label>
                                                      <?php
                                                      if ($exento == "F" || $exento == NULL) {
                                                          echo "<input class='form-control' type='checkbox' name='exento'>";
                                                      }else {
                                                          echo "<input class='form-control' type='checkbox' name='exento' checked>";
                                                      }
                                                      ?>

                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="diaGenerarFacturaCable">Día para generar factura</label>
                                                      <input class="form-control" type="text" name="diaGenerarFacturaCable" value="<?php echo $diaCobro; ?>">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="cortesia">Cortesía</label>
                                                      <?php
                                                      if ($cortesia == "F") {
                                                          echo "<input class='form-control' type='checkbox' name='cortesia'>";
                                                      }else if($cortesia == "T"){
                                                          echo "<input class='form-control' type='checkbox' name='cortesia' checked>";
                                                      }
                                                      else {
                                                          echo "<input class='form-control' type='checkbox' name='cortesia'>";
                                                      }
                                                      ?>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-2">
                                                      <label for="cuotaMensualCable">Cuota mensual</label>
                                                      <input class="form-control" type="text" name="cuotaMensualCable" value="<?php echo $cuotaMensualCable; ?>">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="prepago">Prepago</label>
                                                      <input class="form-control" type="text" name="prepago" value="<?php echo $prepago; ?>">
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="tipoComprobante">Tipo de comprobante a generar</label>
                                                      <select class="form-control" name="tipoComprobante">
                                                          <option value="" selected>Seleccionar</option>
                                                          <?php
                                                          foreach ($arrComprobantes as $key) {
                                                              if ($key['idComprobante'] == $tipoComprobante) {
                                                                  echo "<option value=".$key['idComprobante']." selected>".$key['nombreComprobante']."</option>";
                                                              }
                                                              else {
                                                                  echo "<option value=".$key['idComprobante'].">".$key['nombreComprobante']."</option>";
                                                              }

                                                          }
                                                           ?>
                                                      </select>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="tipoServicio">Tipo de servicio</label>
                                                      <select class="form-control" name="tipoServicio">
                                                          <option value="" selected>Seleccionar</option>
                                                          <?php
                                                          foreach ($arrServicioCable as $key) {
                                                              if ($key['idServicioCable'] == $tipoServicio) {
                                                                  echo "<option value=".$key['idServicioCable']." selected>".$key['nombreServicioCable']."</option>";
                                                              }
                                                              else {
                                                                  echo "<option value=".$key['idServicioCable'].">".$key['nombreServicioCable']."</option>";
                                                              }

                                                          }
                                                           ?>
                                                      </select>
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="mesesContratoCable">Período de contrato en meses</label>
                                                      <input class="form-control" type="text" name="mesesContratoCable" value="<?php echo $periodoContratoCable; ?>">
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-3">
                                                      <label for="vencimientoContratoCable">Fecha de vencimiento de contrato</label>
                                                      <input class="form-control" type="text" name="vencimientoContratoCable" value="<?php echo $vencimientoCable; ?>">
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="inicioContratoCable">Fecha de inicio de contrato</label>
                                                      <input class="form-control" type="text" name="inicioContratoCable" value="<?php echo $fechaInstalacionCable; ?>">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="fechaReconexionCable">Fecha de reconexión</label>
                                                      <input class="form-control" type="text" name="fechaReconexionCable" value="<?php echo $fechaReinstalacionCable; ?>">
                                                  </div>
                                                  <div class="col-md-4">
                                                      <label for="encargadoInstalacionCable"> Técnico que realizó la instalación</label>
                                                      <input class="form-control" type="text" name="encargadoInstalacionCable" value="<?php echo $tecnicoCable; ?>">
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-10">
                                                      <label for="direccionCable">Dirección</label>
                                                      <input class="form-control" type="text" name="direccionCable" value="<?php echo $direccionCable; ?>">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="derivaciones">N° de derivaciones</label>
                                                      <input class="form-control" type="text" name="derivaciones" value="<?php echo $nDerivaciones; ?>">
                                                  </div>
                                              </div>
                                          </div>
                                        </div>

                                      </div>
                                      <!-- Accordion card -->

                                      <!-- Accordion card -->
                                      <div class="card">

                                        <!-- Card header -->
                                        <div class="card-header" role="tab" id="headingTwo2">
                                          <a style="text-decoration:none;" class="collapsed" data-toggle="collapse" data-parent="#accordionEx1" href="#collapseTwo21"
                                            aria-expanded="false" aria-controls="collapseTwo21">
                                            <h5 class="mb-0 alert bg-success">
                                              INTERNET <i class="fas fa-angle-down rotate-icon"></i>
                                            </h5>
                                          </a>
                                        </div>

                                        <!-- Card body -->
                                        <div id="collapseTwo21" class="collapse" role="tabpanel" aria-labelledby="headingTwo21" data-parent="#accordionEx1">
                                          <div class="card-body">
                                              <div class="row">
                                                  <div class="col-md-2">
                                                      <label for="fechaInstalacionInternet">Fecha de instalación</label>
                                                      <input class="form-control" type="text" name="fechaInstalacionInternet" value="<?php echo $fechaInstalacionInter; ?>">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="fechaPrimerFacturaInternet">Fecha primer factura</label>
                                                      <input class="form-control" type="text" name="fechaPrimerFacturaInternet" value="<?php echo $fechaPrimerFacturaInter; ?>">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="tipoServicioInternet">Tipo de servicio</label>
                                                      <select class="form-control" name="tipoServicioInternet">
                                                          <option value="" selected>Seleccionar</option>
                                                          <?php
                                                          foreach ($arrServicioInter as $key) {
                                                              if ($key['idServicioInter'] == $tipoServicioInternet) {
                                                                  echo "<option value=".$key['idServicioInter']." selected>".$key['nombreServicioInter']."</option>";
                                                              }
                                                              else {
                                                                  echo "<option value=".$key['idServicioInter'].">".$key['nombreServicioInter']."</option>";
                                                              }

                                                          }
                                                           ?>
                                                      </select>
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="mesesContratoInternet">Período de contrato en meses</label>
                                                      <input class="form-control" type="text" name="mesesContratoInternet" value="<?php echo $periodoContratoInternet; ?>">
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="diaGenerarFacturaInternet">Día para generar factura</label>
                                                      <input class="form-control" type="text" name="diaGenerarFacturaInternet" value="<?php echo $diaCobroInter; ?>">
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-3">
                                                      <label for="velocidadInternet">Velocidad</label>
                                                      <select class="form-control" name="velocidadInternet">
                                                          <option value="" selected>Seleccionar</option>
                                                          <?php
                                                          foreach ($arrVelocidad as $key) {
                                                              if ($key['idVelocidad'] == $velocidad) {
                                                                  echo "<option value=".$key['idVelocidad']." selected>".$key['nombreVelocidad']."</option>";
                                                              }
                                                              else {
                                                                  echo "<option value=".$key['idVelocidad'].">".$key['nombreVelocidad']."</option>";
                                                              }

                                                          }
                                                           ?>
                                                      </select>
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="cuotaMensualInternet">Cuota mensual</label>
                                                      <input class="form-control" type="text" name="cuotaMensualInternet" value="<?php echo $cuotaMensualInter; ?>">
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="tipoCliente">Tipo de cliente</label>
                                                      <select class="form-control" name="tipoCliente">
                                                          <option value="" selected>Seleccionar</option>
                                                          <?php
                                                          foreach ($arrTiposClientes as $key) {
                                                              if ($key['idTipoCliente'] == $tipoClienteInter) {
                                                                  echo "<option value=".$key['idTipoCliente']." selected>".$key['nombreTipoCliente']."</option>";
                                                              }
                                                              else {
                                                                  echo "<option value=".$key['idTipoCliente'].">".$key['nombreTipoCliente']."</option>";
                                                              }

                                                          }
                                                           ?>
                                                      </select>
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="tecnologia">Tecnología</label>
                                                      <select class="form-control" name="tecnologia">
                                                          <option value="" selected>Seleccionar</option>
                                                          <?php
                                                          foreach ($arrTecnologias as $key) {
                                                              if ($key['idTecnologia'] == $tecnologia || strtolower($key['nombreTecnologia']) == strtolower($tecnologia)) {
                                                                  echo "<option value=".$key['idTecnologia']." selected>".$key['nombreTecnologia']."</option>";
                                                              }
                                                              else {
                                                                  echo "<option value=".$key['idTecnologia'].">".$key['nombreTecnologia']."</option>";
                                                              }

                                                          }
                                                           ?>
                                                      </select>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-3">
                                                      <label for="nContratoVigente">N° de contrato vigente</label>
                                                      <input class="form-control" type="text" name="nContratoVigente" value="<?php echo $nContratoInter; ?>">
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="vencimientoContratoInternet">Vencimiento de contrato</label>
                                                      <input class="form-control" type="text" name="vencimientoContratoInternet" value="<?php echo $vencimientoInternet; ?>">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="ultimaRenovacionInternet">Última renovación</label>
                                                      <input class="form-control" type="text" name="ultimaRenovacionInternet" value="<?php echo $ultimaRenovacionInternet; ?>">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="fechaSuspencionInternet">Fecha de suspención</label>
                                                      <input class="form-control" type="text" name="fechaSuspencionInternet" value="<?php echo $fechaSuspencionInternet; ?>">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="fechaReconexionInternet">Fecha de reconexión</label>
                                                      <input class="form-control" type="text" name="fechaReconexionInternet" value="<?php echo $fechaReconexionInternet; ?>">
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-5">
                                                      <label for="promocion">Promoción</label>
                                                      <input class="form-control" type="text" name="promocion" value="<?php echo $promocion; ?>">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="promocionDesde">Desde</label>
                                                      <input class="form-control" type="text" name="promocionDesde" value="<?php echo $promocionDesde; ?>">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="promocionHasta">Hasta</label>
                                                      <input class="form-control" type="text" name="promocionHasta" value="<?php echo $promocionHasta; ?>">
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="cuotaPromocion">Cuota de la promoción</label>
                                                      <input class="form-control" type="text" name="cuotaPromocion" value="<?php echo $cuotaPromocion; ?>">
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-12">
                                                      <label for="direccionInternet">Dirección</label>
                                                      <input class="form-control" type="text" name="direccionInternet" value="<?php echo $direccionInternet; ?>">
                                                  </div>
                                              </div>
                                              <hr style="border-top: 1px solid red;">
                                              <div class="row">
                                                  <div class="col-md-3">
                                                      <div class="row">
                                                          <div class="col-md-12">
                                                              <label for="colilla">Colilla</label>
                                                              <input class="form-control" type="text" name="colilla" value="<?php echo $colilla; ?>">
                                                          </div>
                                                          <div class="col-md-12">
                                                              <label for="wanip">WAN IP</label>
                                                              <input class="form-control" type="text" name="wanip" value="<?php echo $wanip; ?>">
                                                          </div>
                                                          <div class="col-md-12">
                                                              <label for="coordenadas">Coordenadas</label>
                                                              <input class="form-control" type="text" name="coordenadas" value="<?php if(isset($coordenadas)) echo $coordenadas; ?>">
                                                          </div>
                                                          <div class="col-md-12">
                                                              <label for="nodo">Nodo/Ap/Path</label>
                                                              <input class="form-control" type="text" name="nodo" value="<?php echo $nodo ?>">
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-9">
                                                      <div class="row">
                                                          <div class="col-md-8">
                                                              <label for="modelo">Modelo</label>
                                                              <input class="form-control" type="text" name="modelo" value="<?php echo $modelo; ?>">
                                                          </div>
                                                          <div class="col-md-4">
                                                              <label for="recepcion">Recepción</label>
                                                              <input class="form-control" type="text" name="recepcion" value="<?php echo $recepcion; ?>">
                                                          </div>
                                                          <div class="col-md-8">
                                                              <label for="mac">MAC</label>
                                                              <input class="form-control" type="text" name="mac" value="<?php echo $mac; ?>">
                                                          </div>
                                                          <div class="col-md-4">
                                                              <label for="transmicion">Transmición</label>
                                                              <input class="form-control" type="text" name="transmicion" value="<?php echo $transmision; ?>">
                                                          </div>
                                                          <div class="col-md-8">
                                                              <label for="serie">Serie</label>
                                                              <input class="form-control" type="text" name="serie" value="<?php echo $serie; ?>">
                                                          </div>
                                                          <div class="col-md-4">
                                                              <label for="ruido">Ruido</label>
                                                              <input class="form-control" type="text" name="ruido" value="<?php echo $ruido; ?>">
                                                          </div>
                                                          <div class="col-md-12">
                                                              <label for="claveWifi">Clave WIFI</label>
                                                              <input class="form-control" type="text" name="claveWifi">
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-6">
                                                      <br>
                                                      <button class="btn btn-success btn-block" type="button" name="activarServicio" style="font-size:16px">Activar servicio</button>
                                                  </div>
                                                  <div class="col-md-6">
                                                      <br>
                                                      <button class="btn btn-danger btn-block" type="button" name="desactivarServicio" style="font-size:16px">Desactivar servicio</button>
                                                  </div>
                                              </div>
                                              <hr style="border-top: 1px solid red;">
                                          </div>
                                        </div>

                                      </div>
                                      <!-- Accordion card -->

                                      <!-- Accordion card -->
                                      <div class="card">

                                        <!-- Card header -->
                                        <div class="card-header" role="tab" id="headingThree31">
                                          <a style="text-decoration:none;" class="collapsed" data-toggle="collapse" data-parent="#accordionEx1" href="#collapseThree31"
                                            aria-expanded="false" aria-controls="collapseThree31">
                                            <h5 class="mb-0 alert bg-warning">
                                              TELEFONIA <i class="fas fa-angle-down rotate-icon"></i>
                                            </h5>
                                          </a>
                                        </div>

                                        <!-- Card body -->
                                        <div id="collapseThree31" class="collapse" role="tabpanel" aria-labelledby="headingThree31" data-parent="#accordionEx1">
                                          <div class="card-body">
                                            <h4>Servicio no disponible</h4>
                                          </div>
                                        </div>

                                      </div>
                                      <!-- Accordion card -->

                                    </div>
                                    <!-- Accordion wrapper -->
                                </div>
                                <div class="tab-pane fade" id="ordenes-tecnicas">
                                    <h4 class="alert bg-success"><strong>Historial de ordenes de trabajo</strong></h4>
                                    <div class="ordenes">
                                        <div class="col-md-12">
                                            <button class="btn btn-danger pull-right" type="button" name="button" data-toggle="modal" data-target="#ordenesTrabajo"><i class="fas fa-search"></i></button>
                                            <br><br>
                                        </div>
                                        <table class="table table-bordered table-hover">
                                                <thead class="info">
                                                    <tr class="bg-danger">
                                                        <th>N° de orden</th>
                                                        <th>Tipo de orden</th>
                                                        <th>Fecha de orden</th>
                                                        <th>Fecha realizada</th>
                                                        <th>Actividad cable</th>
                                                        <th>Actividad internet</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        foreach ($arrOrdenesTrabajo as $key) {
                                                            echo "<tr><td>";
                                                            echo $key["idOrdenTrabajo"] . "</td><td>";
                                                            echo $key["tipoOrdenTrabajo"] . "</td><td>";
                                                            echo $key["fechaOrdenTrabajo"] . "</td><td>";
                                                            echo $key["fechatrabajo"] . "</td><td>";
                                                            echo $key["actividadCable"] . "</td><td>";
                                                            echo $key["actividadInter"] . "</td><tr>";
                                                                }
                                                    ?>
                                                </tbody>
                                        </table>
                                    </div>
                                    <h4 class="alert bg-success"><strong>Historial de ordenes de suspensión</strong></h4>
                                    <div class="ordenes">
                                        <div class="col-md-12">
                                            <button class="btn btn-danger pull-right" type="button" name="button" data-toggle="modal" data-target="#ordenesSuspension"><i class="fas fa-search"></i></button>
                                            <br><br>
                                        </div>
                                        <table class="table table-bordered table-hover">
                                                <thead class="info">
                                                    <tr>
                                                        <th>N° de orden</th>
                                                        <th>Tipo de orden</th>
                                                        <th>Fecha de orden</th>
                                                        <th>Fecha realizada</th>
                                                        <th>Actividad cable</th>
                                                        <th>Actividad internet</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        foreach ($arrOrdenesTrabajo as $key) {
                                                            echo "<tr><td>";
                                                            echo $key["idOrdenTrabajo"] . "</td><td>";
                                                            echo $key["codigoCliente"] . "</td><td>";
                                                            echo $key["fechaOrdenTrabajo"] . "</td><td>";
                                                            echo $key["fechatrabajo"] . "</td><td>";
                                                            echo $key["actividadCable"] . "</td><td>";
                                                            echo $key["actividadInter"] . "</td><td>";
                                                                }
                                                            ?>
                                                </tbody>
                                        </table>
                                    </div>
                                    <h4 class="alert bg-success"><strong>Historial de ordenes de reconexión</strong></h4>
                                    <div class="ordenes">
                                        <div class="col-md-12">
                                            <button class="btn btn-danger pull-right" type="button" name="button" data-toggle="modal" data-target="#ordenesReconexion"><i class="fas fa-search"></i></button>
                                            <br><br>
                                        </div>
                                        <table class="table table-bordered table-hover">
                                                <tr class="info">
                                                    <th>N° de orden</th>
                                                    <th>Tipo de orden</th>
                                                    <th>Fecha de orden</th>
                                                    <th>Actividad realizada</th>
                                                    <th>Fecha de trabajo</th>
                                                    <th>Actividad internet</th>
                                                </tr>
                                                <tr>
                                                    <td>99947</td>
                                                    <td>Técnica</td>
                                                    <td>12/10/2019</td>
                                                    <td>Revisión de mala señal</td>
                                                    <td>25/7/2018</td>
                                                    <td>Cambio de contraseña de WIFI</td>
                                                </tr>
                                                <tr>
                                                    <td>99947</td>
                                                    <td>Técnica</td>
                                                    <td>12/10/2019</td>
                                                    <td>Revisión de mala señal</td>
                                                    <td>25/7/2018</td>
                                                    <td>Cambio de contraseña de WIFI</td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="notificaciones-traslados">
                                    <h4 class="alert bg-success"><strong>Historial de traslados</strong></h4>
                                    <div class="ordenes">
                                        <div class="col-md-12">
                                            <button class="btn btn-danger pull-right" type="button" name="button" data-toggle="modal" data-target="#traslados"><i class="fas fa-search"></i></button>
                                            <br><br>
                                        </div>
                                        <table class="table table-bordered table-hover">
                                                <tr class="info">
                                                    <th>N° de orden</th>
                                                    <th>Tipo de orden</th>
                                                    <th>Fecha de orden</th>
                                                    <th>Actividad realizada</th>
                                                    <th>Fecha de trabajo</th>
                                                    <th>Actividad internet</th>
                                                </tr>
                                                <tr>
                                                    <td>99947</td>
                                                    <td>Técnica</td>
                                                    <td>12/10/2019</td>
                                                    <td>Revisión de mala señal</td>
                                                    <td>25/7/2018</td>
                                                    <td>Cambio de contraseña de WIFI</td>
                                                </tr>
                                                <tr>
                                                    <td>99947</td>
                                                    <td>Técnica</td>
                                                    <td>12/10/2019</td>
                                                    <td>Revisión de mala señal</td>
                                                    <td>25/7/2018</td>
                                                    <td>Cambio de contraseña de WIFI</td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- Modal Ordenes de Trabajo -->
                <div id="ordenesTrabajo" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-sm">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Buscar orden de trabajo</h4>
                      </div>
                      <div class="modal-body">
                        <form class="" action="#" method="POST">
                            <input class="form-control" type="text" name="buscarOrdenTrabajo" placeholder="Número de orden">
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Buscar</button>
                        </form>
                      </div>
                    </div>

                  </div>
                </div>
                <!-- Modal Ordenes de Suspension -->
                <div id="ordenesSuspension" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-sm">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Buscar orden de suspensión</h4>
                      </div>
                      <div class="modal-body">
                        <form class="" action="#" method="POST">
                            <input class="form-control" type="text" name="buscarOrdenSuspension" placeholder="Número de orden">
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Buscar</button>
                        </form>
                      </div>
                    </div>

                  </div>
                </div>
                <!-- Modal Ordenes de Reconexion -->
                <div id="ordenesReconexion" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-sm">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Buscar orden de suspensión</h4>
                      </div>
                      <div class="modal-body">
                        <form class="" action="#" method="post">
                            <input class="form-control" type="text" name="buscarOrdenReconexion" placeholder="Número de orden">
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Buscar</button>
                        </form>
                      </div>
                    </div>

                  </div>
                </div>
                <!-- Modal Ordenes de Traslados -->
                <div id="traslados" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-sm">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Buscar traslado</h4>
                      </div>
                      <div class="modal-body">
                        <form class="" action="#" method="post">

                            <input class="form-control" type="text" name="buscarTraslado" placeholder="Número de orden">
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Buscar</button>
                        </form>
                      </div>
                    </div>

                  </div>
                </div>
        </div><!-- /.row -->
        <!-- /#page-wrapper -->

        <!-- Modal Facturación diaria -->
        <div id="buscarCliente" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
              <div style="background-color: #4CAF50; color:white;" class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Buscar cliente</h4>
              </div>
              <form id="generarFacturas" action="../php/generarFacturas.php" method="POST">
              <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <select class="form-control" name="tipoComprobante" required>
                            <option value="1">Factura consumidor final</option>
                            <option value="2">Crédito fiscal</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for=""></label>
                        <select id="mesGenerar" class="form-control" name="mesGenerar" required>
                            <option value="" selected>Mes a generar</option>
                            <option value="01">Enero</option>
                            <option value="02">Febrero</option>
                            <option value="03">Marzo</option>
                            <option value="04">Abril</option>
                            <option value="05">Mayo</option>
                            <option value="06">Junio</option>
                            <option value="07">Julio</option>
                            <option value="08">Agosto</option>
                            <option value="09">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for=""></label>
                        <input id="diaGenerar" class="form-control" type="text" name="diaGenerar" placeholder="Día a generar" required>
                    </div>
                    <div class="col-md-3">
                        <label for=""></label>
                        <input id="anoGenerar" class="form-control" type="text" name="anoGenerar" placeholder="Año a generar" value="<?php echo date('Y') ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for=""></label>
                        <input id="fechaComprobante" class="form-control" type="text" name="fechaComprobante" placeholder="Fecha para el comprobante" required>
                    </div>
                    <div class="col-md-6">
                        <label for=""></label>
                        <input id="fechaVencimiento" class="form-control" type="text" name="vencimiento" placeholder="Vencimiento" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="correlativo"></label>
                        <input class="form-control alert-danger" type="text" name="correlativo" value="25899" required>
                    </div>
                    <div class="col-md-4">
                        <label for=""></label>
                        <select class="form-control" name="tipoServicio" required>
                            <option value="" selected>Tipo de servicio</option>
                            <option value="cable">Cable</option>
                            <option value="internet">Internet</option>
                            <option value="ambos">Ambos</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <br>
                        <label style="color:#2E7D32;" for="cesc">CESC</label>
                        <input type="checkbox" name="cesc" value="1" checked>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                  <div class="row">
                      <div class="col-md-6">
                          <input type="submit" class="btn btn-success btn-lg btn-block" name="submit" value="Generar Facturas">
                      </div>
                      <div class="col-md-6">
                          <button type="button" class="btn btn-default btn-lg btn-block" data-dismiss="modal">Cancelar</button>
                      </div>
                  </div>
              </form>
              </div>
            </div>
          </div>
        </div><!-- Fin Modal Facturación diaria -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../../vendor/datatables/js/dataTables.bootstrap.js"></script>
    <script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../../dist/js/sb-admin-2.js"></script>

</body>

</html>
