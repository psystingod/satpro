<!DOCTYPE html>
<html lang="es">

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

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="../dist/css/login.css">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        img {
            position: relative;
            animation-name: example;
            animation-duration: 3.2s;
        }

        @keyframes example {
            0%   {left:0px; left:-800px;}
            25%  {left:0px; left:50px;}
            50%  {left:25px; left:0px;}
            75%  {left:0px; right:-30px;}
            100% {left:0px; right:0px;}
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <img class="img img-responsive center-block" src="../images/logo2.png" alt="" width="190px" height="170px">
                <div class="login-panel panel panel-default" style="border-radius: 20px;">
                    <div class="panel-heading" style="border-radius: 20px 20px 0px 0px;">
                        <h3 class="panel-title text-center"><strong>Inicio de sesión</strong></h3>
                    </div>
                    <div class="panel-body" style="padding: 25px;">
                        <form action="../php/signin.php" method="POST" role="form">
                            <fieldset>
                                <div class="input-group">
                                    <input class="form-control" placeholder="Usuario" name="user" type="text" autofocus>
                                    <span class="input-group-addon danger"><i class="glyphicon glyphicon-user"></i></span>
                                </div>
                                <br>
                                <div class="input-group">
                                    <input class="form-control" placeholder="Contraseña" name="pass" type="password">
                                    <span class="input-group-addon danger"><i class="glyphicon glyphicon-lock"></i></span>
                                </div>
                                <br>
                                <div class="input-group">
                                    <select class="form-control" name="sucursal" required>
                                        <option value="1" selected>Sucursal Usulután</option>
                                        <option value="2">Sucursal San Miguel</option>
                                        <option value="3">Testing (NO USAR)</option>
                                    </select>
                                    <span class="input-group-addon danger"><i class="glyphicon glyphicon-home"></i></span>
                                </div>
                                <br>
                                <!--<div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Recuerdame
                                    </label>
                                </div>-->
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-danger btn-block" name="submit">Ingresar</button>
                            </fieldset>
                        </form>
                        <?php
                        if (isset($_GET['login'])) {
                            if ($_GET['login'] == 'failed') {
                                echo "</br>";
                                echo "<div class='alert alert-danger'>Verifique su usuario o contraseña</div>";
                            }
                        }
                        ?>
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

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>
