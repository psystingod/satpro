<?php
   require_once('../../../php/connection.php');
   /**
    * Clase para capturar los datos de la solicitud
    */
   class AnularAbono extends ConectionDB
   {
       public function AnularAbono()
       {
           parent::__construct ();
       }

        public function anular()
       {
           try {
               $id = $_GET['idAbono'];

               // SQL query para traer datos del servicio de cable de la tabla clientes
               $query = "SELECT * FROM tbl_abonos WHERE idAbono=:id";
               // Preparación de sentencia
               $statement = $this->dbConnect->prepare($query);
               $statement->bindValue(':id', $id);
               $statement->execute();
               $result = $statement->fetch(PDO::FETCH_ASSOC);
               $mensualidad = $result['mesCargo'];
               $estado = $result['estado'];
               $codigoCliente = $result['codigoCliente'];
               $nFactura = $result['numeroFactura'];
               if ($result['tipoServicio'] == "C") {
                   $cuota = $result['cuotaCable'];
                   $queryCliente = "UPDATE clientes SET saldoCable=saldoCable+:cuota WHERE cod_cliente=:codigoCliente";
               }
               elseif ($result['tipoServicio'] == "I") {
                   $cuota = $result['cuotaInternet'];
                   $queryCliente = "UPDATE clientes SET saldoInternet=saldoInternet+:cuota WHERE cod_cliente=:codigoCliente";
               }

               // SQL query para traer datos del servicio de cable de la tabla clientes
               /*elseif ($estado == "CANCELADA"){//FALTA VER DESDE QUE FACTURA SE VA GENERAR
                   $query = "UPDATE tbl_abonos SET anulada=1, cuotaCable=0, cuotaInternet=0, saldoCable=0, saldoInternet=0, cargoImpuesto=0, totalImpuesto=0, WHERE idAbono=:id";
               }*/
               // Preparación de sentencia
               //$query = "UPDATE tbl_cargos SET anulada=1 WHERE idFactura=:id";
               $this->dbConnect->beginTransaction();
               $query = "UPDATE tbl_abonos SET anulada=1, cuotaCable=0, cuotaInternet=0, saldoCable=0, saldoInternet=0, cargoImpuesto=0, totalImpuesto=0 WHERE idAbono=:id";
               $statement = $this->dbConnect->prepare($query);
               $statement->bindValue(':id', $id, PDO::PARAM_INT);
               $statement->execute();
               sleep(0.5);
               $this->dbConnect->commit();

               //ACTUALIZAR EL SALDO RESTANDOLE EL ABONO ANULADO
               $this->dbConnect->beginTransaction();
               $statement = $this->dbConnect->prepare($queryCliente);
               $statement->bindValue(':cuota', $cuota);
               $statement->bindValue(':codigoCliente', $codigoCliente);
               $statement->execute();
               sleep(0.5);
               $this->dbConnect->commit();

               //VOLVER A DEJAR EL CARGO COMO pendiente
               $this->dbConnect->beginTransaction();
               $etd = "pendiente";
               $query = "UPDATE tbl_cargos SET estado=:estado WHERE numeroFactura=:nFactura AND codigoCliente=:codigoCliente";
               $statement = $this->dbConnect->prepare($query);
               $statement->bindValue(':estado', $etd);
               $statement->bindValue(':nFactura', $nFactura);
               $statement->bindValue(':codigoCliente', $codigoCliente);
               $statement->execute();
               sleep(0.5);
               $this->dbConnect->commit();

               $this->dbConnect = null;

               header('Location: ../cxc.php');

           } catch (Exception $e) {
               print "!Error¡: " . $e->getMessage() . "</br>";
               die();
           }
       }
   }
   $anular = new AnularAbono();
   $anular->anular();
?>
