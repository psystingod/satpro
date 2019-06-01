<?php
   require_once('connection.php');
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
               // SQL query para traer datos del servicio de cable de la tabla clientes
               $query = "SELECT valorImpuesto FROM tbl_impuestos WHERE siglasImpuesto = 'CESC'";
               // Preparación de sentencia
               $statement = $this->dbConnect->prepare($query);
               $statement->execute();
               $result = $statement->fetch(PDO::FETCH_ASSOC);

               $cesc = floatval($result['valorImpuesto']);

               $tipoComprobante = $_POST['tipoComprobante'];
               $mesGenerar = $_POST['mesGenerar'];
               $diaGenerar = $_POST['diaGenerar'];
               $anoGenerar = $_POST['anoGenerar'];
               $fechaGenerar = $anoGenerar."-".$mesGenerar."-".$diaGenerar;
               $fechaComprobante = date_create($_POST['fechaComprobante']);
               $fechaVencimiento = date_create($_POST['vencimiento']);
               $correlativo = $_POST['correlativo'];
               $tipoServicio = $_POST['tipoServicio'];

               if (isset($_POST['cesc'])) {
                   $cesc = $cesc;
               }
               else {
                   $cesc = 0.0;
               }

               if ($_POST['tipoServicio'] == 'cable') {
                   $ts = "C";
                   // SQL query para traer datos del servicio de cable de la tabla clientes
                   $query = "SELECT cod_cliente, nombre, num_registro, direccion, id_municipio, id_departamento, numero_nit, giro, valor_cuota, cuota_in, dia_cobro, cod_cobrador, id_colonia, cod_vendedor, tipo_comprobante, tipo_facturacion, exento FROM clientes5 WHERE NOT servicio_suspendido AND NOT servicio_cortesia AND dia_cobro = :diaCobro AND fecha_primer_factura <= :fechaGenerar AND estado_cliente_in=3";
                   // Preparación de sentencia
                   $statement = $this->dbConnect->prepare($query);
                   $statement->execute(
                       array(':diaCobro' => $diaGenerar,
                             ':fechaGenerar' => $fechaGenerar
                            ));
                   $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                   foreach ($result as $i) {
                       $qry = "INSERT INTO tbl_facturas(tipoFactura, numeroRecibo, codigoCliente, cuotaCable, cuotaInternet, saldoCable, saldoInternet, fechaCobro, fechaVencimiento, fechaFactura, tipoServicio, anticipado, impuesto)VALUES(:tipoComprobante, :numeroRecibo, :codigoCliente, :cuotaCable, :cuotaInternet, :saldoCable, :saldoInternet, :fechaCobro, :fechaVencimiento, :fechaFactura, :tipoServicio, :anticipado, :impuesto)";

                       $stmt = $this->dbConnect->prepare($qry);
                       $stmt->execute(
                           array(':tipoComprobante' => $tipoComprobante,
                                 ':numeroRecibo' => $correlativo,
                                 ':codigoCliente' => str_pad($i["cod_cliente"],6,"0",STR_PAD_LEFT),
                                 ':cuotaCable' => $i['valor_cuota'],
                                 ':cuotaInternet' => $i['cuota_in'],
                                 ':saldoCable' => $i['valor_cuota'],
                                 ':saldoInternet' => $i['cuota_in'],
                                 ':fechaCobro' => $fechaGenerar,
                                 ':fechaFactura' => date_format($fechaComprobante, 'Y-m-d'),
                                 ':fechaVencimiento' => date_format($fechaVencimiento, 'Y-m-d'),
                                 ':tipoServicio' => $ts,
                                 ':anticipado' => 0,
                                 ':impuesto' => $cesc
                                ));
                   }
                   header('Location: ../pages/moduloInventario.php');
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
