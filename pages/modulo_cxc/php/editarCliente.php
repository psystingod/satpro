<?php
require_once('../../../php/connection.php');
session_start();
class EditarCliente extends ConectionDB
{
    public function EditarCliente()
    {
        if(!isset($_SESSION))
        {
      	  session_start();
        }
        parent::__construct ($_SESSION['db']);
    }

    public function editar(){
        try {
            date_default_timezone_set('America/El_Salvador');
            /****************** DATOS GENERALES ***********************/
            $sinServicio = "";
            $estado_cable = $_POST['cable']; //OSEA SI ESTA SUSPENDIDO O NO // F,T,S
            if ($estado_cable == "T") {
                $sinServicio = "F";
            }
            elseif ($estado_cable == "F") {
                $sinServicio = "F";
            }
            elseif ($estado_cable == "S") {
                $sinServicio = "T";
                $estado_cable = null;
            }
            $estado_internet = $_POST['internet']; // 1, 2, 3
            //var_dump($estado_cable);
            //var_dump($estado_internet);
            $codigo = $_POST['codigo'];
            $nContrato = $_POST['contrato'];
            $nFactura = $_POST['factura'];
            $nombre = strtoupper($_POST['nombre']);
            /*$empresa = $_POST["empresa"];*/
            $nRegistro = $_POST['nrc'];
            $dui = $_POST['dui'];
            $lugarExp = $_POST['expedicion'];
            $nit = $_POST['nit'];

            if (strlen($_POST['fechaNacimiento']) < 8) {
                $fechaNacimiento = "";
            }else {
                $date1 = $_POST['fechaNacimiento'];
                $date2 = str_replace('/', '-', $date1);
                $fechaNacimiento = date("Y-m-d", strtotime($date2));
            }

            $direccion = strtoupper($_POST['direccion']);
            $departamento = $_POST['departamento'];
            $municipio = $_POST['municipio'];
            $colonia = $_POST['colonia'];
            $direccionCobro = strtoupper($_POST['direccionCobro']);
            $telefonos = $_POST['telefono'];
            $telTrabajo = $_POST['telefonoTrabajo'];
            $ocupacion = $_POST['ocupacion'];
            $cuentaContable = $_POST['cuentaContable'];
            $formaFacturar = $_POST['formaFacturar']; //Contado o al crédito
            $saldoCable = $_POST['saldoCable'];
            $calidad = $_POST['enCalidad'];
            $saldoInter = $_POST['saldoInternet'];
            $saldoActual = $_POST['saldoActual'];
            if (strlen($saldoActual) < 2) {
                $saldoActual = 0;
            }
            //$diasCredito = $_POST['diasCredito'];
            //$limiteCredito = $_POST['limiteCredito'];
            $tipoComprobante = $_POST['tipoComprobante']; //Credito fiscal o consumidor final
            $facebook = $_POST['facebook'];
            $correo = $_POST['correo'];
            $observaciones = $_POST['notas'];

            /****************** OTROS DATOS ***********************/
            $cobrador = $_POST['cobrador'];
            $contacto1 = $_POST['rf1_nombre'];
            $contacto2 = $_POST['rf2_nombre'];
            $contacto3 = $_POST['rf3_nombre'];
            $telCon1 = $_POST['rp1_telefono'];
            $telCon2 = $_POST['rp2_telefono'];
            $telCon3 = $_POST['rp3_telefono'];
            $paren1 = $_POST['rp1_parentezco'];
            $paren2 = $_POST['rp2_parentezco'];
            $paren3 = $_POST['rp3_parentezco'];
            $dir1 = $_POST['rp1_direccion'];
            $dir2 = $_POST['rp2_direccion'];
            $dir3 = $_POST['rp3_direccion'];
            /****************** DATOS CABLE ***********************/
            if ($estado_cable == "F" || $estado_cable == "T" || $estado_cable == "") {

                if (strlen($_POST['fechaInstalacionCable']) < 8) {
                    $fechaInstalacion = "";
                }else {
                    $date1 = trim($_POST['fechaInstalacionCable']);
                    $date2 = str_replace('/', '-', $date1);
                    $fechaInstalacion = date("Y-m-d", strtotime($date2));
                }

                if (strlen($_POST['fechaPrimerFacturaCable']) < 8) {
                    $fechaPrimerFactura = "";
                }else {
                    $date1 = trim($_POST['fechaPrimerFacturaCable']);
                    $date2 = str_replace('/', '-', $date1);
                    $fechaPrimerFactura = date("Y-m-d", strtotime($date2));
                }

                if (strlen($_POST['fechaSuspensionCable']) < 8) {
                    $fechaSuspensionCable = "";
                }else {
                    $date1 = trim($_POST['fechaSuspensionCable']);
                    $date2 = str_replace('/', '-', $date1);
                    $fechaSuspensionCable = date("Y-m-d", strtotime($date2));
                }

                if (strlen($_POST['vencimientoContratoCable']) < 8) {
                    $vencimientoCable = "";
                }else {
                    $date1 = trim($_POST['vencimientoContratoCable']);
                    $date2 = str_replace('/', '-', $date1);
                    $vencimientoCable = date("Y-m-d", strtotime($date2));
                }

                if (strlen($_POST['inicioContratoCable']) < 8) {
                    $fechaInicioContratoCable = "";
                }else {
                    $date1 = trim($_POST['inicioContratoCable']);
                    $date2 = str_replace('/', '-', $date1);
                    $fechaInicioContratoCable = date("Y-m-d", strtotime($date2));
                }

                if (strlen($_POST['fechaReconexionCable']) < 8) {
                    $fechaReinstalacionCable = "";
                }else {
                    $date1 = trim($_POST['fechaReconexionCable']);
                    $date2 = str_replace('/', '-', $date1);
                    $fechaReinstalacionCable = date("Y-m-d", strtotime($date2));
                }

            }else {

              $fechaInstalacion = "";

              $fechaPrimerFactura = "";

              $fechaSuspensionCable = "";

              $vencimientoCable = "";

              $fechaInicioContratoCable = "";

              $fechaReinstalacionCable = "";
            }

            if (isset($_POST['exento'])) {
                $exento = $_POST['exento'];
            }else {
                $exento = "F";
            }

            $diaCobro = trim($_POST['diaGenerarFacturaCable']);
            $cuotaMensualCable = trim($_POST['cuotaMensualCable']);
            $prepago = trim($_POST['prepago']);

            $tipoServicio = $_POST['tipoServicioCable'];
            $periodoContratoCable = trim($_POST['mesesContratoCable']);

            if (isset($_POST['cortesia'])) {
                $cortesia = $_POST['cortesia'];
            }else {
                $cortesia = "F";
            }
            //var_dump($cortesia);

            $tecnicoCable = $_POST['encargadoInstalacionCable'];
            $codigoCobrador = $_POST['cobrador'];
            $tecnicoInternet = $_POST['encargadoInstalacionInter'];
            $mactv = $_POST['mactv'];
            $nDerivaciones = $_POST['derivaciones'];
            $direccionCable = strtoupper($_POST['direccionCable']);


            /****************** DATOS INTERNET ***********************/

            if ($estado_internet == 1 || $estado_internet == 2 || $estado_internet == 3) {
                if (strlen($_POST['fechaInstalacionInternet']) < 8) {
                    $fechaInstalacionInter = "";
                }else {
                    $date1 = trim($_POST['fechaInstalacionInternet']);
                    $date2 = str_replace('/', '-', $date1);
                    $fechaInstalacionInter = date("Y-m-d", strtotime($date2));
                }

                if (strlen($_POST['fechaPrimerFacturaInternet']) < 8) {
                    $fechaPrimerFacturaInter = "";
                }else {
                    $date1 = trim($_POST['fechaPrimerFacturaInternet']);
                    $date2 = str_replace('/', '-', $date1);
                    $fechaPrimerFacturaInter = date("Y-m-d", strtotime($date2));
                }

                if (strlen($_POST['vencimientoContratoInternet']) < 8) {
                    $vencimientoInternet = "";
                }else {
                    $date1 = trim($_POST['vencimientoContratoInternet']);
                    $date2 = str_replace('/', '-', $date1);
                    $vencimientoInternet = date("Y-m-d", strtotime($date2));
                }

                if (strlen($_POST['ultimaRenovacionInternet']) < 8) {
                    $ultimaRenovacionInternet = "";
                }else {
                    $date1 = trim($_POST['ultimaRenovacionInternet']);
                    $date2 = str_replace('/', '-', $date1);
                    $ultimaRenovacionInternet = date("Y-m-d", strtotime($date2));
                }

                if (strlen($_POST['fechaSuspencionInternet']) < 8) {
                    $fechaSuspencionInternet = "";
                }else {
                    $date1 = trim($_POST['fechaSuspencionInternet']);
                    $date2 = str_replace('/', '-', $date1);
                    $fechaSuspencionInternet = date("Y-m-d", strtotime($date2));
                }

                if (strlen($_POST['fechaReconexionInternet']) < 8) {
                    $fechaReconexionInternet = "";
                }else {
                    $date1 = trim($_POST['fechaReconexionInternet']);
                    $date2 = str_replace('/', '-', $date1);
                    $fechaReconexionInternet = date("Y-m-d", strtotime($date2));
                }

              $costoInstalacionIn = trim($_POST['costoInstalacionIn']);
            }else {

              $fechaInstalacionInter = "";

              $fechaPrimerFacturaInter = "";

              $vencimientoInternet = "";

              $ultimaRenovacionInternet = "";

              $fechaSuspencionInternet = "";

              $fechaReconexionInternet = "";
            }

            $tipoServicioInternet = $_POST['tipoServicioInternet']; //Prepago o pospago
            $periodoContratoInternet = trim($_POST['mesesContratoInternet']);
            $diaCobroInter = trim($_POST['diaGenerarFacturaInternet']);
            $velocidadInter = $_POST['velocidadInternet'];
            $cuotaMensualInter = trim($_POST['cuotaMensualInternet']);
            $tipoClienteInter = $_POST['tipoCliente'];
            $tecnologia = $_POST['tecnologia'];
            $nContratoInter = $_POST['nContratoVigente'];

            $promocion = $_POST['promocion'];
            //$promocionDesde = $_POST['promocionDesde'];
            if (strlen($_POST['promocionDesde']) < 8) {
                $promocionDesde = "";
            }else {
                $promocionDesde = DateTime::createFromFormat('d/m/Y', trim($_POST['promocionDesde']));
                $promocionDesde = $promocionDesde->format('Y-m-d');
            }
            $promocionHasta = $_POST['promocionHasta'];
            $cuotaPromocion = $_POST['cuotaPromocion'];
            $direccionInternet = strtoupper($_POST['direccionInternet']);
            $colilla = $_POST['colilla'];
            $costoInstalacionIn = $_POST['costoInstalacionIn'];
            $nodo = $_POST['nodo'];
            $modelo = $_POST['modelo'];
            $recepcion = $_POST['recepcion'];
            $wanip = $_POST['wanip'];
            $mac = $_POST['mac'];
            $transmision = $_POST['transmision'];
            $coordenadas = $_POST['coordenadas'];
            $serie = $_POST['serie'];
            $ruido = $_POST['ruido'];
            $wifiClave = $_POST['claveWifi'];
            $ultUser = $_SESSION['nombres']." ".$_SESSION['apellidos'];
            $fechaHora = date('d/m/Y h:i:s');

            $query = "UPDATE clientes SET servicio_suspendido=:servicioSuspendido, sin_servicio=:sinServicio, estado_cliente_in=:estadoClienteIn, numero_contrato=:nContrato, num_factura=:nFactura, nombre=:nombre, /*empresa=:empresa,*/ numero_nit=:nit, numero_dui=:dui, lugar_exp=:lugarExp, num_registro=:nrc, saldoCable=:saldoCable, saldoInternet=:saldoInter, direccion_cobro=:dirCobro, direccion=:dir,
                      fecha_nacimiento=:fechaNacimiento, id_departamento=:idDepartamento, id_municipio=:idMunicipio, id_colonia=:idColonia, telefonos=:telefonos, tel_trabajo=:telTrabajo, correo_electronico=:email, profesion=:ocupacion, id_cuenta=:cuentaContable, forma_pago=:formaPago, tipo_comprobante=:tipoComprobante, saldo_actual=:saldoActual, facebook=:facebook, contactos=:contactos, contacto2=:contacto2, contacto3=:contacto3,
                      telcon1=:telcon1, telcon2=:telcon2, telcon3=:telcon3, paren1=:paren1, paren2=:paren2, paren3=:paren3, dir1=:dir1, dir2=:dir2, dir3=:dir3,observaciones=:observaciones,
                      fecha_instalacion=:fechaInstalacionCable, fecha_primer_factura=:fechaPrimerFacturaCable, fecha_suspencion=:fechaSuspensionCable, exento=:exento, dia_cobro=:diaCobro, servicio_cortesia=:servicioCortesia, valor_cuota=:cuotaCable, prepago=:prepago, tipo_servicio=:tipoServicio, mactv=:mactv, periodo_contrato_ca=:periodoContratoCable, vencimiento_ca=:vencimientoCable, fecha_reinstalacion=:fechaReconexCable, id_tecnico=:idTecnico, cod_cobrador=:codigoCobrador, numero_derivaciones=:nDerivaciones, dire_cable=:direCable, fecha_instalacion_in=:fechaInstalacionIn, fecha_primer_factura_in=:fechaPrimerFacturaIn, tipo_servicio_in=:tipoServicioIn, periodo_contrato_int=:periodoContratoIn, dia_corbo_in=:diaCobroIn, id_velocidad=:idVelocidadIn, cuota_in=:cuotaIn, id_tipo_cliente=:tipoClienteIn,
                      tecnologia=:tecnologia, entrega_calidad=:enCalidad, no_contrato_inter=:noContratoIn,
                      vencimiento_in=:vencimientoContratoIn, ult_ren_in=:ultRenIn, fecha_suspencion_in=:fechaSuspensionIn, fecha_reconexion_in=:fechaReconexIn, id_promocion=:promoIn, dese_promocion_in=:promoInDesde, hasta_promocion_in=:promoInHasta, cuota_promocion=:cuotaPromoIn, dire_internet=:direInter, id_tecnico_in=:idTecnicoIn, costo_instalacion_in=:costoInstalacionIn, colilla=:colilla, marca_modem=:modelo, recep_modem=:recepcion, wanip=:wanip, mac_modem=:macModem, trans_modem=:trans, coordenadas=:coordenadas, serie_modem=:serieModem, ruido_modem=:ruido, dire_telefonia=:nodo, clave_modem=:claveWifi, ult_usuario=:ultUser WHERE cod_cliente = :codigo";

            $stmt = $this->dbConnect->prepare($query);
            $stmt->execute(array(
                        ':servicioSuspendido' => $estado_cable,
                        ':sinServicio' => $sinServicio,
                        ':estadoClienteIn' => $estado_internet,
                        ':nContrato' => $nContrato,
                        ':nFactura' => $nFactura,
                        ':nombre' => $nombre,
                        /*':empresa' => $empresa,*/
                        ':nit' => $nit,
                        ':dui' => $dui,
                        ':lugarExp' => $lugarExp,
                        ':nrc' => $nRegistro,
                        ':saldoCable' => $saldoCable,
                        ':saldoInter' => $saldoInter,
                        ':dirCobro' => $direccionCobro,
                        ':dir' => $direccion,
                        ':fechaNacimiento' => $fechaNacimiento,
                        ':idDepartamento' => $departamento,
                        ':idMunicipio' => $municipio,
                        ':idColonia' => $colonia,
                        ':telefonos' => $telefonos,
                        ':telTrabajo' => $telTrabajo,
                        ':email' => $correo,
                        ':ocupacion' => $ocupacion,
                        ':cuentaContable' => $cuentaContable,
                        ':formaPago' => $formaFacturar,
                        ':tipoComprobante' => $tipoComprobante,
                        ':saldoActual' => $saldoActual,
                        ':facebook' => $facebook,
                        ':contactos' => $contacto1,
                        ':contacto2' => $contacto2,
                        ':contacto3' => $contacto3,
                        ':telcon1' => $telCon1,
                        ':telcon2' => $telCon2,
                        ':telcon3' => $telCon3,
                        ':paren1' => $paren1,
                        ':paren2' => $paren2,
                        ':paren3' => $paren3,
                        ':dir1' => $dir1,
                        ':dir2' => $dir2,
                        ':dir3' => $dir3,
                        ':observaciones' => $observaciones,
                        ':fechaInstalacionCable' => $fechaInstalacion,
                        ':fechaPrimerFacturaCable' => $fechaPrimerFactura,
                        ':fechaSuspensionCable' => $fechaSuspensionCable,
                        ':exento' => $exento,
                        ':diaCobro' => $diaCobro,
                        ':servicioCortesia' => $cortesia,
                        ':cuotaCable' => $cuotaMensualCable,
                        ':prepago' => $prepago,
                        ':tipoServicio' => $tipoServicio,
                        ':mactv' => $mactv,
                        ':periodoContratoCable' => $periodoContratoCable,
                        ':vencimientoCable' => $vencimientoCable,
                        ':fechaReconexCable' => $fechaReinstalacionCable,
                        ':idTecnico' => $tecnicoCable,
                        ':codigoCobrador' => $codigoCobrador,
                        ':nDerivaciones' => $nDerivaciones,
                        ':direCable' => $direccionCable,
                        ':fechaInstalacionIn' => $fechaInstalacionInter,
                        ':fechaPrimerFacturaIn' => $fechaPrimerFacturaInter,
                        ':tipoServicioIn' => $tipoServicioInternet,
                        ':periodoContratoIn' => $periodoContratoInternet,
                        ':diaCobroIn' => $diaCobroInter,
                        ':idVelocidadIn' => $velocidadInter,
                        ':cuotaIn' => $cuotaMensualInter,
                        ':tipoClienteIn' => $tipoClienteInter,
                        ':tecnologia' => $tecnologia,
                        ':enCalidad' => $calidad,
                        ':noContratoIn' => $nContratoInter,
                        ':vencimientoContratoIn' => $vencimientoInternet,
                        ':ultRenIn' => $ultimaRenovacionInternet,
                        ':fechaSuspensionIn' => $fechaSuspencionInternet,
                        ':fechaReconexIn' => $fechaReconexionInternet,
                        ':promoIn' => $promocion,
                        ':promoInDesde' => $promocionDesde,
                        ':promoInHasta' => $promocionHasta,
                        ':cuotaPromoIn' => $cuotaPromocion,
                        ':direInter' => $direccionInternet,
                        ':idTecnicoIn' => $tecnicoInternet,
                        ':costoInstalacionIn' => $costoInstalacionIn,
                        ':colilla' => $colilla,
                        ':modelo' => $modelo,
                        ':recepcion' => $recepcion,
                        ':wanip' => $wanip,
                        ':macModem' => $mac,
                        ':trans' => $transmision,
                        ':coordenadas' => $coordenadas,
                        ':serieModem' => $serie,
                        ':ruido' => $ruido,
                        ':nodo' => $nodo,
                        ':claveWifi' => $wifiClave,
                        ':ultUser' => $ultUser,
                        ':codigo' => $codigo
                        ));
            //$codigoClienteNuevo = $this->dbConnect->lastInsertId();

            if (isset($_POST['check1'])) {
                $caja1 = $_POST['caja1'];
                $cas1 = $_POST['cas1'];
                $sn1 = $_POST['sn1'];
                $sql1 = "UPDATE tbl_tv_box SET boxNum=:caja1, cast=:cas1, serialBox=:sn1, clientCode=:clientCode, activeDate=:activeDate, user=:user)";
                $stmt = $this->dbConnect->prepare($sql1);
                $stmt->bindValue(':caja1', $caja1);
                $stmt->bindValue(':cas1', $cas1);
                $stmt->bindValue(':sn1', $sn1);
                $stmt->bindValue(':clientCode', $codigo);
                $stmt->bindValue(':activeDate', $fechaHora);
                $stmt->bindValue(':user', $ultUser);

                $stmt->execute();
            }
            if (isset($_POST['check2'])) {
                $caja2 = $_POST['caja2'];
                $cas2 = $_POST['cas2'];
                $sn2 = $_POST['sn2'];
                $sql2 = "UPDATE tbl_tv_box SET boxNum=:caja2, cast=:cas2, serialBox=:sn2, clientCode=:clientCode, activeDate=:activeDate, user=:user)";
                $stmt = $this->dbConnect->prepare($sql2);
                $stmt->bindValue(':caja2', $caja2);
                $stmt->bindValue(':cas2', $cas2);
                $stmt->bindValue(':sn2', $sn2);
                $stmt->bindValue(':clientCode', $codigo);
                $stmt->bindValue(':activeDate', $fechaHora);
                $stmt->bindValue(':user', $ultUser);

                $stmt->execute();
            }
            if (isset($_POST['check3'])) {
                $caja3 = $_POST['caja3'];
                $cas3 = $_POST['cas3'];
                $sn3 = $_POST['sn3'];
                $sql3 = "UPDATE tbl_tv_box SET boxNum=:caja3, cast=:cas3, serialBox=:sn3, clientCode=:clientCode, activeDate=:activeDate, user=:user)";
                $stmt = $this->dbConnect->prepare($sql3);
                $stmt->bindValue(':caja3', $caja3);
                $stmt->bindValue(':cas3', $cas3);
                $stmt->bindValue(':sn3', $sn3);
                $stmt->bindValue(':clientCode', $codigo);
                $stmt->bindValue(':activeDate', $fechaHora);
                $stmt->bindValue(':user', $ultUser);

                $stmt->execute();
            }
            if (isset($_POST['check4'])) {
                $caja4 = $_POST['caja4'];
                $cas4 = $_POST['cas4'];
                $sn4 = $_POST['sn4'];
                $sql4 = "UPDATE tbl_tv_box SET boxNum=:caja4, cast=:cas4, serialBox=:sn4, clientCode=:clientCode, activeDate=:activeDate, user=:user)";
                $stmt = $this->dbConnect->prepare($sql3);
                $stmt->bindValue(':caja4', $caja4);
                $stmt->bindValue(':cas4', $cas4);
                $stmt->bindValue(':sn4', $sn4);
                $stmt->bindValue(':clientCode', $codigo);
                $stmt->bindValue(':activeDate', $fechaHora);
                $stmt->bindValue(':user', $ultUser);

                $stmt->execute();
            }

            header('Location: ../infoCliente.php?id='.$codigo);

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }
}
$EditarCliente = new EditarCliente();
$EditarCliente->editar();
?>
