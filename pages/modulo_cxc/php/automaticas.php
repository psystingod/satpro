<?php
    require_once('../../../php/connection.php');
    /**
     * Clase para guardar ventas manuales
     */
    class SuspensionAutomatica extends ConectionDB
    {
        public function SuspensionAutomatica()
        {
            if(!isset($_SESSION))
            {
          	  session_start();
            }
            parent::__construct ($_SESSION['db']);
        }
        public function suspender()
        {

            try {
                $codigos = unserialize(stripslashes($_POST["serial"]));

                $fecha = $_POST["fechaElaborada"];
                $motivo =  $_POST["motivo"];
                $ordena = $_POST["ordena"];
                $obser = $_POST["observaciones"];
                $creadoPor = $_SESSION["nombres"]." ".$_SESSION["apellidos"];
                $counter = 0;
                $tipoOrden = "suspension";
                foreach ($codigos as $cod) {
                    $query = "SELECT nombre, telefonos, direccion, servicio_suspendido, sin_servicio, estado_cliente_in, dia_cobro, dia_corbo_in,mactv,colilla,id_velocidad,serie_modem,mac_modem FROM clientes WHERE cod_cliente="$cod[$counter];
                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($result as $cliente) {
                        if (($cliente["servicio_suspendido"] =="F" || $cliente["servicio_suspendido"] =="") && $cliente["sin_servicio"] == "T" && $cliente["estado_cliente_in"] == 1) {
                            $tipoServicio == "I";
                            $query = "INSERT INTO tbl_ordenes_suspension (codigoCliente,fechaOrden,tipoOrden,diaCobro,nombreCliente,direccion,actividadCable,actividadInter,ordenaSuspension,macModem,serieModem,velocidad,colilla,observaciones,tipoServicio,creadoPor)
                                      VALUES (:codigoCliente,:fechaOrden,:tipoOrden,:diaCobro,:nombreCliente,:direccion,:actividadCable,:actividadInter,:ordenaSuspension,:macModem,:serieModem,:velocidad,:colilla,:observaciones,:tipoServicio,:creadoPor)";

                                      $statement = $this->dbConnect->prepare($query);
                                      $statement->execute(array(
                                                  ':codigoCliente' => $cod[$counter],
                                                  ':fechaOrden' => $fecha,
                                                  ':tipoOrden' => $tipoOrden,
                                                  ':diaCobro' => $cliente["dia_corbo_in"],
                                                  ':nombreCliente' => $cliente["nombre"],
                                                  ':direccion' => $cliente["direccion"],
                                                  ':actividadCable' => $motivo,
                                                  ':actividadInter' => $motivo,
                                                  ':ordenaSuspension' => $ordena,
                                                  ':macModem' => $cliente["mac_modem"],
                                                  ':serieModem' => $cliente["serie_modem"],
                                                  ':velocidad' => $cliente["id_velocidad"],
                                                  ':colilla' => $cliente["colilla"],
                                                  ':observaciones' => $obser,
                                                  ':tipoServicio' => $tipoServicio,
                                                  ':creadoPor' => $creadoPor
                                                  ));

                        }elseif (($cliente["servicio_suspendido"] =="F" || $cliente["servicio_suspendido"] =="") && $cliente["sin_servicio"] == "F" && $cliente["estado_cliente_in"] == 1) {
                            $tipoServicio == "I";
                            $query = "INSERT INTO tbl_ordenes_suspension (codigoCliente,fechaOrden,tipoOrden,diaCobro,nombreCliente,direccion,actividadCable,actividadInter,ordenaSuspension,macModem,serieModem,velocidad,colilla,observaciones,tipoServicio,creadoPor)
                                      VALUES (:codigoCliente,:fechaOrden,:tipoOrden,:diaCobro,:nombreCliente,:direccion,:actividadCable,:actividadInter,:ordenaSuspension,:macModem,:serieModem,:velocidad,:colilla,:observaciones,:tipoServicio,:creadoPor)";
                        }elseif (($cliente["servicio_suspendido"] =="F" || $cliente["servicio_suspendido"] =="") && $cliente["estado_cliente_in"] == 3) {
                            $tipoServicio == "C";
                            $query = "INSERT INTO tbl_ordenes_suspension (codigoCliente,fechaOrden,tipoOrden,diaCobro,nombreCliente,direccion,actividadCable,ordenaSuspension,colilla,mactv,observaciones,tipoServicio,creadoPor)
                                      VALUES (:codigoCliente,:fechaOrden,:tipoOrden,:diaCobro,:nombreCliente,:direccion,:actividadCable,:ordenaSuspension,:mactv,:observaciones,:tipoServicio,:creadoPor)";
                        }


                    }
                    $counter++;
                }

            }
            catch (Exception $e)
            {
                print "Error!: " . $e->getMessage() . "</br>";
                die();
                header('Location: ../ventaManual.php?status=failed');
            }
        }
    }
    $save = new SuspensionAutomatica();
    $save->suspender();
?>
