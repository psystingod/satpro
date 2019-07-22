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
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="../dist/css/custom-principal.css">

    <!-- Morris Charts CSS -->
    <link href="../../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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
                <a class="navbar-brand" href="index.php">Cablesat</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown" style="padding:5px;">
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
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="page-header text-center"><b>Estado de cuenta</b></h2>
                        <ul class="nav nav-tabs nav-justified mt-5" id="pills-tab" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#perfil" role="tab" aria-controls="pills-home" aria-selected="true"><b>CABLE</b></a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#documentosf2" role="tab" aria-controls="pills-profile" aria-selected="false"><b>INTERNET</b></a>
                          </li>
                          <!--<li class="nav-item">
                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#expedienteG" role="tab" aria-controls="pills-contact" aria-selected="false">EXPEDIENTE DE GRADUACIÓN</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#otros" role="tab" aria-controls="pills-contact" aria-selected="false">OTROS DOCUMENTOS</a>
                        </li>-->
                        </ul>
                        <div class="tab-content mt-3 mb-3" id="pills-tabContent" style="font-size: 14px;">
                          <div class="tab-pane fade show active" id="perfil" role="tabpanel" aria-labelledby="pills-home-tab">
                              <br>
                              <div class="panel panel-default">
                                  <div class="panel-heading">35856 Diego Armando Herrera</div>
                                  <div class="panel-body">Panel Content</div>
                              </div>
                          </div>
                            <div class="tab-pane fade" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <div class="card">
                                  <div class="card-header">Diego Armando Herrera Flores <button class="btn btn-info btn-sm float-right" data-toggle="modal" data-target="#foto" accesskey="p"><i class="fas fa-image"></i> Fotografía</button></div>
                                  <div class="card-body">

                                      <table class="table table-hover">
                                          <thead class="">
                                              <tr class="">
                                                  <th>DOCUMENTOS</th>
                                                    <th>OBSERVACIONES</th>
                                                    <th>ÚLTIMA MODIFICACIÓN</th>
                                                    <th>OPCIONES</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>CARNET_RESIDENTE:</td>
                                                    <td><input class="form-control" type="text"></td>
                                                    <td><span class="text-danger">25/05/2019</span></td>
                                                    <td>
                                                        <a href="#" class="btn btn-info"><i class="far fa-eye"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-file-upload"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-download"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>CERTIFICADO_SALUD:</td>
                                                    <td><input class="form-control" type="text"></td>
                                                    <td><span class="text-danger">25/05/2019</span></td>
                                                    <td>
                                                        <a href="#" class="btn btn-info"><i class="far fa-eye"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-file-upload"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-download"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>CONSTANCIA_CUOTA_BACHILLERATO:</td>
                                                    <td><input class="form-control" type="text"></td>
                                                    <td><span class="text-danger">25/05/2019</span></td>
                                                    <td>
                                                        <a href="#" class="btn btn-info"><i class="far fa-eye"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-file-upload"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-download"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>DUI:</td>
                                                    <td><input class="form-control" type="text"></td>
                                                    <td><span class="text-danger">25/05/2019</span></td>
                                                    <td>
                                                        <a href="#" class="btn btn-info"><i class="far fa-eye"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-file-upload"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-download"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>NIT:</td>
                                                    <td><input class="form-control" type="text"></td>
                                                    <td><span class="text-danger">25/05/2019</span></td>
                                                    <td>
                                                        <a href="#" class="btn btn-info"><i class="far fa-eye"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-file-upload"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-download"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>PAES:</td>
                                                    <td><input class="form-control" type="text"></td>
                                                    <td><span class="text-danger">25/05/2019</span></td>
                                                    <td>
                                                        <a href="#" class="btn btn-info"><i class="far fa-eye"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-file-upload"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-download"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>PARTIDA_NACIMIENTO:</td>
                                                    <td><input class="form-control" type="text"></td>
                                                    <td><span class="text-danger">25/05/2019</span></td>
                                                    <td>
                                                        <a href="#" class="btn btn-info"><i class="far fa-eye"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-file-upload"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-download"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>PASAPORTE:</td>
                                                    <td><input class="form-control" type="text"></td>
                                                    <td><span class="text-danger">25/05/2019</span></td>
                                                    <td>
                                                        <a href="#" class="btn btn-info"><i class="far fa-eye"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-file-upload"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-download"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>TITULO_BACHILLER:</td>
                                                    <td><input class="form-control" type="text"></td>
                                                    <td><span class="text-danger">25/05/2019</span></td>
                                                    <td>
                                                        <a href="#" class="btn btn-info"><i class="far fa-eye"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-file-upload"></i></a>
                                                        <a href="#" class="btn btn-info"><i class="fas fa-download"></i></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                    </table>
                                  </div>
                              </div>
                            </div>
                          <div class="tab-pane fade" id="expedienteG" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
                          <div class="tab-pane fade" id="otros" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
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

</body>

</html>
