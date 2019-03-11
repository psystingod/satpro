<?php
    require('connection.php');
    /**
     * Clase para ingresar productos al inventario
     */
    class EnterProduct extends ConectionDB
    {
        public function EnterProduct()
        {
            parent::__construct ();
        }
        public function enter()
        {
            try {
                $fecha = $_POST["fecha"];
                $codigo = $_POST["codigo"];
                $mac = $_POST["mac"];
                $serie = $_POST["serie"];
                $estado = $_POST["estado"];
                $bodega = $_POST["bodega"];
                $marca = $_POST["marca"];
                $modelo = $_POST["modelo"];
                $descripcion = $_POST["descripcion"];

                $query = "SELECT count(*) FROM tbl_articuloInternet where Mac='".$mac."' or  serie='".$serie."'";
                $statement = $this->dbConnect->query($query);

                if($statement->fetchColumn() == 1)
                {
                    header('Location: ../pages/inventarioInternet.php?status=FalloRegistro&bodega='.$bodega);
                }
                else
                {

                    $query = "INSERT into tbl_articuloInternet(Codigo,Mac,Serie,Estado,IdBodega,Marca,Modelo,Descripcion,fecha)
                    values (:codigo,:mac,:serie,:estado,(SELECT idBodega FROM tbl_bodega where NombreBodega=:idBodega),:marca,:modelo,:descripcion,:fecha)";
                    // Preparación de sentencia
                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                    ':fecha' => $fecha,
                    ':codigo' => $codigo,
                    ':mac' => $mac,
                    ':serie' => $serie,
                    ':estado' => $estado,
                    ':marca' => $marca,
                    ':modelo'=> $modelo,
                    ':idBodega' => $bodega,
                    ':descripcion' => $descripcion
                    ));

                    $this->dbConnect = NULL;
                    header('Location: ../pages/inventarioInternet.php?status=success&bodega='.$bodega);
                   }

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
                header('Location: ../pages/inventarioBodegas.php?status=failed&bodega='.$bodega);
            }
        }
    }
    $enter = new EnterProduct();
    $enter->enter();
?>
