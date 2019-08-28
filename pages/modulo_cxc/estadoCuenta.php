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
    }

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
    <link rel="shortcut icon" href="../images/cablesat.png" />
    <!-- Bootstrap Core CSS -->
    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Font awesome CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="../dist/css/custom-principal.css">

    <!-- Morris Charts CSS -->
    <link href="../../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">


</head>

<body>

    <?php
         // session_start();
         if(!isset($_SESSION["user"])) {
             header('Location: login.php');
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
                <li class="dropdown" style="padding:5px;">
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
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="page-header text-center" style="border: none;"><b>Estado de cuenta</b></h2>
                        <table class="table table-responsive table-condensed" style="border: none;">
                            <tbody class="">
                                <tr>
                                    <th>Nombre</th>
                                    <td>DIEGO ARMANDO HERRERA FLORES</td>
                                    <th>Fecha</th>
                                    <td><?php date_default_timezone_set('America/El_Salvador'); echo date('d/m/Y H:i:s') ?></td>
                                </tr>
                                <tr>
                                    <th>Domicilio</th>
                                    <td> 7 AVENIDA NORTE FRENTE A ISSS</td>
                                    <th>Día de cobro</th>
                                    <td> 25</td>
                                </tr>
                                <tr>
                                    <th>Teléfonos</th>
                                    <td>7022-1905</td>
                                    <th>última fecha de pago</th>
                                    <td>07/05/2019</td>
                                </tr>
                            </tbody>
                        </table>
                        <ul class="nav nav-tabs nav-justified mt-5" id="pills-tab" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#cable" role="tab" aria-controls="pills-home" aria-selected="true"><b>CABLE</b></a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#internet" role="tab" aria-controls="pills-profile" aria-selected="true"><b>INTERNET</b></a>
                          </li>
                        </ul>

                        <div class="tab-content mt-3 mb-3" id="pills-tabContent" style="font-size: 14px;">
                          <div class="tab-pane fade active" id="cable" role="tabpanel" aria-labelledby="pills-home-tab">
                              <br>
                              <div class="panel panel-success">
                                  <div class="panel-heading">Resumen estado de cuenta cable, <b>saldo actual: $30</b></div>
                                  <div class="panel-body">
                                      <table class="table table-striped">
                                          <thead>
                                              <th>N° recibo</th>
                                              <th>Tipo servicio</th>
                                              <th>N° comprobante</th>
                                              <th>Mes de servicio</th>
                                              <th>Aplicación</th>
                                              <th>Vencimiento</th>
                                              <th>Cargo</th>
                                              <th>Abono</th>
                                              <th>CESC cargo</th>
                                              <th>CESC abono</th>
                                          </thead>
                                      </table>
                                  </div>
                              </div>
                          </div>
                          <div class="tab-pane fade" id="internet" role="tabpanel" aria-labelledby="pills-profile-tab">
                              <br>
                              <div class="panel panel-info">
                                  <div class="panel-heading">Resumen estado de cuenta internet, <b>saldo actual: $23.98</b></div>
                                  <div class="panel-body">
                                      <table class="table table-striped">
                                          <thead>
                                              <th>N° recibo</th>
                                              <th>Tipo servicio</th>
                                              <th>N° comprobante</th>
                                              <th>Mes de servicio</th>
                                              <th>Aplicación</th>
                                              <th>Vencimiento</th>
                                              <th>Cargo</th>
                                              <th>Abono</th>
                                              <th>CESC cargo</th>
                                              <th>CESC abono</th>
                                          </thead>
                                      </table>
                                  </div>
                              </div>
                          </div>
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

</body>

</html>