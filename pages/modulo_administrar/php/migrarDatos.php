<?php
    require('../../../php/connection.php');
    /**
     * Clase para tarer los datos de reportes de la base de datos
     */
    class MigrarDatos extends ConectionDB
    {
        public function MigrarDatos()
        {
            if(!isset($_SESSION))
            {
                session_start();
            }
            parent::__construct ($_SESSION['db']);
        }
        public function migrarAbonos()
        {
            try {
                // SQL query para traer datos de los productos
                $query = "SELECT * FROM ventas_cxc";
                // Preparación de sentencia
                $statement = $this->dbConnect->prepare($query);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                $cero = 0;
                $cancelada = "CANCELADA";

                foreach ($result as $key) {
                    if ($key['anulado'] == "T") {
                        $anulada = 1;
                    }else {
                        $anulada = 0;

                    }
                    if ($key['tipoServicio'] == "C") {
                        $nFactura = $key['prefijo']."-".$key['numeroComprobante'];
                        $query2 = "INSERT INTO tbl_abonos (/*prefijo,*/nombre, direccion, municipio, idColonia, numeroFactura, tipoFactura, codigoCliente, codigoCobrador, cuotaCable, cuotaInternet, fechaFactura, fechaVencimiento, mesCargo, tipoServicio, estado, anticipado, /*cargoImpuesto,*/ totalImpuesto, totalIva, anulada)
                                   VALUES (/*:prefijo,*/:nombre, :direccion, :municipio, :idColonia, :numeroFactura, :tipoFactura, :codigoCliente, :codigoCobrador, :cuotaCable, :cuotaInternet, :fechaFactura, :fechaVencimiento, :mesCargo, :tipoServicio, :estado, :anticipado, /*:cargoImpuesto,*/ :totalImpuesto, :totalIva, :anulada)";

                        $statement = $this->dbConnect->prepare($query2);
                        //$statement->bindParam(':prefijo', $key['prefijo']);
                        $statement->bindParam(':nombre', $key['nombre']);
                        $statement->bindParam(':direccion', $key['direccion']);
                        $statement->bindParam(':municipio', $key['municipio']);
                        $statement->bindParam(':idColonia', $key['idColonia']);
                        $statement->bindParam(':numeroFactura', $nFactura);
                        $statement->bindParam(':tipoFactura', $key['tipoComprobante']);
                        $statement->bindParam(':codigoCliente', $key['codigoCliente']);
                        $statement->bindParam(':codigoCobrador', $key['codigoCobrador']);
                        $statement->bindParam(':cuotaCable', $key['totalComprobante']);
                        $statement->bindParam(':cuotaInternet', $cero);
                        $statement->bindParam(':fechaFactura', $key['fechaComprobante']);
                        $statement->bindParam(':fechaVencimiento', $key['fechaVencimiento']);
                        $statement->bindParam(':mesCargo', $key['numMes']);
                        $statement->bindParam(':tipoServicio', $key['tipoServicio']);
                        $statement->bindParam(':estado', $cancelada);
                        $statement->bindParam(':anticipado', $key['anticipo']);
                        $statement->bindParam(':totalImpuesto', $key['impuesto']);
                        $statement->bindParam(':totalIva', $key['valorIva']);
                        $statement->bindParam(':anulada', $anulada);

                        $statement->execute();
                    }
                    elseif ($key['tipoServicio'] == "I") {
                        $nFactura = $key['prefijo']."-".$key['numeroComprobante'];
                        $query2 = "INSERT INTO tbl_abonos (/*prefijo,*/nombre, direccion, municipio, idColonia, numeroFactura, tipoFactura, codigoCliente, codigoCobrador, cuotaCable, cuotaInternet, fechaFactura, fechaVencimiento, mesCargo, tipoServicio, estado, anticipado, /*cargoImpuesto,*/ totalImpuesto, totalIva, anulada)
                                   VALUES (/*:prefijo,*/:nombre, :direccion, :municipio, :idColonia, :numeroFactura, :tipoFactura, :codigoCliente, :codigoCobrador, :cuotaCable, :cuotaInternet, :fechaFactura, :fechaVencimiento, :mesCargo, :tipoServicio, :estado, :anticipado, /*:cargoImpuesto,*/ :totalImpuesto, :totalIva, :anulada)";

                        $statement = $this->dbConnect->prepare($query2);
                        //$statement->bindParam(':prefijo', $key['prefijo']);
                        $statement->bindParam(':nombre', $key['nombre']);
                        $statement->bindParam(':direccion', $key['direccion']);
                        $statement->bindParam(':municipio', $key['municipio']);
                        $statement->bindParam(':idColonia', $key['idColonia']);
                        $statement->bindParam(':numeroFactura', $nFactura);
                        $statement->bindParam(':tipoFactura', $key['tipoComprobante']);
                        $statement->bindParam(':codigoCliente', $key['codigoCliente']);
                        $statement->bindParam(':codigoCobrador', $key['codigoCobrador']);
                        $statement->bindParam(':cuotaCable', $cero);
                        $statement->bindParam(':cuotaInternet', $key['totalComprobante']);
                        $statement->bindParam(':fechaFactura', $key['fechaComprobante']);
                        $statement->bindParam(':fechaVencimiento', $key['fechaVencimiento']);
                        $statement->bindParam(':mesCargo', $key['numMes']);
                        $statement->bindParam(':tipoServicio', $key['tipoServicio']);
                        $statement->bindParam(':estado', $cancelada);
                        $statement->bindParam(':anticipado', $key['anticipo']);
                        $statement->bindParam(':totalImpuesto', $key['impuesto']);
                        $statement->bindParam(':totalIva', $key['valorIva']);
                        $statement->bindParam(':anulada', $anulada);

                        $statement->execute();
                    }

                }

                $this->dbConnect = NULL;
                //return $result;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }

        public function migrarCargos()
        {
            try {
                // SQL query para traer datos de los productos
                $query = "SELECT * FROM cobros";
                // Preparación de sentencia
                $statement = $this->dbConnect->prepare($query);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                $cero = 0;
                $pendiente = "pendiente";
                $cancelada = "CANCELADA";

                foreach ($result as $key) {
                    if ($key['anulada'] == "T") {
                        $anulada = 1;
                    }else {
                        $anulada = 0;

                    }
                    if ($key['tipoServicio'] == "C") {
                        $query2 = "INSERT INTO tbl_cargos (/*prefijo,*/numeroFactura, tipoFactura, codigoCliente, codigoCobrador, cuotaCable, cuotaInternet, fechaCobro, fechaFactura, fechaVencimiento, fechaAbonado, mesCargo, tipoServicio, estado, anticipado, /*cargoImpuesto,*/ totalImpuesto, anulada)
                                   VALUES (/*:prefijo,*/:numeroFactura, :tipoFactura, :codigoCliente, :codigoCobrador, :cuotaCable, :cuotaInternet, :fechaCobro, :fechaFactura, :fechaVencimiento, :fechaAbonado, :mesCargo, :tipoServicio, :estado, :anticipado, /*:cargoImpuesto,*/ :totalImpuesto, :anulada)";

                        $statement = $this->dbConnect->prepare($query2);
                        //$statement->bindParam(':prefijo', $key['prefijo']);
                        $statement->bindParam(':numeroFactura', $key['numeroComprobante']);
                        $statement->bindParam(':tipoFactura', $key['tipoComprobante']);
                        $statement->bindParam(':codigoCliente', $key['codigoCliente']);
                        $statement->bindParam(':codigoCobrador', $key['codigoCobrador']);
                        $statement->bindParam(':cuotaCable', $key['valorCuota']);
                        $statement->bindParam(':cuotaInternet', $cero);
                        $statement->bindParam(':fechaCobro', $key['fechaCobro']);
                        $statement->bindParam(':fechaFactura', $key['fechaComprobante']);
                        $statement->bindParam(':fechaVencimiento', $key['fechaVencimiento']);
                        $statement->bindParam(':fechaAbonado', $key['fechaPago']);
                        $statement->bindParam(':mesCargo', $key['numMes']);
                        $statement->bindParam(':tipoServicio', $key['tipoServicio']);
                        $statement->bindParam(':estado', $pendiente);
                        $statement->bindParam(':anticipado', $key['anticipo']);
                        $statement->bindParam(':totalImpuesto', $key['impuesto']);
                        $statement->bindParam(':anulada', $anulada);

                        $statement->execute();
                    }
                    elseif ($key['tipoServicio'] == "I") {
                        $query2 = "INSERT INTO tbl_cargos (/*prefijo,*/numeroFactura, tipoFactura, codigoCliente, codigoCobrador, cuotaCable, cuotaInternet, fechaCobro, fechaFactura, fechaVencimiento, fechaAbonado, mesCargo, tipoServicio, estado, anticipado, /*cargoImpuesto,*/ totalImpuesto, anulada)
                                   VALUES (/*:prefijo,*/:numeroFactura, :tipoFactura, :codigoCliente, :codigoCobrador, :cuotaCable, :cuotaInternet, :fechaCobro, :fechaFactura, :fechaVencimiento, :fechaAbonado, :mesCargo, :tipoServicio, :estado, :anticipado, /*:cargoImpuesto,*/ :totalImpuesto, :anulada)";

                        $statement = $this->dbConnect->prepare($query2);
                        //$statement->bindParam(':prefijo', $key['prefijo']);
                        $statement->bindParam(':numeroFactura', $key['numeroComprobante']);
                        $statement->bindParam(':tipoFactura', $key['tipoComprobante']);
                        $statement->bindParam(':codigoCliente', $key['codigoCliente']);
                        $statement->bindParam(':codigoCobrador', $key['codigoCobrador']);
                        $statement->bindParam(':cuotaCable', $cero);
                        $statement->bindParam(':cuotaInternet', $key['valorCuota']);
                        $statement->bindParam(':fechaCobro', $key['fechaCobro']);
                        $statement->bindParam(':fechaFactura', $key['fechaComprobante']);
                        $statement->bindParam(':fechaVencimiento', $key['fechaVencimiento']);
                        $statement->bindParam(':fechaAbonado', $key['fechaPago']);
                        $statement->bindParam(':mesCargo', $key['numMes']);
                        $statement->bindParam(':tipoServicio', $key['tipoServicio']);
                        $statement->bindParam(':estado', $pendiente);
                        $statement->bindParam(':anticipado', $key['anticipo']);
                        $statement->bindParam(':totalImpuesto', $key['impuesto']);
                        $statement->bindParam(':anulada', $anulada);

                        $statement->execute();
                    }

                }

                $this->dbConnect = NULL;
                //return $result;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }

        public function matchCargosAbonos()
        {
            try {
                // SQL query para traer datos de los productos
                $queryCargos = "SELECT tipoFactura, mesCargo,codigoCliente,fechaFactura,tipoServicio FROM tbl_cargos";
                // Preparación de sentencia
                $statement = $this->dbConnect->prepare($queryCargos);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                $cero = 0;
                $pendiente = "pendiente";
                $cancelada = "CANCELADA";

                $queryAbonos = "SELECT numeroFactura, tipoFactura, mesCargo,codigoCliente,fechaFactura,tipoServicio FROM tbl_abonos";

                $statement2 = $this->dbConnect->prepare($queryAbonos);
                $statement2->execute();
                $result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);

                foreach ($result as $key) {

                    foreach ($result2 as $key2) {
                        if ($key['tipoFactura'] == $key2['tipoFactura'] && $key['mesCargo'] == $key2['mesCargo'] && $key['codigoCliente'] == $key2['codigoCliente'] /*&& $key['fechaFactura'] == $key2['fechaFactura']*/ && $key['tipoServicio'] == $key2['tipoServicio']) {
                            //var_dump($key['mesCargo']);
                            //var_dump($key2['mesCargo']);
                            $query = "UPDATE tbl_cargos SET numeroFactura=:numeroFactura, estado=:estado WHERE codigoCliente=:codigoCliente /*AND fechaFactura=:fechaFactura*/ AND mesCargo=:mesCargo AND tipoServicio=:tipoServicio";

                            $statement3 = $this->dbConnect->prepare($query);
                            $statement3->bindParam(':numeroFactura', $key2['numeroFactura']);
                            $statement3->bindParam(':estado', $cancelada);
                            $statement3->bindParam(':codigoCliente', $key['codigoCliente']);
                            //$statement3->bindParam(':fechaFactura', $key['fechaFactura']);
                            $statement3->bindParam(':mesCargo', $key['mesCargo']);
                            $statement3->bindParam(':tipoServicio', $key['tipoServicio']);

                            $statement3->execute();
                        }
                    }
                }
                $this->dbConnect = NULL;
                //return $result;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }

        public function migrarAbonos_cxc()
        {
            try {
                // SQL query para traer datos de los productos
                $query = "SELECT t1.numeroRecibo, t1.codigoCliente, t1.fechaRecibo, t1.codigoCobrador, t1.tipoServicio, t1.impuesto, t2.tipoComprobante, t2.numMes, t2.montoAbonado, t2.anticipo, c1.nombre, c1.direccion, c1.id_municipio, c1.id_colonia
                          FROM abonos_cxc1 as t1 INNER JOIN detalle_abonos_cxc as t2 INNER JOIN clientes as c1 on t1.numeroRecibo = t2.numeroRecibo AND c1.cod_cliente = t1.codigoCliente";
                // Preparación de sentencia
                $statement = $this->dbConnect->prepare($query);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                $cero = 0;
                $cancelada = "CANCELADA";

                foreach ($result as $key) {
                    /*if ($key['anulado'] == "T") {
                        $anulada = 1;
                    }else {
                        $anulada = 0;

                    }*/
                    if ($key['tipoServicio'] == "C") {
                        $query2 = "INSERT INTO tbl_abonos (/*prefijo,*/nombre, direccion, idMunicipio, idColonia, /*numeroFactura,*/ tipoFactura, numeroRecibo, codigoCliente, codigoCobrador, cuotaCable, cuotaInternet, fechaAbonado, fechaVencimiento, mesCargo, tipoServicio, estado, anticipado, /*cargoImpuesto,*/ totalImpuesto/*, totalIva, anulada*/)
                                   VALUES (/*:prefijo,*/:nombre, :direccion, :municipio, :idColonia, /*:numeroFactura,*/ :tipoFactura, :numeroRecibo, :codigoCliente, :codigoCobrador, :cuotaCable, :cuotaInternet, :fechaAbonado, :fechaVencimiento, :mesCargo, :tipoServicio, :estado, :anticipado, /*:cargoImpuesto,*/ :totalImpuesto/*, :totalIva, :anulada*/)";

                        $statement = $this->dbConnect->prepare($query2);
                        //$statement->bindParam(':prefijo', $key['prefijo']);
                        $statement->bindParam(':nombre', $key['nombre']);
                        $statement->bindParam(':direccion', $key['direccion']);
                        $statement->bindParam(':municipio', $key['id_municipio']);
                        $statement->bindParam(':idColonia', $key['id_colonia']);
                        //$statement->bindParam(':numeroFactura', $nFactura);
                        $statement->bindParam(':tipoFactura', $key['tipoComprobante']);
                        $statement->bindParam(':numeroRecibo', $key['numeroRecibo']);
                        $statement->bindParam(':codigoCliente', $key['codigoCliente']);
                        $statement->bindParam(':codigoCobrador', $key['codigoCobrador']);
                        $statement->bindParam(':cuotaCable', $key['montoAbonado']);
                        $statement->bindParam(':cuotaInternet', $cero);
                        $statement->bindParam(':fechaAbonado', $key['fechaRecibo']);
                        $statement->bindParam(':fechaVencimiento', $key['fechaVencimiento']);
                        $statement->bindParam(':mesCargo', $key['numMes']);
                        $statement->bindParam(':tipoServicio', $key['tipoServicio']);
                        $statement->bindParam(':estado', $cancelada);
                        $statement->bindParam(':anticipado', $key['anticipo']);
                        $statement->bindParam(':totalImpuesto', $key['impuesto']);
                        /*$statement->bindParam(':totalIva', $key['valorIva']);
                        $statement->bindParam(':anulada', $anulada);*/

                        $statement->execute();
                    }
                    elseif ($key['tipoServicio'] == "I") {
                        $query2 = "INSERT INTO tbl_abonos (/*prefijo,*/nombre, direccion, idMunicipio, idColonia, /*numeroFactura,*/ tipoFactura, numeroRecibo, codigoCliente, codigoCobrador, cuotaCable, cuotaInternet, fechaAbonado, fechaVencimiento, mesCargo, tipoServicio, estado, anticipado, /*cargoImpuesto,*/ totalImpuesto/*, totalIva, anulada*/)
                                   VALUES (/*:prefijo,*/:nombre, :direccion, :municipio, :idColonia, /*:numeroFactura,*/ :tipoFactura, :numeroRecibo, :codigoCliente, :codigoCobrador, :cuotaCable, :cuotaInternet, :fechaAbonado, :fechaVencimiento, :mesCargo, :tipoServicio, :estado, :anticipado, /*:cargoImpuesto,*/ :totalImpuesto/*, :totalIva, :anulada*/)";

                        $statement = $this->dbConnect->prepare($query2);
                        //$statement->bindParam(':prefijo', $key['prefijo']);
                        $statement->bindParam(':nombre', $key['nombre']);
                        $statement->bindParam(':direccion', $key['direccion']);
                        $statement->bindParam(':municipio', $key['id_municipio']);
                        $statement->bindParam(':idColonia', $key['id_colonia']);
                        //$statement->bindParam(':numeroFactura', $nFactura);
                        $statement->bindParam(':tipoFactura', $key['tipoComprobante']);
                        $statement->bindParam(':numeroRecibo', $key['numeroRecibo']);
                        $statement->bindParam(':codigoCliente', $key['codigoCliente']);
                        $statement->bindParam(':codigoCobrador', $key['codigoCobrador']);
                        $statement->bindParam(':cuotaCable', $cero);
                        $statement->bindParam(':cuotaInternet', $key['montoAbonado']);
                        $statement->bindParam(':fechaAbonado', $key['fechaRecibo']);
                        $statement->bindParam(':fechaVencimiento', $key['fechaVencimiento']);
                        $statement->bindParam(':mesCargo', $key['numMes']);
                        $statement->bindParam(':tipoServicio', $key['tipoServicio']);
                        $statement->bindParam(':estado', $cancelada);
                        $statement->bindParam(':anticipado', $key['anticipo']);
                        $statement->bindParam(':totalImpuesto', $key['impuesto']);
                        /*$statement->bindParam(':totalIva', $key['valorIva']);
                        $statement->bindParam(':anulada', $anulada);*/

                        $statement->execute();
                    }

                }

                $this->dbConnect = NULL;
                //return $result;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }

        public function migrarCargos_cxc()
        {
            try {
                // SQL query para traer datos de los productos
                $query = "SELECT * FROM ventas_cxc";
                // Preparación de sentencia
                $statement = $this->dbConnect->prepare($query);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                $cero = 0;
                $cancelada = "pendiente";

                foreach ($result as $key) {
                    if ($key['anulado'] == "T") {
                        $anulada = 1;
                    }else {
                        $anulada = 0;

                    }
                    if ($key['tipoServicio'] == "C") {
                        $nFactura = $key['prefijo']."-".$key['numeroComprobante'];
                        $query2 = "INSERT INTO tbl_cargos (/*prefijo,*/nombre, direccion, municipio, idColonia, numeroFactura, tipoFactura, codigoCliente, codigoCobrador, cuotaCable, cuotaInternet, fechaFactura, fechaVencimiento, mesCargo, tipoServicio, estado, anticipado, /*cargoImpuesto,*/ totalImpuesto, /*totalIva,*/ anulada)
                                   VALUES (/*:prefijo,*/:nombre, :direccion, :municipio, :idColonia, :numeroFactura, :tipoFactura, :codigoCliente, :codigoCobrador, :cuotaCable, :cuotaInternet, :fechaFactura, :fechaVencimiento, :mesCargo, :tipoServicio, :estado, :anticipado, /*:cargoImpuesto,*/ :totalImpuesto, /*:totalIva,*/ :anulada)";

                        $statement = $this->dbConnect->prepare($query2);
                        //$statement->bindParam(':prefijo', $key['prefijo']);
                        $statement->bindParam(':nombre', $key['nombre']);
                        $statement->bindParam(':direccion', $key['direccion']);
                        $statement->bindParam(':municipio', $key['municipio']);
                        $statement->bindParam(':idColonia', $key['idColonia']);
                        $statement->bindParam(':numeroFactura', $nFactura);
                        $statement->bindParam(':tipoFactura', $key['tipoComprobante']);
                        $statement->bindParam(':codigoCliente', $key['codigoCliente']);
                        $statement->bindParam(':codigoCobrador', $key['codigoCobrador']);
                        $statement->bindParam(':cuotaCable', $key['totalComprobante']);
                        $statement->bindParam(':cuotaInternet', $cero);
                        $statement->bindParam(':fechaFactura', $key['fechaComprobante']);
                        $statement->bindParam(':fechaVencimiento', $key['fechaVencimiento']);
                        $statement->bindParam(':mesCargo', $key['numMes']);
                        $statement->bindParam(':tipoServicio', $key['tipoServicio']);
                        $statement->bindParam(':estado', $cancelada);
                        $statement->bindParam(':anticipado', $key['anticipo']);
                        $statement->bindParam(':totalImpuesto', $key['impuesto']);
                        //$statement->bindParam(':totalIva', $key['valorIva']);
                        $statement->bindParam(':anulada', $anulada);

                        $statement->execute();
                    }
                    elseif ($key['tipoServicio'] == "I") {
                        $nFactura = $key['prefijo']."-".$key['numeroComprobante'];
                        $query2 = "INSERT INTO tbl_cargos (/*prefijo,*/nombre, direccion, municipio, idColonia, numeroFactura, tipoFactura, codigoCliente, codigoCobrador, cuotaCable, cuotaInternet, fechaFactura, fechaVencimiento, mesCargo, tipoServicio, estado, anticipado, /*cargoImpuesto,*/ totalImpuesto, /*totalIva,*/ anulada)
                                   VALUES (/*:prefijo,*/:nombre, :direccion, :municipio, :idColonia, :numeroFactura, :tipoFactura, :codigoCliente, :codigoCobrador, :cuotaCable, :cuotaInternet, :fechaFactura, :fechaVencimiento, :mesCargo, :tipoServicio, :estado, :anticipado, /*:cargoImpuesto,*/ :totalImpuesto, /*:totalIva,*/ :anulada)";

                        $statement = $this->dbConnect->prepare($query2);
                        //$statement->bindParam(':prefijo', $key['prefijo']);
                        $statement->bindParam(':nombre', $key['nombre']);
                        $statement->bindParam(':direccion', $key['direccion']);
                        $statement->bindParam(':municipio', $key['municipio']);
                        $statement->bindParam(':idColonia', $key['idColonia']);
                        $statement->bindParam(':numeroFactura', $nFactura);
                        $statement->bindParam(':tipoFactura', $key['tipoComprobante']);
                        $statement->bindParam(':codigoCliente', $key['codigoCliente']);
                        $statement->bindParam(':codigoCobrador', $key['codigoCobrador']);
                        $statement->bindParam(':cuotaCable', $cero);
                        $statement->bindParam(':cuotaInternet', $key['totalComprobante']);
                        $statement->bindParam(':fechaFactura', $key['fechaComprobante']);
                        $statement->bindParam(':fechaVencimiento', $key['fechaVencimiento']);
                        $statement->bindParam(':mesCargo', $key['numMes']);
                        $statement->bindParam(':tipoServicio', $key['tipoServicio']);
                        $statement->bindParam(':estado', $cancelada);
                        $statement->bindParam(':anticipado', $key['anticipo']);
                        $statement->bindParam(':totalImpuesto', $key['impuesto']);
                        //$statement->bindParam(':totalIva', $key['valorIva']);
                        $statement->bindParam(':anulada', $anulada);

                        $statement->execute();
                    }

                }

                $this->dbConnect = NULL;
                //return $result;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }
    }

    $md = new MigrarDatos();
    //$md->migrarAbonos();
    //$md->migrarCargos();
    $md->matchCargosAbonos();
    //$md->migrarCargos_cxc();
    //$md->migrarAbonos_cxc();
?>
