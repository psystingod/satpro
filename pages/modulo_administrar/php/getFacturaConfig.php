<?php
    require_once('../../php/connection.php');
    /**
     * Clase para tarer los datos de reportes de la base de datos
     */
    class GetFacturaConfig extends ConectionDB
    {
        public function GetFacturaConfig()
        {
            parent::__construct ();
        }
        public function getConfig()
        {
            try {
                // SQL query para traer datos de los empleados
                $query = "SELECT * FROM tbl_facturas_config";
                // Preparación de sentencia
                $statement = $this->dbConnect->prepare($query);
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);

                $this->dbConnect = NULL;
                return $result;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }
    }
?>
