<?php
    session_start();
    require('connection.php');

    class saveDetailsEmpleado extends ConectionDB
    {
        public function saveDetailsEmpleado()
        {
            parent::__construct ();
        }
        public function enter()
        {
                try {
                            date_default_timezone_set('America/El_Salvador');
                            $Codigo = $_POST["Codigo"];
                            $Nombre = $_POST["Nombre"];
                            $Fecha = date('Y/m/d g:ia');
                            $State = 0;
                            $Comentario = $_POST["Comentario"];
                            $Nombre = $_SESSION['nombres'];
                            $Apellido = $_SESSION['apellidos'];
                            $V2 = array();
                            foreach ($Comentario as $K1)
                            {
                                array_push($V2, $K1);
                             }

                             function RecibeArray($url_array)
                                {
                                    $tmp = stripslashes($url_array);
                                    $tmp = urldecode($tmp);
                                    $tmp = unserialize($tmp);
                                    return $tmp;
                                }
                                $array=$_POST['array'];
                                $array=RecibeArray($array);
                                function RecibeArrayI($url_array)
                                   {
                                       $tmp = stripslashes($url_array);
                                       $tmp = urldecode($tmp);
                                       $tmp = unserialize($tmp);
                                       return $tmp;
                                   }
                                   $arrayI=$_POST['Departamento'];
                                   $arrayI=RecibeArrayI($arrayI);
                                for($i=0;$i < count($array); $i++)
                                    {
                                      $query = "INSERT into tbl_articuloempleado(CodigoEmpleado, NombreEmpleado, IdArticuloDepartamento,Comentario,Cantidad,FechaEntregado,IdDepartamento, State)
                                                VALUES(:Codigo, :Nombre,:IdArticulo,:Comentario,1,:Fecha,(SELECT IdDepartamento FROM tbl_departamento where NombreDepartamento=:IdDepartamento),:State)";
                                         $statement = $this->dbConnect->prepare($query);
                                         $statement->execute(array(
                                          ':Codigo' => $Codigo,
                                          ':Nombre' => $Nombre,
                                          ':IdArticulo' => $array[$i],
                                          ':Comentario' => $V2[$i],
                                          // ':Cantidad' => $V1[$i],
                                          ':Fecha' => $Fecha,
                                          ':IdDepartamento' => $arrayI[$i],
                                          ':State' => $State
                                      ));
                                      $Id=$this->dbConnect->lastInsertId();
                                      $query = "UPDATE tbl_articulodepartamento set Cantidad = Cantidad - 1 where IdArticuloDepartamento='".$array[$i]."'";
                                         $statement = $this->dbConnect->prepare($query);
                                         $statement->execute();

                                         $query = "insert into tbl_historialRegistros (IdEmpleado,FechaHora,Tipo_Movimiento,Descripcion)
                                         VALUES((SELECT IdEmpleado from tbl_empleado where Nombres='".$Nombre."' and Apellidos='".$Apellido."'),
                                         '".$Fecha."',6, concat('Articulo/Producto: ', (Select NombreArticulo from tbl_articulodepartamento where IdArticuloDepartamento='".$array[$i]."'),
                                         '. Descripcion: ', (select Comentario from tbl_articuloempleado where IdArticuloEmpleado='".$Id."' ),
                                         '. Empleado: ', (SELECT a.Nombres FROM tbl_empleado as a WHERE  a.Codigo='".$Codigo."') ) )";
                                          $statement = $this->dbConnect->prepare($query);
                                          $statement->execute();
                                   }
                            $this->dbConnect = NULL;
                            header('Location: ../pages/asignarArticuloEmpleado.php?status=success');
                    }
                    catch (Exception $e)
                    {
                        print "!Error¡: " . $e->getMessage() . "</br>";
                        die();
                        header('Location: ../pages/asignarArticuloEmpleado.php?status=failed');
                   }
        }
    }
    $enter = new saveDetailsEmpleado();
    $enter->enter();
?>
