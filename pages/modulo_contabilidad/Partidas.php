<?php
    session_start();

    require("php/getDatos.php");

    $get = new getInfo();
    $Catalogo = $get->getCatalogo();
    $CuentaTipo = $get->getCuentaTipo();
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
                            alert('No tienes permisos para ingresar a esta área. Att: Don Manuel.');
                            window.location.href='../index.php';
                       </script>";
             }
         } else {
             header('Location: ../../php/logout.php');
         }
     ?>
    <div id="wrapper">

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
            <div class="row">

                    <h1 class="page-header alert alert-info">Procesamiento de partidas</h1>
                    <?php
                    if (isset($_GET['status'])) {
                        if ($_GET['status'] == 'success') {
                            echo "<div id='temporal' class='alert alert-warning alert-dismissible'>
                                      <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                      Su registro <strong>ingresó</strong> con exito.
                                  </div>";
                        }
                        else if ($_GET['status'] == 'failed'){
                            echo "<div id='temporal' class='alert alert-danger alert-dismissible'>
                                      <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                      La cuenta contable introducida ya <b>Existe</b>
                                  </div>";
                        }
                        else if ($_GET['status'] == 'Actualizar'){
                          echo "<div id='temporal' class='alert alert-warning alert-dismissible'>
                                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                    Se Actualizo con exito
                                </div>";
                        }
                    }
                    else {
                        echo "<div id='temporal'> </div>";
                    }
                    ?>
            </div>
            <!-- /.row -->
            <div class="row">
                <a href="contabilidad.php"><button class="btn btn-success" type="button" name="regresar"><i class="fas fa-arrow-circle-left"></i> Atrás</button></a>
            </div>
            <br>
            <table class="table table-striped table-hover" id="" >

              <tr>
                  <td><label for="Total">Fecha de la Partida:</label></td>
                  <td><input type='text' size='".$A."' maxlength='30' class='form-control' name='' id='' ></td>
                  <td><label for="Total">Tipo de Partida:</label></td>
                  <td><input type='text' size='".$A."' maxlength='30' class='form-control' name='' id='' ></td>
              </tr>
              <tr>
                  <td><label for="Total">N° departida:</label></td>
                  <td><input type='text' size='".$A."' maxlength='30' class='form-control' name='' id='' ></td>
                  <td><label for="Total">Concepto de Partida:</label></td>
                  <td><textarea name="comentarios" class='form-control' rows="3" cols="50">Escribe aquí tus comentarios</textarea></td>
              </tr>


            </table>
            <div class="row" style="text-align: right; width:460px">
                <a href=""><button class="btn btn-primary pull-right agregar" type="button" name="regresar">Importar a .CSV</button></a>
              <a href=""><button class="btn btn-primary pull-right agregar" type="button" name="regresar">Buscar en Asientos Contables</button></a>
              <a href=""><button class="btn btn-primary pull-right agregar" type="button" name="regresar">Crear Cuentas</button></a>
            </div>
            <div class="row">
                <form class="" action="#" method="POST">

                <!-- <button  title="Presione: Alt+A" class="btn btn-primary pull-right" type="button" name="button" data-toggle="modal" data-target="#agregar" accesskey="a"><i class="fas fa-plus-circle"></i> Agregar Cuenta</button> -->
                <hr>

                    <table class="table table-striped table-hover" id="" >
                        <thead>
                            <tr>

                                <th>Cód. Cuenta</th>
                                <th>Concepto de la transacción</th>
                                <th>Debe</th>
                                <th>Haber</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php
                            for ($i = 0; $i < 5; $i++) {
                              $A = 10;
                              echo
                              "<tr>
                                  <td><input type='text' size='".$A."' maxlength='30' class='form-control' name='' id='' ></td>
                                  <td><input type='text' size='".$A."' maxlength='30' class='form-control' name='' id='' ></td>
                                  <td><input type='text' size='".$A."' maxlength='30' class='form-control' name='' id='' placeholder='0.00'></td>
                                  <td><input type='text' size='".$A."' maxlength='30' class='form-control' name='' id='' placeholder='0.00'></td>
                              </tr>";

                            }
                            echo "
                            <tr>
                              <td><button id='' class='btn btn-primary agregar' type='button' name='button'><i class='fas fa-plus-circle'></i> Nueva Linea</button></td>
                            </tr>
                            <tr>
                            <td colspan='2' align='right'><label for='Total'>Totales:</label></td>
                            <td><input type='text' size='".$A."' maxlength='30' class='form-control' name='' id='' placeholder='0.00'></td>
                            <td><input type='text' size='".$A."' maxlength='30' class='form-control' name='' id='' placeholder='0.00'></td> </tr>

                            <tr>
                            <td colspan='3' align='right'><label for='Total'>Diferencia:</label></td>
                            <td><input type='text' size='".$A."' maxlength='30' class='form-control' name='' id='' placeholder='0.00'></td> </tr>";
                            ?>

                        </tbody>

                    </table>

                    </form>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.col-lg-12 -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Add modal -->

    <div class="modal fade" id="agregar" tabindex="-1" role="dialog" aria-labelledby="agregar">
          <div class="modal-dialog" role="document">
                <div class="modal-content">
                      <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="nuevoProducto">Nueva Cuenta de Catalogo</h4>
                      </div>
                      <form action="php/saveCatologoCuenta.php" method="POST">
                      <div class="modal-body">
                                  <div class="form-row">
                                      <div class="form-group col-md-4 col-xs-4">
                                          <label for="codigo">Codigo de Cuenta</label>
                                          <input type="number" min="0" class="form-control" name="C_Cuenta" id="C_Cuenta" placeholder="Codigo Cuenta" required>
                                      </div>
                                      <div class="form-group col-md-8 col-xs-8">
                                          <label for="nombre">Nombre de Cuenta</label>
                                          <input type="text" class="form-control" name="Nombre_Cuenta" id="Nombre_Cuenta" placeholder="Nombre Cuenta" required>
                                      </div>

                                  </div>
                                  <div class="form-row">

                                      <div class="form-group col-md-12 col-xs-12">

                                          <input type="checkbox" name="Marcar" value="1"> Marque si es una cuenta que puede ser usada directamente en partidas de diario </input><br>
                                      </div>

                                  </div>


                                  <div class="form-row">
                                      <div class="form-group col-md-8 col-xs-8">
                                            <label for="message-text" class="control-label">Codigo Cuenta Padre(Segun Nivel Jerárquico):</label>
                                              <input type="number" min="0" class="form-control" name="CCP_Cuenta" id="CCP_Cuent" required>
                                      </div>
                                      <div class="form-group col-md-4 col-xs-4">
                                            <label for="message-text" class="control-label">Nivel Jerárquico:</label>
                                              <input type="number" min="0" class="form-control" name="N_Cuenta" id="N_Cuenta" required>
                                      </div>
                                  </div>
                                  <div class="form-row">
                                      <div class="form-group col-md-8 col-xs-8">
                                            <label for="message-text" class="control-label">Cargar como cuenta de:</label>
                                            <select class="form-control form-control-lg" name="CuentaTipo" title="Escribe solamente letras y números" required>
                                               <option value="" selected="selected">Seleccionar...</option>

                                                <option value="1" >Activo</option>;
                                                <option value="2" >Pasivo</option>;
                                                <option value="3" >Patrimonio</option>;
                                                <option value="4" >Orden Saldo Deudor</option>;
                                                <option value="5" >Orden Saldo Acreedor</option>;
                                                <option value="6" >Cuenta Liquidadora Resultado</option>;
                                           </select>
                                      </div>
                                      <div class="form-group col-md-4 col-xs-4">
                                            <label for="message-text" class="control-label">Tipo de Cuenta:</label>

                                            <select class="form-control form-control-lg" name="T_Cuenta" required>
                                               <option value="" selected="selected">Seleccionar...</option>
                                               <?php
                                                 foreach ($CuentaTipo as $key) {
                                                     echo "<option value='".strtolower($key['IdCuentaTipo'])." '>".$key['Nombre']."</option>";
                                                 }
                                               ?>
                                           </select>
                                      </div>
                                  </div>
                      </div>
                      <div class="modal-footer">
                            <button type="button" name="Action1" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <input type="submit" name="Action2" class="btn btn-primary" value="Agregar">
                      </div>
                           </form>
                </div>
          </div>
    </div><!-- /Add modal -->
    <!-- jQuery -->
    <script src="../../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../../vendor/datatables/js/dataTables.bootstrap.js"></script>
    <script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../../dist/js/sb-admin-2.js"></script>
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#inventario').DataTable({
            responsive: true,
            "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "No se encontró ningún registro",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No se encontró ningún registro",
            "search": "Buscar: ",
            "searchPlaceholder": "",
            "infoFiltered": "(de un total de _MAX_ total registros)",
            "paginate": {
             "previous": "Anterior",
             "next": "Siguiente"
            }
        }
        });
    });
    </script>


    <script type="text/javascript">
        function transferProduct(id) {
            window.location = "index.html?id="+id;
        }
    </script>

    <script type="text/javascript">
        $(function() {
            $(".checkbox").click(function(){
                $('#traslados').prop('disabled',$('input.checkbox:checked').length == 0);
            });
        });
    </script>

    <script type="text/javascript">
        var d = new Date();
        var month = String((parseInt(d.getMonth()+1)))
        document.getElementById("fecha").value = d.getFullYear()+"/"+month+"/"+d.getDate();
    </script>

</body>

</html>