<?php
    require('../../../php/connection.php');
    /**
     * Clase para ingresar productos al inventario
     */
    class GuardarGestion extends ConectionDB
    {
        public function GuardarGestion()
        {
            if(!isset($_SESSION))
            {
          	  session_start();
            }
            parent::__construct ($_SESSION['db']);
        }
        public function guardar()
        {
            try {
                session_start();
                date_default_timezone_set('America/El_Salvador');
                if (isset($_POST["fechaGestion"])) {
                    $fechaGestion = $_POST["fechaGestion"];
                }
                else {
                    $fechaOrden = "";
                }
                $gestion = $_POST["tipogestion"];
                $fechaSuspension = $_POST["fechaSuspension"];
                $idGestion = $_GET["idGestion"];
                $descripcion = $_POST["descripcion"];
                $tipoServicio = $_POST["tipoServicio"];
                $creadoPor = $_SESSION["user"];

                $this->dbConnect->beginTransaction();
                $query = "INSERT INTO tbl_gestion_clientes (fechaGestion,descripcion,gestion,fechaSuspension,tipoServicio,creadoPor,idGestionGeneral)
                          VALUES (:fechaGestion, :descripcion, :gestion, :fechaSuspension, :tipoServicio, :creadoPor, :idGestionGeneral)";

                $statement = $this->dbConnect->prepare($query);
                $statement->execute(array(
                            ':fechaGestion' => $fechaGestion,
                            ':descripcion' => $descripcion,
                            ':gestion' => $gestion,
                            ':fechaSuspension' => $fechaSuspension,
                            ':tipoServicio' => $tipoServicio,
                            ':creadoPor' => $creadoPor,
                            ':idGestionGeneral' => $idGestion
                            ));
                sleep(0.5);
                //$idGestion = $this->dbConnect->lastInsertId();
                $this->dbConnect->commit();
                header('Location: ../gestionCobros.php?idGestion='.$idGestion);

            }
            catch (Exception $e)
            {
                print "Error!: " . $e->getMessage() . "</br>";
                die();
                header('Location: ../gestionCobros.php?status=failedEdit');
            }
        }
    }
    $save = new GuardarGestion();
    $save->guardar();
?>
