<?php
require_once('../../../php/connection.php');
session_start();
class EditarCliente extends ConectionDB
{
    public function EditarCliente()
    {
        parent::__construct();
    }

    public function editar(){
        try {

            /****************** DATOS GENERALES ***********************/
            $estado_cable = $_POST['cable']; // T o F
            $estado_internet = $_POST['internet']; // 1, 2, 3
            //var_dump($estado_cable);
            //var_dump($estado_internet);
            $codigo = $_POST['codigo'];
            $nContrato = $_POST['contrato'];
            $nFactura = $_POST['factura'];
            $nombre = $_POST['nombre'];
            /*$empresa = $_POST["empresa"];*/
            $nRegistro = $_POST['nrc'];
            $dui = $_POST['dui'];
            $lugarExp = $_POST['expedicion'];
            $nit = $_POST['nit'];
            $fechaNacimiento = $_POST['fechaNacimiento'];
            $direccion = $_POST['direccion'];
            $departamento = $_POST['departamento'];
            $municipio = $_POST['municipio'];
            $colonia = $_POST['colonia'];
            $direccionCobro = $_POST['direccionCobro'];
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

            /****************** OTROS DATOS ***********************/
            $cobrador = $_POST['cobrador'];
            $contacto1 = $_POST['rf1_nombre'];
            $contacto2 = $_POST['rp1_telefono'];
            $contacto3 = $_POST['rp1_direccion'];
            $telCon1 = $_POST['rp1_parentezco'];
            $telCon2 = $_POST['rf2_nombre'];
            $telCon3 = $_POST['rp2_telefono'];
            $paren1 = $_POST['rp2_direccion'];
            $paren2 = $_POST['rp2_parentezco'];
            $paren3 = $_POST['rf3_nombre'];
            $dir1 = $_POST['rp3_telefono'];
            $dir2 = $_POST['rp3_direccion'];
            $dir3 = $_POST['rp3_parentezco'];
            /****************** DATOS CABLE ***********************/
            if ($estado_cable == "T") {
              $date1 = $_POST['fechaInstalacionCable'];
              $date2 = str_replace('/', '-', $date1);
              $fechaInstalacion = date("Y-m-d", strtotime($date2));

              $date1 = $_POST['fechaPrimerFacturaCable'];
              $date2 = str_replace('/', '-', $date1);
              $fechaPrimerFactura = date("Y-m-d", strtotime($date2));

              $date1 = $_POST['fechaSuspensionCable'];
              $date2 = str_replace('/', '-', $date1);
              $fechaSuspensionCable = date("Y-m-d", strtotime($date2));

              $date1 = $_POST['vencimientoContratoCable'];
              $date2 = str_replace('/', '-', $date1);
              $vencimientoCable = date("Y-m-d", strtotime($date2));

              $date1 = $_POST['inicioContratoCable'];
              $date2 = str_replace('/', '-', $date1);
              $fechaInicioContratoCable = date("Y-m-d", strtotime($date2));
              //$fechaSuspensionCable = $_POST[''];
              $date1 = $_POST['fechaReconexionCable'];
              $date2 = str_replace('/', '-', $date1);
              $fechaReinstalacionCable = date("Y-m-d", strtotime($date2));
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

            $diaCobro = $_POST['diaGenerarFacturaCable'];
            if (isset($_POST['cortesia'])) {
                $cortesia = $_POST['cortesia'];
            }else {
                $cortesia = "F";
            }
            var_dump($cortesia);
            $cuotaMensualCable = $_POST['cuotaMensualCable'];
            $prepago = $_POST['prepago'];
            //$tipoComprobante = $_POST[''];
            $tipoServicio = $_POST['tipoServicioCable'];
            $periodoContratoCable = $_POST['mesesContratoCable'];

            $tecnicoCable = $_POST['encargadoInstalacionCable'];
            $codigoCobrador = $_POST['cobrador'];
            $tecnicoInternet = $_POST['encargadoInstalacionInter'];
            $mactv = $_POST['mactv'];
            $nDerivaciones = $_POST['derivaciones'];
            $direccionCable = $_POST['direccionCable'];


            /****************** DATOS INTERNET ***********************/

            if ($estado_internet == 1 || $estado_internet == 2) {
              $date1 = $_POST['fechaInstalacionInternet'];
              $date2 = str_replace('/', '-', $date1);
              $fechaInstalacionInter = date("Y-m-d", strtotime($date2));

              $date1 = $_POST['fechaPrimerFacturaInternet'];
              $date2 = str_replace('/', '-', $date1);
              $fechaPrimerFacturaInter = date("Y-m-d", strtotime($date2));

              $date1 = $_POST['vencimientoContratoInternet'];
              $date2 = str_replace('/', '-', $date1);
              $vencimientoInternet = date("Y-m-d", strtotime($date2));

              $date1 = $_POST['ultimaRenovacionInternet'];
              $date2 = str_replace('/', '-', $date1);
              $ultimaRenovacionInternet = date("Y-m-d", strtotime($date2));

              $date1 = $_POST['fechaSuspencionInternet'];
              $date2 = str_replace('/', '-', $date1);
              $fechaSuspencionInternet = date("Y-m-d", strtotime($date2));

              $date1 = $_POST['fechaReconexionInternet'];
              $date2 = str_replace('/', '-', $date1);
              $fechaReconexionInternet = date("Y-m-d", strtotime($date2));
            }else {

              $fechaInstalacionInter = "";

              $fechaPrimerFacturaInter = "";

              $vencimientoInternet = "";

              $ultimaRenovacionInternet = "";

              $fechaSuspencionInternet = "";

              $fechaReconexionInternet = "";
            }

            $tipoServicioInternet = $_POST['tipoServicioInternet']; //Prepago o pospago
            $periodoContratoInternet = $_POST['mesesContratoInternet'];
            $diaCobroInter = $_POST['diaGenerarFacturaInternet'];
            $velocidadInter = $_POST['velocidadInternet'];
            $cuotaMensualInter = $_POST['cuotaMensualInternet'];
            $tipoClienteInter = $_POST['tipoCliente'];
            $tecnologia = $_POST['tecnologia'];
            $nContratoInter = $_POST['nContratoVigente'];

            $promocion = $_POST['promocion'];
            $promocionDesde = $_POST['promocionDesde'];
            $promocionHasta = $_POST['promocionHasta'];
            $cuotaPromocion = $_POST['cuotaPromocion'];
            $direccionInternet = $_POST['direccionInternet'];
            $colilla = $_POST['colilla'];
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

            $query = "UPDATE clientes SET servicio_suspendido=:servicioSuspendido, estado_cliente_in=:estadoClienteIn, numero_contrato=:nContrato, num_factura=:nFactura, nombre=:nombre, /*empresa=:empresa,*/ numero_nit=:nit, numero_dui=:dui, lugar_exp=:lugarExp, num_registro=:nrc, saldoCable=:saldoCable, saldoInternet=:saldoInter, direccion_cobro=:dirCobro, direccion=:dir,
                      fecha_nacimiento=:fechaNacimiento, id_departamento=:idDepartamento, id_municipio=:idMunicipio, id_colonia=:idColonia, telefonos=:telefonos, tel_trabajo=:telTrabajo, correo_electronico=:email, profesion=:ocupacion, id_cuenta=:cuentaContable, forma_pago=:formaPago, tipo_comprobante=:tipoComprobante, saldo_actual=:saldoActual, facebook=:facebook, contactos=:contactos, contacto2=:contacto2, contacto3=:contacto3,
                      telcon1=:telcon1, telcon2=:telcon2, telcon3=:telcon3, paren1=:paren1, paren2=:paren2, paren3=:paren3, dir1=:dir1, dir2=:dir2, dir3=:dir3,
                      fecha_instalacion=:fechaInstalacionCable, fecha_primer_factura=:fechaPrimerFacturaCable, fecha_suspencion=:fechaSuspensionCable, exento=:exento, dia_cobro=:diaCobro, servicio_cortesia=:servicioCortesia, valor_cuota=:cuotaCable, prepago=:prepago, tipo_servicio=:tipoServicio, mactv=:mactv, periodo_contrato_ca=:periodoContratoCable, vencimiento_ca=:vencimientoCable, fecha_reinstalacion=:fechaReconexCable, id_tecnico=:idTecnico, cod_cobrador=:codigoCobrador, numero_derivaciones=:nDerivaciones, dire_cable=:direCable, fecha_instalacion_in=:fechaInstalacionIn, fecha_primer_factura_in=:fechaPrimerFacturaIn, tipo_servicio_in=:tipoServicioIn, periodo_contrato_int=:periodoContratoIn, dia_corbo_in=:diaCobroIn, id_velocidad=:idVelocidadIn, cuota_in=:cuotaIn, id_tipo_cliente=:tipoClienteIn,
                      tecnologia=:tecnologia, entrega_calidad=:enCalidad, no_contrato_inter=:noContratoIn,
                      vencimiento_in=:vencimientoContratoIn, ult_ren_in=:ultRenIn, fecha_suspencion_in=:fechaSuspensionIn, fecha_reconexion_in=:fechaReconexIn, id_promocion=:promoIn, dese_promocion_in=:promoInDesde, hasta_promocion_in=:promoInHasta, cuota_promocion=:cuotaPromoIn, dire_internet=:direInter, id_tecnico_in=:idTecnicoIn, colilla=:colilla, marca_modem=:modelo, recep_modem=:recepcion, wanip=:wanip, mac_modem=:macModem, trans_modem=:trans, coordenadas=:coordenadas, serie_modem=:serieModem, ruido_modem=:ruido, dire_telefonia=:nodo, clave_modem=:claveWifi, ult_usuario=:ultUser WHERE cod_cliente = :codigo";

            $stmt = $this->dbConnect->prepare($query);
            $stmt->execute(array(
                        ':servicioSuspendido' => $estado_cable,
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
