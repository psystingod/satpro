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
            parent::__construct ();
        }
        public function getActiveClients()
        {
            try {
                    $query = "SELECT COUNT(*) FROM clientes WHERE (servicio_suspendido IS NULL OR servicio_suspendido = 'F') AND (estado_cliente_in = 1 OR estado_cliente_in = 3)";
                    $statement = $this->dbConnect->prepare($query);
                    //ini_set('memory_limit', '-1');
                    $statement->execute();
                    $result = $statement->fetchColumn();
                    return $result;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }

        public function getDailyIncomes()
        {
            try {
                    $query = "SELECT SUM(cuotaCable) FROM clientes WHERE tipoServicio = 'C' AND anulada = 0";
                    $statement = $this->dbConnect->prepare($query);
                    //ini_set('memory_limit', '-1');
                    $statement->execute();
                    $result1 = $statement->fetchColumn();

                    $query = "SELECT SUM(cuotaInternet) FROM clientes WHERE tipoServicio = 'I' AND anulada = 0";
                    $statement = $this->dbConnect->prepare($query);
                    //ini_set('memory_limit', '-1');
                    $statement->execute();
                    $result1 = $statement->fetchColumn();

                    return $result;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }
    }
?>
