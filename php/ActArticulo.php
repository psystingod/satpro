<?php
    require('connection.php');
    /**
     * Clase para ingresar productos al inventario
     */
    class UpdateArticle extends ConectionDB
    {
        public function UpdateArticle()
        {
            parent::__construct ();
        }
        public function assign()
        {
            try {
                // SQL query para ingresar producto
                $IdArticulo = $_POST["Id"];
                $cantidad = $_POST["NCant"];
                $bodega = $_POST["Bdg"];

                $query = "UPDATE tbl_articulo set Cantidad=Cantidad + '".$cantidad."' where IdArticulo='".$IdArticulo."'";
                // Preparación de sentencia
                $statement = $this->dbConnect->prepare($query);
                $statement->execute();

                //GUARDAMOS EL TIPO DE MOVIMIENTO REALIZADO
                $Nombre = $_POST["NOMBRE"];
                $Apellido = $_POST["APELLIDO"];
                date_default_timezone_set('America/El_Salvador');
                $Fecha = date('Y/m/d g:ia');

                $query = "INSERT into tbl_historialRegistros (IdEmpleado,FechaHora,Tipo_Movimiento,Descripcion)
                VALUES((SELECT IdEmpleado from tbl_empleado where Nombres='".$Nombre."' and Apellidos='".$Apellido."'),'". $Fecha."',3
                ,concat('Nombre del Producto/Articulo: ',(SELECT a.NombreArticulo FROM tbl_articulo as a WHERE  IdArticulo= '".$IdArticulo."'),' Cantidad: ".$cantidad."'))";
                 $statement = $this->dbConnect->prepare($query);
                 $statement->execute();
                 //$this->dbConnect = NULL;
                header('Location: ../pages/inventarioBodegas.php?status=success&bodega='.$bodega);

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
                header('Location: ../pages/inventarioBodegas.php?status=ErrorGrave&bodega='.$bodega);
            }
        }
    }
    $assign = new UpdateArticle();
    $assign->assign();
?>
