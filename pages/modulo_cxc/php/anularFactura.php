<?php
   require_once('../../../php/connection.php');
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
               $query = "SELECT * FROM tbl_cargos WHERE idFactura=:id";
               // Preparación de sentencia
               $statement = $this->dbConnect->prepare($query);
               $statement->bindValue(':id', $id);
               $statement->execute();
               $result = $statement->fetch(PDO::FETCH_ASSOC);
               if ($result['tipoServicio'] == "C") {
                   $cuota = $result['cuotaCable'];
               }
               elseif ($result['tipoServicio'] == "I") {
                   $cuota = $result['cuotaInternet'];
               }
               $mensualidad = $result['mesCargo'];
               $estado = $result['estado'];
               $codigoCliente = $result['codigoCliente'];

               // SQL query para traer datos del servicio de cable de la tabla clientes
               if ($estado == "pendiente") {
                   $query = "UPDATE tbl_cargos SET anulada=1 WHERE idFactura=:id";
               }
               elseif ($estado == "CANCELADA"){//FALTA VER DESDE QUE FACTURA SE VA GENERAR
                   $query = "UPDATE tbl_cargos SET anulada=0, estado='pendiente' WHERE idFactura=:id";
               }
               // Preparación de sentencia
               //$query = "UPDATE tbl_cargos SET anulada=1 WHERE idFactura=:id";
               $statement = $this->dbConnect->prepare($query);
               $statement->bindValue(':id', $id, PDO::PARAM_INT);
               $statement->execute();

               // SQL query para traer datos del servicio de cable de la tabla clientes
               $query = "DELETE FROM tbl_abonos WHERE mesCargo=:mesCargo AND codigoCliente=:codigoCliente AND idFactura=:id";
               // Preparación de sentencia
               $statement = $this->dbConnect->prepare($query);
               $statement->bindValue(':mesCargo', $mensualidad);
               $statement->bindValue(':codigoCliente', $codigoCliente);
               $statement->bindValue(':id', $id, PDO::PARAM_INT); //ID FACTURA
               $statement->execute();

               if ($result['tipoServicio'] == "C") {
                   if ($estado == "CANCELADA") {
                       $query = "UPDATE clientes SET saldoCable= saldoCable + :cuota WHERE cod_cliente=:codigoCliente";
                   }elseif ($estado == "pendiente") {
                       $query = "UPDATE clientes SET saldoCable= saldoCable - :cuota WHERE cod_cliente=:codigoCliente";
                   }
               }
               elseif ($result['tipoServicio'] == "I") {
                   if ($estado == "CANCELADA") {
                       $query = "UPDATE clientes SET saldoInternet= saldoInternet + :cuota WHERE cod_cliente=:codigoCliente";
                   }elseif ($estado == "pendiente") {
                       $query = "UPDATE clientes SET saldoInternet= saldoInternet - :cuota WHERE cod_cliente=:codigoCliente";
                   }
               }
               // Preparación de sentencia
               $statement = $this->dbConnect->prepare($query);
               $statement->bindValue(':cuota', $cuota);
               $statement->bindValue(':codigoCliente', $codigoCliente);
               $statement->execute();
               header('Location: ../facturacionGenerada.php');

           } catch (Exception $e) {
               print "!Error¡: " . $e->getMessage() . "</br>";
               die();
           }
       }
   }
   $anular = new AnularFactura();
   $anular->anular();
?>
