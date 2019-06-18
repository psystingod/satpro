<?php
   require_once('../../php/connection.php');
   /**
    * Clase para capturar los datos de la solicitud
    */
   class OrdersInfo extends ConectionDB
   {
       public function OrdersInfo()
       {
           parent::__construct ();
       }

        public function getTecnicos()
       {
           try {
               // SQL query para traer nombre de las categorías
               $query = "SELECT idTecnico, nombresTecnico, apellidosTecnico FROM tbl_tecnicos where state = 1";
               // Preparación de sentencia
               $statement = $this->dbConnect->prepare($query);
               $statement->execute();
               $result = $statement->fetchAll(PDO::FETCH_ASSOC);

               return $result;

           } catch (Exception $e) {
               print "!Error¡: " . $e->getMessage() . "</br>";
               die();
           }
       }
        public function getVendedores()
       {
           try {
               // SQL query para traer nombre de las categorías
               $query = "SELECT idVendedor, nombresVendedor, apellidosVendedor FROM tbl_vendedores where state = 1";
               // Preparación de sentencia
               $statement = $this->dbConnect->prepare($query);
               $statement->execute();
               $result = $statement->fetchAll(PDO::FETCH_ASSOC);

               return $result;

           } catch (Exception $e) {
               print "!Error¡: " . $e->getMessage() . "</br>";
               die();
           }
       }
       public function getActividadesCable()
       {
           try {
               // SQL query para traer nombre de las categorías
               $query = "SELECT idActividadCable, nombreActividad FROM tbl_actividades_cable";
               // Preparación de sentencia
               $statement = $this->dbConnect->prepare($query);
               $statement->execute();
               $result = $statement->fetchAll(PDO::FETCH_ASSOC);

               return $result;

           } catch (Exception $e) {
               print "!Error¡: " . $e->getMessage() . "</br>";
               die();
           }
       }
       public function getActividadesInter()
       {
           try {
               // SQL query para traer nombre de las categorías
               $query = "SELECT idActividadInter, nombreActividad FROM tbl_actividades_inter";
               // Preparación de sentencia
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
