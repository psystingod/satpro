<?php
/*
 * Clase PHP para llamar a todos los registros de clientes en la BD
 */
    require_once('../../php/connection.php');
    /**
     * Clase para tarer los datos de reportes de la base de datos
     */
    class GetAllClients extends ConectionDB
    {
        public function GetAllClients()
        {
            parent::__construct ();
        }
        public function getClients()
        {
            try {
                    $query = "SELECT cod_cliente, nombre, numero_dui, telefonos, direccion FROM tbl_clientes LIMIT 2000";
                    $statement = $this->dbConnect->prepare($query);
                    ini_set('memory_limit', '-1');
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    return $result;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }
    }
?>
