<?php

    require('../../../php/connection.php');
    class SetCobrador extends ConectionDB
    {
        public function SetCobrador()
        {
            if(!isset($_SESSION))
            {
          	  session_start();
            }
            parent::__construct ($_SESSION['db']);
        }
        public function set()
        {
                try {
                    //Capturar datos
                    $action = $_GET['action']; //NUEVO, EDITAR

                    $codigoCobrador = $_POST["codigoCobrador"];
                    $nombresVendedor = $_POST["nombresVendedor"];
                    $apellidosVendedor = $_POST["apellidosVendedor"];

                    if ($action == "nuevo") {
                        $query = "INSERT INTO `tbl_vendedores` (`nombresVendedor`, `apellidosVendedor`) VALUES (:nombresVendedor, :apellidosVendedor)";
                        $statement = $this->dbConnect->prepare($query);
                        $statement->bindValue(':nombresVendedor', $nombresVendedor);
                        $statement->bindValue(':apellidosVendedor', $apellidosVendedor);
                    }elseif ($action == "editar") {
                        $query = "UPDATE `tbl_vendedores` SET `nombresVendedor` = :nombresVendedor, `apellidosVendedor` = :apellidosVendedor WHERE idVendedor = :codigoCobrador";
                        $statement = $this->dbConnect->prepare($query);

                        $statement->bindValue(':codigoCobrador', $codigoCobrador);
                        $statement->bindValue(':nombresVendedor', $nombresVendedor);
                        $statement->bindValue(':apellidosVendedor', $apellidosVendedor);
                    }

                    var_dump($codigoCobrador);
                    $statement->execute();

                    $this->dbConnect = NULL;
                    header('Location: ../vendedores.php?codigoVendedor='.$codigoCobrador.'&status=success');
                    var_dump($codigoCobrador);
                    }catch (Exception $e){
                        print "!Error¡: " . $e->getMessage() . "</br>";
                        die();
                        header('Location: ../vendedores.php?codigoVendedor='.$codigoCobrador.'&status=failed');
                   }
                }
        }
    $set = new SetCobrador();
    $set->set();
?>
