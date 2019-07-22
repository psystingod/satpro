<?php
    require('../../../php/connection.php');
    /**
     * Clase para ingresar productos al inventario
     */
    class GuardarOrden extends ConectionDB
    {
        public function GuardarOrden()
        {
            parent::__construct ();
        }
        public function guardar()
        {
            if ($_POST['tipoServicio'] == 'C') {
                try {
                    date_default_timezone_set('America/El_Salvador');
                    $fechaOrden = date_format(date_create($_POST["fechaOrden"]), 'Y-m-d');
                    $numeroOrden = $_POST["numeroSuspension"];
                    $codigoCliente = $_POST["codigoCliente"];
                    $tipoOrden = "Suspension";
                    $nombreCliente = $_POST['nombreCliente'];
                    $direccion = $_POST['direccionCliente'];
                    //$telefonos = $_POST['telefonos'];
                    //$municipio = $_POST['municipio'];
                    $tipoActividadCable = $_POST['tipoActividadCable']; //Motivo
                    $saldoCable = $_POST['saldoCable'];
                    $ordenaSuspension = $_POST['ordenaSuspensionCable'];
                    $colilla = "Amarilla";
                    $fechaSuspension = date_format(date_create($_POST["fechaSuspension"]), 'Y-m-d');
                    $responsable = $_POST["responsable"];
                    $observaciones = $_POST["observaciones"];
                    $tipoServicio = $_POST["tipoServicio"];
                    $creadoPor = $_POST['creadoPor'];

                    //$Fecha = date('Y/m/d g:i');

                    $query = "INSERT INTO tbl_ordenes_suspension(codigoCliente, fechaOrden, tipoOrden, nombreCliente, actividadCable, saldoCable, ordenaSuspension, direccion, fechaSuspension, idTecnico, colilla, observaciones, tipoServicio, creadoPor)
                              VALUES(:codigoCliente, :fechaOrden, :tipoOrden, :nombreCliente, :idActividadCable, :saldoCable, :ordenaSuspension, :direccion, :fechaSuspension, :idTecnico, :colilla, :observaciones, :tipoServicio, :creadoPor)";

                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                                ':codigoCliente' => $codigoCliente,
                                ':fechaOrden' => $fechaOrden,
                                ':tipoOrden' => $tipoOrden,
                                ':nombreCliente' => $nombreCliente,
                                ':idActividadCable' => $tipoActividadCable,
                                ':saldoCable' => $saldoCable,
                                ':direccion' => $direccion,
                                ':ordenaSuspension' => $ordenaSuspension,
                                ':fechaSuspension' => $fechaSuspension,
                                ':idTecnico' => $responsable,
                                ':colilla' => $colilla,
                                ':observaciones' => $observaciones,
                                ':tipoServicio' => $tipoServicio,
                                ':creadoPor' => $creadoPor
                                ));
                    $numeroOrden = $this->dbConnect->lastInsertId();
                    header('Location: ../ordenSuspension.php?nOrden='.$numeroOrden);

                }
                catch (Exception $e)
                {
                    print "Error!: " . $e->getMessage() . "</br>";
                    die();
                    header('Location: OrdenSuspension?status=failed.php');
                }
                //ACÁ IRÍA EL FIN DEL IF
            }
            else if ($_POST['tipoServicio'] == 'I') {
                try {
                    date_default_timezone_set('America/El_Salvador');
                    $fechaOrden = date_format(date_create($_POST["fechaOrden"]), 'Y-m-d');
                    $numeroOrden = $_POST["numeroOrden"];
                    $codigoCliente = $_POST["codigoCliente"];
                    $tipoOrden = "Suspension";
                    $nombreCliente = $_POST['nombreCliente'];
                    $direccion = $_POST['direccion'];
                    //$telefonos = $_POST['telefonos'];
                    //$municipio = $_POST['municipio'];
                    $tipoActividadInter = $_POST['tipoActividadInter']; //Motivo
                    $saldoInter = $_POST['saldoInternet'];
                    $serieModem = $_POST['serieModem'];
                    $macModem = $_POST['macModem'];
                    $velocidad = $_POST['velocidad'];
                    $ordenaSuspension = $_POST['ordenaSuspensionInter'];
                    $colilla = "Roja";
                    $fechaSuspension = date_format(date_create($_POST["fechaSuspension"]), 'Y-m-d');
                    $responsable = $_POST["responsable"];
                    $observaciones = $_POST["observaciones"];
                    $tipoServicio = $_POST["tipoServicio"];
                    $creadoPor = $_POST['creadoPor'];

                    //$Fecha = date('Y/m/d g:i');

                    $query = "INSERT INTO tbl_ordenes_suspension(codigoCliente, fechaOrden, tipoOrden, nombreCliente, actividadInter, saldoInter, ordenaSuspension, macModem, serieModem, velocidad, direccion, fechaSuspension, idTecnico, colilla, observaciones, tipoServicio, creadoPor)
                              VALUES(:codigoCliente, :fechaOrden, :tipoOrden, :nombreCliente, :idActividadInter, :saldoInter, :ordenaSuspension, :macModem, :serieModem, :velocidad, :direccion, :fechaSuspension, :idTecnico, :colilla, :observaciones, :tipoServicio, :creadoPor)";

                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                                ':codigoCliente' => $codigoCliente,
                                ':fechaOrden' => $fechaOrden,
                                ':tipoOrden' => $tipoOrden,
                                ':nombreCliente' => $nombreCliente,
                                ':idActividadInter' => $tipoActividadInter,
                                ':saldoInter' => $saldoInter,
                                ':direccion' => $direccion,
                                ':ordenaSuspension' => $ordenaSuspension,
                                ':macModem' => $macModem,
                                ':serieModem' => $serieModem,
                                ':velocidad' => $velocidad,
                                ':fechaSuspension' => $fechaSuspension,
                                ':idTecnico' => $responsable,
                                ':colilla' => $colilla,
                                ':observaciones' => $observaciones,
                                ':tipoServicio' => $tipoServicio,
                                ':creadoPor' => $creadoPor
                                ));
                    $numeroOrden = $this->dbConnect->lastInsertId();
                    header('Location: ../ordenSuspension.php?nOrden='.$numeroOrden);

                }
                catch (Exception $e)
                {
                    print "Error!: " . $e->getMessage() . "</br>";
                    die();
                    header('Location: OrdenSuspension?status=failed.php');
                }
                //ACÁ IRÍA EL FIN DEL IF
            }

        }
    }
    $save = new GuardarOrden();
    $save->guardar();
?>
