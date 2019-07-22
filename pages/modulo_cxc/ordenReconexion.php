<?php

    session_start();

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
                        <div class="panel panel-success">
                          <div class="panel-heading">Orden de Reconexión</div>
                          <form id="ordenReconexion" action="#" method="POST">
                          <div class="panel-body">
                              <div class="col-md-12">
                                  <button class="btn btn-default btn-sm" id="nuevaOrdenId" onclick="nuevaOrden()" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Nueva orden"><i class="far fa-file"></i></button>
                                  <button class="btn btn-default btn-sm" id="editar" onclick="editarOrden()" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Editar orden"><i class="far fa-edit"></i></button>
                                  <button class="btn btn-default btn-sm" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Ver cliente"><i class="far fa-eye"></i></button>
                                  <button class="btn btn-default btn-sm" id="guardar" type="submit" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Guardar orden" disabled><i class="far fa-save"></i></button>
                                  <button class="btn btn-default btn-sm" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Buscar orden"><i class="fas fa-search"></i></button>
                                  <button class="btn btn-default btn-sm" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Imprimir orden"><i class="fas fa-print"></i></button>
                                  <div class="pull-right">
                                      <button class="btn btn-default btn-sm" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Nuevo cliente"><i class="far fa-user"></i></button>
                                      <button class="btn btn-default btn-sm" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Estado de cuenta"><i class="far fa-file-alt"></i></button>
                                      <button id="btn-cable" class="btn btn-default btn-sm" onclick="ordenCable()" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Orden de cable"><i class="fas fa-tv"></i></button>
                                      <button id="btn-internet" class="btn btn-default btn-sm" onclick="ordenInternet()" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Orden de internet"><i class="fas fa-wifi"></i></button>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-2">
                                      <br>
                                      <label for="numeroReconexion">N° de reconexión</label>
                                      <input class="form-control input-sm" type="text" name="numeroReconexion" readonly>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="fechaElaborada">Fecha de elaborada</label>
                                      <input class="form-control input-sm" type="text" name="fechaElaborada" readonly>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="codigoCliente">Código del cliente</label>
                                      <input class="form-control input-sm" type="text" name="codigoCliente" readonly>
                                  </div>
                                  <div class="col-md-6">
                                      <br>
                                      <label for="nombreCliente">Nombre del cliente</label>
                                      <input class="form-control input-sm" type="text" name="nombreCliente" readonly>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-10">
                                      <label for="direccionCliente">Dirección</label>
                                      <input class="form-control input-sm" type="text" name="direccionCliente" readonly>
                                  </div>
                                  <div class="col-md-2">
                                      <label for="telefono">Teléfono</label>
                                      <input class="form-control input-sm" type="text" name="telefono" readonly>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-12">
                                      <h4 class="alert alert-info cable"><strong>Cable</strong></h4>
                                      <div class="row">
                                          <div class="col-md-3">
                                              <label for="fechaOrdenoReconexionCable">Fecha que ordenó reconexión</label>
                                              <input class="form-control input-sm cable" type="text" name="fechaOrdenoReconexionCable" readonly>
                                          </div>
                                          <div class="col-md-3">
                                              <label for="ultimaSuspencionCable">Fecha última suspención</label>
                                              <input class="form-control input-sm cable" type="text" name="ultimaSuspencionCable" readonly>
                                          </div>
                                          <div class="col-md-6">
                                              <label for="tipoReconexionCable">Tipo de reconexión</label>
                                              <select class="form-control input-sm cable" name="tipoReconexionCable" disabled>
                                                  <option value="1">Reconexión con contrato</option>
                                                  <option value="2">Reconexión menor a cinco días</option>
                                              </select>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-12">
                                      <h4 class="alert alert-info"><strong>Internet</strong></h4>
                                      <div class="row">
                                          <div class="col-md-3">
                                              <label for="fechaOrdenoReconexionInternet">Fecha que ordenó reconexión</label>
                                              <input class="form-control input-sm internet" type="text" name="fechaOrdenoReconexionInternet" readonly>
                                          </div>
                                          <div class="col-md-3">
                                              <label for="ultimaSuspenxionInternet">Fecha última suspención</label>
                                              <input class="form-control input-sm internet" type="text" name="ultimaSuspenxionInternet" readonly>
                                          </div>
                                          <div class="col-md-6">
                                              <label for="tipoReconexionInternet">Tipo de reconexión</label>
                                              <select class="form-control input-sm internet" name="tipoReconexionInternet" disabled>
                                                  <option value="1">Reconexión con contrato</option>
                                                  <option value="2">Reconexión menor a cinco días</option>
                                              </select>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-4">
                                      <label for="macModem">MAC del modem</label>
                                      <input class="form-control input-sm internet" type="text" name="macModem" readonly>
                                  </div>
                                  <div class="col-md-4">
                                      <label for="serieModem">Serie del modem</label>
                                      <input class="form-control input-sm internet" type="text" name="serieModem" readonly>
                                  </div>
                                  <div class="col-md-4">
                                      <label for="velocidad">Velocidad</label>
                                      <input id="velocidad" class="form-control input-sm internet" type="text" name="velocidad" readonly>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-12">
                                      <label for="observaciones">Observaciones</label>
                                      <textarea class="form-control input-sm" name="observaciones" rows="2" cols="40" readonly></textarea>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-4">
                                      <label for="fechaRealizoReconexion">Fecha que se realizó reconexión</label>
                                      <input class="form-control input-sm" type="text" name="fechaRealizoReconexion" readonly>
                                  </div>
                                  <div class="col-md-8">
                                      <label for="nombreTecnico">Nombre del técnico</label>
                                      <select class="form-control input-sm" name="nombreTecnico" disabled>
                                          <option value=""></option>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-3">
                                      <label for="señalTap">Señal en tap</label>
                                      <input class="form-control input-sm" type="text" name="señalTap" readonly>
                                  </div>
                                  <div class="col-md-3">
                                      <label for="cableUtilizado">Cable Utilizado</label>
                                      <input class="form-control input-sm" type="text" name="cableUtilizado" readonly>
                                  </div>
                                  <div class="col-md-3">
                                      <label for="recepcionTv">Recepción TV</label>
                                      <input class="form-control input-sm cable" type="text" name="recepcionTv" readonly>
                                  </div>
                                  <div class="col-md-3">
                                      <label for="colilla">Colilla</label>
                                      <input class="form-control input-sm" type="text" name="colilla" readonly>
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
    <script src="../../dist/js/jquery-validation-1.19.0/dist/jquery.validate.js"></script>
    <script src="js/ordenReconexion.js"></script>


</body>

</html>
