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
                $bodega = $_POST["bodega"];
                $IdArticulo;
                $nombre = $_POST["nombre"];
                $proveedor = $_POST['proveedor'];
                $descripcion = $_POST['descripcion'];
                $cantidad = $_POST["cantidad"];
                $tipoProducto = $_POST["tProducto"];
                $categoria = $_POST['categoria'];
                $bodega = $_POST["bodega"];
                $pCompra = $_POST['pCompra'];
                $pVenta = $_POST["pVenta"];
                $fechaEntrada = $_POST["fecha"];
                $um = $_POST["um"];
                date_default_timezone_set('America/El_Salvador');
                $Fecha = date('Y/m/d g:ia');

                  //GENERA CODIGO
                  // $Clave = '';
                  // $pattern = '1234567890';
                  // $max = strlen($pattern)-1;
                  // for($i=0;$i < 4;$i++) $Clave .= $pattern{mt_rand(0,$max)};
                  //$codigo = substr($nombre,0,1) . "-" . substr($categoria,0,1) . $Clave;

                //  CONSULTAMOS SI EXISTE EL ARTICULO EN LA TABLA TBL_ARTICULO, SI EXISTE ENTRA A UNA VALIDACION, SI NO EXISTE LO AGREGA
                 $query = "SELECT count(*) FROM tbl_articulo where IdCategoria=(SELECT IdCategoria FROM tbl_categoria where NombreCategoria='".$categoria."')";
                 $statement = $this->dbConnect->query($query);
                 $Cd = $statement->fetchColumn() + 1;
                 $codigo = substr($nombre,0,1) . substr($categoria,0,1) ."0". $Cd;

                 //CONSULTAMOS SI EXISTE EL ARTICULO EN LA TABLA TBL_ARTICULO, SI EXISTE ENTRA A UNA VALIDACION, SI NO EXISTE LO AGREGA
                 $query = "SELECT count(*) FROM tbl_articulo where NombreArticulo= '".$nombre."'";
                 $statement = $this->dbConnect->query($query);
                 if($statement->fetchColumn() == 1)
                 {
                   //CONSULTAMOS SI EXISTE ESE NOMBRE EN ESA BODEGA, SI EXISTE MOSTRAMOS MENSAJE, SI NO EXISTE LO AGREGAMOS, CON EL CODIGO DE ESE MismaBodega
                   //PRODUCTO ENCONTRADO EN OTRA BODEGA
                   $query = "SELECT count(*) from tbl_articulo where NombreArticulo='".$nombre."' and IdBodega=(SELECT tbl_bodega.IdBodega FROM tbl_bodega WHERE tbl_bodega.NombreBodega = '".$bodega."')";
                   $statement = $this->dbConnect->query($query);
                   if($statement->fetchColumn() == 1)
                   {
                     header('Location: ../pages/inventarioBodegas.php?status=failed&bodega='.$bodega);
                   }
                   else
                   {
                     //CONSULTAMOS EL CODIGO DE ESE PRODUCTO EN LA BODEGA QUE EXISTE ACTUALMENTE
                     $query = "SELECT Codigo from tbl_articulo where NombreArticulo='".$nombre."' ";
                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    $NCodigo = "";
                    foreach ($result as $key)
                    {
                       $NCodigo = $key['Codigo'];
                    }
                    //AGREGAMOS EL NUEVO ARTICULO CON EL CODIGO EXISTENTE
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
                    ':codigo' => $NCodigo,
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
                    header('Location: ../pages/inventarioBodegas.php?status=success&bodega='.$bodega);
                   }
                 }
                 else
                  {
                    //AGREGAMOS EL NUEVO ARTICULO
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
                    header('Location: ../pages/inventarioBodegas.php?status=success&bodega='.$bodega);
                 }
                 //GUARDAMOS EL TIPO DE MOVIMIENTO REALIZADO
                 $IdArticulo=$this->dbConnect->lastInsertId();
                 $Nombre = $_POST["NOMBRE"];
                 $Apellido = $_POST["APELLIDO"];
                 $query = "INSERT into tbl_historialRegistros (IdEmpleado,FechaHora,Tipo_Movimiento,Descripcion)
                 VALUES((SELECT IdEmpleado from tbl_empleado where Nombres='".$Nombre."' and Apellidos='".$Apellido."'),'". $Fecha."',2
                 ,concat( 'Nombre del Producto/Articulo: ',  (SELECT a.NombreArticulo FROM tbl_articulo as a WHERE  IdArticulo= '".$IdArticulo."')  , ' Cantidad: ".$cantidad." ' ) )";
                  $statement = $this->dbConnect->prepare($query);
                  $statement->execute();
                  $this->dbConnect = NULL;
            }
            catch (Exception $e)
            {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
                header('Location: ../pages/inventarioBodegas.php?status=failed&bodega='.$bodega);
            }
        }
    }
    $enter = new EnterProduct();
    $enter->enter();
?>
