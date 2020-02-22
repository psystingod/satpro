<?php
/*
 * Clase PHP para llamar a todos los registros de clientes en la BD
 */
    require_once('connection.php');
    /**
     * Clase para tarer los datos de reportes de la base de datos
     */
    class Dashboard extends ConectionDB
    {
        public function Dashboard()
        {
            if(!isset($_SESSION))
            {
                session_start();
            }
            parent::__construct ($_SESSION['db']);
        }
        public function getActiveClients()
        {
            try {

                    return $this->getTotalClientsC() + $this->getTotalClientsI() + $this->getTotalClientsCI();


            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }

        public function getDailyIncomes()
        {
            try {
                    date_default_timezone_set('America/El_Salvador');
                    $date = date("Y-m-d");
                    //$query = "SELECT SUM(cuotaCable + totalImpuesto) FROM tbl_abonos WHERE tipoServicio = 'C' AND anulada = 0 AND DAY(:fecha) = DAY(fechaAbonado)";
                    $query = "SELECT SUM(cuotaCable + totalImpuesto) FROM tbl_abonos WHERE tipoServicio = 'C' AND anulada = 0 AND DAY(:fecha) = DAY(fechaAbonado) AND MONTH(:fecha) = MONTH(fechaAbonado) AND YEAR(:fecha) = YEAR(fechaAbonado)";
                    $statement = $this->dbConnect->prepare($query);
                    $statement->bindParam(':fecha', $date);
                    //ini_set('memory_limit', '-1');
                    $statement->execute();
                    $result1 = $statement->fetchColumn();

                    //$query = "SELECT SUM(cuotaInternet + totalImpuesto) FROM tbl_abonos WHERE tipoServicio = 'I' AND anulada = 0 AND DAY(:fecha) = DAY(fechaAbonado)";
                    $query = "SELECT SUM(cuotaInternet + totalImpuesto) FROM tbl_abonos WHERE tipoServicio = 'I' AND anulada = 0 AND DAY(:fecha) = DAY(fechaAbonado) AND MONTH(:fecha) = MONTH(fechaAbonado) AND YEAR(:fecha) = YEAR(fechaAbonado)";
                    $statement = $this->dbConnect->prepare($query);
                    $statement->bindParam(':fecha', $date);
                    //ini_set('memory_limit', '-1');
                    $statement->execute();
                    $result2 = $statement->fetchColumn();

                    //$query = "SELECT SUM(montoCable + montoInternet + impuesto) FROM tbl_ventas_manuales WHERE anulada = 0 AND DAY(:fecha) = DAY(fechaComprobante)";
                    $query = "SELECT SUM(montoCable + montoInternet + impuesto) FROM tbl_ventas_manuales WHERE anulada = 0 AND DAY(:fecha) = DAY(fechaComprobante) AND MONTH(:fecha) = MONTH(fechaComprobante) AND YEAR(:fecha) = YEAR(fechaComprobante)";
                    $statement = $this->dbConnect->prepare($query);
                    $statement->bindParam(':fecha', $date);
                    //ini_set('memory_limit', '-1');
                    $statement->execute();
                    $result3 = $statement->fetchColumn();

                    return doubleval($result1)+doubleval($result2)+doubleval($result3);

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }
        public function getMonthlyIncomes()
        {
            try {
                    date_default_timezone_set('America/El_Salvador');
                    $date = date("Y-m-d");
                    $query = "SELECT SUM(cuotaCable + totalImpuesto) FROM tbl_abonos WHERE tipoServicio = 'C' AND anulada = 0 AND MONTH(:fecha) = MONTH(fechaAbonado) AND YEAR(:fecha) = YEAR(fechaAbonado)";
                    $statement = $this->dbConnect->prepare($query);
                    $statement->bindParam(':fecha', $date);
                    //ini_set('memory_limit', '-1');
                    $statement->execute();
                    $result1 = $statement->fetchColumn();

                    $query = "SELECT SUM(cuotaInternet + totalImpuesto) FROM tbl_abonos WHERE tipoServicio = 'I' AND anulada = 0 AND MONTH(:fecha) = MONTH(fechaAbonado) AND YEAR(:fecha) = YEAR(fechaAbonado)";
                    $statement = $this->dbConnect->prepare($query);
                    $statement->bindParam(':fecha', $date);
                    //ini_set('memory_limit', '-1');
                    $statement->execute();
                    $result2 = $statement->fetchColumn();

                    $query = "SELECT SUM(montoCable + montoInternet + impuesto) FROM tbl_ventas_manuales WHERE anulada = 0 AND MONTH(:fecha) = MONTH(fechaComprobante) AND YEAR(:fecha) = YEAR(fechaComprobante)";
                    $statement = $this->dbConnect->prepare($query);
                    $statement->bindParam(':fecha', $date);
                    //ini_set('memory_limit', '-1');
                    $statement->execute();
                    $result3 = $statement->fetchColumn();

                    return doubleval($result1)+doubleval($result2)+doubleval($result3);

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }

        public function getTotalEmp()
        {
            try {
                    $query = "SELECT COUNT(*) FROM tbl_empleados";
                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute();
                    $result1 = $statement->fetchColumn();
                    return $result1;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }

        public function getTotalOrders()
        {
            try {
                    $query = "SELECT COUNT(*) FROM tbl_ordenes_trabajo";
                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute();
                    $result1 = $statement->fetchColumn();
                    return $result1;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }

        public function getTotalCableModems()
        {
            try {
                    $query = "SELECT COUNT(*) FROM tbl_articulointernet";
                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute();
                    $result1 = $statement->fetchColumn();
                    return $result1;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }
        public function getTotalClientsC()
        {
            try {
                    $query = "SELECT count(*) FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null or servicio_suspendido='') AND sin_servicio='F' AND estado_cliente_in = 3 AND cod_cliente<>'00000'";
                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute();
                    $result1 = $statement->fetchColumn();
                    return $result1;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }
        public function getTotalClientsI()
        {
            try {
                    $query = "SELECT count(*) FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null or servicio_suspendido='') and sin_servicio='T' AND estado_cliente_in = 1 AND cod_cliente<>'00000'";
                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute();
                    $result1 = $statement->fetchColumn();
                    return $result1;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }
        public function getTotalClientsCI()
        {
            try {
                    $query = "SELECT count(*) FROM clientes WHERE (servicio_suspendido='F' or servicio_suspendido is null or servicio_suspendido='') and sin_servicio='F' AND estado_cliente_in = 1 AND cod_cliente<>'00000'";
                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute();
                    $result1 = $statement->fetchColumn();
                    return $result1;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }
    }
?>
