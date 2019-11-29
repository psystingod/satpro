<?php

    session_start();
    require($_SERVER['DOCUMENT_ROOT'].'/satpro'.'/php/permissions.php');
    $permisos = new Permissions();
    $permisosUsuario = $permisos->getPermissions($_SESSION['id_usuario']);
    // get passed parameter value, in this case, the record ID
    // isset() is a PHP function used to verify if a value is there or not
    if (isset($_GET['id'])) {
        $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record no encontrado.');

        //include database connection
        require_once '../../php/connection.php';
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
            $estado_cable = $row['servicio_suspendido']; // 0 o 1 SIN Servicio
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
            $saldoCable = $row['saldoCable'];
            $saldoInter = $row['saldoInternet'];
            $saldoActual = $row['saldo_actual'];
            $diasCredito = $row['dias_credito'];
            $limiteCredito = $row['limite_credito'];
            $tipoComprobante = $row['tipo_comprobante']; //Credito fiscal o consumidor final
            $facebook = $row['facebook'];
            $correo = $row['correo_electronico'];
            $calidad = $row['entrega_calidad'];
            //var_dump($calidad);

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
    elseif (isset($_GET['action'])) {
        /****************** DATOS GENERALES ***********************/
        $estado_cable = ""; // 0 o 1
        $estado_internet = ""; // 1, 2, 3
        $codigo = "";
        $nContrato = "";
        $nFactura = "";
        $nombre = "";
        $empresa = "";
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
        $contacto1 = "";
        $contacto2 = "";
        $contacto3 = "";
        $telCon1 = "";
        $telCon2 = "";
        $telCon3 = "";
        $paren1 = "";
        $paren2 = "";
        $paren3 = "";
        $dir1 = "";
        $dir2 = "";
        $dir3 = "";

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
 //Array de ordenes de trabajo por cliente
 $arrOrdenesTrabajo = $data->getDataOrders('tbl_ordenes_trabajo', $codigo);
 $arrOrdenesSuspension = $data->getDataOrders('tbl_ordenes_suspension', $codigo);
 $arrOrdenesReconex = $data->getDataOrders('tbl_ordenes_reconexion', $codigo);
 $arrTraslados = $data->getDataOrders('tbl_ordenes_traslado', $codigo);

 if (isset($_GET['gen'])) {
     if ($_GET['gen'] == "no") {
         echo "<script>alert('No se encontraron datos para generar facturación')</script>";
     }elseif ($_GET['gen'] == "yes") {
         echo "<script>alert('Facturación generada con exito')</script>";
     }
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
                        <li><a href="ordenTraslado.php" target="_blank">Ordenes de traslado</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown procesos">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Documentación <i class="fas fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="php/f3.php<?php echo "?id=".$id; ?>" target="_blank">Documento F-3</a>
                        </li>
                        <li><a href="php/f4.php<?php echo "?id=".$id; ?>" target="_blank">Documento F-4</a>
                        </li>
                        <li><a href="php/f5.php<?php echo "?id=".$id; ?>" target="_blank">Documento F-5</a>
                        </li>
                        <li><a href="php/f9.php<?php echo "?id=".$id; ?>" target="_blank">Documento F-9</a>
                        </li>
                        <li><a href="php/allDoc.php<?php echo "?id=".$id; ?>" target="_blank">Documentación completa</a>
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
                            <td><button class="btn btn-warning btn-block" id="btn-editar" name="editar" onclick="editarCliente();" title="Editar"><i class="far fa-edit fa-2x"></i></button></td>
                            <td><button id="btn-nuevo" name="agregar" onclick="nuevoCliente();" class="btn btn-primary btn-block" title="Nuevo"><i class="fas fa-user-plus fa-2x"></i></button></td>
                        </tr>

                        <tr>
                            <td><button class="btn btn-primary btn-block" style="font-size: 16px;"><i class="far fa-file-alt"></i> Contrato de cable</button></td>
                            <td><button class="btn btn-primary btn-block" style="font-size: 16px;"><i class="far fa-file-alt"></i> Contrato de internet</button></td>
                            <td><a href="estadoCuenta.php?codigoCliente=<?php echo $codigo; ?>" target="_blank"><button class="btn btn-primary btn-block" style="font-size: 16px;"><i class="fas fa-dollar"></i> Estado de cuenta</button></a></td>
                <form id="formClientes" class="" action="#" method="POST">
                            <td><button id="btn-guardar" class="btn btn-danger btn-block" title="Guardar" disabled><i class="fas fa-save fa-2x"></i></button></td>
                        </tr>
                        <!--<tr>
                            <td><button class="btn btn-info btn-block" style="font-size: 16px; ;">Reporte</button></td>
                            <td colspan="2"><button class="btn btn-info btn-block" style="font-size: 16px; ;">Compromiso de internet</button></td>

                        </tr>-->
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
                                <td><label class='switch'><input id='activoCable' class='switch' type='radio' name ='cable' value='activo' checked disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input id='suspendidoCable' class='switch' type='radio' name ='cable' value='suspendido' disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input id='sinCable' class='switch' type='radio' name ='cable' value='sin' disabled><span class='slider round'></span></label></td>
                            </tr>";
                        }
                        else if ($estado_cable == "T") {
                            echo "<tr class='info'>
                                <th>TV</th>
                                <td><label class='switch'><input id='activoCable' class='switch' type='radio' name ='cable' value='activo' disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input id='suspendidoCable' class='switch' type='radio' name ='cable' value='suspendido' checked disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input id='sinCable' class='switch' type='radio' name ='cable' value='sin' disabled><span class='slider round'></span></label></td>
                            </tr>";
                        }
                        else {
                            echo "<tr class='info'>
                                <th>TV</th>
                                <td><label class='switch'><input id='activoCable' class='switch' type='radio' name ='cable' value='activo' disabled required><span class='slider round'></span></label></td>
                                <td><label class='switch'><input id='suspendidoCable' class='switch' type='radio' name ='cable' value='suspendido' disabled required><span class='slider round'></span></label></td>
                                <td><label class='switch'><input id='sinCable' class='switch' type='radio' name ='cable' value='sin' checked disabled required><span class='slider round'></span></label></td>
                            </tr>";
                        }
                        if ($estado_internet == 1) {
                            echo "<tr class='success'>
                                <th>Internet</th>
                                <td><label class='switch'><input id='activoInter' class='switch' type='radio' name='internet' value='activo' checked disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input id='suspendidoInter' class='switch' type='radio' name='internet' value='suspendido' disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input id='sinInter' class='switch' type='radio' name='internet' value='sin' disabled><span class='slider round'></span></label></td>
                            </tr>";
                        }
                        elseif ($estado_internet == 2) {
                            echo "<tr class='success'>
                                <th>Internet</th>
                                <td><label class='switch'><input id='activoInter' class='switch' type='radio' name='internet' value='activo' disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input id='suspendidoInter' class='switch' type='radio' name='internet' value='suspendido' checked disabled><span class='slider round'></span></label></td>
                                <td><label class='switch'><input id='sinInter' class='switch' type='radio' name='internet' value='sin' disabled><span class='slider round'></span></label></td>
                            </tr>";
                        }
                        else {
                            echo "<tr class='success'>
                                <th>Internet</th>
                                <td><label class='switch'><input id='activoInter' class='switch' type='radio' name='internet' value='activo' disabled required><span class='slider round'></span></label></td>
                                <td><label class='switch'><input id='suspendidoInter' class='switch' type='radio' name='internet' value='suspendido' disabled required><span class='slider round'></span></label></td>
                                <td><label class='switch'><input id='sinInter' class='switch' type='radio' name='internet' value='sin' checked disabled required><span class='slider round'></span></label></td>
                            </tr>";
                        }
                        echo "<tr class='warning'>
                            <th>Teléfono</th>
                            <td><label class='switch'><input class='switch' type='radio' name='telefono' value='activo' disabled><span class='slider round'></span></label></td>
                            <td><label class='switch'><input class='switch' type='radio' name='telefono' value='suspendido' disabled><span class='slider round'></span></label></td>
                            <td><label class='switch'><input class='switch' type='radio' name='telefono' value='sin' checked disabled><span class='slider round'></span></label></td>
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
                                            <input class="form-control input-sm" type="text" name="codigo" value="<?php echo $codigo; ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="contrato">Número de contrato</label>
                                            <input class="form-control input-sm" type="text" name="contrato" value="<?php echo $nContrato; ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="factura">Número de factura</label>
                                            <input class="form-control input-sm" type="text" name="factura" value="<?php echo $nFactura; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9">
                                            <label for="nombre">Nombre</label>
                                            <input class="form-control input-sm" type="text" name="nombre" value="<?php echo $nombre; ?>" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="ncr">Número de registro</label>
                                            <input class="form-control input-sm" type="text" name="nrc" value="<?php echo $nRegistro; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!--<div class="col-md-9">
                                            <label for="empresa">Empresa</label>
                                            <input class="form-control input-sm" type="text" name="empresa" value="<?php //echo $empresa; ?>" readonly>
                                        </div>-->

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="saldoCable">Saldo actual cable</label>
                                            <input class="form-control input-sm" type="text" name="saldoCable" value="<?php echo round($saldoCable,2); ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="saldoInternet">Saldo actual internet</label>
                                            <input class="form-control input-sm" type="text" name="saldoInternet" value="<?php echo round($saldoInter,2); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="dui">DUI</label>
                                            <input class="form-control input-sm" type="text" name="dui" pattern="[0-9]{8}-[0-9]{1}" value="<?php echo $dui; ?>" readonly required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="expedicion">Lugar y fecha de expedición</label>
                                            <input class="form-control input-sm" type="text" name="expedicion" value="<?php echo $lugarExp; ?>" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="nit">NIT</label>
                                            <input class="form-control input-sm" type="text" name="nit" value="<?php echo $nit; ?>" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="fechaNacimiento">Fecha de nacimiento</label>
                                            <input class="form-control input-sm" type="text" name="fechaNacimiento" value="<?php echo $fechaNacimiento; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="direccion">Dirección</label>
                                            <textarea class="form-control input-sm" name="direccion" rows="2" cols="40" readonly><?php echo $direccion; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="departamento">Departamento</label>
                                            <select class="form-control input-sm" name="departamento" disabled>
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
                                            <select class="form-control input-sm" name="municipio" disabled>
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
                                            <select class="form-control input-sm" name="colonia" disabled>
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
                                            <textarea class="form-control input-sm" name="direccionCobro" rows="2" cols="40" readonly><?php echo $direccionCobro; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="telefono">Teléfono</label>
                                            <input class="form-control input-sm" type="text" name="telefono" value="<?php echo $telefonos; ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="telefonoTrabajo">Teléfono de trabajo</label>
                                            <input class="form-control input-sm" type="text" name="telefonoTrabajo" value="<?php echo $telTrabajo; ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="ocupacion">Ocupación</label>
                                            <input class="form-control input-sm" type="text" name="ocupacion" value="<?php echo $ocupacion; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="cuentaContable">Cuenta contable</label>
                                            <input class="form-control input-sm" type="text" name="cuentaContable" value="<?php echo $cuentaContable; ?>" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="formaFacturar">Forma al facturar</label>
                                            <select class="form-control input-sm" name="formaFacturar" disabled>
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
                                        <div class="col-md-4">
                                            <label for="tipoComprobante">Tipo de comprobante</label>
                                            <select class="form-control input-sm" name="tipoComprobante" disabled>
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
                                            <label for="saldoActual">Saldo actual</label>
                                            <input class="form-control input-sm" type="text" name="saldoActual" value="<?php echo $saldoActual; ?>" readonly>
                                        </div>
                                        <!--<div class="col-md-2">
                                            <label for="limiteCredito">Días de crédito</label>
                                            <input class="form-control input-sm" type="text" name="diasCredito" value="<?php echo $diasCredito; ?>" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="limiteCredito">Límite de crédito</label>
                                            <input class="form-control input-sm" type="text" name="limiteCredito" value="<?php echo $limiteCredito; ?>" readonly>
                                        </div> -->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="facebook">Cuenta de Facebook</label>
                                            <input class="form-control input-sm" type="text" name="facebook" value="<?php echo $facebook; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="correo">Correo electrónico</label>
                                            <input class="form-control input-sm" type="text" name="correo" value="<?php echo $correo; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="otros-datos">
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="cobrador">Cobrador que lo atiende</label>
                                            <select class="form-control input-sm" name="cobrador" disabled>
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
                                            <input class="form-control input-sm" type="text" name="rf1_nombre" value="<?php echo $contacto1; ?>" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="rp1_telefono">Teléfono</label>
                                            <input class="form-control input-sm" type="text" name="rp1_telefono" value="<?php echo $telCon1; ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="rp1_direccion">Dirección</label>
                                            <input class="form-control input-sm" type="text" name="rp1_direccion" value="<?php echo $dir1; ?>" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="rp1_parentezco">Parentezco</label>
                                            <input class="form-control input-sm" type="text" name="rp1_parentezco" value="<?php echo $paren1; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="rp2_nombre">Referencia personal #2</label>
                                            <input class="form-control input-sm" type="text" name="rf2_nombre" value="<?php echo $contacto2; ?>" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="rp2_telefono">Teléfono</label>
                                            <input class="form-control input-sm" type="text" name="rp2_telefono" value="<?php echo $telCon2; ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="rp2_direccion">Dirección</label>
                                            <input class="form-control input-sm" type="text" name="rp2_direccion" value="<?php echo $dir2; ?>" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="rp2_parentezco">Parentezco</label>
                                            <input class="form-control input-sm" type="text" name="rp2_parentezco" value="<?php echo $paren2; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="rp3_nombre">Referencia personal #3</label>
                                            <input class="form-control input-sm" type="text" name="rf3_nombre" value="<?php echo $contacto3; ?>" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="rp3_telefono">Teléfono</label>
                                            <input class="form-control input-sm" type="text" name="rp3_telefono" value="<?php echo $telCon3; ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="rp3_direccion">Dirección</label>
                                            <input class="form-control input-sm" type="text" name="rp3_direccion" value="<?php echo $dir3; ?>" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="rp3_parentezco">Parentezco</label>
                                            <input class="form-control input-sm" type="text" name="rp3_parentezco" value="<?php echo $paren3; ?>" readonly>
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
                                                  <div class="col-md-3">
                                                      <label for="fechaInstalacionCable">Fecha de instalación</label>
                                                      <input class="form-control input-sm" type="text" name="fechaInstalacionCable" value="<?php echo $fechaInstalacion; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="fechaPrimerFacturaCable">Fecha primer factura</label>
                                                      <input class="form-control input-sm" type="text" name="fechaPrimerFacturaCable" value="<?php echo $fechaPrimerFactura; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="exento">Exento</label>
                                                      <?php
                                                      if ($exento == "F" || $exento == NULL) {
                                                          echo "<input id='exento' onchange='getExento(this)' class='form-control input-sm' type='checkbox' name='exento' value='F'>";
                                                      }else {
                                                          echo "<input id='exento' onchange='getExento(this)' class='form-control input-sm' type='checkbox' name='exento' value='T' checked>";
                                                      }
                                                      ?>

                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="diaGenerarFacturaCable">Día cobro</label>
                                                      <input class="form-control input-sm" type="text" name="diaGenerarFacturaCable" value="<?php echo $diaCobro; ?>" readonly required>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="cortesia">Cortesía</label>
                                                      <?php
                                                      if ($cortesia == "F") {
                                                          echo "<input id='cortesia' onchange='getCortesia(this)' class='form-control input-sm' type='checkbox' name='cortesia' value='F'>";
                                                      }else if($cortesia == "T"){
                                                          echo "<input id='cortesia' onchange='getCortesia(this)' class='form-control input-sm' type='checkbox' name='cortesia' value='T' checked>";
                                                      }
                                                      else {
                                                          echo "<input id='cortesia' onchange='getCortesia(this)' class='form-control input-sm' type='checkbox' name='cortesia' value='F'>";
                                                      }
                                                      ?>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-2">
                                                      <label for="cuotaMensualCable">Cuota mensual</label>
                                                      <input class="form-control input-sm" type="text" name="cuotaMensualCable" value="<?php echo $cuotaMensualCable; ?>" readonly required>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="prepago">Prepago</label>
                                                      <input class="form-control input-sm" type="text" name="prepago" value="<?php echo $prepago; ?>" readonly required>
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="tipoServicio">Tipo de servicio</label>
                                                      <select class="form-control input-sm" name="tipoServicioCable" disabled>
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
                                                      <label for="mactv">MAC TV</label>
                                                      <input class="form-control input-sm" type="text" name="mactv" value="<?php echo $mactv; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="mesesContratoCable">Meses de contrato</label>
                                                      <input class="form-control input-sm" type="text" name="mesesContratoCable" value="<?php echo $periodoContratoCable; ?>" readonly>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-3">
                                                      <label for="inicioContratoCable">Inicio de contrato</label>
                                                      <input class="form-control input-sm" type="text" name="inicioContratoCable" value="<?php echo $fechaInstalacion; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="vencimientoContratoCable">Vence contrato</label>
                                                      <input class="form-control input-sm" type="text" name="vencimientoContratoCable" value="<?php echo $vencimientoCable; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="fechaSuspensionCable">Fecha suspension</label>
                                                      <input class="form-control input-sm" type="text" name="fechaSuspensionCable" value="<?php echo $fechaSuspensionCable; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="fechaReconexionCable">Fecha de reconexión</label>
                                                      <input class="form-control input-sm" type="text" name="fechaReconexionCable" value="<?php echo $fechaReinstalacionCable; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="derivaciones">N° de derivaciones</label>
                                                      <input class="form-control input-sm" type="text" name="derivaciones" value="<?php echo $nDerivaciones; ?>" readonly>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-12">
                                                      <label for="encargadoInstalacionCable">Técnico que realizó la instalación</label>
                                                      <select class="form-control input-sm" name="encargadoInstalacionCable" disabled>
                                                          <option value="" selected>Seleccionar</option>
                                                          <?php
                                                          foreach ($arrTecnicos as $key) {
                                                              if ($key['idTecnico'] == $tecnicoCable) {
                                                                  echo "<option value=".$key['idTecnico']." selected>".$key['nombreTecnico']."</option>";
                                                              }
                                                              else {
                                                                  echo "<option value=".$key['idTecnico'].">".$key['nombreTecnico']."</option>";
                                                              }

                                                          }
                                                           ?>
                                                      </select>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-12">
                                                      <label for="direccionCable">Dirección</label>
                                                      <input class="form-control input-sm" type="text" name="direccionCable" value="<?php echo $direccionCable; ?>" readonly>
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
                                                      <input class="form-control input-sm" type="text" name="fechaInstalacionInternet" value="<?php echo $fechaInstalacionInter; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="fechaPrimerFacturaInternet">Fecha primer factura</label>
                                                      <input class="form-control input-sm" type="text" name="fechaPrimerFacturaInternet" value="<?php echo $fechaPrimerFacturaInter; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="tipoServicioInternet">Tipo de servicio</label>
                                                      <select class="form-control input-sm" name="tipoServicioInternet" disabled>
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
                                                      <label for="mesesContratoInternet">Meses de contrato</label>
                                                      <input class="form-control input-sm" type="text" name="mesesContratoInternet" value="<?php echo $periodoContratoInternet; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="diaGenerarFacturaInternet">Día para generar factura</label>
                                                      <input class="form-control input-sm" type="text" name="diaGenerarFacturaInternet" value="<?php echo $diaCobroInter; ?>" readonly>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-3">
                                                      <label for="velocidadInternet">Velocidad</label>
                                                      <select class="form-control input-sm" name="velocidadInternet" disabled>
                                                          <option value="" selected>Seleccionar</option>
                                                          <?php
                                                          foreach ($arrVelocidad as $key) {
                                                              if ($key['idVelocidad'] == $velocidadInter) {
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
                                                      <input class="form-control input-sm" type="text" name="cuotaMensualInternet" value="<?php echo $cuotaMensualInter; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="tipoCliente">Tipo de cliente</label>
                                                      <select class="form-control input-sm" name="tipoCliente" disabled>
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
                                                      <select class="form-control input-sm" name="tecnologia" disabled>
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
                                                  <div class="col-md-12">
                                                      <label for="enCalidad">En calidad de</label>
                                                      <input class="form-control input-sm" type="text" name="enCalidad" value="<?php echo $calidad; ?>" readonly>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-3">
                                                      <label for="nContratoVigente">N° de contrato vigente</label>
                                                      <input class="form-control input-sm" type="text" name="nContratoVigente" value="<?php echo $nContratoInter; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="vencimientoContratoInternet">Vencimiento de contrato</label>
                                                      <input class="form-control input-sm" type="text" name="vencimientoContratoInternet" value="<?php echo $vencimientoInternet; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="ultimaRenovacionInternet">Última renovación</label>
                                                      <input class="form-control input-sm" type="text" name="ultimaRenovacionInternet" value="<?php echo $ultimaRenovacionInternet; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="fechaSuspencionInternet">Fecha de suspención</label>
                                                      <input class="form-control input-sm" type="text" name="fechaSuspencionInternet" value="<?php echo $fechaSuspencionInternet; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="fechaReconexionInternet">Fecha de reconexión</label>
                                                      <input class="form-control input-sm" type="text" name="fechaReconexionInternet" value="<?php echo $fechaReconexionInternet; ?>" readonly>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-5">
                                                      <label for="promocion">Promoción</label>
                                                      <input class="form-control input-sm" type="text" name="promocion" value="<?php echo $promocion; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="promocionDesde">Desde</label>
                                                      <input class="form-control input-sm" type="text" name="promocionDesde" value="<?php echo $promocionDesde; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label for="promocionHasta">Hasta</label>
                                                      <input class="form-control input-sm" type="text" name="promocionHasta" value="<?php echo $promocionHasta; ?>" readonly>
                                                  </div>
                                                  <div class="col-md-3">
                                                      <label for="cuotaPromocion">Cuota de la promoción</label>
                                                      <input class="form-control input-sm" type="text" name="cuotaPromocion" value="<?php echo $cuotaPromocion; ?>" readonly>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-12">
                                                      <label for="encargadoInstalacionInter">Técnico que realizó la instalación</label>
                                                      <select class="form-control input-sm" name="encargadoInstalacionInter" disabled>
                                                          <option value="" selected>Seleccionar</option>
                                                          <?php
                                                          foreach ($arrTecnicos as $key) {
                                                              if ($key['idTecnico'] == $tecnicoInternet) {
                                                                  echo "<option value=".$key['idTecnico']." selected>".$key['nombreTecnico']."</option>";
                                                              }
                                                              else {
                                                                  echo "<option value=".$key['idTecnico'].">".$key['nombreTecnico']."</option>";
                                                              }

                                                          }
                                                           ?>
                                                      </select>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-12">
                                                      <label for="direccionInternet">Dirección</label>
                                                      <input class="form-control input-sm" type="text" name="direccionInternet" value="<?php echo $direccionInternet; ?>" readonly>
                                                  </div>
                                              </div>
                                              <hr style="border-top: 1px solid #0288D1;">
                                              <div class="row">
                                                  <div class="col-md-3">
                                                      <div class="row">
                                                          <div class="col-md-12">
                                                              <label for="colilla">Colilla</label>
                                                              <input class="form-control input-sm" type="text" name="colilla" value="<?php echo $colilla; ?>" readonly>
                                                          </div>
                                                          <div class="col-md-12">
                                                              <label for="wanip">WAN IP</label>
                                                              <input class="form-control input-sm" type="text" name="wanip" value="<?php echo $wanip; ?>" readonly>
                                                          </div>
                                                          <div class="col-md-12">
                                                              <label for="coordenadas">Coordenadas</label>
                                                              <input class="form-control input-sm" type="text" name="coordenadas" value="<?php if(isset($coordenadas)) echo $coordenadas; ?>" readonly>
                                                          </div>
                                                          <div class="col-md-12">
                                                              <label for="nodo">Nodo/Ap/Path</label>
                                                              <input class="form-control input-sm" type="text" name="nodo" value="<?php echo $nodo ?>" readonly>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-9">
                                                      <div class="row">
                                                          <div class="col-md-8">
                                                              <label for="modelo">Modelo</label>
                                                              <input class="form-control input-sm" type="text" name="modelo" value="<?php echo $modelo; ?>" readonly>
                                                          </div>
                                                          <div class="col-md-4">
                                                              <label for="recepcion">Recepción</label>
                                                              <input class="form-control input-sm" type="text" name="recepcion" value="<?php echo $recepcion; ?>" readonly>
                                                          </div>
                                                          <div class="col-md-8">
                                                              <label for="mac">MAC</label>
                                                              <input class="form-control input-sm" type="text" name="mac" value="<?php echo $mac; ?>" readonly>
                                                          </div>
                                                          <div class="col-md-4">
                                                              <label for="transmicion">Transmisión</label>
                                                              <input class="form-control input-sm" type="text" name="transmision" value="<?php echo $transmision; ?>" readonly>
                                                          </div>
                                                          <div class="col-md-8">
                                                              <label for="serie">Serie</label>
                                                              <input class="form-control input-sm" type="text" name="serie" value="<?php echo $serie; ?>" readonly>
                                                          </div>
                                                          <div class="col-md-4">
                                                              <label for="ruido">Ruido</label>
                                                              <input class="form-control input-sm" type="text" name="ruido" value="<?php echo $ruido; ?>" readonly>
                                                          </div>
                                                          <div class="col-md-12">
                                                              <label for="claveWifi">Clave WIFI</label>
                                                              <input class="form-control input-sm" type="text" name="claveWifi" value="<?php echo $wifiClave; ?>" readonly>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-6">
                                                      <br>
                                                      <button class="btn btn-success btn-block" type="button" name="agregar" style="font-size:16px">Activar servicio</button>
                                                  </div>
                                                  <div class="col-md-6">
                                                      <br>
                                                      <button class="btn btn-danger btn-block" type="button" name="eliminar" style="font-size:16px">Desactivar servicio</button>
                                                  </div>
                                              </div>
                                              <hr style="border-top: 1px solid #0288D1;">
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
                </form>             <!-- Accordion wrapper -->
                                </div>
                                <div class="tab-pane fade" id="ordenes-tecnicas">
                                    <h4 class="alert bg-info"><strong>Historial de ordenes de trabajo</strong></h4>
                                    <div class="ordenes">
                                        <!-- <div class="col-md-12">
                                            <button class="btn btn-danger pull-right" type="button" name="button" data-toggle="modal" data-target="#ordenesTrabajo"><i class="fas fa-search"></i></button>
                                            <br><br>
                                        </div>-->
                                        <table class="table table-bordered table-hover">
                                                <thead class="info">
                                                    <tr class="bg-warning">
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
                                                            echo "<a href='ordenTrabajo.php?nOrden={$key["idOrdenTrabajo"]}' target='_blank' class='btn btn-primary btn-xs'>".$key["idOrdenTrabajo"] . "</td><td>";
                                                            echo $key["tipoOrdenTrabajo"] . "</td><td>";
                                                            echo date_format(date_create($key["fechaOrdenTrabajo"]), 'd/m/Y') . "</td><td>";
                                                            echo $key["fechaTrabajo"] . "</td><td>";
                                                            echo $key["actividadCable"] . "</td><td>";
                                                            echo $key["actividadInter"] . "</td><tr>";
                                                        }
                                                    ?>
                                                </tbody>
                                        </table>
                                    </div>
                                    <h4 class="alert bg-info"><strong>Historial de ordenes de suspensión</strong></h4>
                                    <div class="ordenes">
                                        <!--<div class="col-md-12">
                                            <button class="btn btn-danger pull-right" type="button" name="button" data-toggle="modal" data-target="#ordenesSuspension"><i class="fas fa-search"></i></button>
                                            <br><br>
                                        </div>-->
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
                                                        foreach ($arrOrdenesSuspension as $key) {
                                                            echo "<tr><td>";
                                                            echo "<a href='ordenSuspension.php?nOrden={$key["idOrdenSuspension"]}' target='_blank' class='btn btn-primary btn-xs'>".$key["idOrdenSuspension"] . "</td><td>";
                                                            echo $key["tipoOrden"] . "</td><td>";
                                                            echo $key["fechaOrden"] . "</td><td>";
                                                            echo $key["fechaSuspension"] . "</td><td>";
                                                            echo $data2->getAcById($key["actividadCable"]) . "</td><td>";
                                                            echo $data2->getAiById($key["actividadInter"]) . "</td><tr>";
                                                        }
                                                    ?>
                                                </tbody>
                                        </table>
                                    </div>
                                    <h4 class="alert bg-info"><strong>Historial de ordenes de reconexión</strong></h4>
                                    <div class="ordenes">
                                        <!--<div class="col-md-12">
                                            <button class="btn btn-danger pull-right" type="button" name="button" data-toggle="modal" data-target="#ordenesReconexion"><i class="fas fa-search"></i></button>
                                            <br><br>
                                        </div>-->
                                        <table class="table table-bordered table-hover">
                                                <thead class="info">
                                                    <tr class="bg-success">
                                                        <th>N° de orden</th>
                                                        <th>Tipo de orden</th>
                                                        <th>Fecha de orden</th>
                                                        <th>Fecha realizada Cable</th>
                                                        <th>Fecha realizada Internet</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        foreach ($arrOrdenesReconex as $key) {
                                                            echo "<tr><td>";
                                                            echo "<a href='ordenReconexion.php?nOrden={$key["idOrdenReconex"]}' target='_blank' class='btn btn-primary btn-xs'>".$key["idOrdenReconex"] . "</td><td>";
                                                            echo $key["tipoOrden"] . "</td><td>";
                                                            echo $key["fechaOrden"] . "</td><td>";
                                                            echo $key["fechaSuspension"] . "</td><td>";
                                                            echo $key["fechaReconexCable"] . "</td><td>";
                                                            echo $key["fechaReconexInter"] . "</td><tr>";
                                                        }
                                                    ?>
                                                </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="notificaciones-traslados">
                                    <h4 class="alert bg-info"><strong>Historial de traslados</strong></h4>
                                    <div class="ordenes">
                                        <table class="table table-bordered table-hover">
                                            <thead class="info">
                                                <tr class="bg-success">
                                                    <th>N° de orden</th>
                                                    <th>Tipo de orden</th>
                                                    <th>Fecha de orden</th>
                                                    <th>Fecha traslado</th>
                                                    <th>Tipo servicio</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach ($arrTraslados as $key) {
                                                        echo "<tr><td>";
                                                        echo "<a href='ordenTraslado.php?nOrden={$key["idOrdenTraslado"]}' target='_blank' class='btn btn-primary btn-xs'>".$key["idOrdenTraslado"] . "</td><td>";
                                                        echo $key["tipoOrden"] . "</td><td>";
                                                        echo $key["fechaOrden"] . "</td><td>";
                                                        echo $key["fechaTraslado"] . "</td><td>";
                                                        echo $key["tipoServicio"] . "</td><td>";
                                                    }
                                                ?>
                                            </tbody>
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
    <script src="js/clientes.js"></script>
    <script src="../../dist/js/sb-admin-2.js"></script>
    <script type="text/javascript">
        var permisos = '<?php echo $permisosUsuario;?>'
    </script>
    <script src="../modulo_administrar/js/permisos.js"></script>

    <?php
    if (isset($_GET['action'])) {
        echo '<script>
        document.getElementById("btn-editar").disabled = true;
        document.getElementById("btn-guardar").disabled = false;
        document.getElementById("btn-guardar").disabled = false;
        var clearInputs = document.getElementsByClassName("input-sm");
        for (var i = 0; i < clearInputs.length; i++) {
            clearInputs[i].value = "";
            if (clearInputs[i].readOnly == true) {
                clearInputs[i].readOnly = false;
            }
            else if (clearInputs[i].disabled == true) {
                clearInputs[i].disabled = false;
            }
        }
        var clearSw = document.getElementsByClassName("switch");
        for (var i = 0; i < clearSw.length; i++) {
            clearSw[i].value = "";
            if (clearSw[i].readOnly == true) {
                clearSw[i].readOnly = false;
                clearSw[i].checked = false;
            }
            else if (clearSw[i].disabled == true) {
                clearSw[i].disabled = false;
                clearSw[i].checked = false;
            }
        }
        //CABLE
        document.getElementById("activoCable").value = "F";
        document.getElementById("suspendidoCable").value = "T";
        document.getElementById("sinCable").value = "";

        //INTERNET
        document.getElementById("activoInter").value = "1";
        document.getElementById("suspendidoInter").value = "2";
        document.getElementById("sinInter").value = "3";

        //CORTESIA Y EXENTOS
        if (document.getElementById("exento").checked == true) {
            document.getElementById("exento").value = "T";
        }else if (document.getElementById("exento").checked == false) {
            document.getElementById("exento").value = "F";
        }

        function getExento(){
            if (document.getElementById("exento").checked == true) {
                document.getElementById("exento").value = "T";
            }else if (document.getElementById("exento").checked == false) {
                document.getElementById("exento").value = "F";
            }
        }

        if (document.getElementById("cortesia").checked == true) {
            document.getElementById("cortesia").value = "T";
        }else if (document.getElementById("cortesia").checked == false) {
            document.getElementById("cortesia").value = "F";
        }

        function getCortesia(){
            if (document.getElementById("cortesia").checked == true) {
                document.getElementById("cortesia").value = "T";
            }else if (document.getElementById("cortesia").checked == false) {
                document.getElementById("cortesia").value = "F";
            }
        }

        document.getElementById("formClientes").action = "php/nuevoCliente.php";
        </script>';

    }
    ?>

</body>

</html>
