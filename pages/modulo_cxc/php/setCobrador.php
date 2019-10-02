<?php
 session_start();
    require('../../../php/connection.php');
    class SetCobrador extends ConectionDB
    {
        public function SetCobrador()
        {
            parent::__construct ();
        }
        public function set()
        {
                try {
                    //Capturar datos
                    $action = $_GET['action']; //NUEVO, EDITAR

                    $codigoCobrador = $_POST["codigoCobrador"];
                    $nombreCobrador = $_POST["nombreCobrador"];
                    var_dump($codigoCobrador);
                    $prefijoCobro = $_POST["prefijoCobro"];
                    $desdeNumero = $_POST["desdeNumero"];

                    $hastaNumero = $_POST['hastaNumero'];
                    $ultimoNumero = $_POST['ultimoNumero'];

                    if ($action == "nuevo") {
                        $query = "INSERT INTO `tbl_cobradores` (`nombreCobrador`, `prefijoCobro`, `desdeNumero`, `hastaNumero`, `numeroAsignador`) VALUES (:nombreCobrador, :prefijoCobro, :desdeNumero, :hastaNumero, :numeroAsignador)";
                        $statement = $this->dbConnect->prepare($query);
                        $statement->bindValue(':nombreCobrador', $nombreCobrador);
                        $statement->bindValue(':prefijoCobro', $prefijoCobro);
                        $statement->bindValue(':desdeNumero', $desdeNumero);
                        $statement->bindValue(':hastaNumero', $hastaNumero);
                        $statement->bindValue(':numeroAsignador', $ultimoNumero);
                    }elseif ($action == "editar") {
                        $query = "UPDATE `tbl_cobradores` SET `nombreCobrador` = :nombreCobrador, `prefijoCobro` = :prefijoCobro, `desdeNumero` = :desdeNumero, `hastaNumero` = :hastaNumero, `numeroAsignador` = :numeroAsignador WHERE codigoCobrador = :codigoCobrador";
                        $statement = $this->dbConnect->prepare($query);

                        $statement->bindValue(':codigoCobrador', $codigoCobrador);
                        $statement->bindValue(':nombreCobrador', $nombreCobrador);
                        $statement->bindValue(':prefijoCobro', $prefijoCobro);
                        $statement->bindValue(':desdeNumero', $desdeNumero);
                        $statement->bindValue(':hastaNumero', $hastaNumero);
                        $statement->bindValue(':numeroAsignador', $ultimoNumero);
                    }

                    var_dump($codigoCobrador);
                    $statement->execute();

                    $this->dbConnect = NULL;
                    header('Location: ../cobradores.php?codigoCobrador='.$codigoCobrador.'&status=success');
                    var_dump($codigoCobrador);
                    }catch (Exception $e){
                        print "!Error¡: " . $e->getMessage() . "</br>";
                        die();
                        header('Location: ../cobradores.php?codigoCobrador='.$codigoCobrador.'&status=failed');
                   }
                }
        }
    $set = new SetCobrador();
    $set->set();
?>
