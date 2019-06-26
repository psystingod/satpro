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
                    $numeroOrden = $_POST["numeroOrden"];
                    $codigoCliente = $_POST["codigoCliente"];
                    $tipoOrden = $_POST["tipoOrden"];
                    $nombreCliente = $_POST['nombreCliente'];
                    $telefonos = $_POST['telefonos'];
                    $municipio = $_POST['municipio'];
                    $tipoActividadCable = $_POST['tipoActividadCable'];
                    $saldoCable = $_POST['saldoCable'];
                    $direccionCable = $_POST['direccionCable'];
                    $colilla = "Amarilla";
                    $fechaTrabajo = $_POST["fechaTrabajo"];
                    $hora = $_POST["hora"];
                    $fechaProgramacion = $_POST["fechaProgramacion"];
                    $responsable = $_POST["responsable"];
                    $observaciones = $_POST["observaciones"];
                    $vendedor = $_POST["vendedor"];
                    $recepcionTv = $_POST["recepcionTv"];
                    $tecnologia = $_POST["tecnologia"];
                    $tipoServicio = $_POST["tipoServicio"];
                    $creadoPor = $_POST['creadoPor'];

                    //$Fecha = date('Y/m/d g:i');

                    $query = "INSERT INTO tbl_ordenes_trabajo(codigoCliente, fechaOrdenTrabajo, tipoOrdenTrabajo, nombreCliente, telefonos, idMunicipio, idActividadCable, saldoCable, direccionCable, fechaTrabajo, hora, fechaProgramacion, idTecnico, observaciones, idVendedor, tecnologia, recepcionTv, tipoServicio, creadoPor                                                                                              )
                              VALUES(:codigoCliente, :fechaOrdenTrabajo, :tipoOrdenTrabajo, :nombreCliente, :telefonos, :idMunicipio, :idActividadCable, :saldoCable, :direccionCable, :fechaTrabajo, :hora, :fechaProgramacion, :idTecnico, :observaciones, :idVendedor, :tecnologia, :recepcionTv, :tipoServicio, :creadoPor)";

                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                                ':codigoCliente' => $codigoCliente,
                                ':fechaOrdenTrabajo' => $fechaOrden,
                                ':tipoOrdenTrabajo' => $tipoOrden,
                                ':nombreCliente' => $nombreCliente,
                                ':telefonos' => $telefonos,
                                ':idMunicipio' => $municipio,
                                ':idActividadCable' => $tipoActividadCable,
                                ':saldoCable' => $saldoCable,
                                ':direccionCable' => $direccionCable,
                                ':fechaTrabajo' => $fechaTrabajo,
                                ':hora' => $hora,
                                ':fechaProgramacion' => $fechaProgramacion,
                                ':idTecnico' => $responsable,
                                ':observaciones' => $observaciones,
                                ':idVendedor' => $vendedor,
                                ':tecnologia' => $tecnologia,
                                ':recepcionTv' => $recepcionTv,
                                ':tipoServicio' => $tipoServicio,
                                ':creadoPor' => $creadoPor
                                ));
                    $numeroOrden = $this->dbConnect->lastInsertId();
                    header('Location: ../ordenTrabajo.php?nOrden='.$numeroOrden);

                }
                catch (Exception $e)
                {
                    print "Error!: " . $e->getMessage() . "</br>";
                    die();
                    header('Location: nuevaOrdenTrabajo.php');
                }
                //ACÁ IRÍA EL FIN DEL IF
            }
            else if ($_POST['tipoServicio'] == 'I') {
                try {
                    date_default_timezone_set('America/El_Salvador');

                    $fechaOrden = $_POST["fechaOrden"];
                    $codigoCliente = $_POST["codigo"];
                    $tipoOrden = $_POST["tipoOrden"];
                    $nombreCliente = $_POST['nombreCliente'];
                    $telefonos = $_POST['telefonos'];
                    $municipio = $_POST['municipio'];
                    $tipoActividadInter = $_POST['tipoActividadInter'];
                    $saldoInter = $_POST['saldoInter'];
                    $direccionInter = $_POST['direccionInter'];
                    $macModem = $_POST["macModem"];
                    $serieModem = $_POST["serieModem"];
                    $velocidad = $_POST["velocidad"];
                    $rx = $_POST["rx"];
                    $tx = $_POST["tx"];
                    $snr = $_POST["snr"];
                    $colilla = $_POST["colilla"];
                    $fechaTrabajo = $_POST["fechaTrabajo"];
                    $hora = $_POST["hora"];
                    $fechaProgramacion = $_POST["fechaProgramacion"];
                    $responsable = $_POST["responsable"];
                    $coordenadas = $_POST["coordenadas"];
                    $observaciones = $_POST["observaciones"];
                    $tecnologia = $_POST["tecnologia"];
                    $marcaModelo = $_POST["marcaModelo"];
                    $nodo = $_POST["nodo"];
                    $vendedor = $_POST["vendedor"];
                    $tipoServicio = $_POST["tipoServicio"];
                    $creadoPor = $_POST['creadoPor'];

                    //$Fecha = date('Y/m/d g:i');

                    $query = "INSERT INTO tbl_ordenes_trabajo(codigoCliente, fechaOrdenTrabajo, tipoOrdenTrabajo, nombreCliente, telefonos, idMunicipio, idActividadInter, saldoInter, direccionInter, macModem, serieModem, velocidad, rx, tx, snr, colilla, fechaTrabajo, hora, fechaProgramacion, idTecnico, coordenadas, observaciones, marcaModelo, tecnologia, nodo, idVendedor, tipoServicio, creadoPor                                                                                              )
                              VALUES(:codigoCliente, :fechaOrdenTrabajo, :tipoOrdenTrabajo, :nombreCliente, :telefonos, :idMunicipio, :idActividadInter, :saldoInter, :direccionInter, :macModem, :serieModem, :velocidad, :rx, :tx, :snr, :colilla, :fechaTrabajo, :hora, :fechaProgramacion, :idTecnico, :coordenadas, :observaciones, :marcaModelo, :tecnologia, :nodo, :idVendedor, :tipoServicio, :creadoPor)";

                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                                ':codigoCliente' => $codigoCliente,
                                ':fechaOrdenTrabajo' => $fechaOrden,
                                ':tipoOrdenTrabajo' => $tipoOrden,
                                ':nombreCliente' => $nombreCliente,
                                ':telefonos' => $telefonos,
                                ':idMunicipio' => $municipio,
                                ':idActividadInter' => $tipoActividadInter,
                                ':saldoInter' => $saldoInter,
                                ':direccionInter' => $direccionInter,
                                ':macModem' => $macModem,
                                ':serieModem' => $serieModem,
                                ':velocidad' => $velocidad,
                                ':rx' => $rx,
                                ':tx' => $tx,
                                ':snr' => $snr,
                                ':colilla' => $colilla,
                                ':fechaTrabajo' => $fechaTrabajo,
                                ':hora' => $hora,
                                ':fechaProgramacion' => $fechaProgramacion,
                                ':idTecnico' => $responsable,
                                ':coordenadas' => $coordenadas,
                                ':observaciones' => $observaciones,
                                ':marcaModelo' => $marcaModelo,
                                ':tecnologia' => $tecnologia,
                                ':nodo' => $nodo,
                                ':idVendedor' => $vendedor,
                                ':tipoServicio' => $tipoServicio,
                                ':creadoPor' => $creadoPor
                                ));

                        $numeroOrden = $this->dbConnect->lastInsertId();
                        header('Location: ../ordenTrabajo.php?nOrden='.$numeroOrden);

                }
                catch (Exception $e)
                {
                    print "Error!: " . $e->getMessage() . "</br>";
                    die();
                    header('Location: nuevaOrdenTrabajo.php');
                }
                //ACÁ IRÍA EL FIN DEL IF
            }

        }
    }
    $save = new GuardarOrden();
    $save->guardar();
?>
