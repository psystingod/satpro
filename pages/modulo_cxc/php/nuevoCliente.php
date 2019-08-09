<?php
require_once('../../../php/connection.php');
session_start();
class NuevoCliente extends ConectionDB
{
    public function NuevoCliente()
    {
        parent::__construct();
    }

    public function guardar(){
        try {

            /****************** DATOS GENERALES ***********************/
            $estado_cable = $_POST['cable']; // T o F
            $estado_internet = $_POST['internet']; // 1, 2, 3
            var_dump($estado_cable);
            var_dump($estado_internet);
            $codigo = $_POST['codigo'];
            $nContrato = $_POST['contrato'];
            $nFactura = $_POST['factura'];
            $nombre = $_POST['nombre'];
            $empresa = $_POST["empresa"];
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
            $fechaInstalacion = $_POST['fechaInstalacionCable'];
            $fechaPrimerFactura = $_POST['fechaPrimerFacturaCable'];
            $fechaSuspensionCable = $_POST['fechaSuspensionCable'];
            $exento = $_POST['exento'];
            $diaCobro = $_POST['diaGenerarFacturaCable'];
            $cortesia = $_POST['cortesia'];
            $cuotaMensualCable = $_POST['cuotaMensualCable'];
            $prepago = $_POST['prepago'];
            //$tipoComprobante = $_POST[''];
            $tipoServicio = $_POST['tipoServicioCable'];
            $periodoContratoCable = $_POST['mesesContratoCable'];
            $vencimientoCable = $_POST['vencimientoContratoCable'];
            $fechaInicioContratoCable = $_POST['inicioContratoCable'];
            //$fechaSuspensionCable = $_POST[''];
            $fechaReinstalacionCable = $_POST['fechaReconexionCable'];
            $tecnicoCable = $_POST['encargadoInstalacionCable'];
            $codigoCobrador = $_POST['cobrador'];
            $tecnicoInternet = $_POST['encargadoInstalacionInter'];
            $mactv = $_POST['mactv'];
            $nDerivaciones = $_POST['derivaciones'];
            $direccionCable = $_POST['direccionCable'];

            /****************** DATOS INTERNET ***********************/
            $fechaInstalacionInter = $_POST['fechaInstalacionInternet'];
            $fechaPrimerFacturaInter = $_POST['fechaPrimerFacturaInternet'];
            $tipoServicioInternet = $_POST['tipoServicioInternet']; //Prepago o pospago
            $periodoContratoInternet = $_POST['mesesContratoInternet'];
            $diaCobroInter = $_POST['diaGenerarFacturaInternet'];
            $velocidadInter = $_POST['velocidadInternet'];
            $cuotaMensualInter = $_POST['cuotaMensualInternet'];
            $tipoClienteInter = $_POST['tipoCliente'];
            $tecnologia = $_POST['tecnologia'];
            $nContratoInter = $_POST['nContratoVigente'];
            $vencimientoInternet = $_POST['vencimientoContratoInternet'];
            $ultimaRenovacionInternet = $_POST['ultimaRenovacionInternet'];
            $fechaSuspencionInternet = $_POST['fechaSuspencionInternet'];
            $fechaReconexionInternet = $_POST['fechaReconexionInternet'];
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
            $creadoPor = $_SESSION['nombres']." ".$_SESSION['apellidos'];
            $fechaHora = date('d/m/Y h:i:s');

            $query = "INSERT INTO clientes(servicio_suspendido, estado_cliente_in, numero_contrato, num_factura, nombre, empresa, numero_nit, numero_dui, lugar_exp, num_registro, saldoCable, saldoInternet, direccion_cobro, direccion, fecha_nacimiento, id_departamento, id_municipio, id_colonia, telefonos, tel_trabajo, correo_electronico, profesion, id_cuenta, forma_pago, tipo_comprobante, saldo_actual, facebook, contactos, contacto2, contacto3,
                      telcon1, telcon2, telcon3, paren1, paren2, paren3, dir1, dir2, dir3,
                      fecha_instalacion, fecha_primer_factura, fecha_suspencion, exento, dia_cobro, servicio_cortesia, valor_cuota, prepago, tipo_servicio, mactv, periodo_contrato_ca, vencimiento_ca, fecha_reinstalacion, id_tecnico, cod_cobrador, numero_derivaciones, dire_cable, fecha_instalacion_in, fecha_primer_factura_in, tipo_servicio_in, periodo_contrato_int, dia_corbo_in, id_velocidad, cuota_in, id_tipo_cliente, tecnologia, no_contrato_inter,
                      vencimiento_in, ult_ren_in, fecha_suspencion_in, fecha_reconexion_in, id_promocion, dese_promocion_in, hasta_promocion_in, cuota_promocion, dire_internet, id_tecnico_in, colilla, marca_modem, recep_modem, wanip, mac_modem, trans_modem, coordenadas, serie_modem, ruido_modem, dire_telefonia, clave_modem, creado_por, fecha_hora_creacion)
                      VALUES(:servicioSuspendido, :estadoClienteIn, :nContrato, :nFactura, :nombre, :empresa, :nit, :dui, :lugarExp, :nrc, :saldoCable, :saldoInter, :dirCobro, :dir, :fechaNacimiento, :idDepartamento, :idMunicipio, :idColonia, :telefonos, :telTrabajo, :email, :ocupacion, :cuentaContable, :formaPago, :tipoComprobante, :saldoActual, :facebook, :contactos, :contacto2, :contacto3, :telcon1, :telcon2, :telcon3, :paren1, :paren2, :paren3, :dir1, :dir2, :dir3,
                      :fechaInstalacionCable, :fechaPrimerFacturaCable, :fechaSuspensionCable, :exento, :diaCobro, :servicioCortesia, :cuotaCable, :prepago, :tipoServicio, :mactv, :periodoContratoCable, :vencimientoCable, :fechaReconexCable, :idTecnico, :codigoCobrador, :nDerivaciones, :direCable, :fechaInstalacionIn, :fechaPrimerFacturaIn, :tipoServicioIn, :periodoContratoIn, :diaCobroIn, :idVelocidadIn, :cuotaIn, :tipoClienteIn, :tecnologia, :noContratoIn,
                      :vencimientoContratoIn, :ultRenIn, :fechaSuspensionIn, :fechaReconexIn, :promoIn, :promoInDesde, :promoInHasta, :cuotaPromoIn, :direInter, :idTecnicoIn, :colilla, :modelo, :recepcion, :wanip, :macModem, :trans, :coordenadas, :serieModem, :ruido, :nodo, :claveWifi, :creadoPor, :fechaHoraCreado)";

            $stmt = $this->dbConnect->prepare($query);
            $stmt->execute(array(
                        ':servicioSuspendido' => $estado_cable,
                        ':estadoClienteIn' => $estado_internet,
                        ':nContrato' => $nContrato,
                        ':nFactura' => $nFactura,
                        ':nombre' => $nombre,
                        ':empresa' => $empresa,
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
                        ':creadoPor' => $creadoPor,
                        ':fechaHoraCreado' => $fechaHora
                        ));
            $codigoClienteNuevo = $this->dbConnect->lastInsertId();
            header('Location: ../infoCliente.php?id='.$codigoClienteNuevo);

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }
}
$nuevoCliente = new NuevoCliente();
$nuevoCliente->guardar();
?>
