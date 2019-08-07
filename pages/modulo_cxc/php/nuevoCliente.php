<?php
/****************** DATOS GENERALES ***********************/
$estado_cable = $_POST['cable']; // 0 o 1
$estado_internet = $_POST['internet']; // 1, 2, 3
var_dump($estado_cable);
var_dump($estado_internet);
$codigo = $_POST['codigo'];
$nContrato = $_POST['contrato'];
$nFactura = $_POST['factura'];
$nombre = $_POST['nombre'];
$empresa = $row["empresa"];
$nRegistro = $_POST['ncr'];
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
$saldoActual = $_POST['saldoActual'];
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
$tecnicoCable = $_POST[''];
$direccionCable = $_POST[''];
$nDerivaciones = $_POST[''];

/****************** DATOS INTERNET ***********************/
$fechaInstalacionInter = $_POST[''];
$fechaPrimerFacturaInter = $_POST[''];
$tipoServicioInternet = $_POST[''];
$nodo = $_POST[''];
$periodoContratoInternet = $_POST[''];
$diaCobroInter = $_POST[''];
$velocidadInter = $_POST[''];
$cuotaMensualInter = $_POST[''];
$tipoClienteInter = $_POST[''];
$tecnologia = $_POST[''];
$nContratoInter = $_POST[''];
$vencimientoInternet = $_POST[''];
$ultimaRenovacionInternet = $_POST[''];
$fechaSuspencionInternet = $_POST[''];
$fechaReconexionInternet = $_POST[''];
$promocion = $_POST[''];
$promocionDesde = $_POST[''];
$promocionHasta = $_POST[''];
$cuotaPromocion = $_POST[''];
$direccionInternet = $_POST[''];
$colilla = $_POST[''];
$modelo = $_POST[''];
$recepcion = $_POST[''];
$wanip = $_POST[''];
$mac = $_POST[''];
$transmision = $_POST[''];
//$coordenadas = $row['coordenadas'];
$serie = $_POST[''];
$ruido = $_POST[''];
$wifiClave = $_POST[''];
?>
