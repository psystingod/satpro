<?php
    require('../../php/connection.php');
    /**
     * Clase para tarer los datos de reportes de la base de datos
     */
    class GetInfo extends ConectionDB
    {
        public function GetInfo()
        {
            parent::__construct ();
        }
        public function getCuentaTipo()
        {
            try {

                  $query = "SELECT * from tbl_CuentaTipo";
                          $statement = $this->dbConnect->prepare($query);
                          $statement->execute();
                          $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                          return $result;
            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }

        public function getCatalogo()
        {
            try {

                  $query = "SELECT * FROM tbl_cuentas_flujo as cf inner join tbl_CuentaTipo as ct on cf.tipo_cuenta=ct.IdCuentaTipo";
                          $statement = $this->dbConnect->prepare($query);
                          $statement->execute();
                          $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                          return $result;
            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }

        public function getCategoria()
        {
            try {

                  $query = "select * from tbl_categoria";
                          $statement = $this->dbConnect->prepare($query);
                          $statement->execute();
                          $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                          return $result;
            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }

        public function getUnidadMedida()
        {
            try {

                  $query = "select * from tbl_unidadmedida";
                          $statement = $this->dbConnect->prepare($query);
                          $statement->execute();
                          $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                          return $result;
            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }
        public function getDepartamentos()
        {
            try {

                  $query = "SELECT * FROM tbl_departamento";
                          $statement = $this->dbConnect->prepare($query);
                          $statement->execute();
                          $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                          return $result;
            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }
        public function getTipo()
        {
            try {

                  $query = "SELECT * FROM tbl_tipoproducto";
                          $statement = $this->dbConnect->prepare($query);
                          $statement->execute();
                          $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                          return $result;
            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }

        public function getPlazas()
        {
            try {

                  $query = "SELECT * from tbl_plazas";
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
