<?php
    require('../../../php/connection.php');
    /**
     * Clase para ingresar productos al inventario
     */
    class NewEmployee extends ConectionDB
    {
        public function NewEmployee()
        {
            parent::__construct ();
        }
        public function saveEmployee()
        {
            try {
                $idEmpleado = $_POST["codigo"];
                $nombres = $_POST["nombres"];
                $apellidos = $_POST["apellidos"];
                $nombreiss = $_POST["nombreiss"];
                $direccionParticular = $_POST["direccionParticular"];
                $telefono = $_POST["telefono"];
                $municipio = $_POST["municipio"];
                $departamento = $_POST["departamento"];
                $dui = $_POST["dui"];
                $extendidoEn = $_POST["extendidoEn"];
                $fechaExpedicion = $_POST["fechaExpedicion"];
                $fechaNacimiento = $_POST["fechaNacimiento"];
                $edad = $_POST["edad"];
                $nacionalidad = $_POST["nacionalidad"];
                $nivelEstudios = $_POST["nivelEstudios"];
                $nit = $_POST["nit"];
                $licencia = $_POST["licencia"];
                $numIsss = $_POST["numIss"];
                $nup = $_POST["nup"];
                $clase = $_POST["clase"];
                $estatura = $_POST["estatura"];
                $peso = $_POST["peso"];
                $sexo = $_POST["sexo"];
                $tipoSangre = $_POST["tipoSangre"];
                $profesionOficio = $_POST["profesionOficio"];
                $estadoCivil = $_POST["estadoCivil"];
                $senalesEspeciales = $_POST["senalesEspeciales"];
                $nombreConyugue = $_POST["nombreConyugue"];
                $lugarTrabajoConyugue = $_POST["lugarTrabajoConyugue"];
                $nombrePadre = $_POST["nombrePadre"];
                $nombreMadre = $_POST["nombreMadre"];
                $contactos = $_POST["contactos"];
                $fechaIngreso = $_POST["fechaIngreso"];
                $fechaContratacion = $_POST["fechaContratacion"];
                $salarioOrdinario = $_POST["salarioOrdinario"];
                $fechaCambioSalario = $_POST["fechaCambioSalario"];
                $centroTrabajoEmpleado = $_POST["centroTrabajoEmpleado"];
                $cuentaBanco = $_POST["cuentaBanco"];
                $afpPertenece = $_POST["afpPertenece"];
                $afpPorcent = $_POST["afpPorcent"];
                $personaAutorizada = $_POST["personaAutorizada"];
                $nCuenta = $_POST["nCuenta"];
                $cargoPlaza = $_POST["cargoPlaza"];
                $depto = $_POST["depto"];
                $tipoContratacion = $_POST["tipoContratacion"];
                $cca = $_POST["cca"];
                $ccpi = $_POST["ccpi"];
                $ccr = $_POST["ccr"];
                $nacimiento = date_create($_POST["nacimiento"]);
                $departamento = $_POST["departamento"];
                $direccion = $_POST["direccion"];
                $state = 1;

                /* DATOS PARA TABLA USUARIOS */
                $usuario = $codigo;
                $clave = password_hash($codigo, PASSWORD_DEFAULT);
                $rol = $_POST["rol"];

                $query = "INSERT INTO tbl_empleado(Codigo, Nombres, Apellidos, Direccion, Dui, Nit, Isss, Afp, E_Familiar, G_Academico, FechaNacimiento, Telefono, IdUsuario, IdDepartamento, State)
                          VALUES(:codigo, :nombres, :apellidos, :direccion, :dui, :nit, :isss, :afp, :estadoFamiliar, :gradoAcademico, :nacimiento, :telefono,
                                :idUsuario, (SELECT IdDepartamento FROM tbl_departamento WHERE NombreDepartamento = :departamento), :state)";

                $statement = $this->dbConnect->prepare($query);

                $statement->execute(array(
                ':codigo' => $codigo,
                ':nombres'=> $nombres,
                ':apellidos' => $apellidos,
                ':direccion' => $direccion,
                ':dui' => $dui,
                ':nit'=> $nit,
                ':isss' => $isss,
                ':afp' => $afp,
                ':estadoFamiliar' => $estadoFamiliar,
                ':gradoAcademico'=> $gradoAcademico,
                ':nacimiento' => date_format($nacimiento, 'Y-m-d'),
                ':telefono' => $telefono,
                ':idUsuario'=> null,
                ':departamento' => $departamento,
                ':state' => 1
                ));

                /* UPDATE IDUSUARIO */
                $lastId = $this->dbConnect->lastInsertId();

                $query = "UPDATE tbl_empleado SET IdUsuario = $lastId WHERE IdEmpleado = $lastId";

                $statement = $this->dbConnect->prepare($query);

                $statement->execute();
                /* /UPDATE IDUSUARIO */

                /* INSERT NEW USER BASED ON NEW EMPLOYEE */
                $query = "INSERT INTO tbl_usuario(IdUsuario, Usuario, Clave, Rol, State) VALUES (:idUsuario, :usuario, :clave, :rol, :state)";
                $statement = $this->dbConnect->prepare($query);

                $statement->execute(array(
                ':idUsuario' => $lastId,
                ':usuario' => $usuario,
                ':clave' => $clave,
                ':rol' => $rol,
                ':state' => 1
                ));
                /* END INSERT NEW USER */
                if ($rol == "administracion") {
                    /* INSERT NEW PERMISSIONS BASED ON NEW EMPLOYEE TYPE */
                    $query = "INSERT INTO tbl_permisosglobal(Madmin, Mcont, Mplan, Macti, Minve, Miva, Mbanc, Mcxc, Mcxp, Ag, Ed, El, IdUsuario) VALUES
                                                            (:madmin, :mcont, :mplan, :macti, :minve, :miva, :mbanc, :mcxc, :mcxp, :ag, :ed, :el, :idusuario)";
                    $statement = $this->dbConnect->prepare($query);

                    $statement->execute(array(
                    ':madmin' => 1,
                    ':mcont' => 2,
                    ':mplan' => 4,
                    ':macti' => 8,
                    ':minve' => 16,
                    ':miva' => 32,
                    ':mbanc' => 64,
                    ':mcxc' => 128,
                    ':mcxp' => 256,
                    ':ag' => 1,
                    ':ed' => 2,
                    ':el' => 4,
                    ':idusuario' => $lastId
                    ));
                    /* END INSERT NEW PERMISSIONS */
                }
                else if ($rol == "subgerencia") {
                    /* INSERT NEW PERMISSIONS BASED ON NEW EMPLOYEE TYPE */
                    $query = "INSERT INTO tbl_permisosglobal(Madmin, Mcont, Mplan, Macti, Minve, Miva, Mbanc, Mcxc, Mcxp, Ag, Ed, El, IdUsuario) VALUES
                                                            (:madmin, :mcont, :mplan, :macti, :minve, :miva, :mbanc, :mcxc, :mcxp, :ag, :ed, :el, :idusuario)";
                    $statement = $this->dbConnect->prepare($query);

                    $statement->execute(array(
                    ':madmin' => 0,
                    ':mcont' => 0,
                    ':mplan' => 0,
                    ':macti' => 0,
                    ':minve' => 16,
                    ':miva' => 0,
                    ':mbanc' => 0,
                    ':mcxc' => 128,
                    ':mcxp' => 256,
                    ':ag' => 1,
                    ':ed' => 2,
                    ':el' => 0,
                    ':idusuario' => $lastId
                    ));
                    /* END INSERT NEW PERMISSIONS */
                }
                else if ($rol == "jefatura") {
                    /* INSERT NEW PERMISSIONS BASED ON NEW EMPLOYEE TYPE */
                    $query = "INSERT INTO tbl_permisosglobal(Madmin, Mcont, Mplan, Macti, Minve, Miva, Mbanc, Mcxc, Mcxp, Ag, Ed, El, IdUsuario) VALUES
                                                            (:madmin, :mcont, :mplan, :macti, :minve, :miva, :mbanc, :mcxc, :mcxp, :ag, :ed, :el, :idusuario)";
                    $statement = $this->dbConnect->prepare($query);

                    $statement->execute(array(
                    ':madmin' => 0,
                    ':mcont' => 0,
                    ':mplan' => 0,
                    ':macti' => 0,
                    ':minve' => 16,
                    ':miva' => 0,
                    ':mbanc' => 0,
                    ':mcxc' => 128,
                    ':mcxp' => 0,
                    ':ag' => 1,
                    ':ed' => 0,
                    ':el' => 0,
                    ':idusuario' => $lastId
                    ));
                    /* END INSERT NEW PERMISSIONS */
                }

                else if ($rol == "contabilidad") {
                    /* INSERT NEW PERMISSIONS BASED ON NEW EMPLOYEE TYPE */
                    $query = "INSERT INTO tbl_permisosglobal(Madmin, Mcont, Mplan, Macti, Minve, Miva, Mbanc, Mcxc, Mcxp, Ag, Ed, El, IdUsuario) VALUES
                                                            (:madmin, :mcont, :mplan, :macti, :minve, :miva, :mbanc, :mcxc, :mcxp, :ag, :ed, :el, :idusuario)";
                    $statement = $this->dbConnect->prepare($query);

                    $statement->execute(array(
                    ':madmin' => 0,
                    ':mcont' => 2,
                    ':mplan' => 4,
                    ':macti' => 8,
                    ':minve' => 16,
                    ':miva' => 32,
                    ':mbanc' => 64,
                    ':mcxc' => 128,
                    ':mcxp' => 256,
                    ':ag' => 1,
                    ':ed' => 2,
                    ':el' => 0,
                    ':idusuario' => $lastId
                    ));
                    /* END INSERT NEW PERMISSIONS */
                }

               $this->dbConnect = NULL;
               header('Location: ../seguridad.php?status=success');

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
                header('Location: ../seguridad.php?status=failed');
            }
        }
    }
    $enter = new NewEmployee();
    $enter->saveEmployee();
?>
