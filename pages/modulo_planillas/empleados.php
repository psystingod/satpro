<?php
    require("php/getAllPlazas.php");
    $plazas = new GetAllPlazas();
    $arrayPlazas = $plazas->getPlazas();
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
                        <li><a href="../../php/logout.php"><i class="fas fa-sign-out-alt"></i></i> Salir</a>
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
                                            <button class="btn btn-default btn-sm" type="button" data-toggle="tooltip" data-placement="bottom" title="Buscar empleado"><i class="fas fa-search"></i></button>
                                            <button class="btn btn-default btn-sm" type="button" data-toggle="tooltip" data-placement="bottom" title="Imprimir ficha"><i class="fas fa-print"></i></button>
                                            <form id="empleados" action="" method="POST">
                                            <button class="btn btn-default btn-sm pull-right" id="guardar" type="submit" data-toggle="tooltip" data-placement="bottom" title="Guardar cambios" disabled><i class="far fa-save"></i></button>
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
                                    <li><a href="#refex" data-toggle="tab">Expericencia y referencias</a>
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
                                                <input class="form-control input-sm" type="text" name="idEmpleado" readonly>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="nombres">Nombres</label>
                                                <input class="form-control input-sm" type="text" name="nombres" readonly>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="apellidos">Apellidos</label>
                                                <input class="form-control input-sm" type="text" name="apellidos" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="idEmpleado">Nombre según ISSS</label>
                                                <input class="form-control input-sm" type="text" name="nombreiss" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="direccionParticular">Dirección particular</label>
                                                <textarea class="form-control input-sm" type="text" name="direccionParticular" rows="2" cols="20" readonly></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="telefono">Teléfonos</label>
                                                <input class="form-control input-sm" type="text" name="telefono" readonly>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="municipio">Municipio</label>
                                                <input class="form-control input-sm" type="text" name="municipio" readonly>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="deptoPais">Departamento</label>
                                                <input class="form-control input-sm" type="text" name="deptoPais" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="dui">DUI</label>
                                                <input class="form-control input-sm" type="text" name="dui" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="extendidoEn">Extendido en</label>
                                                <input class="form-control input-sm" type="text" name="extendidoEn" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="fechaExpedicion">Fecha de expedición</label>
                                                <input class="form-control input-sm" type="text" name="fechaExpedicion" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="fechaNacimiento">Fecha de nacimiento</label>
                                                <input class="form-control input-sm" type="text" name="fechaNacimiento" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-1">
                                                <label for="edad">Edad</label>
                                                <input class="form-control input-sm" type="text" name="edad" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="nacionalidad">Nacionalidad</label>
                                                <input class="form-control input-sm" type="text" name="nacionalidad" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="nivelEstudios">Nivel de estudios</label>
                                                <input class="form-control input-sm" type="text" name="nivelEstudios" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="nit">NIT</label>
                                                <input class="form-control input-sm" type="text" name="nit" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="licencia">N° de licencia de conducir</label>
                                                <input class="form-control input-sm" type="text" name="licencia" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="numIsss">N° ISSS</label>
                                                <input class="form-control input-sm" type="text" name="numIsss" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="nup">NUP</label>
                                                <input class="form-control input-sm" type="text" name="nup" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="clase">Clase</label>
                                                <input class="form-control input-sm" type="text" name="clase" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="estatura">Estatura(mts)</label>
                                                <input class="form-control input-sm" type="text" name="estatura" readonly>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="peso">Peso(lbs)</label>
                                                <input class="form-control input-sm" type="text" name="peso" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="sexo">Sexo</label>
                                                <input class="form-control input-sm" type="text" name="sexo" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tipoSangre">Tipo de sangre</label>
                                                <input class="form-control input-sm" type="text" name="tipoSangre" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="profesionOficio">Profesión u oficio</label>
                                                <input class="form-control input-sm" type="text" name="profesionOficio" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="estadoCivil">Estado civil</label>
                                                <input class="form-control input-sm" type="text" name="estadoCivil" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- TAB -->
                                    <div class="tab-pane fade" id="otros-datos">
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="senalesEspeciales">Señales especiales</label>
                                                <input class="form-control input-sm" type="text" name="senalesEspeciales" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nombreConyugue">Nombre del conyugue</label>
                                                <input class="form-control input-sm" type="text" name="nombreConyugue" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="lugarTrabajoConyugue">Lugar de trabajo del conyugue</label>
                                                <input class="form-control input-sm" type="text" name="lugarTrabajoConyugue" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nombrePadre">Nombre del padre</label>
                                                <input class="form-control input-sm" type="text" name="nombrePadre" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nombreMadre">Nombre de la madre</label>
                                                <input class="form-control input-sm" type="text" name="nombreMadre" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="contactos">Contáctos</label>
                                                <input class="form-control input-sm" type="text" name="contactos" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="fechaIngreso">Fecha de ingreso</label>
                                                <input class="form-control input-sm" type="date" name="fechaIngreso" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="fechaContratacion">Fecha de contratación</label>
                                                <input class="form-control input-sm" type="date" name="fechaContratacion" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="salarioOrdinario">Salario ordinario(Mensual)</label>
                                                <input class="form-control input-sm" type="text" name="salarioOrdinario" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="fechaCambioSalario">Fecha de cambio de salario</label>
                                                <input class="form-control input-sm" type="date" name="fechaCambioSalario" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="centroTrabajoEmpleado">Centro de trabajo del empleado</label>
                                                <input class="form-control input-sm" type="text" name="centroTrabajoEmpleado" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="afpPertenece">AFP a la que pertecene</label>
                                                <input class="form-control input-sm" type="text" name="afpPertenece" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="afpPorcent">Porcentaje AFP</label>
                                                <input class="form-control input-sm" type="text" name="afpPorcent" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="isssPorcent">Porcentaje ISSS</label>
                                                <input class="form-control input-sm" type="text" name="isssPorcent" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-7">
                                                <label for="personaAutorizada">Persona autorizada para recibir salario</label>
                                                <input class="form-control input-sm" type="text" name="personaAutorizada" readonly>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="nCuenta">N° de cuenta</label>
                                                <input class="form-control input-sm" type="text" name="nCuenta" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="banco">Banco donde posee la cuenta</label>
                                                <input class="form-control input-sm" type="text" name="banco" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="cargoPlaza">Cargo o plaza asignada</label>
                                                <select class="form-control input-sm" type="text" name="cargoPlaza" readonly>
                                                    <?php
                                                    foreach ($arrayPlazas as $key) {
                                                        echo '<option value="'.strtolower($key["nombrePlaza"]).'">'.$key["nombrePlaza"].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="cargoPlaza">Rol a desempeñar</label>
                                                <select class="form-control input-sm" type="text" name="rol" readonly>
                                                    <?php
                                                    echo '<option value="administracion">Administración</option>';
                                                    echo '<option value="subgerencia">Subgerencia</option>';
                                                    echo '<option value="jefatura">Jefatura</option>';
                                                    echo '<option value="contabilidad">Contabilidad</option>';
                                                    echo '<option value="atencion">Atención al cliente</option>';
                                                    echo '<option value="mantenimiento">Mantenimiento</option>';
                                                    echo '<option value="seguridad">Seguridad</option>';
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="deptoEmpresa">Departamento</label>
                                                <select class="form-control input-sm" type="text" name="deptoEmpresa" readonly>
                                                    <?php
                                                    echo '<option value="">Informática</option>';
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tipoContratacion">Tipo de contratación</label>
                                                <select class="form-control input-sm" type="text" name="tipoContratacion" readonly>
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
                                                  <input type="text" class="form-control input-sm" name="cca" readonly>
                                                  <span class="input-group-btn">
                                                      <button type="submit" class="btn btn-search btn-sm" data-toggle="modal" data-target="#catalogoCuentas"><i class="fas fa-search"></i></button>
                                                  </span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="ccpi">Cuenta contable de permisos internos</label>
                                                <div class="input-group">
                                                  <input type="text" class="form-control input-sm" name="ccpi" readonly>
                                                  <span class="input-group-btn">
                                                      <button type="submit" class="btn btn-search btn-sm" data-toggle="modal" data-target="#catalogoCuentas"><i class="fas fa-search"></i></button>
                                                  </span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="ccr">Cuenta contable para renta</label>
                                                <div class="input-group">
                                                  <input type="text" class="form-control input-sm" name="ccr" readonly>
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
                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#educacionModal">Agregar</button><br><br>
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
                                    <div class="tab-pane fade" id="refex">
                                        <div class="row">
                                            <h4>Experiencia y referencias laborales</h4>
                                            <div class="col-md-6">
                                                <label for="empresa1">Empresa</label>
                                                <input class="form-control input-sm" type="text" name="empresa1" readonly>
                                                <label for="cargo1">Cargo</label>
                                                <input class="form-control input-sm" type="text" name="cargo1" readonly>
                                                <label for="jefe1">Jefe</label>
                                                <input class="form-control input-sm" type="text" name="jefe1" readonly>
                                                <label for="tiempoTrabajo1">Tiempo de trabajo</label>
                                                <input class="form-control input-sm" type="text" name="tiempoTrabajo1" readonly>
                                                <label for="motivoRetiro1">Motivo del retiro</label>
                                                <input class="form-control input-sm" type="text" name="motivoRetiro1" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="empresa2">Empresa</label>
                                                <input class="form-control input-sm" type="text" name="empresa2" readonly>
                                                <label for="cargo2">Cargo</label>
                                                <input class="form-control input-sm" type="text" name="cargo2" readonly>
                                                <label for="jefe2">Jefe</label>
                                                <input class="form-control input-sm" type="text" name="jefe2" readonly>
                                                <label for="tiempoTrabajo2">Tiempo de trabajo</label>
                                                <input class="form-control input-sm" type="text" name="tiempoTrabajo2" readonly>
                                                <label for="motivoRetiro2">Motivo del retiro</label>
                                                <input class="form-control input-sm" type="text" name="motivoRetiro2" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <h4>Referencias familiares</h4>
                                            <div class="col-md-9">
                                                <label for="nombreRef1">Nombre</label>
                                                <input class="form-control input-sm" type="text" name="nombreRef1" readonly>
                                                <label for="nombreRef2">Nombre</label>
                                                <input class="form-control input-sm" type="text" name="nombreRef2" readonly>
                                                <label for="nombreRef3">Nombre</label>
                                                <input class="form-control input-sm" type="text" name="nombreRef3" readonly>
                                                <label for="nombreRef4">Nombre</label>
                                                <input class="form-control input-sm" type="text" name="nombreRef4" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="ref1Num">Número</label>
                                                <input class="form-control input-sm" type="text" name="ref1Num" readonly>
                                                <label for="ref2Num">Número</label>
                                                <input class="form-control input-sm" type="text" name="ref2Num" readonly>
                                                <label for="ref3Num">Número</label>
                                                <input class="form-control input-sm" type="text" name="ref3Num" readonly>
                                                <label for="ref4Num">Número</label>
                                                <input class="form-control input-sm" type="text" name="ref4Num" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
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
            <div id="educacionModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar nivel educativo</h4>
                  </div>
                  <div class="modal-body">
                    <form class="" action="index.html" method="post">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="nivelEducativo">Nivel educativo</label>
                                <select class="form-control" name="nivelEducativo">
                                    <option value="Universitario">Universitario</option>
                                    <option value="Bachillerato">Bachillerato</option>
                                    <option value="Técnico">Técnico</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="institucion">Institución</label>
                                <input class="form-control" type="text" name="institucion">
                            </div>
                            <div class="col-md-4">
                                <label for="titulo">Título obtenido</label>
                                <input class="form-control" type="text" name="titulo">
                            </div>
                        </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <input type='submit' class='btn btn-primary' value='Agregar'>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
