<?php
    require('../../../php/connection.php');
    /**
     * Clase para ingresar productos al inventario
     */
    class EditarOrden extends ConectionDB
    {
        public function EditarOrden()
        {
            if(!isset($_SESSION))
            {
          	  session_start();
            }
            parent::__construct ($_SESSION['db']);
        }
        public function editar()
        {
            if ($_POST['tipoServicio'] == 'C') {
                try {
                    date_default_timezone_set('America/El_Salvador');
                    $str = $_POST["fechaOrden"];
                    $date = DateTime::createFromFormat('d/m/Y', $str);
                    $fechaOrden = $date->format('Y-m-d');
                    $numeroOrden = $_POST["numeroSuspension"];
                    $codigoCliente = $_POST["codigoCliente"];
                    $tipoOrden = "Suspension";
                    $diaCobro = $_POST['diaCobro'];
                    $nombreCliente = $_POST['nombreCliente'];
                    $ordenaSuspension = $_POST['ordenaSuspensionCable'];
                    //$telefonos = $_POST['telefonos'];
                    $tipoActividadCable = $_POST['tipoActividadCable'];
                    $saldoCable = $_POST['saldoCable'];
                    $direccion = $_POST['direccionCliente'];
                    $mactv = $_POST['mactv'];
                    $colilla = $_POST['colilla'];
                    $str2 = $_POST["fechaSuspension"];
                    if ($str2 >= 7) {
                        $date2 = DateTime::createFromFormat('d/m/Y', $str2);
                        $fechaSuspension = $date2->format('Y-m-d');
                    }else {
                        $fechaSuspension = "";
                    }
                    $responsable = $_POST["responsable"];
                    $observaciones = $_POST["observaciones"];
                    $tipoServicio = $_POST["tipoServicio"];
                    $creadoPor = $_POST['creadoPor'];

                    //$Fecha = date('Y/m/d g:i');
                    $query = "UPDATE tbl_ordenes_suspension SET codigoCliente=:codigoCliente, fechaOrden=:fechaOrden, tipoOrden=:tipoOrden, diaCobro=:diaCobro, nombreCliente=:nombreCliente, actividadCable=:idActividadCable, saldoCable=:saldoCable, direccion=:direccion, fechaSuspension=:fechaSuspension, ordenaSuspension=:ordenaSuspension, idTecnico=:idTecnico, mactv=:mactv, observaciones=:observaciones, tipoServicio=:tipoServicio, creadoPor=:creadoPor WHERE idOrdenSuspension=:idOrdenSuspension";

                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                                ':codigoCliente' => $codigoCliente,
                                ':fechaOrden' => $fechaOrden,
                                ':tipoOrden' => $tipoOrden,
                                ':diaCobro' => $diaCobro,
                                ':nombreCliente' => $nombreCliente,
                                //':telefonos' => $telefonos,
                                ':idActividadCable' => $tipoActividadCable,
                                ':saldoCable' => $saldoCable,
                                ':direccion' => $direccion,
                                ':fechaSuspension' => $fechaSuspension,
                                ':ordenaSuspension' => $ordenaSuspension,
                                ':idTecnico' => $responsable,
                                ':mactv' => $mactv,
                                ':observaciones' => $observaciones,
                                ':tipoServicio' => $tipoServicio,
                                ':creadoPor' => $creadoPor,
                                ':idOrdenSuspension' => $numeroOrden
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

                    $str = $_POST["fechaOrden"];
                    $date = DateTime::createFromFormat('d/m/Y', $str);
                    $fechaOrden = $date->format('Y-m-d');
                    $numeroOrden = $_POST["numeroSuspension"];
                    $codigoCliente = $_POST["codigoCliente"];
                    $tipoOrden = "Suspension";
                    $diaCobro = $_POST["diaCobro"];
                    $nombreCliente = $_POST['nombreCliente'];
                    //$telefonos = $_POST['telefonos'];
                    $tipoActividadInter = $_POST['tipoActividadInternet'];
                    $saldoInter = $_POST['saldoInternet'];
                    $direccion = $_POST['direccionCliente'];
                    $macModem = $_POST["macModem"];
                    $serieModem = $_POST["serieModem"];
                    $velocidad = $_POST["velocidad"];
                    $ordenaSuspension = $_POST["ordenaSuspensionInter"];
                    $colilla = $_POST["colilla"];
                    $str2 = $_POST["fechaSuspension"];
                    if ($str2 >= 7) {
                        $date2 = DateTime::createFromFormat('d/m/Y', $str2);
                        $fechaSuspension = $date2->format('Y-m-d');
                    }else {
                        $fechaSuspension = "";
                    }
                    $responsable = $_POST["responsable"];
                    $observaciones = $_POST["observaciones"];
                    $tipoServicio = $_POST["tipoServicio"];
                    $creadoPor = $_POST['creadoPor'];

                    //$Fecha = date('Y/m/d g:i');

                    $query = "UPDATE tbl_ordenes_suspension SET codigoCliente=:codigoCliente, fechaOrden=:fechaOrden, tipoOrden=:tipoOrden, diaCobro=:diaCobro, nombreCliente=:nombreCliente,
                    actividadInter=:idActividadInter, tipoServicio=:tipoServicio, saldoInter=:saldoInter, ordenaSuspension=:ordenaSuspension, direccion=:direccion, fechaSuspension=:fechaSuspension, idTecnico=:idTecnico, observaciones=:observaciones, macModem=:macModem, serieModem=:serieModem, velocidad=:velocidad, colilla=:colilla, creadoPor=:creadoPor WHERE idOrdenSuspension=:idOrdenSuspension";

                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                                ':codigoCliente' => $codigoCliente,
                                ':fechaOrden' => $fechaOrden,
                                ':tipoOrden' => $tipoOrden,
                                ':diaCobro' => $diaCobro,
                                ':nombreCliente' => $nombreCliente,
                                ':idActividadInter' => $tipoActividadInter,
                                ':tipoServicio' => $tipoServicio,
                                ':saldoInter' => $saldoInter,
                                ':ordenaSuspension' => $ordenaSuspension,
                                ':direccion' => $direccion,
                                ':macModem' => $macModem,
                                ':serieModem' => $serieModem,
                                ':velocidad' => $velocidad,
                                ':colilla' => $colilla,
                                ':fechaSuspension' => $fechaSuspension,
                                ':idTecnico' => $responsable,
                                ':observaciones' => $observaciones,
                                ':tipoServicio' => $tipoServicio,
                                ':creadoPor' => $creadoPor,
                                ':idOrdenSuspension' => $numeroOrden
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
