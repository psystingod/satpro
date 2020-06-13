<?php
require_once('../../../php/connection.php');

class UpdateTvBox extends ConectionDB
{
    public function UpdateTvBox()
    {
        if(!isset($_SESSION))
        {
      	  session_start();
        }
        parent::__construct ($_SESSION['db']);
    }

    public function update(){
        try {
            date_default_timezone_set('America/El_Salvador');
            /****************** DATOS GENERALES ***********************/

            $fechaHora = date("Y-m-d");
            $id = $_GET['id'];
            $creadoPor = $_SESSION['nombres']." ".$_SESSION['apellidos'];
            $count = $_GET['count'];
            $caja1 = $_POST['caja'.$count];
            $cas1 = $_POST['cas'.$count];
            $sn1 = $_POST['sn'.$count];
            $this->dbConnect->beginTransaction();
            $sql1 = "UPDATE tbl_tv_box SET boxNum=:caja1, cast=:cas1, serialBox=:sn1, activeDate=:activeDate, user=:user WHERE idBox=:id";
            $stmt = $this->dbConnect->prepare($sql1);
            $stmt->bindValue(':caja1', $caja1);
            $stmt->bindValue(':cas1', $cas1);
            $stmt->bindValue(':sn1', $sn1);
            $stmt->bindValue(':activeDate', $fechaHora);
            $stmt->bindValue(':user', $creadoPor);
            $stmt->bindValue(':id', $id);

            $stmt->execute();
            //echo "<script>alert('Caja digital añadida')</script>";
            echo "<script>window.close();</script>";

            sleep(0.5);
            $this->dbConnect->commit();

        } catch (Exception $e) {
            print "!Error¡: " . $e->getMessage() . "</br>";
            die();
        }
    }
}
$nuevoCliente = new UpdateTvBox();
$nuevoCliente->update();
?>
