<?php
   require_once('../../../php/connection.php');
   /**
    * Clase para capturar los datos de la solicitud
    */
   class AnularFactura extends ConectionDB
   {
       public function AnularFactura()
       {
           if(!isset($_SESSION))
           {
               session_start();
           }
           parent::__construct ($_SESSION['db']);
       }

        public function anular()
       {
           try {
               $numeroFactura = $_GET['numeroFactura'];
               $codigoCliente = $_GET['codigoCliente'];
               $tipoServicio = $_GET['tipoServicio'];
               $mesCargo = $_GET['mesCargo'];

               // SQL query para traer datos del servicio de cable de la tabla clientes
               $query = "SELECT * FROM tbl_cargos WHERE numeroFactura=:numeroFactura AND codigoCliente=:codigoCliente AND tipoServicio=:tipoServicio AND mesCargo=:mesCargo";
               // Preparación de sentencia
               $statement = $this->dbConnect->prepare($query);
               $statement->bindValue(':numeroFactura', $numeroFactura);
               $statement->bindValue(':codigoCliente', $codigoCliente);
               $statement->bindValue(':tipoServicio', $tipoServicio);
               $statement->bindValue(':mesCargo', $mesCargo);
               $statement->execute();

               $result = $statement->fetch(PDO::FETCH_ASSOC);
               $mensualidad = $result['mesCargo']."*";
               $estado = $result['estado'];
               $codigoCliente = $result['codigoCliente'];
               $nFactura = $result['numeroFactura'];
               /*if ($result['tipoServicio'] == "C") {
                   $cuota = $result['cuotaCable'];
                   $queryCliente = "UPDATE clientes SET saldoCable=saldoCable+:cuota WHERE cod_cliente=:codigoCliente";
               }
               elseif ($result['tipoServicio'] == "I") {
                   $cuota = $result['cuotaInternet'];
                   $queryCliente = "UPDATE clientes SET saldoInt|ernet=saldoInternet+:cuota WHERE cod_cliente=:codigoCliente";
               }*/

               // SQL query para traer datos del servicio de cable de la tabla clientes
               /*elseif ($estado == "CANCELADA"){//FALTA VER DESDE QUE FACTURA SE VA GENERAR
                   $query = "UPDATE tbl_abonos SET anulada=1, cuotaCable=0, cuotaInternet=0, saldoCable=0, saldoInternet=0, cargoImpuesto=0, totalImpuesto=0, WHERE idAbono=:id";
               }*/
               // Preparación de sentencia
               //$query = "UPDATE tbl_cargos SET anulada=1 WHERE idFactura=:id";
               $this->dbConnect->beginTransaction();
               $query = "UPDATE tbl_cargos SET cuotaCable=0.00, cuotaInternet=0.00, saldoCable=0.00, saldoInternet=0.00, mesCargo=:mes, cargoImpuesto=0.00, totalImpuesto=0.00, anulada=1 WHERE numeroFactura=:numeroFactura AND codigoCliente=:codigoCliente AND tipoServicio=:tipoServicio AND mesCargo=:mesCargo";
               //$query = "UPDATE tbl_cargos SET anulada=1, cuotaCable=0, cuotaInternet=0, saldoCable=0, saldoInternet=0, cargoImpuesto=0, totalImpuesto=0 WHERE idFactura=:id";
               $statement = $this->dbConnect->prepare($query);
               //$statement->bindValue(':mes', $mensualidad);
               $statement->bindValue(':numeroFactura', $numeroFactura);
               $statement->bindValue(':codigoCliente', $codigoCliente);
               $statement->bindValue(':tipoServicio', $tipoServicio);
               $statement->bindValue(':mesCargo', $mesCargo);
               $statement->execute();
               sleep(0.5);
               $this->dbConnect->commit();

               $this->dbConnect = null;

               //header('Location: ../facturacionGenerada.php');

           } catch (Exception $e) {
               print "!Error¡: " . $e->getMessage() . "</br>";
               die();
           }
       }
   }
   $anular = new AnularFactura();
   $anular->anular();
?>
