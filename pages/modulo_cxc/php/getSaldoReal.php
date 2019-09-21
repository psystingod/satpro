<?php
   require_once('../../php/connection.php');
   /**
    * Clase para capturar los datos de la solicitud
    */
   class GetSaldoReal extends ConectionDB
   {
       public function GetSaldoReal()
       {
           parent::__construct ();
       }

        public function getSaldoCable($id)
       {
           try {
               /*******INICIO DE INSTRUCCIONES PARA SACAR EL SALDO REAL DEL CLIENTE**********/
               // prepare select query
               $query = "SELECT SUM(cuotaCable) FROM tbl_cargos WHERE codigoCliente = ? LIMIT 0,1";
               $stmt = $this->dbConnect->prepare( $query );
               // this is the first question mark
               $stmt->bindParam(1, $id);
               // execute our query
               $stmt->execute();
               // store retrieved row to a variable
               $totalCargosCable = $stmt->fetchColumn();
               /***  ABONOS  ***/
               // prepare select query
               $query = "SELECT SUM(cuotaCable) FROM tbl_abonos WHERE codigoCliente = ? LIMIT 0,1";
               $stmt = $this->dbConnect->prepare( $query );

               $stmt->bindParam(1, $id);
               // execute our query
               $stmt->execute();
               // store retrieved row to a variable
               $totalAbonosCable = $stmt->fetchColumn();
               $saldoRealCable = floatVal($totalCargosCable) - floatVal($totalAbonosCable);

               return $saldoRealCable;

           } catch (Exception $e) {
               print "!Error¡: " . $e->getMessage() . "</br>";
               die();
           }
       }

       public function getSaldoInter($id)
      {
          try {
              /*******INICIO DE INSTRUCCIONES PARA SACAR EL SALDO REAL DEL CLIENTE**********/

              // prepare select query
              $query = "SELECT SUM(cuotaInternet) FROM tbl_cargos WHERE codigoCliente = ? LIMIT 0,1";
              $stmt = $this->dbConnect->prepare( $query );
              // this is the first question mark
              $stmt->bindParam(1, $id);
              // execute our query
              $stmt->execute();
              // store retrieved row to a variable
              $totalCargosInter = $stmt->fetchColumn();
              /***  ABONOS  ***/
              // prepare select query
              $query = "SELECT SUM(cuotaInternet) FROM tbl_abonos WHERE codigoCliente = ? LIMIT 0,1";
              $stmt = $this->dbConnect->prepare( $query );
              // this is the first question mark
              $stmt->bindParam(1, $id);
              // execute our query
              $stmt->execute();

              // store retrieved row to a variable
              $totalAbonosInter = $stmt->fetchColumn();
              $saldoRealInter = floatVal($totalCargosInter) - floatVal($totalAbonosInter);
              /*******FINAL DE INSTRUCCIONES PARA SACAR EL SALDO REAL DEL CLIENTE**********/

              return $saldoRealInter;

          } catch (Exception $e) {
              print "!Error¡: " . $e->getMessage() . "</br>";
              die();
          }
      }
   }
?>
