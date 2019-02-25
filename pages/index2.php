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
    <link rel="shortcut icon" href="../images/Cablesat.png" />
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Font awesome CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="../dist/css/custom-principal.css">

    <!-- Morris Charts CSS -->
    <link href="../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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
                <a class="navbar-brand" href="index.html">Cablesat</a>
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
                        <li><a href="../php/logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a>
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
                            <a href="index.php"><i class="fas fa-home active"></i> Principal</a>
                        </li>
                        <li>
                            <a class="" href="infoCliente.php"><i class="fas fa-users"></i> Clientes</a>
                        </li>
                        <li>
                            <a href="moduloInventario.php"><i class="fas fa-scroll"></i> Inventario</a>
                        </li>
                        <li>
                            <a href="index2.php"><i class="fas fa-file-signature"></i>Planillas</a>
                        </li>
                        <li>
                            <a href="index2.php"><i class="fas fa-money-check-alt"></i> Contabilidad</a>
                        </li>
                        <li>
                            <a href="index2.php"><i class="fas fa-university"></i> Bancos</a>
                        </li>
                        <li>
                            <a href="cxc.php"><i class="fas fa-hand-holding-usd"></i> Cuentas por cobrar</a>
                        </li>
                        <li>
                            <a href="index2.php"><i class="fas fa-money-bill-wave"></i> Cuentas por pagar</a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-8">
                    <h4>Estadísticas</h4>
                    <h6>Vista rápida de las estadísticas de la empresa</h6>
                    <div class="row estadistics">
                        <div class="col-lg-6">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-values">
                                <div class="value">5160</div>
                                <div class="name">Clientes</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="stat-icon">
                                <i class="fas fa-warehouse"></i>
                            </div>
                            <div class="stat-values">
                                <div class="value">2500</div>
                                <div class="name">Productos</div>
                            </div>
                        </div>
                    </div>
                    <div class="row estadistics">
                        <div class="col-lg-6">
                            <div class="stat-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="stat-values">
                                <div class="value">$400</div>
                                <div class="name">Ingreso diario</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="stat-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="stat-values">
                                <div class="value">150</div>
                                <div class="name">Empleados</div>
                            </div>
                        </div>
                    </div>
                    <div class="row estadistics">
                        <div class="col-lg-6">
                            <div class="stat-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="stat-values">
                                <div class="value">50</div>
                                <div class="name">Ordenes de trabajo</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-values">
                                <div class="value">$10,000</div>
                                <div class="name">Ingresos este mes</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <h4>Actividades</h4>
                    <h6>Nuevas instalaciones, suspensiones o renovaciones</h6>
                    <div class="row orders">
                        <div id="morris-donut-chart"></div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-8">
                    <h4>Comparativa</h4>
                    <h6>Comparativa con el año anterior</h6>
                    <div class="row comparative">
                        <div id="morris-area-chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="../vendor/raphael/raphael.min.js"></script>
    <script src="../vendor/morrisjs/morris.min.js"></script>
    <script src="../data/morris-data.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
</body>

</html>
