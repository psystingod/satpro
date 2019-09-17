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

         if (isset($_GET['gen'])) {
             if ($_GET['gen'] == "no") {
                 echo "<script>alert('No se encontraron datos para generar facturación')</script>";
             }elseif ($_GET['gen'] == "yes") {
                 echo "<script>alert('Facturación generada con exito.')</script>";
             }
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
                        Transacciones <i class="fas fa-caret-down"></i>
                    </a>
                    <?php
                    if(!isset($_SESSION["user"])) {
                        header('Location: ../login.php');
                    }elseif (isset($_SESSION["user"])) {
                        if ($_SESSION["rol"] == 'administracion' || $_SESSION["rol"] == 'subgerencia') {
                            echo
                            '<ul class="dropdown-menu dropdown-user">
                                <li><a href="#" data-toggle="modal" data-target="#facturacionDiaria">Facturación diaria</a>
                                </li>
                                <li><a href="facturacionGenerada.php" target="_blank">Ver facturas generadas</a>
                                </li>
                            </ul>';
                        }
                    }
                    ?>
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
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"><b>Módulo de cuentas por cobrar</b></h1>
                        <div class="row">
                            <a class="" href="clientes.php"><div class="col-lg-6 btn btn-default">
                                <div class="stat-icon">
                                    <i class="fas fa-users fa-3x"></i>
                                </div>
                                <div class="stat-values">
                                    <br>
                                    <div class="name">Clientes</div>
                                </div>
                            </div></a>

                            <a class="" href="abonos.php"><div class="col-lg-6 btn btn-default">
                                <div class="stat-icon">
                                    <i class="fas fa-money-bill-alt fa-3x"></i>
                                </div>
                                <div class="stat-values">
                                    <br>
                                    <div class="name">Abonos</div>
                                </div>
                            </div></a>
                        </div>
                        <!--<div class="row">
                            <a href="reportes.php"><div class="col-lg-6 btn btn-default">
                                <div class="stat-icon">
                                    <i class="fas fa-file-alt fa-3x"></i>
                                </div>
                                <div class="stat-values">
                                    <br>
                                    <div class="name">Reportes Inventario</div>
                                </div>
                            </div></a>
                            <a href="HistorialArticulo.php"><div class="col-lg-6 btn btn-default">
                                <div class="stat-icon">
                                    <i class="fas fa-scroll fa-3x"></i>
                                </div>
                                  <div class="stat-values">
                                    <br>
                                    <div class="name">Detalle de Ingresos de Articulos</div>
                                </div>
                            </div></a>
                        </div>-->

                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
            <!-- Modal Facturación diaria -->
            <div id="facturacionDiaria" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div style="background-color: #1565C0; color:white;" class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Generar facturas</h4>
                  </div>
                  <form id="generarFacturas" action="php/generarFacturas.php?" method="POST">
                  <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <select class="form-control" name="tipoComprobante" required>
                                <option value="2">Factura consumidor final</option>
                                <option value="1">Crédito fiscal</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
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
                        <div class="col-md-4">
                            <label for=""></label>
                            <select id="diaGenerar" class="form-control" name="diaGenerar" required>
                                <option value="" selected>Día a generar</option>
                                <option value="01">1</option>
                                <option value="02">2</option>
                                <option value="03">3</option>
                                <option value="04">4</option>
                                <option value="05">5</option>
                                <option value="06">6</option>
                                <option value="07">7</option>
                                <option value="08">8</option>
                                <option value="09">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>
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

    <script type="text/javascript">
        // Get the input field
        var mes = document.getElementById("mesGenerar");
        var dia = document.getElementById("diaGenerar");
        var ano = document.getElementById("anoGenerar");

        $('#generarFacturas').on('keyup keypress', function(e) {
          var keyCode = e.keyCode || e.which;
          if (keyCode === 13) {
            e.preventDefault();
            return false;
          }
        });

        // Execute a function when the user releases a key on the keyboard
        ano.addEventListener("keyup", function(event) {
        // Number 13 is the "Enter" key on the keyboard
        if (event.keyCode === 13) {
        // Cancel the default action, if needed
        event.preventDefault();
        // Trigger the button element with a click
        var mesGenerar = document.getElementById("mesGenerar").value;
        var diaGenerar = document.getElementById("diaGenerar").value;
        var anoGenerar = document.getElementById("anoGenerar").value;

        var fechaComprobante = new Date(mesGenerar+"-"+diaGenerar+"-"+anoGenerar);
        console.log(fechaComprobante.setMonth(fechaComprobante.getMonth()+1));
        document.getElementById("fechaComprobante").value = fechaComprobante.toLocaleDateString();
        console.log(fechaComprobante.setDate(fechaComprobante.getDate()+8));
        document.getElementById("fechaVencimiento").value = fechaComprobante.toLocaleDateString();
        }
        });
    </script>

</body>

</html>
