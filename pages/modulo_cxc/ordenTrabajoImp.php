<?php

?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Ordenes de trabajo</title>
        <link rel="shortcut icon" href="../../images/Cablesat.png" />
        <!-- Bootstrap Core CSS -->
        <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="../../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

        <!-- Font awesome CSS -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">

        <!-- Custom CSS -->
        <link href="../../dist/css/sb-admin-2.css" rel="stylesheet">
        <link rel="stylesheet" href="../dist/css/custom-principal.css">
        <link rel="stylesheet" href="js/menu.css">

        <!-- Morris Charts CSS -->
        <link href="../../vendor/morrisjs/morris.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="../../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    </head>
    <body style="background-color: #FAFAFA;">
        <br>
        <div class="row-fluid">
            <div class="col-md-12">
                <div class="panel panel-danger">
                  <div class="panel-heading"><h3 style="padding:0; margin:0;" class="text-center"><b>Orden de trabajo</b></h3><h4 style="padding:0; margin:0;" class="text-center"><b>Usulután centro</b></h4></div>
                  <div class="panel-body">
                      <div class="row">
                          <div class="col-md-6">
                              <h6 class="label label-danger pull-left" style="font-size:13px;" class="text-left">N°111213</h6>
                          </div>
                          <div class="col-md-6">
                              <h6 class="label label-danger pull-right" style="font-size:13px;" class="text-right">Día de cobro 5</h6>
                          </div>
                      </div>
                    <div class="row">
                        <div class="col-md-3 col-xs-3">
                            <label for="fecha">Fecha</label>
                            <input class="form-control" type="text" name="fecha" value="">
                        </div>
                        <div class="col-md-2 col-xs-2">
                            <label for="fecha">Código</label>
                            <input class="form-control" type="text" name="codigo" value="">
                        </div>
                        <div class="col-md-7 col-xs-7">
                            <label for="nombre">Nombre</label>
                            <input class="form-control" type="text" name="nombre" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <label for="direccion">Dirección</label>
                            <input class="form-control" type="text" name="direccion" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 col-xs-2">
                            <label for="hora">Hora</label>
                            <input class="form-control" type="text" name="hora" value="">
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <label for="telefono">Teléfono</label>
                            <input class="form-control" type="text" name="telefono" value="">
                        </div>
                        <div class="col-md-7 col-xs-7">
                            <label for="trabajo">Trabajo a relizar</label>
                            <input class="form-control" type="text" name="trabajo" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-4">
                            <label for="tecnico">Técnico</label>
                            <input class="form-control" type="text" name="tecnico" value="">
                        </div>
                        <div class="col-md-8 col-xs-8">
                            <label for="observaciones">Observaciones</label>
                            <input class="form-control" type="text" name="observaciones" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1 col-xs-1">
                            <label for="snr">SNR</label>
                            <input class="form-control" type="text" name="snr" value="">
                        </div>
                        <div class="col-md-1 col-xs-1">
                            <label for="tx">TX</label>
                            <input class="form-control" type="text" name="tx" value="">
                        </div>
                        <div class="col-md-1 col-xs-1">
                            <label for="rx">RX</label>
                            <input class="form-control" type="text" name="rx" value="">
                        </div>
                        <div class="col-md-1 col-xs-1">
                            <label for="velocidad">Velocidad</label>
                            <input class="form-control" type="text" name="velocidad" value="">
                        </div>
                        <div class="col-md-2 col-xs-2">
                            <label for="marca">Marca/Modelo</label>
                            <input class="form-control" type="text" name="marca" value="">
                        </div>
                        <div class="col-md-2 col-xs-2">
                            <label for="mac">MAC</label>
                            <input class="form-control" type="text" name="mac" value="">
                        </div>
                        <div class="col-md-2 col-xs-2">
                            <label for="colilla">Colilla</label>
                            <input class="form-control" type="text" name="colilla" value="">
                        </div>
                        <div class="col-md-2 col-xs-2">
                            <label for="tecnologia">Tecnología</label>
                            <input class="form-control" type="text" name="tecnologia" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <label for="coordenadas">coordenadas/otros datos</label>
                            <input class="form-control" type="text" name="coordenadas" value="">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-xs-4">

                            <input class="form-control alert-danger" type="text" name="cliente" value="">
                        </div>
                        <div class="col-md-4 col-xs-4">

                            <input class="form-control alert-danger" type="text" name="tecnico" value="">
                        </div>
                        <div class="col-md-4 col-xs-4">

                            <input class="form-control alert-danger" type="text" name="autorizacion" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-4">
                            <label class="pull-center" for="">CLIENTE</label>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <label for="">TECNICO</label>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <label for="">AUTORIZACION</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-4">
                            <label style="border:dotted;" class="label label-danger" for="">CREADO POR: DIEGO</label>
                        </div>
                        <div class="col-md-4 col-xs-4">

                        </div>
                        <div class="col-md-4 col-xs-4">
                            <label style="border:dotted;" class="label label-info pull-right" for="">INTERNET</label>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
        <!-- jQuery -->
        <script src="../vendor/jquery/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="../vendor/metisMenu/metisMenu.min.js"></script>

        <!-- DataTables JavaScript -->
        <script src="../vendor/datatables/js/dataTables.bootstrap.js"></script>
        <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
        <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
        <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
        <!-- Custom Theme JavaScript -->
        <script src="../dist/js/sb-admin-2.js"></script>
        <style media="screen">
            .form-control{

            }
        </style>
    </body>
</html>
