<?php
    require('../../connection.php');
    /**
     * Clase para ingresar productos al inventario
     */
    class SaveClient extends ConectionDB
    {
        public function SaveClient()
        {
            parent::__construct ();
        }
        public function save()
        {
            try {

                $codigo = $_POST["codigo"];
                $nContrato = $_POST["nContrato"];
                $nFactura = $_POST['nFactura'];
                $nombres = $_POST['nombres'];
                $apellidos = $_POST['apellidos'];
                $empresa = $_POST["empresa"];
                $ncr = $_POST["ncr"];
                $dui = $_POST['dui'];
                $bodega = $_POST["bodega"];
                $expedicion = $_POST['expedicion'];
                $nit = $_POST["nit"];
                $fechaNacimiento = $_POST["fechaNacimiento"];
                $direccion = $_POST["direccion"];
                $departamento = $_POST["departamento"];
                $municipio = $_POST["municipio"];
                $colonia = $_POST["colonia"];
                $direccionCobro = $_POST["direccionCobro"];
                $telefono = $_POST["telefono"];
                $telefonoTrabajo = $_POST["telefonoTrabajo"];
                $ocupacion = $_POST["ocupacion"];
                $cuentaContable = $_POST["cuentaContable"];
                $formaFacturar = $_POST["formaFacturar"];
                $saldoActual = $_POST["saldoActual"];
                $diasCredito = $_POST["diasCredito"];
                $limiteCredito = $_POST["limiteCredito"];
                $tipoFacturacion = $_POST["tipoFacturacion"];
                $facebook = $_POST["facebook"];
                $cobrador = $_POST["cobrador"];
                $rf1_nombre = $_POST["rf1_nombre"];
                $rp1_telefono = $_POST["rp1_telefono"];
                $rp1_direccion = $_POST["rp1_direccion"];
                $rp1_parentezco = $_POST["rp1_parentezco"];
                $rf2_nombre = $_POST["rf2_nombre"];
                $rp2_telefono = $_POST["rp2_telefono"];
                $rp2_direccion = $_POST["rp2_direccion"];
                $rp2_parentezco = $_POST["rp2_parentezco"];
                $rf3_nombre = $_POST["rf3_nombre"];
                $rp3_telefono = $_POST["rp3_telefono"];
                $rp3_direccion = $_POST["rp3_direccion"];
                $rp3_parentezco = $_POST["rp3_parentezco"];
                $activarCable = $_POST["activarCable"]; // SERVICIO ACTIVADO O DESACTIVADO DE CABLE
                $fechaInstalacionCable = $_POST["fechaInstalacionCable"];
                $fechaPrimerFacturaCable = $_POST["fechaPrimerFacturaCable"];
                $exento = $_POST["exento"];
                $diaGenerarFacturaCable = $_POST["diaGenerarFacturaCable"];
                $cortesia = $_POST["cortesia"];
                $cuotaMensualCable = $_POST["cuotaMensualCable"];
                $prepago = $_POST["prepago"];
                $tipoComprobante = $_POST["tipoComprobante"];
                $tipoServicio = $_POST["tipoServicio"];
                $mesesContratoCable = $_POST["mesesContratoCable"];
                $vencimientoContratoCable = $_POST["vencimientoContratoCable"];
                $inicioContratoCable = $_POST["inicioContratoCable"];
                $fechaReconexionCable = $_POST["fechaReconexionCable"];
                $encargadoInstalacionCable = $_POST["encargadoInstalacionCable"];
                $direccionCable = $_POST["direccionCable"];
                $derivaciones = $_POST["derivaciones"];
                $ = $_POST[""];
                $ = $_POST[""];
                $ = $_POST[""];
                $ = $_POST[""];
                $ = $_POST[""];
                $ = $_POST[""];
                $ = $_POST[""];
                $ = $_POST[""];
                $ = $_POST[""];
                $ = $_POST[""];
                $ = $_POST[""];

                date_default_timezone_set('America/El_Salvador');
                $Fecha = date('Y/m/d g:i');

                //CONSULTAMOS LA CANTIDAD DE ARTICULOS EN LA CATEGORIA SELECCIONADA, PARA GENERAR UN NUEVO CODIGO CORRELATIVO
                 $query = "SELECT count(*) FROM tbl_articulo where IdCategoria=(SELECT IdCategoria FROM tbl_categoria where NombreCategoria='".$categoria."')";
                 $statement = $this->dbConnect->prepare($query);

            }
            catch (Exception $e)
            {
                print "Error!: " . $e->getMessage() . "</br>";
                die();
                header('Location: ../pages/inventarioBodegas.php?status=ErrorGrave&bodega='.$bodega);
            }
        }
    }
    $enter = new EnterProduct();
    $enter->enter();
?>
