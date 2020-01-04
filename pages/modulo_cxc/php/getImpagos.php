<?php
require_once('../../php/connection.php');

/**
 * Clase para traer toda la información de los clientes de la BD
 */
class Impagos extends ConectionDB
{

    function Impagos()
    {
        if(!isset($_SESSION))
        {
            session_start();
        }
        parent::__construct ($_SESSION['db']);
    }

    public function getImpagos(){
        try {
                //$c = "C";
                // Total servicio CABLE
                $query = "SELECT codigoCliente, nombre FROM tbl_cargos group by `codigoCliente` HAVING COUNT(*) = 2;";
                $statement = $this->dbConnect->prepare($query);
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
