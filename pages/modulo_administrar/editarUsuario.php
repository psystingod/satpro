<?php
    const ACCESO = 1;
    const AGREGAR = 2;
    const EDITAR = 4;
    const ELIMINAR = 8;

    function getAccess($permisosActuales, $permisoRequerido){
        return ((intval($permisosActuales) & intval($permisoRequerido)) == 0) ? false : true;
    }
    session_start();
    require("../../php/connection.php");
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
<link rel="shortcut icon" href="../../images/Cablesat.png" />
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
         if(!isset($_SESSION["user"])) {
            header('Location: ../../php/logout.php');
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
                            <a href='../index.php'><i class='fas fa-home active'></i> Principal</a>
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
        <?php
            // get passed parameter value, in this case, the record ID
            // isset() is a PHP function used to verify if a value is there or not
            $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Registro no encontrado.');

            //include database connection
        $obj = new ConectionDB();
        $con = $obj->dbConnect;

            // read current record's data
            try {
                // prepare select query
                $query = "SELECT * FROM tbl_usuario, tbl_empleado WHERE tbl_usuario.IdUsuario = ? AND tbl_empleado.IdEmpleado = ?";
                $stmt = $con->prepare( $query );

                // this is the first question mark
                $stmt->bindParam(1, $id);
                $stmt->bindParam(2, $id);

                // execute our query
                $stmt->execute();

                // store retrieved row to a variable
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // values to fill up our form
                $nombres = $row['Nombres'];
                $apellidos = $row['Apellidos'];
                $usuario = $row['Usuario'];
                $clave = $row['Clave'];
                $rol = $row['Rol'];

                // prepare select query
                $query = "SELECT IdModulo FROM tbl_permisosusuariomodulo WHERE IdUsuario = $id";
                $stmt = $con->prepare( $query );

                // execute our query
                $stmt->execute();

                // store retrieved row to a variable
                $arrayIdModules = array();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($row as $key) {
                    array_push($arrayIdModules, $key["IdModulo"]);
                }

                $arrayModules = array();
                foreach ($arrayIdModules as $idModule) {
                    $i = 0;
                    $query = "SELECT valor FROM tbl_modulos WHERE IdModulo = $idModule[$i]";
                    $stmt = $con->prepare( $query );
                    $stmt->execute();
                    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($row as $key) {
                        array_push($arrayModules, $key["valor"]);
                    }
                    $i++;
                }

                $totalPermissionsModules = 0;
                foreach ($arrayModules as $permission) {
                    $totalPermissionsModules = $totalPermissionsModules + intval($permission);
                }

                // prepare select query
                $query = "SELECT IdPermisos FROM tbl_permisosusuario WHERE IdUsuario = $id";
                $stmt = $con->prepare( $query );

                // execute our query
                $stmt->execute();

                // store retrieved row to a variable
                $arrayIdPermissions = array();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($row as $key) {

                    array_push($arrayIdPermissions, $key["IdPermisos"]);
                }

                $arrayPermissions = array();
                foreach ($arrayIdPermissions as $idPermission) {
                    $i = 0;
                    $query = "SELECT valor FROM tbl_permisos WHERE IdPermisos = $idPermission[$i]";
                    $stmt = $con->prepare( $query );
                    $stmt->execute();
                    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($row as $key) {
                        array_push($arrayPermissions, $key["valor"]);
                    }
                    $i++;
                }

                $totalPermissions = 0;
                foreach ($arrayPermissions as $permission) {
                    $totalPermissions = $totalPermissions + intval($permission);
                }

                $con = NULL;


            }

            // show error
            catch(PDOException $exception){
                die('ERROR: ' . $exception->getMessage());
            }
        ?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header">Usuario: <?php echo "<strong>".$usuario."</strong>" ?></h3>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <table class='table table-hover table-responsive table-bordered'>
                        <tr>
                            <th width="200px">Nombres</th>
                            <?php echo "<td><input class='form-control' type='text' id='nombres' name='nombres' value='".htmlspecialchars($nombres, ENT_QUOTES)."'></td>";?>
                        </tr>
                        <tr>
                            <th width="200px">Apellidos</th>
                            <?php echo "<td><input class='form-control' type='text' id='nombres' name='apellidos' value='".htmlspecialchars($apellidos, ENT_QUOTES)."'></td>";?>
                        </tr>
                        <tr>
                            <th width="200px">Usuario</th>
                            <?php echo "<td><input class='form-control' type='text' id='nombres' name='usuario' value='".htmlspecialchars($usuario, ENT_QUOTES)."'></td>";?>
                        </tr>
                        <tr>
                            <th width="200px">Contraseña</th>
                            <?php echo "<td><input class='form-control' type='text' id='nombres' name='clave' value='".htmlspecialchars($clave, ENT_QUOTES)."'></td>";?>
                        </tr>
                        <tr>
                            <th width="200px">Rol que desempeña</th>
                            <?php echo "<td><input class='form-control' type='text' id='nombres' name='rol' value='".htmlspecialchars($rol, ENT_QUOTES)."'></td>";?>
                        </tr>
                    </table>
                </div>
                <!-- Code to update user -->

                <?php

                // check if form was submitted
                if($_POST){

                    try{

                        // write update query
                        // in this case, it seemed like we have so many fields to pass and
                        // it is better to label them and not use question marks
                        $query1 = "SELECT * FROM tbl_permisosusuariomodulo WHERE IdModulo = 1";

                        $query1 = "UPDATE products
                                    SET name=:name, description=:description, price=:price
                                    WHERE id = :id";

                        // prepare query for excecution
                        $stmt = $con->prepare($query);

                        // posted values
                        $name=htmlspecialchars(strip_tags($_POST['name']));
                        $description=htmlspecialchars(strip_tags($_POST['description']));
                        $price=htmlspecialchars(strip_tags($_POST['price']));

                        // bind the parameters
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':id', $id);

                        // Execute the query
                        if($stmt->execute()){
                            echo "<div class='alert alert-success'>Record was updated.</div>";
                        }else{
                            echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                        }

                    }

                    // show errors
                    catch(PDOException $exception){
                        die('ERROR: ' . $exception->getMessage());
                    }
                }
                ?>

                <!-- Form to update user -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3 class="page-header">Permisos de <strong>acceso</strong></h3>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row -->
                        <table class='table table-hover table-responsive table-bordered'>
                            <th>MODULO</th>
                            <th>ACCESO</th>
                            <?php
                            if (setMenu($totalPermissionsModules, ADMINISTRADOR)) {
                                echo "<tr><td>ADMINISTRADOR</td><td><input type='checkbox' name='administrador' value='1' checked></td></tr>";
                            }else {
                                echo "<tr><td>ADMINISTRADOR</td><td><input type='checkbox' name='administrador' value='1'></td></tr>";
                            }
                            if (setMenu($totalPermissionsModules, CONTABILIDAD)) {
                                echo "<tr><td>CONTABILIDAD</td><td><input type='checkbox' name='contabilidad' value='2' checked></td></tr>";
                            }else {
                                echo "<tr><td>CONTABILIDAD</td><td><input type='checkbox' name='contabilidad' value='2'></td></tr>";
                            }
                            if (setMenu($totalPermissionsModules, PLANILLA)) {
                                echo "<tr><td>PLANILLA</td><td><input type='checkbox' name='planilla' value='3' checked></td></tr>";
                            }else {
                                echo "<tr><td>PLANILLA</td><td><input type='checkbox' name='planilla' value='3'></td></tr>";
                            }
                            if (setMenu($totalPermissionsModules, ACTIVOFIJO)) {
                                echo "<tr><td>ACTIVO FIJO</td><td><input type='checkbox' name='activoFijo' value='4' checked></td></tr>";
                            }else {
                                echo "<tr><td>ACTIVO FIJO</td><td><input type='checkbox' name='activoFijo' value='4'></td></tr>";
                            }
                            if (setMenu($totalPermissionsModules, INVENTARIO)) {
                                echo "<tr><td>INVENTARIO</td><td><input type='checkbox' name='inventario' value='5' checked></td></tr>";
                            }else {
                                echo "<tr><td>INVENTARIO</td><td><input type='checkbox' name='inventario' value='5'></td></tr>";
                            }
                            if (setMenu($totalPermissionsModules, IVA)) {
                                echo "<tr><td>IVA</td><td><input type='checkbox' name='iva' value='6' checked></td></tr>";
                            }else {
                                echo "<tr><td>IVA</td><td><input type='checkbox' name='iva' value='6'></td></tr>";
                            }
                            if (setMenu($totalPermissionsModules, BANCOS)) {
                                echo "<tr><td>BANCOS</td><td><input type='checkbox' name='bancos' value='7' checked></td></tr>";
                            }else {
                                echo "<tr><td>BANCOS</td><td><input type='checkbox' name='bancos' value='7'></td></tr>";
                            }
                            if (setMenu($totalPermissionsModules, CXC)) {
                                echo "<tr><td>CUENTAS POR COBRAR</td><td><input type='checkbox' name='cxc' value='8' checked></td></tr>";
                            }else {
                                echo "<tr><td>CUENTAS POR COBRAR</td><td><input type='checkbox' name='cxc' value='8'></td></tr>";
                            }
                            if (setMenu($totalPermissionsModules, CXP)) {
                                echo "<tr><td>CUENTAS POR PAGAR</td><td><input type='checkbox' name='cxp' value='9' checked></td></tr>";
                            }else {
                                echo "<tr><td>CUENTAS POR PAGAR</td><td><input type='checkbox' name='cxp' value='9'></td></tr>";
                            }
                            ?>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3 class="page-header">Permisos <strong>globales</strong></h3>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row -->
                        <table class='table table-hover table-responsive table-bordered'>
                            <th>AGREGAR</th>
                            <th>EDITAR</th>
                            <th>ELIMINAR</th>
                            <?php
                            if (setMenu($totalPermissions, AGREGAR)) {
                                echo "<tr><td><input type='checkbox' name='agregar' value='2' checked></td>";
                            }else {
                                echo "<tr><td><input type='checkbox' name='agregar' value='2'></td>";
                            }
                            if (setMenu($totalPermissions, EDITAR)) {
                                echo "<td><input type='checkbox' name='editar' value='3' checked></td>";
                            }else {
                                echo "<td><input type='checkbox' name='editar' value='3'></td>";
                            }
                            if (setMenu($totalPermissions, ELIMINAR)) {
                                echo "<td><input type='checkbox' name='eliminar' value='4' checked></td></tr>";
                            }else {
                                echo "<td><input type='checkbox' name='eliminar' value='4'></td></tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">

                    </div>
                    <div class="col-lg-4">
                        <button class="btn btn-default" type="button" name="regresar">Cancelar</button>
                        <button class="btn btn-primary" type="submit" name="submit">Guardar cambios</button>
                    </div>
                    <div class="col-lg-4">

                    </div>
                </div>
                </form>
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
