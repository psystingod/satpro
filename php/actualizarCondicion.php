<?php
    require('connection.php');
    /**
     * Clase para ingresar productos al inventario
     */
    class ActualizarCondicion extends ConectionDB
    {
        public function EnterProduct()
        {
            parent::__construct ();
        }
        public function actualizar()
        {
            try {
                $bodega = $_GET["bodega"];
                $id = $_GET["id"];
                $condicion = $_GET["condicion"];
                    $query = "UPDATE tbl_articulointernet SET condicion=:condicion WHERE IdArticulo=:id";
                    // Preparación de sentencia
                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                    ':id' => $id,
                    ':condicion' => $condicion,
                    ));

                    header('Location: ../pages/inventarioInternet.php?bodega='.$bodega);

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
                header('Location: ../pages/inventarioBodegas.php?status=failed&bodega='.$bodega);
            }
        }
    }
    $actualizar = new ActualizarCondicion();
    $actualizar->actualizar();
?>
