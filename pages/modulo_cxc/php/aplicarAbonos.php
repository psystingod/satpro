<?php
   require_once('../../../php/connection.php');
   /**
    * Clase para capturar los datos de la solicitud
    */
   class AplicarAbonos extends ConectionDB
   { //MODIFICAR FECHA FACTURA SOLAMENTE DE CARGOS MÁS NO DE ABONOS
       public function AplicarAbonos()
       {
           parent::__construct ();
       }

        public function aplicar()
       {
           try {
               // Verificamos el tipo de comprobante del cliente
               //HAY QUE VER PROBLEMA CON LAS FECHAS Y EL SALDO DE CADA SERVICIO NO DEBE SER EL PENDIENTE
               if (isset($_POST['consumidorFinal'])) {
                   $tipoComprobante = $_POST['consumidorFinal'];
               }

               if (isset($_POST['creditoFiscal'])) {
                   $tipoComprobante = $_POST['creditoFiscal'];
               }
               // Fin de verificación del tipo de comprobante del cliente
               var_dump("Probandollegar hasta acá1");
               $codigoCliente = $_POST['codigoCliente'];

               $fechaAbonado = $_POST['fechaAbono'];
               $date = str_replace('/', '-', $fechaAbonado);
               $fechaAbonado = date('Y-m-d', strtotime($date));
               $formaPago = $_POST['formaPago'];
               if (isset($_POST['mesx1']) && !isset($_POST['mesx2'])) {
                   if ($_POST['servicio'] == "c") {
                       $tipoServicio = strtoupper($_POST['servicio']);
                       $idFactura1 = $_POST['idFacturax1'];
                       $nRecibox1 = $_POST['nFacturax1'];
                       $impSeg = $_POST['impSeg'];

                       //CALCULANDO FECHAS
                       if (isset($_POST['fechaCobrox1'])) {
                           $fechaCobrox1 = date_format(date_create($_POST['fechaCobrox1']), 'Y-m-d');
                       }else {
                           $fechaCobrox1 = "//";
                       }
                       if (isset($_POST['fechaFacturax1'])) {
                           $fechaFacturax1 = date_format(date_create($_POST['fechaFacturax1']), 'Y-m-d');
                           $date = str_replace('/', '-', $fechaFacturax1);
                           $fechaFacturax1 = date('Y-m-d', strtotime($date));
                       }else {
                           $fechaFacturax1 = "//";
                       }
                       if (isset($_POST['vencimientox1'])) {
                           $fechaVencimientox1 = $_POST['vencimientox1'];
                           $date = str_replace('/', '-', $fechaVencimientox1);
                           $fechaVencimientox1 = date('Y-m-d', strtotime($date));
                       }else {
                           $fechaVencimientox1 = "//";
                       }

                       $mesCargo1 = $_POST['mesCargo1'];
                       $cesc = $_POST['porImp'];
                       $cuotaCable = $_POST['valorCuota'];
                       $saldoCable = $_POST['pendiente']; // Quizá update
                       $estado = "CANCELADA";
                       var_dump("Probandollegar hasta acá2");
                       $qry = "UPDATE tbl_cargos SET tipoFactura=:tipoComprobante, numeroRecibo=:numeroRecibo, codigoCliente=:codigoCliente, cuotaCable=:cuotaCable, saldoCable=:saldoCable, fechaCobro=:fechaCobro, fechaVencimiento=:fechaVencimiento, fechaAbonado=:fechaAbonado, fechaFactura=:fechaFactura, mesCargo=:mesCargo, tipoServicio=:tipoServicio, estado=:estado, cargoImpuesto=:cargoImpuesto, totalImpuesto=:totalImpuesto WHERE idFactura=:idFactura";

                       $stmt = $this->dbConnect->prepare($qry);
                       $stmt->execute(
                           array(':tipoComprobante' => $tipoComprobante,
                                 ':numeroRecibo' => $nRecibox1,
                                 ':codigoCliente' => $codigoCliente,
                                 ':cuotaCable' => $cuotaCable,
                                 ':saldoCable' => $saldoCable,
                                 ':fechaCobro' => $fechaCobrox1,
                                 ':fechaFactura' => $fechaFacturax1,
                                 ':fechaVencimiento' => $fechaVencimientox1,
                                 ':fechaAbonado' => $fechaAbonado,
                                 ':mesCargo' => $mesCargo1,
                                 ':tipoServicio' => $tipoServicio,
                                 ':estado' => $estado,
                                 ':totalImpuesto' => $impSeg,
                                 ':idFactura' => $idFactura1,
                                 ':cargoImpuesto' => $cesc

                                ));

                        //ACA HACER UN INSERT DEL ABONO EN LA TABLA ABONOS
                        $qry2 = "INSERT INTO tbl_abonos(tipoFactura, numeroRecibo, codigoCliente, cuotaCable, saldoCable, fechaCobro, fechaVencimiento, fechaFactura, mesCargo, tipoServicio, fechaAbonado, estado, cargoImpuesto, totalImpuesto, idFactura)VALUES(:tipoComprobante, :numeroRecibo, :codigoCliente, :cuotaCable, :saldoCable, :fechaCobro, :fechaVencimiento, :fechaFactura, :mesCargo, :tipoServicio, :fechaAbonado, :estado, :cargoImpuesto, :totalImpuesto, :idFactura)";

                        $stmt2 = $this->dbConnect->prepare($qry2);
                        $stmt2->execute(
                            array(':tipoComprobante' => $tipoComprobante,
                                  ':numeroRecibo' => $nRecibox1,
                                  ':codigoCliente' => $codigoCliente,
                                  ':cuotaCable' => $cuotaCable,
                                  ':saldoCable' => $saldoCable,
                                  ':fechaCobro' => $fechaCobrox1,
                                  ':fechaFactura' => $fechaFacturax1,
                                  ':fechaVencimiento' => $fechaVencimientox1,
                                  ':fechaAbonado' => $fechaAbonado,
                                  ':mesCargo' => $mesCargo1,
                                  ':tipoServicio' => $tipoServicio,
                                  ':estado' => $estado,
                                  ':totalImpuesto' => $impSeg,
                                  ':idFactura' => $idFactura1,
                                  ':cargoImpuesto' => $cesc
                                 ));

                        //ACA HACER ACTUALIZACION DE SALDO EN TABLA CLIENTES
                        $qry3 = "UPDATE clientes SET saldoCable= saldoCable - :cuotaCable, fecha_ult_pago=:fechaUltPago WHERE cod_cliente=:codigoCliente";

                        $stmt3 = $this->dbConnect->prepare($qry3);
                        $stmt3->execute(
                            array(
                                  ':codigoCliente' => $codigoCliente,
                                  ':cuotaCable' => floatVal($cuotaCable),
                                  ':fechaUltPago' => $fechaAbonado
                                 ));

                        header('Location: ../abonos.php?abonado=yes');
                   }
                   elseif ($_POST['servicio'] == "i") {
                       $tipoServicio = strtoupper($_POST['servicio']);
                       $idFactura1 = $_POST['idFacturax1'];
                       $nRecibox1 = $_POST['nFacturax1'];
                       //CALCULANDO FECHAS
                       if (isset($_POST['fechaCobrox1'])) {
                           $fechaCobrox1 = date_format(date_create($_POST['fechaCobrox1']), 'Y-m-d');
                       }else {
                           $fechaCobrox1 = "//";
                       }
                       if (isset($_POST['fechaFacturax1'])) {
                           $fechaFacturax1 = date_format(date_create($_POST['fechaFacturax1']), 'Y-m-d');
                           $date = str_replace('/', '-', $fechaFacturax1);
                           $fechaFacturax1 = date('Y-m-d', strtotime($date));
                       }else {
                           $fechaFacturax1 = "//";
                       }
                       if (isset($_POST['vencimientox1'])) {
                           $fechaVencimientox1 = $_POST['vencimientox1'];
                           $date = str_replace('/', '-', $fechaVencimientox1);
                           $fechaVencimientox1 = date('Y-m-d', strtotime($date));
                       }else {
                           $fechaVencimientox1 = "//";
                       }
                       $mesCargo1 = $_POST['mesCargo1'];
                       $cesc = $_POST['porImp'];
                       $cuotaInter = $_POST['valorCuota'];
                       $saldoInter = $_POST['pendiente']; // Quizá update
                       $estado = "CANCELADA";
                       var_dump("Probandollegar hasta acá");
                       $impSeg = $_POST['impSeg'];
                       $qry = "UPDATE tbl_cargos SET tipoFactura=:tipoComprobante, numeroRecibo=:numeroRecibo, codigoCliente=:codigoCliente, cuotaInter=:cuotaInter, saldoInter=:saldoInter, fechaCobro=:fechaCobro, fechaVencimiento=:fechaVencimiento, fechaAbonado=:fechaAbonado, fechaFactura=:fechaFactura, mesCargo=:mesCargo, tipoServicio=:tipoServicio, estado=:estado, cargoImpuesto=:cargoImpuesto, totalImpuesto=:totalImpuesto WHERE idFactura=:idFactura";

                       $stmt = $this->dbConnect->prepare($qry);
                       $stmt->execute(
                           array(':tipoComprobante' => $tipoComprobante,
                                 ':numeroRecibo' => $nRecibox1,
                                 ':codigoCliente' => $codigoCliente,
                                 ':cuotaInter' => $cuotaInter,
                                 ':saldoInter' => $saldoInter,
                                 ':fechaCobro' => $fechaCobrox1,
                                 ':fechaFactura' => $fechaFacturax1,
                                 ':fechaVencimiento' => $fechaVencimientox1,
                                 ':fechaAbonado' => $fechaAbonado,
                                 ':mesCargo' => $mesCargo1,
                                 ':tipoServicio' => $tipoServicio,
                                 ':estado' => $estado,
                                 ':totalImpuesto' => $impSeg,
                                 ':idFactura' => $idFactura1,
                                 ':cargoImpuesto' => $cesc
                                ));

                        //ACA HACER UN INSERT DEL ABONO EN LA TABLA ABONOS
                        $qry2 = "INSERT INTO tbl_abonos(tipoFactura, numeroRecibo, codigoCliente, cuotaInternet, saldoInternet, fechaCobro, fechaVencimiento, fechaFactura, mesCargo, tipoServicio, fechaAbonado, estado, cargoImpuesto, totalImpuesto, idFactura)VALUES(:tipoComprobante, :numeroRecibo, :codigoCliente, :cuotaInternet, :saldoInternet, :fechaCobro, :fechaVencimiento, :fechaFactura, :mesCargo, :tipoServicio, :fechaAbonado, :estado, :cargoImpuesto, :totalImpuesto, :idFactura)";

                        $stmt2 = $this->dbConnect->prepare($qry2);
                        $stmt2->execute(
                            array(':tipoComprobante' => $tipoComprobante,
                                  ':numeroRecibo' => $nRecibox1,
                                  ':codigoCliente' => $codigoCliente,
                                  ':cuotaInternet' => $cuotaInter,
                                  ':saldoInternet' => $saldoInter,
                                  ':fechaCobro' => $fechaCobrox1,
                                  ':fechaFactura' => $fechaFacturax1,
                                  ':fechaVencimiento' => $fechaVencimientox1,
                                  ':fechaAbonado' => $fechaAbonado,
                                  ':mesCargo' => $mesCargo1,
                                  ':tipoServicio' => $tipoServicio,
                                  ':estado' => $estado,
                                  ':totalImpuesto' => $impSeg,
                                  ':idFactura' => $idFactura1,
                                  ':cargoImpuesto' => $cesc
                                 ));

                        //ACA HACER ACTUALIZACION DE SALDO EN TABLA CLIENTES
                        $qry3 = "UPDATE clientes SET saldoInternet= saldoInternet - :cuotaInter, fecha_ult_pago=:fechaUltPago WHERE cod_cliente=:codigoCliente";

                        $stmt3 = $this->dbConnect->prepare($qry3);
                        $stmt3->execute(
                            array(
                                  ':codigoCliente' => $codigoCliente,
                                  ':cuotaInter' => floatVal($cuotaInter),
                                  ':fechaUltPago' => $fechaAbonado
                                 ));

                                var_dump("Probandollegar hasta acá");
                                header('Location: ../abonos.php?abonado=yes');
                   }
               }
               elseif (isset($_POST['mesx1']) && isset($_POST['mesx2'])) {
                   //Comprobamos el tipo de comprobante del cliente
                   if ($_POST['servicio'] == "c") {
                       $tipoServicio = strtoupper($_POST['servicio']);
                       $idFactura1 = $_POST['idFacturax1'];
                       $nRecibox1 = $_POST['nFacturax1'];
                       //CALCULANDO FECHAS
                       if (isset($_POST['fechaCobrox1'])) {
                           $fechaCobrox1 = date_format(date_create($_POST['fechaCobrox1']), 'Y-m-d');
                       }else {
                           $fechaCobrox1 = "//";
                       }
                       if (isset($_POST['fechaFacturax1'])) {
                           $fechaFacturax1 = date_format(date_create($_POST['fechaFacturax1']), 'Y-m-d');
                           $date = str_replace('/', '-', $fechaFacturax1);
                           $fechaFacturax1 = date('Y-m-d', strtotime($date));
                       }else {
                           $fechaFacturax1 = "//";
                       }
                       if (isset($_POST['vencimientox1'])) {
                           $fechaVencimientox1 = $_POST['vencimientox1'];
                           $date = str_replace('/', '-', $fechaVencimientox1);
                           $fechaVencimientox1 = date('Y-m-d', strtotime($date));
                       }else {
                           $fechaVencimientox1 = "//";
                       }
                       $mesCargo1 = $_POST['mesCargo1'];
                       $cesc = $_POST['porImp'];
                       $cuotaCable = $_POST['valorCuota'];
                       $saldoCable = $_POST['pendiente']; // Quizá update
                       $estado = "CANCELADA";
                       $impSeg = $_POST['impSeg'];
                       $impSeg1 = $impSeg/2;
                       $impSeg2 = $impSeg/2;

                       $qry = "UPDATE tbl_cargos SET tipoFactura=:tipoComprobante, numeroRecibo=:numeroRecibo, codigoCliente=:codigoCliente, cuotaCable=:cuotaCable, saldoCable=:saldoCable, fechaCobro=:fechaCobro, fechaVencimiento=:fechaVencimiento, fechaAbonado=:fechaAbonado, fechaFactura=:fechaFactura, mesCargo=:mesCargo, tipoServicio=:tipoServicio, estado=:estado, cargoImpuesto=:cargoImpuesto, totalImpuesto=:totalImpuesto WHERE idFactura=:idFactura";

                       $stmt = $this->dbConnect->prepare($qry);
                       $stmt->execute(
                           array(':tipoComprobante' => $tipoComprobante,
                                 ':numeroRecibo' => $nRecibox1,
                                 ':codigoCliente' => $codigoCliente,
                                 ':cuotaCable' => $cuotaCable,
                                 ':saldoCable' => $saldoCable,
                                 ':fechaCobro' => $fechaCobrox1,
                                 ':fechaFactura' => $fechaFacturax1,
                                 ':fechaVencimiento' => $fechaVencimientox1,
                                 ':fechaAbonado' => $fechaAbonado,
                                 ':mesCargo' => $mesCargo1,
                                 ':tipoServicio' => $tipoServicio,
                                 ':estado' => $estado,
                                 ':totalImpuesto' => $impSeg1,
                                 ':idFactura' => $idFactura1,
                                 ':cargoImpuesto' => $cesc
                                ));

                        //ACA HACER UN INSERT DEL ABONO EN LA TABLA ABONOS
                        $qry2 = "INSERT INTO tbl_abonos(tipoFactura, numeroRecibo, codigoCliente, cuotaCable, saldoCable, fechaCobro, fechaVencimiento, fechaFactura, mesCargo, tipoServicio, fechaAbonado, estado, cargoImpuesto, totalImpuesto, idFactura)VALUES(:tipoComprobante, :numeroRecibo, :codigoCliente, :cuotaCable, :saldoCable, :fechaCobro, :fechaVencimiento, :fechaFactura, :mesCargo, :tipoServicio, :fechaAbonado, :estado, :cargoImpuesto, :totalImpuesto, :idFactura)";

                        $stmt2 = $this->dbConnect->prepare($qry2);
                        $stmt2->execute(
                            array(':tipoComprobante' => $tipoComprobante,
                                  ':numeroRecibo' => $nRecibox1,
                                  ':codigoCliente' => $codigoCliente,
                                  ':cuotaCable' => $cuotaCable,
                                  ':saldoCable' => $saldoCable,
                                  ':fechaCobro' => $fechaCobrox1,
                                  ':fechaFactura' => $fechaFacturax1,
                                  ':fechaVencimiento' => $fechaVencimientox1,
                                  ':fechaAbonado' => $fechaAbonado,
                                  ':mesCargo' => $mesCargo1,
                                  ':tipoServicio' => $tipoServicio,
                                  ':estado' => $estado,
                                  ':totalImpuesto' => $impSeg1,
                                  ':idFactura' => $idFactura1,
                                  ':cargoImpuesto' => $cesc
                                 ));

                        //ACA HACER ACTUALIZACION DE SALDO EN TABLA CLIENTES
                        $qry3 = "UPDATE clientes SET saldoCable= saldoCable - :cuotaCable, fecha_ult_pago=:fechaUltPago WHERE cod_cliente=:codigoCliente";

                        $stmt3 = $this->dbConnect->prepare($qry3);
                        $stmt3->execute(
                            array(
                                  ':codigoCliente' => $codigoCliente,
                                  ':cuotaCable' => floatVal($cuotaCable),
                                  ':fechaUltPago' => $fechaAbonado
                                 ));

                       // MENSUALIDAD 2
                       $tipoServicio = strtoupper($_POST['servicio']);
                       $idFactura2 = $_POST['idFacturax2'];
                       $nRecibox2 = $_POST['nFacturax2'];
                       //CALCULANDO FECHAS
                       if (isset($_POST['fechaCobrox2'])) {
                           $fechaCobrox2 = date_format(date_create($_POST['fechaCobrox2']), 'Y-m-d');
                       }else {
                           $fechaCobrox2 = "//";
                       }
                       if (isset($_POST['fechaFacturax2'])) {
                           $fechaFacturax2 = date_format(date_create($_POST['fechaFacturax2']), 'Y-m-d');
                           $date = str_replace('/', '-', $fechaFacturax2);
                           $fechaFacturax2 = date('Y-m-d', strtotime($date));
                       }else {
                           $fechaFacturax2 = "//";
                       }
                       if (isset($_POST['vencimientox2'])) {
                           $fechaVencimientox2 = $_POST['vencimientox2'];
                           $date = str_replace('/', '-', $fechaVencimientox2);
                           $fechaVencimientox2 = date('Y-m-d', strtotime($date));
                       }else {
                           $fechaVencimientox2 = "//";
                       }
                       $mesCargo2 = $_POST['mesCargo2'];
                       $cesc = $_POST['porImp'];
                       $cuotaCable = $_POST['valorCuota'];
                       $saldoCable = $_POST['pendiente']; // Quizá update
                       $estado = "CANCELADA";

                       $qry = "UPDATE tbl_cargos SET tipoFactura=:tipoComprobante, numeroRecibo=:numeroRecibo, codigoCliente=:codigoCliente, cuotaCable=:cuotaCable, saldoCable=:saldoCable, fechaCobro=:fechaCobro, fechaVencimiento=:fechaVencimiento, fechaAbonado=:fechaAbonado, fechaFactura=:fechaFactura, mesCargo=:mesCargo, tipoServicio=:tipoServicio, estado=:estado, cargoImpuesto=:cargoImpuesto, totalImpuesto=:totalImpuesto WHERE idFactura=:idFactura";

                       $stmt = $this->dbConnect->prepare($qry);
                       $stmt->execute(
                           array(':tipoComprobante' => $tipoComprobante,
                                 ':numeroRecibo' => $nRecibox2,
                                 ':codigoCliente' => $codigoCliente,
                                 ':cuotaCable' => $cuotaCable,
                                 ':saldoCable' => $saldoCable,
                                 ':fechaCobro' => $fechaCobrox2,
                                 ':fechaFactura' => $fechaFacturax2,
                                 ':fechaVencimiento' => $fechaVencimientox2,
                                 ':fechaAbonado' => $fechaAbonado,
                                 ':mesCargo' => $mesCargo2,
                                 ':tipoServicio' => $tipoServicio,
                                 ':estado' => $estado,
                                 ':totalImpuesto' => $impSeg2,
                                 ':idFactura' => $idFactura2,
                                 ':cargoImpuesto' => $cesc
                                ));

                        //ACA HACER UN INSERT DEL ABONO EN LA TABLA ABONOS
                        $qry2 = "INSERT INTO tbl_abonos(tipoFactura, numeroRecibo, codigoCliente, cuotaCable, saldoCable, fechaCobro, fechaVencimiento, fechaFactura, mesCargo, tipoServicio, fechaAbonado, estado, cargoImpuesto, totalImpuesto, idFactura)VALUES(:tipoComprobante, :numeroRecibo, :codigoCliente, :cuotaCable, :saldoCable, :fechaCobro, :fechaVencimiento, :fechaFactura, :mesCargo, :tipoServicio, :fechaAbonado, :estado, :cargoImpuesto, :totalImpuesto, :idFactura)";

                        $stmt2 = $this->dbConnect->prepare($qry2);
                        $stmt2->execute(
                            array(':tipoComprobante' => $tipoComprobante,
                                  ':numeroRecibo' => $nRecibox2,
                                  ':codigoCliente' => $codigoCliente,
                                  ':cuotaCable' => $cuotaCable,
                                  ':saldoCable' => $saldoCable,
                                  ':fechaCobro' => $fechaCobrox2,
                                  ':fechaFactura' => $fechaFacturax2,
                                  ':fechaVencimiento' => $fechaVencimientox2,
                                  ':fechaAbonado' => $fechaAbonado,
                                  ':mesCargo' => $mesCargo2,
                                  ':tipoServicio' => $tipoServicio,
                                  ':estado' => $estado,
                                  ':totalImpuesto' => $impSeg2,
                                  ':idFactura' => $idFactura2,
                                  ':cargoImpuesto' => $cesc
                                 ));

                        //ACA HACER ACTUALIZACION DE SALDO EN TABLA CLIENTES
                        $qry3 = "UPDATE clientes SET saldoCable= saldoCable - :cuotaCable, fecha_ult_pago=:fechaUltPago WHERE cod_cliente=:codigoCliente";

                        $stmt3 = $this->dbConnect->prepare($qry3);
                        $stmt3->execute(
                            array(
                                  ':codigoCliente' => $codigoCliente,
                                  ':cuotaCable' => floatVal($cuotaCable),
                                  ':fechaUltPago' => $fechaAbonado
                                 ));

                                header('Location: ../abonos.php?abonado=yes');

                   }
                   elseif ($_POST['servicio'] == "i") {
                       $tipoServicio = strtoupper($_POST['servicio']);
                       $idFactura1 = $_POST['idFacturax1'];
                       $nRecibox1 = $_POST['nFacturax1'];
                       //CALCULANDO FECHAS
                       if (isset($_POST['fechaCobrox1'])) {
                           $fechaCobrox1 = date_format(date_create($_POST['fechaCobrox1']), 'Y-m-d');
                       }else {
                           $fechaCobrox1 = "//";
                       }
                       if (isset($_POST['fechaFacturax1'])) {
                           $fechaFacturax1 = date_format(date_create($_POST['fechaFacturax1']), 'Y-m-d');
                           $date = str_replace('/', '-', $fechaFacturax1);
                           $fechaFacturax1 = date('Y-m-d', strtotime($date));
                       }else {
                           $fechaFacturax1 = "//";
                       }
                       if (isset($_POST['vencimientox1'])) {
                           $fechaVencimientox1 = $_POST['vencimientox1'];
                           $date = str_replace('/', '-', $fechaVencimientox1);
                           $fechaVencimientox1 = date('Y-m-d', strtotime($date));
                       }else {
                           $fechaVencimientox1 = "//";
                       }
                       $mesCargo1 = $_POST['mesCargo1'];
                       $cesc = $_POST['porImp'];
                       $cuotaCable = $_POST['valorCuota'];
                       $saldoCable = $_POST['pendiente']; // Quizá update
                       $cuotaInter = $_POST['valorCuota'];
                       $saldoInter = $_POST['pendiente']; // Quizá update
                       $estado = "CANCELADA";
                       $impSeg = $_POST['impSeg'];
                       $impSeg1 = $impSeg/2;
                       $impSeg2 = $impSeg/2;

                       $qry = "UPDATE tbl_cargos SET tipoFactura=:tipoComprobante, numeroRecibo=:numeroRecibo, codigoCliente=:codigoCliente, cuotaCable=:cuotaCable, saldoCable=:saldoCable, fechaCobro=:fechaCobro, fechaVencimiento=:fechaVencimiento, fechaAbonado=:fechaAbonado, fechaFactura=:fechaFactura, mesCargo=:mesCargo, tipoServicio=:tipoServicio, estado=:estado, cargoImpuesto=:cargoImpuesto, totalImpuesto=:totalImpuesto WHERE idFactura=:idFactura";

                       $stmt = $this->dbConnect->prepare($qry);
                       $stmt->execute(
                           array(':tipoComprobante' => $tipoComprobante,
                                 ':numeroRecibo' => $nRecibox1,
                                 ':codigoCliente' => $codigoCliente,
                                 ':cuotaCable' => $cuotaCable,
                                 ':saldoCable' => $saldoCable,
                                 ':fechaCobro' => $fechaCobrox1,
                                 ':fechaFactura' => $fechaFacturax1,
                                 ':fechaVencimiento' => $fechaVencimientox1,
                                 ':fechaAbonado' => $fechaAbonado,
                                 ':mesCargo' => $mesCargo1,
                                 ':tipoServicio' => $tipoServicio,
                                 ':estado' => $estado,
                                 ':totalImpuesto' => $impSeg1,
                                 ':idFactura' => $idFactura1,
                                 ':cargoImpuesto' => $cesc
                                ));

                        //ACA HACER UN INSERT DEL ABONO EN LA TABLA ABONOS
                        $qry2 = "INSERT INTO tbl_abonos(tipoFactura, numeroRecibo, codigoCliente, cuotaInternet, saldoInternet, fechaCobro, fechaVencimiento, fechaFactura, mesCargo, tipoServicio, fechaAbonado, estado, cargoImpuesto, totalImpuesto, idFactura)VALUES(:tipoComprobante, :numeroRecibo, :codigoCliente, :cuotaInternet, :saldoInternet, :fechaCobro, :fechaVencimiento, :fechaFactura, :mesCargo, :tipoServicio, fechaAbonado, :estado, :cargoImpuesto, :totalImpuesto, :idFactura)";

                        $stmt2 = $this->dbConnect->prepare($qry2);
                        $stmt2->execute(
                            array(':tipoComprobante' => $tipoComprobante,
                                  ':numeroRecibo' => $nRecibox1,
                                  ':codigoCliente' => $codigoCliente,
                                  ':cuotaInternet' => $cuotaInter,
                                  ':saldoInternet' => $saldoInter,
                                  ':fechaCobro' => $fechaCobrox1,
                                  ':fechaFactura' => $fechaFacturax1,
                                  ':fechaVencimiento' => $fechaVencimientox1,
                                  ':fechaAbonado' => $fechaAbonado,
                                  ':mesCargo' => $mesCargo1,
                                  ':tipoServicio' => $tipoServicio,
                                  ':estado' => $estado,
                                  ':totalImpuesto' => $impSeg1,
                                  ':idFactura' => $idFactura1,
                                  ':cargoImpuesto' => $cesc
                                 ));

                        //ACA HACER ACTUALIZACION DE SALDO EN TABLA CLIENTES
                        $qry3 = "UPDATE clientes SET saldoInternet= saldoInternet - :cuotaInter, fecha_ult_pago=:fechaUltPago WHERE cod_cliente=:codigoCliente";

                        $stmt3 = $this->dbConnect->prepare($qry3);
                        $stmt3->execute(
                            array(
                                  ':codigoCliente' => $codigoCliente,
                                  ':cuotaInter' => floatVal($cuotaInter),
                                  ':fechaUltPago' => $fechaAbonado
                                 ));

                       // MENSUALIDAD 2
                       $tipoServicio = strtoupper($_POST['servicio']);
                       $idFactura2 = $_POST['idFacturax2'];
                       $nRecibox2 = $_POST['nFacturax2'];
                       //CALCULANDO FECHAS
                       if (isset($_POST['fechaCobrox2'])) {
                           $fechaCobrox2 = date_format(date_create($_POST['fechaCobrox2']), 'Y-m-d');
                       }else {
                           $fechaCobrox2 = "//";
                       }
                       if (isset($_POST['fechaFacturax2'])) {
                           $fechaFacturax2 = date_format(date_create($_POST['fechaFacturax2']), 'Y-m-d');
                           $date = str_replace('/', '-', $fechaFacturax2);
                           $fechaFacturax2 = date('Y-m-d', strtotime($date));
                       }else {
                           $fechaFacturax2 = "//";
                       }
                       if (isset($_POST['vencimientox2'])) {
                           $fechaVencimientox2 = $_POST['vencimientox2'];
                           $date = str_replace('/', '-', $fechaVencimientox2);
                           $fechaVencimientox2 = date('Y-m-d', strtotime($date));
                       }else {
                           $fechaVencimientox2 = "//";
                       }
                       $mesCargo2 = $_POST['mesCargo2'];
                       $cesc = $_POST['porImp'];
                       $cuotaCable = $_POST['valorCuota'];
                       $saldoCable = $_POST['pendiente']; // Quizá update
                       $estado = "CANCELADA";

                       $qry = "UPDATE tbl_cargos SET tipoFactura=:tipoComprobante, numeroRecibo=:numeroRecibo, codigoCliente=:codigoCliente, cuotaCable=:cuotaCable, saldoCable=:saldoCable, fechaCobro=:fechaCobro, fechaVencimiento=:fechaVencimiento, fechaAbonado=:fechaAbonado, fechaFactura=:fechaFactura, mesCargo=:mesCargo, tipoServicio=:tipoServicio, estado=:estado, cargoImpuesto=:cargoImpuesto, totalImpuesto=:totalImpuesto WHERE idFactura=:idFactura";

                       $stmt = $this->dbConnect->prepare($qry);
                       $stmt->execute(
                           array(':tipoComprobante' => $tipoComprobante,
                                 ':numeroRecibo' => $nRecibox2,
                                 ':codigoCliente' => $codigoCliente,
                                 ':cuotaCable' => $cuotaCable,
                                 ':saldoCable' => $saldoCable,
                                 ':fechaCobro' => $fechaCobrox2,
                                 ':fechaFactura' => $fechaFacturax2,
                                 ':fechaVencimiento' => $fechaVencimientox2,
                                 ':fechaAbonado' => $fechaAbonado,
                                 ':mesCargo' => $mesCargo2,
                                 ':tipoServicio' => $tipoServicio,
                                 ':estado' => $estado,
                                 ':totalImpuesto' => $impSeg2,
                                 ':idFactura' => $idFactura2,
                                 ':cargoImpuesto' => $cesc
                                ));

                        //ACA HACER UN INSERT DEL ABONO EN LA TABLA ABONOS
                        $qry2 = "INSERT INTO tbl_abonos(tipoFactura, numeroRecibo, codigoCliente, cuotaInternet, saldoInternet, fechaCobro, fechaVencimiento, fechaFactura, mesCargo, tipoServicio, fechaAbonado, estado, cargoImpuesto, totalImpuesto, idFactura)VALUES(:tipoComprobante, :numeroRecibo, :codigoCliente, :cuotaInternet, :saldoInternet, :fechaCobro, :fechaVencimiento, :fechaFactura, :mesCargo, :tipoServicio, :fechaAbonado, :estado, :cargoImpuesto, :totalImpuesto, :idFactura)";

                        $stmt2 = $this->dbConnect->prepare($qry2);
                        $stmt2->execute(
                            array(':tipoComprobante' => $tipoComprobante,
                                  ':numeroRecibo' => $nRecibox2,
                                  ':codigoCliente' => $codigoCliente,
                                  ':cuotaInternet' => $cuotaInter,
                                  ':saldoInternet' => $saldoInter,
                                  ':fechaCobro' => $fechaCobrox2,
                                  ':fechaFactura' => $fechaFacturax2,
                                  ':fechaVencimiento' => $fechaVencimientox2,
                                  ':fechaAbonado' => $fechaAbonado,
                                  ':mesCargo' => $mesCargo2,
                                  ':tipoServicio' => $tipoServicio,
                                  ':estado' => $estado,
                                  ':totalImpuesto' => $impSeg2,
                                  ':idFactura' => $idFactura2,
                                  ':cargoImpuesto' => $cesc
                                 ));

                        //ACA HACER ACTUALIZACION DE SALDO EN TABLA CLIENTES
                        $qry3 = "UPDATE clientes SET saldoInternet= saldoInternet - :cuotaInter, fecha_ult_pago=:fechaUltPago WHERE cod_cliente=:codigoCliente";

                        $stmt3 = $this->dbConnect->prepare($qry3);
                        $stmt3->execute(
                            array(
                                  ':codigoCliente' => $codigoCliente,
                                  ':cuotaInter' => floatVal($cuotaInter),
                                  ':fechaUltPago' => $fechaAbonado
                                 ));

                                header('Location: ../abonos.php?abonado=yes');
                   }
               }
               else { //SIRVE PARA LOS PAGOS DE FACTURAS ANTES DE QUE SE GENEREN
                   if ($_POST['servicio'] == "c") {
                       $tipoServicio = strtoupper($_POST['servicio']);
                       //$idFactura1 = $_POST['idFacturax1'];
                       //$nRecibox1 = $_POST['nFacturax1'];
                       //$fechaCobrox1 = date_format(date_create($_POST['fechaCobrox1']), 'Y-m-d');
                       //$fechaFacturax1 = date_format(date_create($_POST['fechaFacturax1']), 'Y-m-d');
                       //$fechaVencimientox1 = date_format(date_create($_POST['fechaVencimientox1']), 'Y-m-d');
                       $tipoServicio = strtoupper($_POST['servicio']);
                       $mesCargo1 = $_POST['meses'];
                       $cesc = $_POST['porImp'];
                       $cuotaCable = $_POST['valorCuota'];
                       $impSeg = $_POST['impSeg'];

                       //$saldoCable = $_POST['pendiente']; // Quizá update
                       $estado = "CANCELADA";
                       $qry = "INSERT INTO tbl_cargos (tipoFactura, codigoCliente, cuotaCable, fechaAbonado, mesCargo, tipoServicio, estado, cargoImpuesto, totalImpuesto) VALUES(:tipoComprobante, :codigoCliente, :cuotaCable, :fechaAbonado, :mesCargo, :tipoServicio, :estado, :cargoImpuesto, :totalImpuesto)";

                       $stmt = $this->dbConnect->prepare($qry);
                       $stmt->execute(
                           array(':tipoComprobante' => $tipoComprobante,
                                 ':codigoCliente' => $codigoCliente,
                                 ':cuotaCable' => $cuotaCable,
                                 ':fechaAbonado' => $fechaAbonado,
                                 ':mesCargo' => $mesCargo1,
                                 ':tipoServicio' => $tipoServicio,
                                 ':estado' => $estado,
                                 ':cargoImpuesto' => $cesc,
                                 ':totalImpuesto' => $impSeg
                                ));
                       $lastId = $stmt->lastInsertId();

                        $qry2 = "INSERT INTO tbl_abonos (tipoFactura, codigoCliente, cuotaCable, fechaAbonado, mesCargo, tipoServicio, estado, cargoImpuesto, totalImpuesto, idFactura) VALUES(:tipoComprobante, :codigoCliente, :cuotaCable, :fechaAbonado, :mesCargo, :tipoServicio, :estado, :cargoImpuesto, :totalImpuesto, :idFactura)";

                        $stmt2 = $this->dbConnect->prepare($qry2);
                        $stmt2->execute(
                            array(':tipoComprobante' => $tipoComprobante,
                                  ':codigoCliente' => $codigoCliente,
                                  ':cuotaCable' => $cuotaCable,
                                  ':fechaAbonado' => $fechaAbonado,
                                  ':mesCargo' => $mesCargo1,
                                  ':tipoServicio' => $tipoServicio,
                                  ':estado' => $estado,
                                  ':cargoImpuesto' => $cesc,
                                  ':totalImpuesto' => $impSeg,
                                  ':idFactura' => $lastId
                                 ));

                        //ACA HACER ACTUALIZACION DE SALDO EN TABLA CLIENTES
                        $qry3 = "UPDATE clientes SET saldoCable= saldoCable - :cuotaCable WHERE cod_cliente=:codigoCliente";

                        $stmt3 = $this->dbConnect->prepare($qry3);
                        $stmt3->execute(
                            array(
                                  ':codigoCliente' => $_POST["codigoCliente"],
                                  ':cuotaCable' => floatVal($cuotaCable)
                                 ));

                                header('Location: ../abonos.php?abonado=yes');
                   }
                   elseif ($_POST['servicio'] == "i") {
                       $tipoServicio = strtoupper($_POST['servicio']);
                       //$idFactura1 = $_POST['idFacturax1'];
                       //$nRecibox1 = $_POST['nFacturax1'];
                       //$fechaCobrox1 = date_format(date_create($_POST['fechaCobrox1']), 'Y-m-d');
                       //$fechaFacturax1 = date_format(date_create($_POST['fechaFacturax1']), 'Y-m-d');
                       //$fechaVencimientox1 = date_format(date_create($_POST['fechaVencimientox1']), 'Y-m-d');
                       $mesCargo1 = $_POST['meses'];
                       $cesc = $_POST['porImp'];
                       $cuotaInter = $_POST['valorCuota'];
                       $impSeg = $_POST['impSeg'];
                       //$saldoCable = $_POST['pendiente']; // Quizá update
                       $estado = "CANCELADA";
                       $qry = "INSERT INTO tbl_cargos (tipoFactura, codigoCliente, cuotaInternet, fechaAbonado, mesCargo, estado, cargoImpuesto, totalImpuesto) VALUES(:tipoComprobante, :codigoCliente, :cuotaInter, :fechaAbonado, :mesCargo, :estado, :cargoImpuesto, :totalImpuesto)";

                       $stmt = $this->dbConnect->prepare($qry);
                       $stmt->execute(
                           array(':tipoComprobante' => $tipoComprobante,
                                 ':codigoCliente' => $codigoCliente,
                                 ':cuotaInter' => $cuotaInter,
                                 ':fechaAbonado' => $fechaAbonado,
                                 ':mesCargo' => $mesCargo1,
                                 ':tipoServicio' => $tipoServicio,
                                 ':estado' => $estado,
                                 ':cargoImpuesto' => $cesc,
                                 ':totalImpuesto' => $impSeg
                                ));
                        $lastId = $stmt->lastInsertId();

                        $qry2 = "INSERT INTO tbl_abonos (tipoFactura, codigoCliente, cuotaInternet, fechaAbonado, mesCargo, estado, cargoImpuesto, totalImpuesto, idFactura) VALUES(:tipoComprobante, :codigoCliente, :cuotaInter, :fechaAbonado, :mesCargo, :estado, :cargoImpuesto, :totalImpuesto, :idFactura)";

                        $stmt2 = $this->dbConnect->prepare($qry2);
                        $stmt2->execute(
                            array(':tipoComprobante' => $tipoComprobante,
                                  ':codigoCliente' => $codigoCliente,
                                  ':cuotaInter' => $cuotaInter,
                                  ':fechaAbonado' => $fechaAbonado,
                                  ':mesCargo' => $mesCargo1,
                                  ':tipoServicio' => $tipoServicio,
                                  ':estado' => $estado,
                                  ':cargoImpuesto' => $cesc,
                                  ':totalImpuesto' => $impSeg,
                                  ':idFactura' => $lastId
                                 ));

                        //ACA HACER ACTUALIZACION DE SALDO EN TABLA CLIENTES
                        $qry3 = "UPDATE clientes SET saldoInternet= saldoInternet - :cuotaInter, fecha_ult_pago=:fechaUltPago WHERE cod_cliente=:codigoCliente";

                        $stmt3 = $this->dbConnect->prepare($qry3);
                        $stmt3->execute(
                            array(
                                  ':codigoCliente' => $_POST["codigoCliente"],
                                  ':cuotaInter' => floatVal($cuotaInter),
                                  ':fechaUltPago' => $fechaAbonado
                                 ));

                                var_dump("Probandollegar hasta acá");
                                header('Location: ../abonos.php?abonado=yes');

                   }
               }

           } catch (Exception $e) {
               print "!Error¡: " . $e->getMessage() . "</br>";
               die();
               header('Location: ../abonos.php?abonado=no');
           }
       }
   }
   $aplicar = new AplicarAbonos();
   $aplicar->aplicar();
?>
