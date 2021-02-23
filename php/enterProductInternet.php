<?php
    require('connection.php');
    /**
     * Clase para ingresar productos al inventario
     */
    class EnterProduct extends ConectionDB
    {
        public function EnterProduct()
        {
<<<<<<< HEAD
            session_start();
=======
            if(!isset($_SESSION))
            {
                session_start([
                    'cookie_lifetime' => 86400,
                ]);
            }
>>>>>>> 5217f37d1bac7a0cef4ccc090dacd613611b185a
            parent::__construct ($_SESSION['db']);
        }
        public function enter()
        {
            try {
<<<<<<< HEAD
                $nombreProducto = $_POST["nombreProducto"];
=======
                $queryVen = "SELECT IdArticulo FROM tbl_articulointernet order by IdArticulo DESC LIMIT 0, 1";
                // Preparación de sentencia
                $statementVen = $this->dbConnect->prepare($queryVen);
                $statementVen->execute();
                $resultVen = $statementVen->fetch(PDO::FETCH_ASSOC);
                $ultimoArticulo = $resultVen["IdArticulo"]; //ULTIMO ARTICULO

>>>>>>> 5217f37d1bac7a0cef4ccc090dacd613611b185a
                $mac = $_POST["mac"];
                $serie = $_POST["serie"];
                $estado = $_POST["estado"];
                $bodega = $_POST["bodega"];
                $marca = $_POST["marca"];
                $modelo = $_POST["modelo"];
                $descripcion = $_POST["descripcion"];
                $proveedor = $_POST["proveedor"];
                $Fabricante = $_POST["Fabricante"];
                $Categoria = $_POST["Categoria"];
                $Tecnologia = $_POST["Tecnologia"];
                $Fecha = date('Y/m/d g:i');
                $fechaForm = $_POST["fecha"];
                $condicion = "En bodega";
                $Garantia = $_POST["Garantia"];
                $nFactura =$_POST["nFactura"];
                date_default_timezone_set('America/El_Salvador');
<<<<<<< HEAD
                
                
=======
                $fechaForm = $_POST["fecha"];
                //$Fecha = date('Y/m/d g:i');
>>>>>>> 5217f37d1bac7a0cef4ccc090dacd613611b185a
                $query = "SELECT count(*) FROM tbl_articulointernet where Mac='".$mac."' or  serie='".$serie."'";
                $statement = $this->dbConnect->query($query);

                if($statement->fetchColumn() == 1)
                {
                    header('Location: ../pages/inventarioInternet.php?status=FalloRegistro&bodega='.$bodega);
                }
                else
                {
<<<<<<< HEAD

                    $query = "INSERT into tbl_articulointernet(nombreProducto,Mac,Serie,Estado,IdBodega,Marca,Modelo,Descripcion,Proveedor,Fabricante,Categoria,Tecnologia,fecha,condicion,Garantia,nFactura)
                    values (:nombreProducto,:mac,:serie,:estado,(SELECT idBodega FROM tbl_bodega where NombreBodega=:idBodega),:marca,:modelo,:descripcion,(SELECT Nombre FROM tbl_proveedor where IdProveedor = :proveedor),:Fabricante,:Categoria,:Tecnologia,:fecha,:condicion,:Garantia,:nFactura)";
                    // Preparación de sentencia
                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                    ':nombreProducto' => $nombreProducto,
=======
                    $ultimoArticulo = $ultimoArticulo + 1;
                    $query = "INSERT into tbl_articulointernet(IdArticulo,Mac,Serie,Estado,IdBodega,Marca,Modelo,Descripcion,Proveedor,fecha,docsis,nosh,condicion)
                    values (:idArticulo,:mac,:serie,:estado,(SELECT idBodega FROM tbl_bodega where NombreBodega=:idBodega),:marca,:modelo,:descripcion,(SELECT Nombre FROM tbl_proveedor where IdProveedor = :proveedor),:fecha,:docsis,:nosh,:condicion)";
                    // Preparación de sentencia
                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                    ':idArticulo' => $ultimoArticulo,
                    ':fecha' => $fechaForm,
>>>>>>> 5217f37d1bac7a0cef4ccc090dacd613611b185a
                    ':mac' => $mac,
                    ':serie' => $serie,
                    ':estado' => $estado,
                    ':idBodega' => $bodega,
                    ':marca' => $marca,
                    ':modelo'=> $modelo,
                    ':descripcion' => $descripcion,
                    ':proveedor'=> $proveedor,
                    ':Fabricante'=> $Fabricante,
                    ':Categoria' => $Categoria,
                    ':Tecnologia' => $Tecnologia,
                    ':fecha' => $fechaForm,
                    ':condicion' => $condicion,
                    ':Garantia' => $Garantia,
                    ':nFactura' => $nFactura
                    ));var_dump($fechaForm);

                    $query2 = "SELECT IdProveedor, Nombre FROM tbl_proveedor WHERE IdProveedor = :idProv";
                    // Preparación de sentencia
                    $statement = $this->dbConnect->prepare($query2);
                    $statement->execute(array(
                    ':idProv' => $proveedor
                    ));

                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $key) {
                        $nombreProveedor = $key["Nombre"];
                        $idProv = $key["IdProveedor"];
                    }


                    //GUARDAMOS EL HISTORIAL DE LA ENTRADA

                    $nombreArticuloHistorial = $mac;
                    $nombreEmpleadoHistorial = $_POST['nombreEmpleadoHistorial'];
                    $nombreBodegaHistorial = $bodega;
                    $cantidadHistorial = 1;
                    $tipoMovimientoHistorial = "Nuevo ingreso de producto Internet";

                    $query = "INSERT into tbl_historialentradas (nombreArticulo, nombreEmpleado, fechaHora, tipoMovimiento, cantidad, bodega)
                              VALUES(:nombreArticuloHistorial, :nombreEmpleadoHistorial, CURRENT_TIMESTAMP(), :tipoMovimientoHistorial, :cantidadHistorial, :nombreBodegaHistorial)";

                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                    ':nombreArticuloHistorial' => $nombreArticuloHistorial,
                    ':nombreEmpleadoHistorial' => $nombreEmpleadoHistorial,
                    ':tipoMovimientoHistorial' => $tipoMovimientoHistorial,
                    ':cantidadHistorial' => $cantidadHistorial,
                    ':nombreBodegaHistorial' => $nombreBodegaHistorial
                    ));

                    header('Location: ../pages/inventarioInternet.php?status=success&bodega='.$bodega.'&proveedor='.$nombreProveedor.'&idProv='.$idProv.'&marca='.$marca.'&modelo='.$modelo.'&Categoria='.$Categoria.'&Tecnologia='.$Tecnologia.'&fecha='.$fechaForm);
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
