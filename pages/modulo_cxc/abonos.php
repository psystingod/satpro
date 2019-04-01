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
                        <div class="panel panel-primary">
                          <div class="panel-heading">Abonos</div>
                          <form id="ordenTrabajo" action="#" method="POST">
                          <div class="panel-body">
                              <div class="col-md-12">
                                  <div class="pull-right">
                                      <button class="btn btn-default btn-sm" type="button" name="btn_nuevo" data-toggle="tooltip" data-placement="bottom" title="Estado de cuenta">Estado de cuenta</button>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-2">
                                      <br>
                                      <label for="nRecibo">N° de recibo</label>
                                      <input class="form-control input-sm" type="text" name="nRecibo" readonly>
                                  </div>
                                  <div class="col-md-4">
                                      <br>
                                      <label for="numeroOrden">Zona</label>
                                      <select class="form-control input-sm" name="zona" readonly>
                                          <?php
                                          echo "<option value=''>Zona de cobro #1</option>";
                                          ?>
                                      </select>
                                  </div>
                                  <div class="col-md-4">
                                      <br>
                                      <label for="numeroOrden">Cobrador</label>
                                      <select class="form-control input-sm" name="cobrador" readonly>
                                          <?php
                                          echo "<option value=''>Colecturia</option>";
                                          ?>
                                      </select>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="n">*</label>
                                      <input class="form-control input-sm" type="text" name="n" value="" readonly>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-2">
                                      <br>
                                      <label for="claseOrden">Fecha del abono</label>
                                      <input class="form-control input-sm input-sm" name="fechaAbono" type="text" value= "<?php echo date('d/m/Y'); ?>" readonly>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="telefonos">Código</label>
                                      <input class="form-control input-sm input-sm" type="text" name="telefonos" readonly>
                                  </div>
                                  <div class="col-md-4">
                                      <br>
                                      <label for="nombreCliente">Nombre del cliente</label>
                                      <input class="form-control input-sm input-sm" type="text" name="nombreCliente" value="Diego Armando Herrera Flores" readonly>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="nrc">NRC</label>
                                      <input class="form-control input-sm input-sm" type="text" name="nrc" readonly>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="servicio">Servicio</label>
                                      <select class="form-control input-sm" name="servicio" readonly>
                                          <?php
                                          echo "<option value=''>Cable</option>"
                                          ?>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-2">
                                      <br>
                                      <label for="macModem">Valor de la cuota</label>
                                      <input class="form-control input-sm" type="text" name="valorCuota" readonly>
                                  </div>
                                  <div class="col-md-10">
                                      <br>
                                      <label for="serieModem">Dirección</label>
                                      <input class="form-control input-sm" type="text" name="serieModem" readonly>
                                  </div>

                              </div>
                              <div class="form-row">
                                  <div class="col-md-2">
                                      <br>
                                      <label for="formaPago">Forma de pago</label>
                                      <select class="form-control input-sm" type="text" name="formaPago" readonly>
                                          <option value="">Efectivo</option>
                                      </select>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="hora">Total a pagar</label>
                                      <input class="form-control input-sm" type="text" name="hora" readonly>
                                  </div>
                                  <div class="col-md-1">
                                      <br>
                                      <label for="porImp">% imp</label>
                                      <input class="form-control input-sm" type="text" name="porImp" readonly>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="impSeg">Impusto seguridad</label>
                                      <input type="text" class="form-control input-sm" name="impSeg" readonly>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="totalAbono">Total a abonar</label>
                                      <input class="form-control input-sm" type="text" name="totalAbono" readonly>
                                  </div>
                                  <div class="col-md-3">
                                      <br>
                                      <label for="totalAbonoImpSeg">Con Impuesto de seguridad</label>
                                      <input type="text" class="form-control input-sm alert-danger" name="totalAbonoImpSeg" value="0.00" style="color:red;">
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-7">
                                      <br>
                                      <label for="concepto">Concepto</label>
                                      <input class="form-control input-sm" type="text" name="concepto" readonly>
                                  </div>
                                  <div class="col-md-2">
                                      <br>
                                      <label for="diaCobro">Día cobro</label>
                                      <input class="form-control input-sm" type="text" name="diaCobro" readonly>
                                  </div>
                                  <div class="col-md-3">
                                      <br>
                                      <label for="cuotaImpuesto">Cuota/solo impuesto</label>
                                      <select class="form-control input-sm" type="text" name="cuotaImpuesto" readonly>
                                          <option value="">Solo impuesto</option>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-12">
                                      <br>
                                      <table class="table table-hover table-striped">
                                          <tr class="">
                                              <th class="bg-success">Abonar?</th>
                                              <th class="bg-success">N°comprobante</th>
                                              <th class="bg-success">Cuota mes</th>
                                              <th class="bg-success">Servicio desde</th>
                                              <th class="bg-success">Fecha vencimiento</th>
                                              <th class="bg-success">Total comprobante</th>
                                              <th class="bg-success">Saldo comprobante</th>
                                              <th class="bg-success">Monto abonar</th>
                                          </tr>
                                          <tr>
                                              <td>Prueba</td>
                                              <td>Prueba</td>
                                              <td>Prueba</td>
                                              <td>Prueba</td>
                                              <td>Prueba</td>
                                              <td>Prueba</td>
                                              <td>Prueba</td>
                                              <td>Prueba</td>
                                          </tr>
                                          <tr>
                                              <td>Prueba</td>
                                              <td>Prueba</td>
                                              <td>Prueba</td>
                                              <td>Prueba</td>
                                              <td>Prueba</td>
                                              <td>Prueba</td>
                                              <td>Prueba</td>
                                              <td>Prueba</td>
                                          </tr>
                                      </table>
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-2">
                                  </div>
                                  <div class="col-md-2">
                                  </div>
                                  <div class="col-md-2">
                                  </div>
                                  <div class="col-md-2">
                                      <label for="meses">TOTALES</label>
                                  </div>
                                  <div class="col-md-2">
                                      <input class="form-control input-sm alert-danger" type="text" name="total1" style="color:red;">
                                  </div>
                                  <div class="col-md-2">
                                      <input class="form-control input-sm alert-danger" type="text" name="total2" style="color:red;">
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-2">
                                  </div>
                                  <div class="col-md-2">
                                  </div>
                                  <div class="col-md-2">
                                  </div>
                                  <div class="col-md-2">
                                      <label for="meses">PENDIENTE</label>
                                  </div>
                                  <div class="col-md-2">
                                  </div>
                                  <div class="col-md-2">
                                      <input class="form-control input-sm alert-danger" type="text" name="pendiente" value="" style="color:red;">
                                  </div>
                              </div>
                              <div class="form-row">
                                  <div class="col-md-8">
                                      <label for="meses">Meses</label>
                                      <textarea class="form-control" name="meses" rows="4" cols="40"></textarea>
                                  </div>
                                  <div class="col-md-4">
                                      <label for="meses" style="color: brown;"></label>
                                      <button class="btn btn-default btn-lg btn-block" type="button" name="button"><i class="fas fa-check" style="color: green;"></i> Aplicar abonos</button>
                                      <button class="btn btn-default btn-lg btn-block" type="button" name="button"><i class="fas fa-sign-out-alt" style="color: brown;"></i> Salir</button>
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
    <script src="js/ordenTrabajo.js"></script>


</body>

</html>
