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
                    if (isset($_POST["fechaOrden"])) {
                        $str = $_POST["fechaOrden"];
                        $date = DateTime::createFromFormat('d/m/Y', $str);
                        $fechaOrden = $date->format('Y-m-d');
                    }
                    else {
                        $fechaOrden = "";
                    }

                    /*if (!empty($_POST["ultSuspCable"])) {
                        $str1 = $_POST["ultSuspCable"];
                        $date1 = DateTime::createFromFormat('d/m/Y', $str1);
                        $ultSuspCable = $date1->format('Y-m-d');
                    }
                    else {
                        $ultSuspCable = "";
                    }*/

                    $numeroOrden = $_POST["numeroTraslado"];
                    $diaCobro = $_POST["diaCobro"];
                    $codigoCliente = $_POST["codigoCliente"];
                    $tipoOrden = "Traslado";
                    $nombreCliente = $_POST['nombreCliente'];
                    $direccion = $_POST['direccionCliente'];
                    $direccionTraslado = $_POST['direccionTraslado'];
                    $telefonos = $_POST['telefonos'];
                    $idDepartamento = $_POST['departamento'];
                    $idMunicipio = $_POST['municipio'];
                    $idColonia = $_POST['colonia'];
                    //$municipio = $_POST['municipio'];
                    //$tipoReconexCable = $_POST['tipoReconexCable']; //Motivo
                    $saldoCable = $_POST['saldoCable'];
                    //$ordenaSuspension = $_POST['ordenaSuspensionCable'];
                    $colilla = ucwords($_POST['colilla']);
                    $fechaTraslado = $_POST["fechaTraslado"];
                    //$responsable = $_POST["responsable"];
                    $mactv = $_POST["mactv"];
                    $observaciones = $_POST["observaciones"];
                    $tipoServicio = $_POST["tipoServicio"];
                    $responsable = $_POST["responsable"];
                    $creadoPor = $_POST['creadoPor'];

                    //$Fecha = date('Y/m/d g:i');
                    $query = "UPDATE tbl_ordenes_traslado SET codigoCliente=:codigoCliente, fechaOrden=:fechaOrden, tipoOrden=:tipoOrden, saldoCable=:saldoCable, diaCobro=:diaCobro, nombreCliente=:nombreCliente, direccion=:direccion, direccionTraslado=:direccionTraslado, idDepartamento=:idDepartamento, idMunicipio=:idMunicipio, idColonia=:idColonia, telefonos=:telefonos,
                                     colilla=:colilla, fechaTraslado=:fechaTraslado, idTecnico=:idTecnico, mactv=:mactv, observaciones=:observaciones, tipoServicio=:tipoServicio, creadoPor=:creadoPor WHERE idOrdenTraslado=:idOrdenTraslado";

                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                                ':codigoCliente' => $codigoCliente,
                                ':fechaOrden' => $fechaOrden,
                                ':tipoOrden' => $tipoOrden,
                                ':saldoCable' => $saldoCable,
                                ':diaCobro' => $diaCobro,
                                ':nombreCliente' => $nombreCliente,
                                ':direccion' => $direccion,
                                ':direccionTraslado' => $direccionTraslado,
                                ':idDepartamento' => $idDepartamento,
                                ':idMunicipio' => $idMunicipio,
                                ':idColonia' => $idColonia,
                                ':telefonos' => $telefonos,
                                ':colilla' => $colilla,
                                //':tipoReconexCable' => $tipoReconexCable,

                                //':fechaReconexCable' => $fechaReconexCable,

                                //':ultSuspCable' => $ultSuspCable,
                                ':fechaTraslado' => $fechaTraslado,
                                ':idTecnico' => $responsable,
                                ':mactv' => $mactv,

                                ':observaciones' => $observaciones,
                                ':tipoServicio' => $tipoServicio,
                                ':idTecnico' => $responsable,
                                ':creadoPor' => $creadoPor,
                                ':idOrdenTraslado' => $numeroOrden,
                                ));

                    header('Location: ../ordenTraslado.php?nOrden='.$numeroOrden);

                }
                catch (Exception $e)
                {
                    print "Error!: " . $e->getMessage() . "</br>";
                    die();
                    header('Location: ../ordenTraslado.php?status=failedEdit');
                }

            }
            else if ($_POST['tipoServicio'] == 'I') {
                try {
                    date_default_timezone_set('America/El_Salvador');
                    if (isset($_POST["fechaOrden"])) {
                        $str = $_POST["fechaOrden"];
                        $date = DateTime::createFromFormat('d/m/Y', $str);
                        $fechaOrden = $date->format('Y-m-d');
                    }
                    else {
                        $fechaOrden = "";
                    }

                    /*if (!empty($_POST["ultSuspCable"])) {
                        $str1 = $_POST["ultSuspCable"];
                        $date1 = DateTime::createFromFormat('d/m/Y', $str1);
                        $ultSuspCable = $date1->format('Y-m-d');
                    }
                    else {
                        $ultSuspCable = "";
                    }*/

                    $numeroOrden = $_POST["numeroTraslado"];
                    $diaCobro = $_POST["diaCobro"];
                    $codigoCliente = $_POST["codigoCliente"];
                    $tipoOrden = "Traslado";
                    $nombreCliente = $_POST['nombreCliente'];
                    $direccion = $_POST['direccionCliente'];
                    $direccionTraslado = $_POST['direccionTraslado'];
                    $telefonos = $_POST['telefonos'];
                    //$municipio = $_POST['municipio'];
                    //$tipoReconexCable = $_POST['tipoReconexCable']; //Motivo
                    $saldoCable = $_POST['saldoCable'];
                    //$ordenaSuspension = $_POST['ordenaSuspensionCable'];
                    $colilla = ucwords($_POST['colilla']);
                    $fechaTraslado = $_POST["fechaTraslado"];
                    //$responsable = $_POST["responsable"];
                    $mactv = $_POST["mactv"];
                    $idDepartamento = $_POST['departamento'];
                    $idMunicipio = $_POST['municipio'];
                    $idColonia = $_POST['colonia'];
                    $macModem = $_POST["macModem"];
                    $serieModem = $_POST["serieModem"];
                    $saldoInter = $_POST['saldoInter'];
                    //$velocidad = $_POST["velocidad"];
                    $observaciones = $_POST["observaciones"];
                    $tipoServicio = $_POST["tipoServicio"];
                    $responsable = $_POST["responsable"];
                    $creadoPor = $_POST['creadoPor'];

                    //$Fecha = date('Y/m/d g:i');
                    $query = "UPDATE tbl_ordenes_traslado SET codigoCliente=:codigoCliente, fechaOrden=:fechaOrden, tipoOrden=:tipoOrden, saldoInter=:saldoInter, diaCobro=:diaCobro, nombreCliente=:nombreCliente, direccion=:direccion, direccionTraslado=:direccionTraslado, idDepartamento=:idDepartamento, idMunicipio=:idMunicipio, idColonia=:idColonia, telefonos=:telefonos,
                                     macModem=:macModem,serieModem=:serieModem,colilla=:colilla, fechaTraslado=:fechaTraslado, idTecnico=:idTecnico, mactv=:mactv, observaciones=:observaciones, tipoServicio=:tipoServicio, creadoPor=:creadoPor WHERE idOrdenTraslado=:idOrdenTraslado";

                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                                ':codigoCliente' => $codigoCliente,
                                ':fechaOrden' => $fechaOrden,
                                ':tipoOrden' => $tipoOrden,
                                ':saldoInter' => $saldoInter,
                                ':diaCobro' => $diaCobro,
                                ':nombreCliente' => $nombreCliente,
                                ':direccion' => $direccion,
                                ':direccionTraslado' => $direccionTraslado,
                                ':idDepartamento' => $idDepartamento,
                                ':idMunicipio' => $idMunicipio,
                                ':idColonia' => $idColonia,
                                ':telefonos' => $telefonos,
                                ':macModem' => $macModem,
                                ':serieModem' => $serieModem,
                                //':velocidad' => $velocidad,
                                ':colilla' => $colilla,
                                //':tipoReconexCable' => $tipoReconexCable,

                                //':fechaReconexCable' => $fechaReconexCable,

                                //':ultSuspCable' => $ultSuspCable,
                                ':fechaTraslado' => $fechaTraslado,
                                ':idTecnico' => $responsable,
                                ':mactv' => $mactv,

                                ':observaciones' => $observaciones,
                                ':tipoServicio' => $tipoServicio,
                                ':idTecnico' => $responsable,
                                ':creadoPor' => $creadoPor,
                                ':idOrdenTraslado' => $numeroOrden,
                                ));

                    header('Location: ../ordenTraslado.php?nOrden='.$numeroOrden);

                }
                catch (Exception $e)
                {
                    print "Error!: " . $e->getMessage() . "</br>";
                    die();
                    header('Location: ../ordenTraslado.php?status=failedEdit');
                }
                //ACÁ IRÍA EL FIN DEL IF
            }

        }
    }
    $edit = new EditarOrden();
    $edit->editar();
?>
