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
                                <span style="font-size: 17px;">Ficha de empleado</span>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                            <button class="btn btn-default btn-sm" id="nuevoEmpleado" onclick="nuevoEmpleado()" type="button" name="nuevo" data-toggle="tooltip" data-placement="bottom" title="Nuevo empleado"><i class="far fa-file"></i></button>
                                            <button class="btn btn-default btn-sm" id="editarEmpleado" onclick="editarEmpleado()" type="button" name="editar" data-toggle="tooltip" data-placement="bottom" title="Editar empleado"><i class="far fa-edit"></i></button>
                                            <button class="btn btn-default btn-sm" id="guardar" type="submit" data-toggle="tooltip" data-placement="bottom" title="Guardar cambios" disabled><i class="far fa-save"></i></button>
                                            <button class="btn btn-default btn-sm" type="button" data-toggle="tooltip" data-placement="bottom" title="Buscar empleado"><i class="fas fa-search"></i></button>
                                            <button class="btn btn-default btn-sm" type="button" data-toggle="tooltip" data-placement="bottom" title="Imprimir ficha"><i class="fas fa-print"></i></button>
                                        </div>
                                    </div>
                                    <br><br>
                                </div>
                                <!-- Nav tabs -->
                                <ul class="nav nav-pills nav-justified">
                                    <li class="active"><a href="#datos-generales" data-toggle="tab">Datos generales</a>
                                    </li>
                                    <li><a href="#otros-datos" data-toggle="tab">Otros datos</a>
                                    </li>
                                    <li><a href="#educacion" data-toggle="tab">Educación</a>
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
                                            <form id="empleados" action="" method="POST">
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
                                                <input class="form-control input-sm" type="text" name="nombreiss">
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
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="profesionOficio">Profesión u oficio</label>
                                                <input class="form-control input-sm" type="text" name="profesionOficio">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="estadoCivil">Estado civil</label>
                                                <input class="form-control input-sm" type="text" name="estadoCivil">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- TAB -->
                                    <div class="tab-pane fade" id="otros-datos">
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="senalesEspeciales">Señales especiales</label>
                                                <input class="form-control input-sm" type="text" name="senalesEspeciales">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nombreConyugue">Nombre del conyugue</label>
                                                <input class="form-control input-sm" type="text" name="nombreConyugue">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="lugarTrabajoConyugue">Lugar de trabajo del conyugue</label>
                                                <input class="form-control input-sm" type="text" name="lugarTrabajoConyugue">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nombrePadre">Nombre del padre</label>
                                                <input class="form-control input-sm" type="text" name="nombrePadre">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nombreMadre">Nombre de la madre</label>
                                                <input class="form-control input-sm" type="text" name="nombreMadre">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="contactos">Contáctos</label>
                                                <input class="form-control input-sm" type="text" name="contactos">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="fechaIngreso">Fecha de ingreso</label>
                                                <input class="form-control input-sm" type="text" name="fechaIngreso">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="fechaContratacion">Fecha de contratación</label>
                                                <input class="form-control input-sm" type="text" name="fechaContratacion">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="salarioOrdinario">Salario ordinario(Mensual)</label>
                                                <input class="form-control input-sm" type="text" name="salarioOrdinario">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="fechaCambioSalario">Fecha de cambio de salario</label>
                                                <input class="form-control input-sm" type="text" name="fechaCambioSalario">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="centroTrabajoEmpleado">Centro de trabajo del empleado</label>
                                                <input class="form-control input-sm" type="text" name="centroTrabajoEmpleado">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="cuentaBanco">Banco en el que posee la cuenta</label>
                                                <input class="form-control input-sm" type="text" name="cuentaBanco">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="afpPertenece">AFP a la que pertecene</label>
                                                <input class="form-control input-sm" type="text" name="afpPertenece">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="afpPorcent">Porcentaje AFP</label>
                                                <input class="form-control input-sm" type="text" name="afpPorcent">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="personaAutorizada">Persona autorizada para recibir salario</label>
                                                <input class="form-control input-sm" type="text" name="personaAutorizada">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="nCuenta">N° de cuenta</label>
                                                <input class="form-control input-sm" type="text" name="nCuenta">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label for="cargoPlaza">Cargo o plaza asignada</label>
                                                <select class="form-control input-sm" type="text" name="cargoPlaza">
                                                    <?php
                                                    echo '<option value="">Ingeniero de sistemas</option>'
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="depto">Departamento</label>
                                                <select class="form-control input-sm" type="text" name="depto">
                                                    <?php
                                                    echo '<option value="">Informática</option>'
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="tipoContratacion">Tipo de contratación</label>
                                                <select class="form-control input-sm" type="text" name="tipoContratacion">
                                                    <?php
                                                    echo '<option value="">Permanente</option>'
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <br>
                                            <div class="col-md-4">
                                                <label for="cca">Cuenta contable de anticipo</label>
                                                <div class="input-group">
                                                  <input type="text" class="form-control input-sm" name="cca">
                                                  <span class="input-group-btn">
                                                      <button type="submit" class="btn btn-search btn-sm" data-toggle="modal" data-target="#catalogoCuentas"><i class="fas fa-search"></i></button>
                                                  </span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="ccpi">Cuenta contable de permisos internos</label>
                                                <div class="input-group">
                                                  <input type="text" class="form-control input-sm" name="ccpi">
                                                  <span class="input-group-btn">
                                                      <button type="submit" class="btn btn-search btn-sm" data-toggle="modal" data-target="#catalogoCuentas"><i class="fas fa-search"></i></button>
                                                  </span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="ccr">Cuenta contable para renta</label>
                                                <div class="input-group">
                                                  <input type="text" class="form-control input-sm" name="ccr">
                                                  <span class="input-group-btn">
                                                      <button type="submit" class="btn btn-search btn-sm" data-toggle="modal" data-target="#catalogoCuentas"><i class="fas fa-search"></i></button>
                                                  </span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal catalogo de cuentas -->
                                        <div id="catalogoCuentas" class="modal fade" role="dialog">
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
                                        <!-- Modal catalogo de cuentas -->
                                    </div>
                                    <!-- TAB -->
                                    <div class="tab-pane fade" id="educacion">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <br>
                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Agregar</button><br><br>
                                                <table class="table table-hover table-striped">
                                                    <tr>
                                                        <th class="bg-info">Nivel de estudios</th>
                                                        <th class="bg-info">Título obtenido</th>
                                                        <th class="bg-info">Institución</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--TAB-CONTENT-->
                            </div>
                        </div>

                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
            <!-- Modal -->
            <div id="educacion" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar nivel educativo</h4>
                  </div>
                  <div class="modal-body">
                    <form class="" action="index.html" method="post">
                        <select class="" name="">
                            <option value="Universitario">Universitario</option>
                            <option value="Bachillerato">Bachillerato</option>
                            <option value="Técnico">Técnico</option>
                        </select>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>
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
    <script src="js/empleados.js"></script>

</body>
</html>
