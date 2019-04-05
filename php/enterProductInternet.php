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
                $mac = $_POST["mac"];
                $serie = $_POST["serie"];
                $estado = $_POST["estado"];
                $bodega = $_POST["bodega"];
                $marca = $_POST["marca"];
                $modelo = $_POST["modelo"];
                $descripcion = $_POST["descripcion"];
                date_default_timezone_set('America/El_Salvador');
                $Fecha = date('Y/m/d g:i');
                $query = "SELECT count(*) FROM tbl_articulointernet where Mac='".$mac."' or  serie='".$serie."'";
                $statement = $this->dbConnect->query($query);

                if($statement->fetchColumn() == 1)
                {
                    header('Location: ../pages/inventarioInternet.php?status=FalloRegistro&bodega='.$bodega);
                }
                else
                {

                    $query = "INSERT into tbl_articulointernet(Mac,Serie,Estado,IdBodega,Marca,Modelo,Descripcion,fecha)
                    values (:mac,:serie,:estado,(SELECT idBodega FROM tbl_bodega where NombreBodega=:idBodega),:marca,:modelo,:descripcion,:fecha)";
                    // Preparación de sentencia
                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                    ':fecha' => $Fecha,
                    ':mac' => $mac,
                    ':serie' => $serie,
                    ':estado' => $estado,
                    ':marca' => $marca,
                    ':modelo'=> $modelo,
                    ':idBodega' => $bodega,
                    ':descripcion' => $descripcion
                    ));


                    // $IdArticulo=$this->dbConnect->lastInsertId();
                    // $Nombre = $_POST["NOMBRE"];
                    // $Apellido = $_POST["APELLIDO"];
                    // $query = "insert into tbl_historialRegistros (IdEmpleado,FechaHora,Tipo_Movimiento,Descripcion)
                    // VALUES((SELECT IdEmpleado from tbl_empleado where Nombres='".$Nombre."' and Apellidos='".$Apellido."'),'". $Fecha."',2
                    // ,concat( 'Modelo del Producto/Articulo: ',  (SELECT a.Modelo FROM tbl_articuloInternet as a WHERE  a.IdArticulo= '".$IdArticulo."')  , ' MAC: ".$mac." ' ) )";
                    //  $statement = $this->dbConnect->prepare($query);
                    //  $statement->execute();
                    // $this->dbConnect = NULL;
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
