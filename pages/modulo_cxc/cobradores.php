<?php
    session_start();
    require_once "php/GetAllInfo.php";
    $cobradores = new GetAllInfo();

    if (isset($_GET["codigoCobrador"])) {
        $arrCob = $cobradores->getDataCob('tbl_cobradores', $_GET['codigoCobrador']);
        $codigoCobrador = $arrCob['codigoCobrador'];
        $nombreCobrador = $arrCob['nombreCobrador'];
        $prefijoCobro = $arrCob['prefijoCobro'];
        $desdeNumero = $arrCob['desdeNumero'];
        $hastaNumero = $arrCob['hastaNumero'];
        $ultimoNumero = $arrCob['numeroAsignador'];
    }else {
        $codigoCobrador = "";
        $nombreCobrador = "";
        $prefijoCobro = "";
        $desdeNumero = "";
        $hastaNumero = "";
        $ultimoNumero = "";
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
         // session_start();
         if(isset($_SESSION["user"])) {
             if ($_SESSION["rol"] != "administracion") {
                 echo "<script>
                            alert('No tienes permisos para ingresar a esta área');
                            window.location.href='../index.php';
                       </script>";
             }
         } else {
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
                        <li><a href="php/logout.php"><i class="fas fa-sign-out-alt"></i></i> Salir</a>
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


        <div id="page-wrapper">

           <!-- /.row -->
           <div class="row">
               <div class="col-lg-12">
                   <h1 class="page-header"><b>Área de cobros</b></h1>
                   <div class="panel panel-primary">
                      <div class="panel-heading">Datos del cobrador</div>
                      <div class="panel-body">
                          <button class="btn btn-default" type="button" id="nuevo" name="nuevo" onclick="nuevoCobrador();"><i class="far fa-file"></i></button>
                          <button class="btn btn-default" type="button" id="editar" name="editar" onclick="editarCobrador();"><i class="fas fa-edit"></i></button>
                          <button class="btn btn-default" type="button" id="buscar" name="buscar" data-toggle="modal" data-target="#buscarCobrador"><i class="fas fa-search"></i></button>
                          <?php
                          if (isset($_GET['status'])) {
                              if ($_GET['status'] == "failed") {
                                  echo '
                                  <script>
                                  alert("Error: Ocurrió un problema al realizar este procedimiento, intentelo nuevamente o cumuníquese con el administrador del sistema.");
                                  </script>
                                  ';
                              }elseif ($_GET['status'] == "success") {
                                  echo '
                                  <script>
                                  alert("Operación realizada satisfactoriamente.");
                                  </script>
                                  ';
                              }
                          }
                          ?>
                      <form id="frmCob" class="" action="" method="POST">
                          <div class="row">
                              <div class="col-md-3">
                                  <label for="codigoCobrador">Código del cobrador</label>
                                  <input class="form-control" type="text" id="codigoCobrador" value="<?php echo $codigoCobrador; ?>" readonly>
                                  <input class="form-control" type="hidden" name="codigoCobrador" value="<?php echo $codigoCobrador; ?>" >
                              </div>
                              <div class="col-md-9">
                                  <label for="nombreCobrador">Nombre del cobrador</label>
                                  <input class="form-control" type="text" name="nombreCobrador" value="<?php echo $nombreCobrador; ?>" readonly required>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-md-3">
                                  <label for="prefijoCobro">Prefijo cobro</label>
                                  <input class="form-control" type="text" name="prefijoCobro" value="<?php echo $prefijoCobro; ?>" readonly required>
                              </div>
                              <div class="col-md-3">
                                  <label for="desdeNumero">Desde</label>
                                  <input class="form-control" type="text" name="desdeNumero" value="<?php echo $desdeNumero; ?>" readonly required>
                              </div>
                              <div class="col-md-3">
                                  <label for="hastaNumero">Hasta</label>
                                  <input class="form-control" type="text" name="hastaNumero" value="<?php echo $hastaNumero; ?>" readonly required>
                              </div>
                              <div class="col-md-3">
                                  <label for="ultimoNumero">último número de recibo</label>
                                  <input class="form-control" type="text" name="ultimoNumero" value="<?php echo $ultimoNumero; ?>" readonly required>
                              </div>
                          </div>
                          <br>
                          <button class="btn btn-success pull-right" type="submit" id="guardar" name="guardar" disabled>Guardar configuración</button>
                      </form>
                      </div>
                   </div>
               </div>
           </div>
           <!-- /.row -->
           <!-- Modal -->
           <div id="buscarCobrador" class="modal fade" role="dialog">
             <div class="modal-dialog modal-lg">

               <!-- Modal content-->
               <div class="modal-content">
                 <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                   <h4 class="modal-title">Buscar cobrador</h4>
                 </div>
                 <div class="modal-body">
                     <div class="row">
                         <div class="col-md-12">
                             <input class="form-control" type="text" name="caja_busqueda" id="caja_busqueda" placeholder="Código cobrador, Nombre, Desde numero, Último número">
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-md-12">
                             <div id="datos">

                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="modal-footer">
                   <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                 </div>
               </div>

             </div>
           </div>
        </div>


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

    <!-- Custom Theme JavaScript -->
    <script src="js/searchcob.js"></script>
    <script src="js/cobradores.js"></script>

</body>

</html>
