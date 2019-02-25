<?php
 session_start();
    require('../../php/connection.php');

    class saveDepartamento extends ConectionDB
    {
        public function saveDepartamento()
        {
            parent::__construct ();
        }
        public function Guardar()
        {

            if(isset($_POST['Action1']))
            {
                header('Location: ../departamentos.php');
            }
            else if(isset($_POST['Action2']))
            {
                try {
                    //Capturar datos
                    $CodigoDepartamento = $_POST["CodigoDepartamento"];
                    $NombreDepartamento = $_POST["NombreDepartamento"];
                     $Descripcion = $_POST["Descripcion"];
                     $State = 0;
                     //Validar que no exista
                     $query = "SELECT count(*) from tbl_departamento where NombreDepartamento='".$NombreDepartamento."' or CodigoDepartamento='".$CodigoDepartamento."'";
                    $statement = $this->dbConnect->query($query);

                    if($statement->fetchColumn() >= 1)
                    {
                           header('Location: ../departamentos.php?status=failed');
                    }
                    else
                    {
                            $query = " INSERT INTO tbl_departamento(CodigoDepartamento, NombreDepartamento,Descripcion,IdEmpleado,State)
                            VALUES(:CodigoDepartamento, :NombreDepartamento, :Descripcion,0,:State)";
                               $statement = $this->dbConnect->prepare($query);
                               $statement->execute(array(
                                 ':CodigoDepartamento' => $CodigoDepartamento,
                                ':NombreDepartamento' => $NombreDepartamento,
                                ':Descripcion' => $Descripcion,
                                ':State' => $State
                            ));
                            $this->dbConnect = NULL;
                          header('Location: ../departamentos.php?status=success');
                    }
                    }
                    catch (Exception $e)
                    {
                        print "!Error¡: " . $e->getMessage() . "</br>";
                        die();
                        header('Location: ../departamentos.php?status=failed');
                   }
            }
        }
    }
    $enter = new saveDepartamento();
    $enter->Guardar();
?>
