<?php
    require('../../../php/connection.php');
    /**
     * Clase para ingresar productos al inventario
     */
    class EditarOrden extends ConectionDB
    {
        public function EditarOrden()
        {
            parent::__construct ();
        }
        public function editar()
        {
            if ($_POST['tipoServicio'] == 'C') {
                try {
                    date_default_timezone_set('America/El_Salvador');
                    $fechaOrden = date_format(date_create($_POST["fechaOrden"]), 'Y-m-d');
                    $numeroOrden = $_POST["numeroSuspension"];
                    $codigoCliente = $_POST["codigoCliente"];
                    $tipoOrden = "Suspensión";
                    $nombreCliente = $_POST['nombreCliente'];
                    $ordenaSuspension = $_POST['ordenaSuspensionCable'];
                    //$telefonos = $_POST['telefonos'];
                    $tipoActividadCable = $_POST['tipoActividadCable'];
                    $saldoCable = $_POST['saldoCable'];
                    $direccion = $_POST['direccionCliente'];
                    $colilla = $_POST['colilla'];
                    $fechaSuspension = date_format(date_create($_POST["fechaSuspension"]), 'Y-m-d');
                    $responsable = $_POST["responsable"];
                    $observaciones = $_POST["observaciones"];
                    $tipoServicio = $_POST["tipoServicio"];
                    $creadoPor = $_POST['creadoPor'];

                    //$Fecha = date('Y/m/d g:i');
                    $query = "UPDATE tbl_ordenes_suspension SET codigoCliente=:codigoCliente, fechaOrden=:fechaOrden, tipoOrden=:tipoOrden, nombreCliente=:nombreCliente, actividadCable=:idActividadCable, saldoCable=:saldoCable, direccion=:direccion, fechaSuspension=:fechaSuspension, ordenaSuspension=:ordenaSuspension, idTecnico=:idTecnico, observaciones=:observaciones, tipoServicio=:tipoServicio, creadoPor=:creadoPor WHERE codigoCliente=:codigoCliente";

                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                                ':codigoCliente' => $codigoCliente,
                                ':fechaOrden' => $fechaOrden,
                                ':tipoOrden' => $tipoOrden,
                                ':nombreCliente' => $nombreCliente,
                                //':telefonos' => $telefonos,
                                ':idActividadCable' => $tipoActividadCable,
                                ':saldoCable' => $saldoCable,
                                ':direccion' => $direccion,
                                ':fechaSuspension' => $fechaSuspension,
                                ':ordenaSuspension' => $ordenaSuspension,
                                ':idTecnico' => $responsable,
                                ':observaciones' => $observaciones,
                                ':tipoServicio' => $tipoServicio,
                                ':creadoPor' => $creadoPor
                                ));
                    //$numeroOrden = $this->dbConnect->lastInsertId();
                    header('Location: ../ordenSuspension.php?nOrden='.$numeroOrden);

                }
                catch (Exception $e)
                {
                    print "Error!: " . $e->getMessage() . "</br>";
                    die();
                    header('Location: ../ordenSuspension.php?status=failedEdit');
                }

            }
            else if ($_POST['tipoServicio'] == 'I') {
                try {
                    date_default_timezone_set('America/El_Salvador');

                    $fechaOrden = date_format(date_create($_POST["fechaOrden"]), 'Y-m-d');
                    $numeroOrden = $_POST["numeroOrden"];
                    $codigoCliente = $_POST["codigoCliente"];
                    $tipoOrden = $_POST["tipoOrden"];
                    $nombreCliente = $_POST['nombreCliente'];
                    //$telefonos = $_POST['telefonos'];
                    $tipoActividadInter = $_POST['tipoActividadInternet'];
                    $saldoInter = $_POST['saldoInternet'];
                    $direccion = $_POST['direccionCliente'];
                    $macModem = $_POST["macModem"];
                    $serieModem = $_POST["serieModem"];
                    $velocidad = $_POST["velocidad"];
                    $colilla = $_POST["colilla"];
                    $fechaSuspension = $_POST["fechaSuspension"];
                    $responsable = $_POST["responsable"];
                    $observaciones = $_POST["observaciones"];
                    $tipoServicio = $_POST["tipoServicio"];
                    $creadoPor = $_POST['creadoPor'];

                    //$Fecha = date('Y/m/d g:i');

                    $query = "UPDATE tbl_ordenes_suspension SET codigoCliente=:codigoCliente, fechaOrden=:fechaOrden, tipoOrdenTrabajo=:tipoOrdenTrabajo, nombreCliente=:nombreCliente,
                    actividadInter=:idActividadInter, tipoServicio=:tipoServicio, saldoInter=:saldoInter, direccion=:direccion, fechaSuspension=:fechaSuspension, idTecnico=:idTecnico, observaciones=:observaciones, macModem=:macModem, serieModem=:serieModem, velocidad=:velocidad, colilla=:colilla, creadoPor=:creadoPor WHERE codigoCliente=:codigoCliente";

                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                                ':codigoCliente' => $codigoCliente,
                                ':fechaOrden' => $fechaOrden,
                                ':tipoOrden' => $tipoOrden,
                                ':nombreCliente' => $nombreCliente,
                                ':telefonos' => $telefonos,
                                ':idActividadInter' => $tipoActividadInter,
                                ':tipoServicio' => $tipoServicio,
                                ':saldoInter' => $saldoInter,
                                ':direccion' => $direccion,
                                ':macModem' => $macModem,
                                ':serieModem' => $serieModem,
                                ':velocidad' => $velocidad,
                                ':colilla' => $colilla,
                                ':fechaSuspension' => $fechaSuspension,
                                ':idTecnico' => $responsable,
                                ':observaciones' => $observaciones,
                                ':tipoServicio' => $tipoServicio,
                                ':creadoPor' => $creadoPor
                                ));

                        //$numeroOrden = $this->dbConnect->lastInsertId();
                        header('Location: ../ordenSuspension.php?nOrden='.$numeroOrden);

                }
                catch (Exception $e)
                {
                    print "Error!: " . $e->getMessage() . "</br>";
                    die();
                    header('Location: ../ordenSuspension.php?status=failedEdit');
                }
                //ACÁ IRÍA EL FIN DEL IF
            }

        }
    }
    $edit = new EditarOrden();
    $edit->editar();
?>
