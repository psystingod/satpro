<?php
   require_once('connection.php');
   /**
    * Clase para capturar los datos de la solicitud
    */
   class AnularFactura extends ConectionDB
   {
       public function AnularFactura()
       {
           parent::__construct ();
       }

        public function anular()
       {
           try {
               $id = $_GET['id'];
               // SQL query para traer datos del servicio de cable de la tabla clientes
               $query = "UPDATE tbl_facturas SET anulada=1 WHERE idFactura=:id";
               // Preparación de sentencia
               $statement = $this->dbConnect->prepare($query);
               $statement->bindValue(':id', $id, PDO::PARAM_INT);
               $statement->execute();
               header('Location:../pages/facturacionGenerada.php');

           } catch (Exception $e) {
               print "!Error¡: " . $e->getMessage() . "</br>";
               die();
           }
       }
   }
   $anular = new AnularFactura();
   $anular->anular();
?>
