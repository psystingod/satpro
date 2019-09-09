<?php
   require_once('../../../php/connection.php');
   /**
    * Clase para capturar los datos de la solicitud
    */
   class GenerarFacturas extends ConectionDB
   {
       public function GenerarFacturas()
       {
           parent::__construct ();
       }

        public function generar()
       {
           try {
               date_default_timezone_set('America/El_Salvador');
               // SQL query para traer datos del servicio de cable de la tabla clientes
               $query = "SELECT valorImpuesto FROM tbl_impuestos WHERE siglasImpuesto = 'CESC'";
               // Preparación de sentencia
               $statement = $this->dbConnect->prepare($query);
               $statement->execute();
               $result = $statement->fetch(PDO::FETCH_ASSOC);

               $cesc = floatval($result['valorImpuesto']);

               $tipoComprobante = $_POST['tipoComprobante']; // Credito fiscal o consumidor final
               $mesGenerar = $_POST['mesGenerar'];
               $diaGenerar = $_POST['diaGenerar'];
               var_dump($diaGenerar == 05);
               $anoGenerar = $_POST['anoGenerar'];
               $fechaGenerar1 = $anoGenerar."-".$mesGenerar."-".$diaGenerar; //FECHA PIVOTE
               $fechaGenerar = date_format(date_create($fechaGenerar1), 'd/m/Y');
               $mesCargo = date_format(date_create($fechaGenerar1), 'm/Y');
               //var_dump($mesCargo);
               $comprobante = $_POST['fechaComprobante'];
               $vencimiento = $_POST['vencimiento'];
               $comprobante2 = str_replace("/", "-", $comprobante);
               $vencimiento2 = str_replace("/", "-", $vencimiento);
               //var_dump($comprobante2);
               //var_dump($vencimiento2);
               $fechaComprobante = date_format(date_create($comprobante2), 'Y-m-d');
               $fechaVencimiento = date_format(date_create($vencimiento2), 'Y-m-d');
               //var_dump($fechaComprobante);
               //var_dump($fechaVencimiento);

               $correlativo = $_POST['correlativo'];
               $tipoServicio = $_POST['tipoServicio'];
               $estado = "pendiente";

               if (isset($_POST['cesc'])) {
                   $cesc = $cesc;
               }
               else {
                   $cesc = 0.0;
               }

               if ($_POST['tipoServicio'] == 'cable') {
                   $ts = "C";
                   // SQL query para traer datos del servicio de cable de la tabla clientes
                   $query = "SELECT cod_cliente, nombre, num_registro, direccion, id_municipio, id_departamento, numero_nit, giro, valor_cuota, cuota_in, dia_cobro, cod_cobrador, id_colonia, cod_vendedor, tipo_comprobante, tipo_facturacion, exento, servicio_cortesia FROM clientes WHERE servicio_suspendido='F' AND servicio_cortesia='F' AND dia_cobro = :diaCobro AND fecha_primer_factura <= :fechaGenerar AND estado_cliente_in=3";
                   // Preparación de sentencia
                   $statement = $this->dbConnect->prepare($query);
                   $statement->execute(
                       array(':diaCobro' => $diaGenerar,
                             ':fechaGenerar' => $fechaGenerar1
                            ));
                   $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                   if (count($result) == 0) {
                       header('Location: ../cxc.php?gen=no');
                   }
                   else {
                       $vuelta = 0;
                       foreach ($result as $i) {

                           $qry = "SELECT COUNT(mesCargo)FROM tbl_cargos WHERE codigoCliente=:codigoCliente AND mesCargo=:mesCargo AND tipoServicio=:tipoServicio";

                           $stmt = $this->dbConnect->prepare($qry);
                           $stmt->execute(
                               array(':codigoCliente' => $i["cod_cliente"],
                                     ':mesCargo' => $mesCargo,
                                     ':tipoServicio' => $ts,
                                    ));
                           $contador = $stmt->fetchColumn();

                           var_dump("vuelta: " . $vuelta++);
                           //var_dump($contador);
                           if ($contador == 0) {

                               $qry = "INSERT INTO tbl_cargos(tipoFactura, numeroRecibo, codigoCliente, cuotaCable, cuotaInternet, fechaCobro, fechaVencimiento, fechaFactura, mesCargo, tipoServicio, estado, cargoImpuesto)VALUES(:tipoComprobante, :numeroRecibo, :codigoCliente, :cuotaCable, :cuotaInternet, :fechaCobro, :fechaVencimiento, :fechaFactura, :mesCargo, :tipoServicio, :estado, :cargoImpuesto)";

                               $stmt = $this->dbConnect->prepare($qry);
                               $stmt->execute(
                                   array(':tipoComprobante' => $tipoComprobante,
                                         ':numeroRecibo' => $correlativo,
                                         ':codigoCliente' => $i["cod_cliente"],
                                         ':cuotaCable' => $i['valor_cuota'],
                                         ':cuotaInternet' => $i['cuota_in'],
                                         ':fechaCobro' => $fechaGenerar1,
                                         ':fechaFactura' => $fechaComprobante,
                                         ':fechaVencimiento' => $fechaVencimiento,
                                         ':mesCargo' => $mesCargo,
                                         ':tipoServicio' => $ts,
                                         ':estado' => $estado,
                                         ':cargoImpuesto' => $cesc
                                        ));

                                        //ACA HACER ACTUALIZACION DE SALDO
                                        $qry2 = "UPDATE tbl_cargos SET saldoCable= saldoCable + :cuotaCable WHERE codigoCliente=:codigoCliente AND mesCargo=:mesCargo AND tipoServicio=:tipoServicio";

                                        $stmt2 = $this->dbConnect->prepare($qry2);
                                        $stmt2->execute(
                                            array(
                                                  ':cuotaCable' => floatval($i['valor_cuota']),
                                                  ':codigoCliente' => $i["cod_cliente"],
                                                  ':mesCargo' => $mesCargo,
                                                  ':tipoServicio' => $ts,
                                                 ));

                                         //ACA HACER ACTUALIZACION DE SALDO EN TABLA CLIENTES
                                         $qry3 = "UPDATE clientes SET saldoCable= saldoCable + :cuotaCable WHERE cod_cliente=:codigoCliente";

                                         $stmt3 = $this->dbConnect->prepare($qry3);
                                         $stmt3->execute(
                                             array(
                                                   ':codigoCliente' => $i["cod_cliente"],
                                                   ':cuotaCable' => floatval($i['valor_cuota'])
                                                  ));

                           }elseif ($contador > 0) {
                               continue;
                           }

                       }
                       header('Location: ../cxc.php?gen=yes');
                   }
               }

               else if ($_POST['tipoServicio'] == 'internet') {
                   $ts = "I";
                   // SQL query para traer datos del servicio de cable de la tabla clientes
                   $query = "SELECT cod_cliente, nombre, num_registro, direccion, id_municipio, id_departamento, numero_nit, giro, valor_cuota, cuota_in, dia_cobro, cod_cobrador, id_colonia, cod_vendedor, tipo_comprobante, tipo_facturacion, exento FROM clientes WHERE dia_corbo_in = :diaCobro AND fecha_primer_factura_in <= :fechaGenerar AND estado_cliente_in=1";
                   // Preparación de sentencia
                   $statement = $this->dbConnect->prepare($query);
                   $statement->execute(
                       array(':diaCobro' => $diaGenerar,
                             ':fechaGenerar' => $fechaGenerar
                            ));
                   $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                   if (count($result) == 0) {
                       header('Location: ../cxc.php?gen=yes');
                   }
                   else {
                       foreach ($result as $i) {
                           $qry = "SELECT COUNT(mesCargo)FROM tbl_cargos WHERE codigoCliente=:codigoCliente AND mesCargo=:mesCargo AND tipoServicio=:tipoServicio";

                           $stmt = $this->dbConnect->prepare($qry);
                           $stmt->execute(
                               array(':codigoCliente' => $i["cod_cliente"],
                                     ':mesCargo' => $mesCargo,
                                     ':tipoServicio' => $ts,
                                    ));
                           $contador = $stmt->fetchColumn();

                           var_dump("vuelta: " . $vuelta++);
                           //var_dump($contador);
                           if ($contador == 0) {
                               $qry = "INSERT INTO tbl_cargos(tipoFactura, numeroRecibo, codigoCliente, cuotaCable, cuotaInternet, fechaCobro, fechaVencimiento, fechaFactura, mesCargo, tipoServicio, estado, cargoImpuesto)VALUES(:tipoComprobante, :numeroRecibo, :codigoCliente, :cuotaCable, :cuotaInternet, :saldoCable, :saldoInternet, :fechaCobro, :fechaVencimiento, :fechaFactura, :mesCargo, :tipoServicio, :estado, :cargoImpuesto)";

                               $stmt = $this->dbConnect->prepare($qry);
                               $stmt->execute(
                                   array(':tipoComprobante' => $tipoComprobante,
                                         ':numeroRecibo' => $correlativo,
                                         ':codigoCliente' => $i["cod_cliente"],
                                         ':cuotaCable' => $i['valor_cuota'],
                                         ':cuotaInternet' => $i['cuota_in'],
                                         ':fechaCobro' => $fechaGenerar1,
                                         ':fechaFactura' => $fechaComprobante,
                                         ':fechaVencimiento' => $fechaVencimiento,
                                         ':mesCargo' => $mesCargo,
                                         ':tipoServicio' => $ts,
                                         ':estado' => $estado,
                                         ':cargoImpuesto' => $cesc
                                        ));

                                        //ACA HACER ACTUALIZACION DE SALDO
                                        $qry2 = "UPDATE tbl_cargos SET saldoInternet= saldoInternet + :cuotaInter WHERE codigoCliente=:codigoCliente AND mesCargo=:mesCargo AND tipoServicio=:tipoServicio";

                                        $stmt2 = $this->dbConnect->prepare($qry2);
                                        $stmt2->execute(
                                            array(
                                                  ':cuotaInter' => floatval($i['cuota_in']),
                                                  ':codigoCliente' => $i["cod_cliente"],
                                                  ':mesCargo' => $mesCargo,
                                                  ':tipoServicio' => $ts,
                                                 ));

                                         //ACA HACER ACTUALIZACION DE SALDO EN TABLA CLIENTES
                                         $qry3 = "UPDATE clientes SET saldoInternet= saldoInternet + :cuotaInter WHERE cod_cliente=:codigoCliente";

                                         $stmt3 = $this->dbConnect->prepare($qry3);
                                         $stmt3->execute(
                                             array(
                                                   ':codigoCliente' => $i["cod_cliente"],
                                                   ':cuotaInter' => floatval($i['cuota_in'])
                                                  ));
                           }elseif ($contador > 0) {
                               continue;
                           }

                       }
                       header('Location: ../cxc.php?gen=yes');
                   }

               }

           } catch (Exception $e) {
               print "!Error¡: " . $e->getMessage() . "</br>";
               die();
           }
       }
   }
   $generar = new GenerarFacturas();
   $generar->generar();
?>
