<?php
    session_start();
    require('connection.php');

    class saveDetailsEncargadoDepartamento extends ConectionDB
    {
        public function saveDetailsEncargadoDepartamento()
        {
            parent::__construct ();
        }
        public function enter()
        {

            if(isset($_POST['Action1']))
            {
                header('Location: ../pages/AsignarEncargadoDepartamento.php');
            }
            else if(isset($_POST['Action2']))
            {
                try {

                              $Departamento = $_POST["Departamento"];
                              $CodigoEmpleado = $_POST["CodigoEmpleado"];

                                    /////////////////////////////////

                                    $query = "SELECT count(*) from tbl_empleado where Codigo='".$CodigoEmpleado."'";
                                    $statement = $this->dbConnect->query($query);

                                    if($statement->fetchColumn() == 0)
                                    {
                                      header('Location: ../pages/asignarEncargadoDepartamento.php?status=CodigoEmpleado');
                                    }
                                    else
                                     {
                                       $query = "SELECT count(*) from tbl_departamento where NombreDepartamento = '".$Departamento."' and  IdEmpleado =(SELECT IdEmpleado FROM tbl_empleado where Codigo='".$CodigoEmpleado."')";
                                       $statement = $this->dbConnect->query($query);

                                       if($statement->fetchColumn() == 1)
                                       {
                                         header('Location: ../pages/asignarEncargadoDepartamento.php?status=failed');
                                       }
                                       else {
                                            $query = "UPDATE tbl_departamento set IdEmpleado =(SELECT IdEmpleado FROM tbl_empleado where Codigo='".$CodigoEmpleado."') where NombreDepartamento='".$Departamento."'";
                                            $statement = $this->dbConnect->prepare($query);
                                            $statement->execute();

                                         header('Location: ../pages/asignarEncargadoDepartamento.php?status=success');
                                       }
                                    }
                            $this->dbConnect = NULL;
                    }
                    catch (Exception $e)
                    {
                        print "!Error¡: " . $e->getMessage() . "</br>";
                        die();
                        header('Location: ../pages/asignarEncargadoDepartamento.php?status=failed');
                   }
            }
        }
    }
    $enter = new saveDetailsEncargadoDepartamento();
    $enter->enter();
?>
