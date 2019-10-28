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

               //NUMERO DE FACTURA Y PREFIJO
               // SQL query para traer datos del servicio de FACTURA de la tabla clientes
               $query = "SELECT * FROM tbl_facturas_config";
               // Preparación de sentencia
               $statement = $this->dbConnect->prepare($query);
               $statement->execute();
               $result = $statement->fetch(PDO::FETCH_ASSOC);

               $prefijoFactura = $result['prefijoFactura'];
               $prefijoFiscal = $result['prefijoFiscal'];
               $prefijoFacturaPeque = $result['prefijoFacturaPeque'];
               $ultimaFactura = $result['ultimaFactura'];
               $ultimaFiscal = $result['ultimaFiscal'];
               $ultimaPeque = $result['ultimaPeque'];
               $rangoDesdeFactura = $result['rangoDesdeFactura'];
               $rangoHastaFactura = $result['rangoHastaFactura'];
               $rangoDesdeFiscal = $result['rangoDesdeFiscal'];
               $rangoHastaFiscal = $result['rangoHastaFiscal'];
               $rangoDesdePeque = $result['rangoDesdePeque'];
               $rangoHastaPeque = $result['rangoHastaPeque'];
               //FIN OBTENCIÓN DE PREFIJO

               $tipoComprobante = $_POST['tipoComprobante']; // Credito fiscal o consumidor final
               $mesGenerar = $_POST['mesGenerar'];
               $diaGenerar = $_POST['diaGenerar'];
               //var_dump($diaGenerar == 05);
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

               $correlativo = "---"; //$_POST['correlativo'];
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
                   if ($tipoComprobante == 1) {
                       // SQL query para traer datos del servicio de cable de la tabla clientes
                       $query = "SELECT cod_cliente, nombre, num_registro, direccion, id_municipio, id_departamento, numero_nit, giro, valor_cuota, cuota_in, dia_cobro, cod_cobrador, id_colonia, cod_vendedor, tipo_comprobante, tipo_facturacion, exento, servicio_cortesia FROM clientes WHERE (servicio_suspendido IS NULL OR servicio_suspendido = 'F') AND (servicio_cortesia IS NULL OR servicio_cortesia = 'F') AND dia_cobro = :diaCobro AND fecha_primer_factura <= :fechaGenerar AND estado_cliente_in=3 AND tipo_comprobante =:tipoComprobante";
                       // Preparación de sentencia
                       $statement = $this->dbConnect->prepare($query);
                       $statement->execute(
                           array(':diaCobro' => $diaGenerar,
                                 ':fechaGenerar' => $fechaGenerar1,
                                 ':tipoComprobante' => $tipoComprobante
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

                               //var_dump("vuelta: " . $vuelta++);
                               //var_dump($contador);
                               if ($contador == 0) {
                                   if ($ultimaFiscal <= $rangoHastaFiscal) {
                                       $ultimaFiscal = $ultimaFiscal + 1;
                                       $numeroFactura = $prefijoFiscal ."-". strval($ultimaFiscal);
                                       //$this->dbConnect->beginTransaction(); $this->dbConnect->exec('LOCK TABLES tbl_cargos, tbl_abonos, clientes, tbl_facturas_config WRITE');
                                       $this->dbConnect->beginTransaction();
                                       $qry = "INSERT INTO tbl_cargos(tipoFactura, numeroFactura, numeroRecibo, codigoCliente, codigoCobrador, cuotaCable, cuotaInternet, fechaCobro, fechaVencimiento, fechaFactura, mesCargo, tipoServicio, estado, cargoImpuesto, totalImpuesto, exento)VALUES(:tipoComprobante, :numeroFactura, :numeroRecibo, :codigoCliente, :codigoCobrador, :cuotaCable, :cuotaInternet, :fechaCobro, :fechaVencimiento, :fechaFactura, :mesCargo, :tipoServicio, :estado, :cargoImpuesto, :totalImpuesto, :exento)";

                                       $stmt = $this->dbConnect->prepare($qry);
                                       $stmt->execute(
                                           array(':tipoComprobante' => $tipoComprobante,
                                                 ':numeroFactura' => $numeroFactura,
                                                 ':numeroRecibo' => $correlativo,
                                                 ':codigoCliente' => $i["cod_cliente"],
                                                 ':codigoCobrador' => $i["cod_cobrador"],
                                                 ':cuotaCable' => $i['valor_cuota'],
                                                 ':cuotaInternet' => $i['cuota_in'],
                                                 ':fechaCobro' => $fechaGenerar1,
                                                 ':fechaFactura' => $fechaComprobante,
                                                 ':fechaVencimiento' => $fechaVencimiento,
                                                 ':mesCargo' => $mesCargo,
                                                 ':tipoServicio' => $ts,
                                                 ':estado' => $estado,
                                                 ':cargoImpuesto' => $cesc,
                                                 ':exento' => $i['exento'],
                                                 ':totalImpuesto' => substr((($i['valor_cuota']/1.13)*$cesc),0,4),

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

                                                 //ACA HACER ACTUALIZACION DE LA TABLA DE FACTURAS CONFIG
                                                 $qry4 = "UPDATE tbl_facturas_config SET ultimaFiscal= :ultimaFiscal";

                                                 $stmt4 = $this->dbConnect->prepare($qry4);
                                                 $stmt4->execute(
                                                     array(
                                                           ':ultimaFiscal' => $ultimaFiscal
                                                          ));

                                                 sleep(0.5);
                                                 $this->dbConnect->commit();
                                    //header('Location: ../cxc.php?gen=yes');
                                   }elseif($ultimaFiscal > $rangoHastaFiscal) {
                                       header('Location: ../../modulo_administrar/configFacturas.php?gen=continuecre');
                                   }

                               }elseif ($contador > 0) {
                                   continue;
                               }

                           }
                           //header('Location: ../cxc.php?gen=yes');
                       }
                   }
                   elseif($tipoComprobante == 2){
                       // SQL query para traer datos del servicio de cable de la tabla clientes
                       $query = "SELECT cod_cliente, nombre, num_registro, direccion, id_municipio, id_departamento, numero_nit, giro, valor_cuota, cuota_in, dia_cobro, cod_cobrador, id_colonia, cod_vendedor, tipo_comprobante, tipo_facturacion, exento, servicio_cortesia FROM clientes WHERE (servicio_suspendido IS NULL OR servicio_suspendido = 'F') AND (servicio_cortesia IS NULL OR servicio_cortesia = 'F') AND dia_cobro = :diaCobro AND fecha_primer_factura <= :fechaGenerar AND estado_cliente_in=3 AND tipo_comprobante =:tipoComprobante";
                       // Preparación de sentencia
                       $statement = $this->dbConnect->prepare($query);
                       $statement->execute(
                           array(':diaCobro' => $diaGenerar,
                                 ':fechaGenerar' => $fechaGenerar1,
                                 ':tipoComprobante' => $tipoComprobante
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

                               //var_dump("vuelta: " . $vuelta++);
                               //var_dump($contador);
                               if ($contador == 0) {

                                   if ($ultimaFactura <= $rangoHastaFactura) {
                                       $ultimaFactura = $ultimaFactura + 1;
                                       $numeroFactura = strval($prefijoFactura) ."-". strval($ultimaFactura);
                                       $this->dbConnect->beginTransaction();
                                       $qry = "INSERT INTO tbl_cargos(tipoFactura, numeroFactura, numeroRecibo, codigoCliente, codigoCobrador, cuotaCable, cuotaInternet, fechaCobro, fechaVencimiento, fechaFactura, mesCargo, tipoServicio, estado, cargoImpuesto, totalImpuesto, exento)VALUES(:tipoComprobante, :numeroFactura, :numeroRecibo, :codigoCliente, :codigoCobrador, :cuotaCable, :cuotaInternet, :fechaCobro, :fechaVencimiento, :fechaFactura, :mesCargo, :tipoServicio, :estado, :cargoImpuesto, :totalImpuesto, :exento)";

                                       $stmt = $this->dbConnect->prepare($qry);
                                       $stmt->execute(
                                           array(':tipoComprobante' => $tipoComprobante,
                                                 ':numeroFactura' => $numeroFactura,
                                                 ':numeroRecibo' => $correlativo,
                                                 ':codigoCliente' => $i["cod_cliente"],
                                                 ':codigoCobrador' => $i["cod_cobrador"],
                                                 ':cuotaCable' => $i['valor_cuota'],
                                                 ':cuotaInternet' => $i['cuota_in'],
                                                 ':fechaCobro' => $fechaGenerar1,
                                                 ':fechaFactura' => $fechaComprobante,
                                                 ':fechaVencimiento' => $fechaVencimiento,
                                                 ':mesCargo' => $mesCargo,
                                                 ':tipoServicio' => $ts,
                                                 ':estado' => $estado,
                                                 ':cargoImpuesto' => $cesc,
                                                 ':exento' => $i['exento'],
                                                 ':totalImpuesto' => substr((($i['valor_cuota']/1.13)*$cesc),0,4),

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

                                                 //ACA HACER ACTUALIZACION DE LA TABLA DE FACTURAS CONFIG
                                                 $qry4 = "UPDATE tbl_facturas_config SET ultimaFactura= :ultimaFactura";

                                                 $stmt4 = $this->dbConnect->prepare($qry4);
                                                 $stmt4->execute(
                                                     array(
                                                           ':ultimaFactura' => $ultimaFactura
                                                          ));

                                                 sleep(0.5);
                                                 $this->dbConnect->commit();
                                    //header('Location: ../cxc.php?gen=yes');
                                   }elseif($ultimaFactura > $rangoHastaFactura) {
                                       header('Location: ../../modulo_administrar/configFacturas.php?gen=continuecon');
                                   }

                               }elseif ($contador > 0) {
                                   continue;
                               }

                           }
                           //header('Location: ../cxc.php?gen=yes');
                       }
                   }
               }

               else if ($_POST['tipoServicio'] == 'internet') {
                   $ts = "I";
                   if ($tipoComprobante == 1) {
                       // SQL query para traer datos del servicio de cable de la tabla clientes
                       $query = "SELECT cod_cliente, nombre, num_registro, direccion, id_municipio, id_departamento, numero_nit, giro, valor_cuota, cuota_in, dia_cobro, cod_cobrador, id_colonia, cod_vendedor, tipo_comprobante, tipo_facturacion, exento FROM clientes WHERE estado_cliente_in=1 AND dia_corbo_in = :diaCobro AND fecha_primer_factura_in <= :fechaGenerar AND tipo_comprobante =:tipoComprobante";
                       // Preparación de sentencia
                       $statement = $this->dbConnect->prepare($query);
                       $statement->execute(
                           array(':diaCobro' => $diaGenerar,
                                 ':fechaGenerar' => $fechaGenerar1,
                                 ':tipoComprobante' => $tipoComprobante
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

                               //var_dump("vuelta: " . $vuelta++);
                               //var_dump($contador);
                               if ($contador == 0) {
                                   if ($ultimaFiscal <= $rangoHastaFiscal) {
                                       $ultimaFiscal = $ultimaFiscal + 1;
                                       $numeroFactura = $prefijoFiscal ."-". strval($ultimaFiscal);
                                       $this->dbConnect->beginTransaction();
                                       $qry = "INSERT INTO tbl_cargos(tipoFactura, numeroFactura, numeroRecibo, codigoCliente, codigoCobrador, cuotaCable, cuotaInternet, fechaCobro, fechaVencimiento, fechaFactura, mesCargo, tipoServicio, estado, cargoImpuesto, totalImpuesto, exento)VALUES(:tipoComprobante, :numeroFactura, :numeroRecibo, :codigoCliente, :codigoCobrador,
                                              :cuotaCable, :cuotaInternet, :fechaCobro, :fechaVencimiento, :fechaFactura, :mesCargo, :tipoServicio, :estado, :cargoImpuesto, :totalImpuesto, :exento)";

                                       $stmt = $this->dbConnect->prepare($qry);
                                       $stmt->execute(
                                           array(':tipoComprobante' => $tipoComprobante,
                                                 ':numeroFactura' => $numeroFactura,
                                                 ':numeroRecibo' => $correlativo,
                                                 ':codigoCliente' => $i["cod_cliente"],
                                                 ':codigoCobrador' => $i["cod_cobrador"],
                                                 ':cuotaCable' => $i['valor_cuota'],
                                                 ':cuotaInternet' => $i['cuota_in'],
                                                 ':fechaCobro' => $fechaGenerar1,
                                                 ':fechaFactura' => $fechaComprobante,
                                                 ':fechaVencimiento' => $fechaVencimiento,
                                                 ':mesCargo' => $mesCargo,
                                                 ':tipoServicio' => $ts,
                                                 ':estado' => $estado,
                                                 ':cargoImpuesto' => $cesc,
                                                 ':exento' => $i['exento'],
                                                 ':totalImpuesto' => substr((($i['cuota_in']/1.13)*$cesc),0,4),

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

                                                 //ACA HACER ACTUALIZACION DE LA TABLA DE FACTURAS CONFIG
                                                 $qry4 = "UPDATE tbl_facturas_config SET ultimaFiscal= :ultimaFiscal";

                                                 $stmt4 = $this->dbConnect->prepare($qry4);
                                                 $stmt4->execute(
                                                     array(
                                                           ':ultimaFiscal' => $ultimaFiscal
                                                          ));

                                                 sleep(0.5);
                                                 $this->dbConnect->commit();

                                    //header('Location: ../cxc.php?gen=yes');
                                   }elseif($ultimaFiscal > $rangoHastaFiscal) {
                                       header('Location: ../../modulo_administrar/configFacturas.php?gen=continuecre');
                                   }

                               }elseif ($contador > 0) {
                                   continue;
                               }

                           }
                           //header('Location: ../cxc.php?gen=yes');
                       }
                   }
                   elseif($tipoComprobante == 2){
                       // SQL query para traer datos del servicio de cable de la tabla clientes
                       $query = "SELECT cod_cliente, nombre, num_registro, direccion, id_municipio, id_departamento, numero_nit, giro, valor_cuota, cuota_in, dia_cobro, cod_cobrador, id_colonia, cod_vendedor, tipo_comprobante, tipo_facturacion, exento FROM clientes WHERE estado_cliente_in=1 AND dia_corbo_in = :diaCobro AND fecha_primer_factura_in <= :fechaGenerar AND tipo_comprobante =:tipoComprobante";
                       // Preparación de sentencia
                       $statement = $this->dbConnect->prepare($query);
                       $statement->execute(
                           array(':diaCobro' => $diaGenerar,
                                 ':fechaGenerar' => $fechaGenerar1,
                                 ':tipoComprobante' => $tipoComprobante
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

                               //var_dump("vuelta: " . $vuelta++);
                               //var_dump($contador);
                               if ($contador == 0) {
                                   if ($ultimaFactura <= $rangoHastaFactura) {
                                       $ultimaFactura = $ultimaFactura + 1;
                                       $numeroFactura = $prefijoFactura ."-". strval($ultimaFactura);
                                       $this->dbConnect->beginTransaction();
                                       $qry = "INSERT INTO tbl_cargos(tipoFactura, numeroFactura, numeroRecibo, codigoCliente, codigoCobrador, cuotaCable, cuotaInternet, fechaCobro, fechaVencimiento, fechaFactura, mesCargo, tipoServicio, estado, cargoImpuesto, totalImpuesto, exento)VALUES(:tipoComprobante, :numeroFactura, :numeroRecibo, :codigoCliente, :codigoCobrador, :cuotaCable, :cuotaInternet,
                                               :fechaCobro, :fechaVencimiento, :fechaFactura, :mesCargo, :tipoServicio, :estado, :cargoImpuesto, :totalImpuesto, :exento)";

                                       $stmt = $this->dbConnect->prepare($qry);
                                       $stmt->execute(
                                           array(':tipoComprobante' => $tipoComprobante,
                                                 ':numeroFactura' => $numeroFactura,
                                                 ':numeroRecibo' => $correlativo,
                                                 ':codigoCliente' => $i["cod_cliente"],
                                                 ':codigoCobrador' => $i["cod_cobrador"],
                                                 ':cuotaCable' => $i['valor_cuota'],
                                                 ':cuotaInternet' => $i['cuota_in'],
                                                 ':fechaCobro' => $fechaGenerar1,
                                                 ':fechaFactura' => $fechaComprobante,
                                                 ':fechaVencimiento' => $fechaVencimiento,
                                                 ':mesCargo' => $mesCargo,
                                                 ':tipoServicio' => $ts,
                                                 ':estado' => $estado,
                                                 ':cargoImpuesto' => $cesc,
                                                 ':exento' => $i['exento'],
                                                 ':totalImpuesto' => substr((($i['cuota_in']/1.13)*$cesc),0,4)

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

                                                 //ACA HACER ACTUALIZACION DE LA TABLA DE FACTURAS CONFIG
                                                 $qry4 = "UPDATE tbl_facturas_config SET ultimaFactura= :ultimaFactura";

                                                 $stmt4 = $this->dbConnect->prepare($qry4);
                                                 $stmt4->execute(
                                                     array(
                                                     ':ultimaFactura' => $ultimaFactura
                                                          ));

                                                 sleep(0.5);
                                                 $this->dbConnect->commit();

                                    //header('Location: ../cxc.php?gen=yes');
                                   }elseif($ultimaFactura > $rangoHastaFactura) {
                                       header('Location: ../../modulo_administrar/configFacturas.php?gen=continuecon');
                                   }

                               }elseif ($contador > 0) {
                                   continue;
                               }

                           }
                           //header('Location: ../cxc.php?gen=yes');
                       }
                   }
               }
               header('Location: ../cxc.php?gen=yes');
           } catch (Exception $e) {
               print "!Error¡: " . $e->getMessage() . "</br>";
               die();
           }
       }
   }
   $generar = new GenerarFacturas();
   $generar->generar();
?>
