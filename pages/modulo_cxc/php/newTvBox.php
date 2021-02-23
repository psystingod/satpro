<?php
require_once('../../../php/connection.php');

class NuevoCliente extends ConectionDB
{
    public function NuevoCliente()
    {
        if(!isset($_SESSION))
        {
      	  session_start();
        }
        parent::__construct ($_SESSION['db']);
    }

    public function guardar(){
        try {
            date_default_timezone_set('America/El_Salvador');
            /****************** DATOS GENERALES ***********************/

            $fechaHora = date("Y-m-d");
            $creadoPor = $_SESSION['nombres']." ".$_SESSION['apellidos'];
            $codigoCliente = $_GET['codigoCiente'];
            $counter = $_GET['counter'];
            $caja1 = $_POST['caja'.$counter];
            $cas1 = $_POST['cas'.$counter];
            $sn1 = $_POST['sn'.$counter];
            $this->dbConnect->beginTransaction();
            $sql1 = "INSERT INTO tbl_tv_box (boxNum, cast, serialBox, clientCode, activeDate, user) VALUES (:caja1, :cas1, :sn1, :clientCode, :activeDate, :user)";
            $stmt = $this->dbConnect->prepare($sql1);
            $stmt->bindValue(':caja1', $caja1);
            $stmt->bindValue(':cas1', $cas1);
            $stmt->bindValue(':sn1', $sn1);
            $stmt->bindValue(':clientCode', $codigoCliente);
            $stmt->bindValue(':activeDate', $fechaHora);
            $stmt->bindValue(':user', $creadoPor);

            $stmt->execute();

            echo "<script>window.close();</script>";

            sleep(0.5);
            $this->dbConnect->commit();

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }
}
$nuevoCliente = new NuevoCliente();
$nuevoCliente->guardar();
?>
