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
                    $str = $_POST["fechaOrden"];
                    $date = DateTime::createFromFormat('d/m/Y', $str);
                    $fechaOrden = $date->format('Y-m-d');
                    $numeroOrden = $_POST["numeroSuspension"];
                    $diaCobro = $_POST["diaCobro"];
                    $codigoCliente = $_POST["codigoCliente"];
                    $tipoOrden = "Suspension";
                    $nombreCliente = $_POST['nombreCliente'];
                    $direccion = $_POST['direccionCliente'];
                    //$telefonos = $_POST['telefonos'];
                    //$municipio = $_POST['municipio'];
                    $tipoActividadCable = $_POST['tipoActividadCable']; //Motivo
                    $saldoCable = $_POST['saldoCable'];
                    $ordenaSuspension = $_POST['ordenaSuspensionCable'];
                    $colilla = ucwords($_POST['colilla']);
                    $str2 = $_POST["fechaSuspension"];
                    if ($str2 >= 7) {
                        $date2 = DateTime::createFromFormat('d/m/Y', $str2);
                        $fechaSuspension = $date2->format('Y-m-d');
                    }else {
                        $fechaSuspension = "";
                    }

                    $responsable = $_POST["responsable"];
                    $mactv = $_POST["mactv"];
                    $observaciones = $_POST["observaciones"];
                    $tipoServicio = $_POST["tipoServicio"];
                    $creadoPor = $_POST['creadoPor'];

                    //$Fecha = date('Y/m/d g:i');

                    $query = "INSERT INTO tbl_ordenes_suspension(codigoCliente, fechaOrden, tipoOrden, diaCobro, nombreCliente, actividadCable, saldoCable, ordenaSuspension, direccion, fechaSuspension, idTecnico, mactv, colilla, observaciones, tipoServicio, creadoPor)
                              VALUES(:codigoCliente, :fechaOrden, :tipoOrden, :diaCobro, :nombreCliente, :idActividadCable, :saldoCable, :ordenaSuspension, :direccion, :fechaSuspension, :idTecnico, :mactv, :colilla, :observaciones, :tipoServicio, :creadoPor)";

                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                                ':codigoCliente' => $codigoCliente,
                                ':fechaOrden' => $fechaOrden,
                                ':tipoOrden' => $tipoOrden,
                                ':diaCobro' => $diaCobro,
                                ':nombreCliente' => $nombreCliente,
                                ':idActividadCable' => $tipoActividadCable,
                                ':saldoCable' => $saldoCable,
                                ':direccion' => $direccion,
                                ':ordenaSuspension' => $ordenaSuspension,
                                ':fechaSuspension' => $fechaSuspension,
                                ':idTecnico' => $responsable,
                                ':mactv' => $mactv,
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
                    $str = $_POST["fechaOrden"];
                    $date = DateTime::createFromFormat('d/m/Y', $str);
                    $fechaOrden = $date->format('Y-m-d');
                    $numeroOrden = $_POST["numeroOrden"];
                    $codigoCliente = $_POST["codigoCliente"];
                    $tipoOrden = "Suspension";
                    $diaCobro = $_POST["diaCobro"];
                    $nombreCliente = $_POST['nombreCliente'];
                    $direccion = $_POST['direccionCliente'];
                    //$telefonos = $_POST['telefonos'];
                    //$municipio = $_POST['municipio'];
                    $tipoActividadInter = $_POST['tipoActividadInternet']; //Motivo
                    $saldoInter = $_POST['saldoInternet'];
                    $serieModem = $_POST['serieModem'];
                    $macModem = $_POST['macModem'];
                    $velocidad = $_POST['velocidad'];
                    $ordenaSuspension = $_POST['ordenaSuspensionInter'];
                    $colilla = ucwords($_POST['colilla']);
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

                    $query = "INSERT INTO tbl_ordenes_suspension(codigoCliente, fechaOrden, tipoOrden, diaCobro, nombreCliente, actividadInter, saldoInter, ordenaSuspension, macModem, serieModem, velocidad, direccion, fechaSuspension, idTecnico, colilla, observaciones, tipoServicio, creadoPor)
                              VALUES(:codigoCliente, :fechaOrden, :tipoOrden, :diaCobro, :nombreCliente, :idActividadInter, :saldoInter, :ordenaSuspension, :macModem, :serieModem, :velocidad, :direccion, :fechaSuspension, :idTecnico, :colilla, :observaciones, :tipoServicio, :creadoPor)";

                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                                ':codigoCliente' => $codigoCliente,
                                ':fechaOrden' => $fechaOrden,
                                ':tipoOrden' => $tipoOrden,
                                ':diaCobro' => $diaCobro,
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
