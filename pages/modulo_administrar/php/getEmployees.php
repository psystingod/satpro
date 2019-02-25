<?php
    require_once('../../php/connection.php');
    /**
     * Clase para tarer los datos de reportes de la base de datos
     */
    class GetEmployees extends ConectionDB
    {
        public function GetEmployees()
        {
            parent::__construct ();
        }
        public function getEmployeesRecords()
        {
            try {
                // SQL query para traer datos de los empleados
                $query = "SELECT * FROM tbl_empleado";
                // Preparación de sentencia
                $statement = $this->dbConnect->prepare($query);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                $this->dbConnect = NULL;
                return $result;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }
    }
?>
