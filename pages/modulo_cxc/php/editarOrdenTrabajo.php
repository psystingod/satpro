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

                    $query = "UPDATE tbl_ordenes_trabajo SET codigoCliente=:codigoCliente, fechaOrdenTrabajo=:fechaOrdenTrabajo, tipoOrdenTrabajo=:tipoOrdenTrabajo, nombreCliente=:nombreCliente, telefonos=:telefonos, idMunicipio=:idMunicipio, actividadCable=:idActividadCable, saldoCable=:saldoCable, direccionCable=:direccionCable, fechaTrabajo=:fechaTrabajo, hora=:hora, fechaProgramacion=:fechaProgramacion, idTecnico=:idTecnico, observaciones=:observaciones, idVendedor=:idVendedor, tecnologia=:tecnologia, recepcionTv=:recepcionTv, tipoServicio=:tipoServicio, creadoPor=:creadoPor WHERE codigoCliente=:codigoCliente";

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
                    //$numeroOrden = $this->dbConnect->lastInsertId();
                    header('Location: ../ordenTrabajo.php?nOrden='.$numeroOrden);

                }
                catch (Exception $e)
                {
                    print "Error!: " . $e->getMessage() . "</br>";
                    die();
                    header('Location: ../ordenTrabajo.php?status=failedEdit');
                }
                //ACÁ IRÍA EL FIN DEL IF
            }
            else if ($_POST['tipoServicio'] == 'I') {
                try {
                    date_default_timezone_set('America/El_Salvador');

                    $fechaOrden = $_POST["fechaOrden"];
                    $numeroOrden = $_POST["numeroOrden"];
                    $codigoCliente = $_POST["codigoCliente"];
                    $tipoOrden = $_POST["tipoOrden"];
                    $nombreCliente = $_POST['nombreCliente'];
                    $telefonos = $_POST['telefonos'];
                    $municipio = $_POST['municipio'];
                    $tipoActividadInter = $_POST['tipoActividadInternet'];
                    $saldoInter = $_POST['saldoInternet'];
                    $direccionInter = $_POST['direccionInternet'];
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

                    $query = "UPDATE tbl_ordenes_trabajo SET codigoCliente=:codigoCliente, fechaOrdenTrabajo=:fechaOrdenTrabajo, tipoOrdenTrabajo=:tipoOrdenTrabajo, nombreCliente=:nombreCliente,
                    telefonos=:telefonos, idMunicipio=:idMunicipio, actividadInter=:idActividadInter, tipoServicio=:tipoServicio, saldoInter=:saldoInter, direccionInter=:direccionInter, fechaTrabajo=:fechaTrabajo, hora=:hora, fechaProgramacion=:fechaProgramacion, idTecnico=:idTecnico, observaciones=:observaciones, idVendedor=:idVendedor, tecnologia=:tecnologia, macModem=:macModem, serieModem=:serieModem, velocidad=:velocidad, rx=:rx, tx=:tx, snr=:snr, colilla=:colilla, marcaModelo=:marcaModelo, nodo=:nodo, coordenadas=:coordenadas, creadoPor=:creadoPor WHERE codigoCliente=:codigoCliente";

                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                                ':codigoCliente' => $codigoCliente,
                                ':fechaOrdenTrabajo' => $fechaOrden,
                                ':tipoOrdenTrabajo' => $tipoOrden,
                                ':nombreCliente' => $nombreCliente,
                                ':telefonos' => $telefonos,
                                ':idMunicipio' => $municipio,
                                ':idActividadInter' => $tipoActividadInter,
                                ':tipoServicio' => $tipoServicio,
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

                        //$numeroOrden = $this->dbConnect->lastInsertId();
                        header('Location: ../ordenTrabajo.php?nOrden='.$numeroOrden);

                }
                catch (Exception $e)
                {
                    print "Error!: " . $e->getMessage() . "</br>";
                    die();
                    header('Location: ../ordenTrabajo.php?status=failedEdit');
                }
                //ACÁ IRÍA EL FIN DEL IF
            }

        }
    }
    $edit = new EditarOrden();
    $edit->editar();
?>
