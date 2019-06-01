<?php
   require_once('../../php/connection.php');
   /**
    * Clase para capturar los datos de la solicitud
    */
   class GetImpuestos extends ConectionDB
   {
       public function GetImpuestos()
       {
           parent::__construct ();
       }

        public function verImpuestos($siglas)
       {
           try {
               // SQL query para traer datos de la tabla impuestos
               $query = "SELECT idImpuesto, valorImpuesto FROM tbl_impuestos WHERE siglasImpuesto = :siglas";
               // Preparación de sentencia
               $statement = $this->dbConnect->prepare($query);
               $statement->execute(array(':siglas' => $siglas ));
               $result = $statement->fetch(PDO::FETCH_ASSOC);
               $statement->closeCursor();


               return $result;

           } catch (Exception $e) {
               print "!Error¡: " . $e->getMessage() . "</br>";
               die();
           }
       }
   }
?>
