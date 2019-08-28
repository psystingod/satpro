<?php
require_once('../../php/connection.php');

/**
 * Clase para traer toda la información de los clientes de la BD
 */
class GetAllInfo extends ConectionDB
{

    function GetAllInfo()
    {
        parent::__construct ();
    }
    public function getData($tabla){
        try {
                $query = "SELECT * FROM $tabla";
                $statement = $this->dbConnect->prepare($query);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }

    public function getDataOrders($tabla, $codigoCliente){
        try {
                $query = "SELECT * FROM $tabla WHERE codigoCliente = :codigoCliente";
                $statement = $this->dbConnect->prepare($query);
                $statement->bindValue(':codigoCliente', $codigoCliente);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }

    public function getDataAbonos($tabla, $codigoCliente, $tipoServicio, $estado){
        try {
                //$estado = "pendiente";
                $query = "SELECT * FROM $tabla WHERE codigoCliente = :codigoCliente AND tipoServicio=:tipoServicio AND estado=:estado";
                $statement = $this->dbConnect->prepare($query);
                $statement->bindValue(':codigoCliente', $codigoCliente);
                $statement->bindValue(':tipoServicio', $tipoServicio);
                $statement->bindValue(':estado', $estado);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }
    //Acá comienzan las funciones para obtener los datos de clientes
    /*public function getDepartamento($idDepartamento){
        try {
                $query = "SELECT * FROM tbl_departamentos_cxc WHERE idDepartamento=:idDepartamento";
                $statement = $this->dbConnect->prepare($query);
                $statement->bindParam(':idDepartamento', $idDepartamento, PDO::PARAM_INT);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }
    public function getMunicipio($idMunicipio){
        try {
                $query = "SELECT * FROM municipios_cxc WHERE Id_municipio=:idMunicipio";
                $statement = $this->dbConnect->prepare($query);
                $statement->bindParam(':idMunicipio', $idMunicipio, PDO::PARAM_INT);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }
    public function getColonia($idColonia){
        try {
                $query = "SELECT * FROM colonias_cxc WHERE Id_colonia=:idColonia";
                $statement = $this->dbConnect->prepare($query);
                $statement->bindParam(':idColonia', $idColonia, PDO::PARAM_INT);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }
    public function getFormaPago($idFormaPago){
        try {
                $query = "SELECT * FROM tbl_forma_pago WHERE idFormaPago=:idFormaPago";
                $statement = $this->dbConnect->prepare($query);
                $statement->bindParam(':idFormaPago', $idFormaPago, PDO::PARAM_INT);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }
    public function getCobrador($codigoCobrador){
        try {
                $query = "SELECT codigoCobrador, nombreCobrador FROM cobradores WHERE codigoCobrador=:codigoCobrador";
                $statement = $this->dbConnect->prepare($query);
                $statement->bindParam(':codigoCobrador', $codigoCobrador, PDO::PARAM_SRT);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }
    //Esta función nos tare el nombre del tipo de comprobante a generar, Crédito fiscal y Consumidor final
    public function getTipoComprobante($idComprobante){
        try {
                $query = "SELECT * FROM tbl_comprobantes WHERE idComprobante=:idComprobante";
                $statement = $this->dbConnect->prepare($query);
                $statement->bindParam(':idComprobante', $idComprobante, PDO::PARAM_INT);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }
    public function getTipoServicioCable($idTipoServicioCable){
        try {
                $query = "SELECT * FROM tbl_servicios_cable WHERE idServicioCable=:idServicioCable";
                $statement = $this->dbConnect->prepare($query);
                $statement->bindParam(':idServicioCable', $idTipoServicioCable, PDO::PARAM_INT);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }
    public function getTipoServicioInter($idTipoServicioInter){
        try {
                $query = "SELECT * FROM tbl_servicios_inter WHERE idServicioInter=:idServicioInter";
                $statement = $this->dbConnect->prepare($query);
                $statement->bindParam(':idServicioInter', $idTipoServicioInter, PDO::PARAM_INT);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }
    public function getTecnico($idTecnico){
        try {
                $query = "SELECT * FROM tecnicos_cxc WHERE Id_tecnico=:idTecnico";
                $statement = $this->dbConnect->prepare($query);
                $statement->bindParam(':idTecnico', $idTecnico, PDO::PARAM_INT);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }
    public function getVelocidades($idVelocidad){
        try {
                $query = "SELECT * FROM tbl_velocidades WHERE idVelocidad=:idVelocidad";
                $statement = $this->dbConnect->prepare($query);
                $statement->bindParam(':idVelocidad', $idVelocidad, PDO::PARAM_INT);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }
    public function getTipoCliente($idTipoCliente){
        try {
                $query = "SELECT * FROM Tipos_clientes WHERE Id_tipo=:idTipoCliente";
                $statement = $this->dbConnect->prepare($query);
                $statement->bindParam(':idTipoCliente', $idTipoCliente, PDO::PARAM_INT);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }*/
}

?>