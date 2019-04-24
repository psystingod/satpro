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
                $idEmpleado = $_POST["idEmpleado"];
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
                $empresa1 = $_POST["empresa1"];
                $cargo1 = $_POST["cargo1"];
                $jefe1 = $_POST["jefe1"];
                $tiempoTrabajo1 = $_POST["tiempoTrabajo1"];
                $motivoRetiro1 = $_POST["motivoRetiro1"];
                $empresa2 = $_POST["empresa2"];
                $cargo2 = $_POST["cargo2"];
                $jefe2 = $_POST["jefe2"];
                $tiempoTrabajo2 = $_POST["tiempoTrabajo2"];
                $motivoRetiro2 = $_POST["motivoRetiro2"];
                $nombreRef1 = $_POST["nombreRef1"];
                $ref1Num = $_POST["ref1Num"];
                $nombreRef2 = $_POST["nombreRef2"];
                $ref2Num = $_POST["ref2Num"];
                $nombreRef3 = $_POST["nombreRef3"];
                $ref3Num = $_POST["ref3Num"];
                $nombreRef4 = $_POST["nombreRef4"];
                $ref4Num = $_POST["ref4Num"];
                $state = 1;

                /* DATOS PARA TABLA USUARIOS */
                $usuario = $dui;
                $clave = password_hash($dui, PASSWORD_DEFAULT);
                $rol = $_POST["rol"];

                $query = "INSERT INTO tbl_empleados(nombres, apellidos, nombre_isss, sexo, municipio, departamento, telefonos, celular, numero_nit, no_licencia, no_documento, fecha_expedicion, no_isss, no_nup, profesion_oficio,
                        lugar_nacimiento, nacionalidad, estado_civil, fecha_nacimiento, edad, nivel_estudios, clase, estatura, peso, tipo_sangre, doc_lugarext, senales_especiales, nombre_padre, nombre_madre, nombre_conyugue,
                        trabajo_conyugue, id_centro, persona_autorizada, id_afp, id_banco, id_departamento, tipo_contratacion, id_plaza, cargo, numero_cuenta, por_afp, aplicar_isss, cuota_seguro, fecha_ingreso, fecha_contratacion,
                        salario_ordinario, fecha_salario, empresa_refer1, cargo_refer1, jefe_refer1, tiempo_refer1, motivo_retiro1, empresa_refer2, cargo_refer2, jefe_refer2, tiempo_refer2, motivo_retiro2, nomb_ref_per1, tel_ref_per1,
                        nomb_ref_per2, tel_ref_per2, nomb_ref_per3, tel_ref_per3, nombre_ref_fam1, nombre_ref_fam2, estado_empleado, IdUsuario)
                        VALUES(:codigo, :nombres, :apellidos, :direccion, :dui, :nit, :isss, :afp, :estadoFamiliar, :gradoAcademico, :nacimiento, :telefono,
                        :idUsuario, (SELECT IdDepartamento FROMs tbl_departamento WHERE NombreDepartamento = :departamento), :state)";

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
                else if ($rol == "atencion") {
                    /* INSERT NEW PERMISSIONS BASED ON NEW EMPLOYEE TYPE */
                    $query = "INSERT INTO tbl_permisosglobal(Madmin, Mcont, Mplan, Macti, Minve, Miva, Mbanc, Mcxc, Mcxp, Ag, Ed, El, IdUsuario) VALUES
                                                            (:madmin, :mcont, :mplan, :macti, :minve, :miva, :mbanc, :mcxc, :mcxp, :ag, :ed, :el, :idusuario)";
                    $statement = $this->dbConnect->prepare($query);

                    $statement->execute(array(
                    ':madmin' => 0,
                    ':mcont' => 0,
                    ':mplan' => 0,
                    ':macti' => 0,
                    ':minve' => 0,
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
