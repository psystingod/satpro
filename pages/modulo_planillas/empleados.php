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

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"><strong>Empleados</strong></h1>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                Todos los empleados
                            </div>
                            <div class="panel-body">

                            </div>
                        </div>
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
                                    <div class="col-md-2">
                                        <label for="idEmpleado">Id empleado</label>
                                        <input class="form-control input-sm" type="text" name="idEmpleado">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="nombres">Nombres</label>
                                        <input class="form-control input-sm" type="text" name="nombres">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="apellidos">Apellidos</label>
                                        <input class="form-control input-sm" type="text" name="apellidos">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="idEmpleado">Nombre según ISSS</label>
                                        <input class="form-control input-sm" type="text" name="idEmpleado">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="direccionParticular">Dirección particular</label>
                                        <textarea class="form-control input-sm" type="text" name="direccionParticular" rows="4" cols="40"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="telefono">Teléfonos</label>
                                        <input class="form-control input-sm" type="text" name="telefono">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="municipio">Municipio</label>
                                        <input class="form-control input-sm" type="text" name="municipio">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="departamento">Departamento</label>
                                        <input class="form-control input-sm" type="text" name="departamento">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="dui">DUI</label>
                                        <input class="form-control input-sm" type="text" name="dui">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="extendidoEn">Extendido en</label>
                                        <input class="form-control input-sm" type="text" name="extendidoEn">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="fechaExpedicion">Fecha de expedición</label>
                                        <input class="form-control input-sm" type="text" name="fechaExpedicion">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="fechaNacimiento">Fecha de nacimiento</label>
                                        <input class="form-control input-sm" type="text" name="fechaNacimiento">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <label for="edad">Edad</label>
                                        <input class="form-control input-sm" type="text" name="edad">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="nacionalidad">Nacionalidad</label>
                                        <input class="form-control input-sm" type="text" name="nacionalidad">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="nivelEstudios">Nivel de estudios</label>
                                        <input class="form-control input-sm" type="text" name="nivelEstudios">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="nit">NIT</label>
                                        <input class="form-control input-sm" type="text" name="nit">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="licencia">N° de licencia de conducir</label>
                                        <input class="form-control input-sm" type="text" name="licencia">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="numIsss">N° ISSS</label>
                                        <input class="form-control input-sm" type="text" name="numIsss">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="nup">NUP</label>
                                        <input class="form-control input-sm" type="text" name="nup">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="clase">Clase</label>
                                        <input class="form-control input-sm" type="text" name="clase">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="estatura">Estatura(mts)</label>
                                        <input class="form-control input-sm" type="text" name="estatura">
                                    </div>
                                    <div class="col-md-1">
                                        <label for="peso">Peso(lbs)</label>
                                        <input class="form-control input-sm" type="text" name="peso">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="sexo">Sexo</label>
                                        <input class="form-control input-sm" type="text" name="sexo">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="tipoSangre">Tipo de sangre</label>
                                        <input class="form-control input-sm" type="text" name="tipoSangre">
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
    <script src="../../dist/js/jquery-validation-1.19.0/dist/jquery.validate.js"></script>
    <script src="js/ordenTrabajo.js"></script>


</body>
</html>
