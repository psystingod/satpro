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

                $codigo = $_POST["codigo"];
                $bodega = $_POST["bodega"];

                $query = "SELECT count(*) FROM tbl_articulo where Codigo='".$codigo."' and IdBodega=(SELECT tbl_bodega.IdBodega FROM tbl_bodega WHERE tbl_bodega.NombreBodega ='".$bodega."')";
                $statement = $this->dbConnect->query($query);

                if($statement->fetchColumn() == 1)
                {
                    header('Location: ../pages/inventarioBodegas.php?status=FalloRegistro&bodega='.$bodega);
                }
                else
                {
                    // SQL query para ingresar producto
                    $IdArticulo;
                    $codigo = $_POST["codigo"];
                    $nombre = $_POST["nombre"];
                    $proveedor = $_POST['proveedor'];
                    $descripcion = $_POST['descripcion'];
                    $cantidad = $_POST["cantidad"];
                    $tipoProducto = $_POST["tProducto"];
                    $categoria = $_POST['categoria'];
                   // $subCategoria = $_POST["subCategoria"];
                    $bodega = $_POST["bodega"];
                    $pCompra = $_POST['pCompra'];
                    $pVenta = $_POST["pVenta"];
                    $fechaEntrada = $_POST["fecha"];
                    $um = $_POST["um"];
                    date_default_timezone_set('America/El_Salvador');
                    $Fecha = date('Y/m/d g:ia');
                    $query = "INSERT INTO tbl_articulo(Codigo, NombreArticulo, Descripcion, Cantidad, PrecioCompra, PrecioVenta, FechaEntrada, IdUnidadMedida, IdTipoProducto, IdCategoria, IdProveedor, IdBodega)
                    VALUES(:codigo, :nombre, :descripcion, :cantidad, :precioCompra, :precioVenta, :fechaEntrada,
                    (SELECT IdUnidadMedida FROM tbl_unidadmedida WHERE NombreUnidadMedida = :idUm),
                    (SELECT tbl_tipoproducto.IdTipoProducto FROM tbl_tipoproducto WHERE tbl_tipoproducto.NombreTipoProducto = :idTip),
                    (SELECT tbl_categoria.IdCategoria FROM tbl_categoria WHERE tbl_categoria.NombreCategoria = :idSubCat),
                    (SELECT tbl_proveedor.IdProveedor FROM tbl_proveedor WHERE tbl_proveedor.Nombre = :idProv),
                    (SELECT tbl_bodega.IdBodega FROM tbl_bodega WHERE tbl_bodega.NombreBodega = :idBodega))";
                    // Preparación de sentencia
                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                    ':codigo' => $codigo,
                    ':nombre' => $nombre,
                    ':descripcion' => $descripcion,
                    ':cantidad' => $cantidad,
                    ':precioCompra' => $pCompra,
                    ':precioVenta' => $pVenta,
                    ':fechaEntrada' => $fechaEntrada,
                    ':idUm' => $um,
                    ':idTip' => $tipoProducto,
                    ':idSubCat' => $categoria,
                    ':idProv' => $proveedor,
                    ':idBodega' => $bodega
                    ));



                    $IdArticulo=$this->dbConnect->lastInsertId();
                 $Nombre = $_POST["NOMBRE"];
                 $Apellido = $_POST["APELLIDO"];
                $query = "INSERT into tbl_histoingreso(FechaIngreso, IdArticulo, Cantidad, IdBodega, IdEmpleado,Tipo)
                                 VALUES(:Fecha,:IdArticulo,:Cantidad,(SELECT tbl_bodega.IdBodega FROM tbl_bodega WHERE tbl_bodega.NombreBodega = :IdBodega),
                                 (SELECT IdEmpleado from tbl_empleado where Nombres=:Nombres and Apellidos=:Apellidos),0) ";
                 $statement = $this->dbConnect->prepare($query);
                 $statement->execute(array(
                 ':Fecha' => $Fecha,
                 ':IdArticulo'=> $IdArticulo,
                 ':Cantidad' => $cantidad,
                 ':IdBodega' => $bodega,
                 ':Nombres' => $Nombre,
                 ':Apellidos' => $Apellido
                 ));

                    $this->dbConnect = NULL;
                    header('Location: ../pages/inventarioBodegas.php?status=success&bodega='.$bodega);
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
