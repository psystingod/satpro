<?php
    require('connection.php');
    require('permissions.php');
    require('modulePermissions.php');
    /**
     * Clase para iniciar sesión de los usuarios
     */
    class Login extends ConectionDB
    {
        public function Login()
        {
            parent::__construct ();
        }
        public function startSession()
        {
                /**
                 * Datos del usuario
                 */
                $user = $_POST['user'];
                $pass = $_POST['pass'];

                $query1 = "SELECT * FROM tbl_usuario WHERE Usuario = :user";
                // prepare query for excecution
                $stmt = $this->dbConnect->prepare($query1);
                // Execute the query
                $stmt->execute(array(
                ':user' => $user
                ));

                $result = $stmt->fetch();

                if (password_verify($pass, $result['Clave'])) {

                    // SQL query para obtener datos del usuario
                    $query = "SELECT * FROM tbl_empleados, tbl_usuario WHERE Usuario=:user AND tbl_empleados.id_empleado = tbl_usuario.IdUsuario";
                    // Prepare statement
                    $statement = $this->dbConnect->prepare($query);
                    $statement->execute(array(
                        ':user' => $user
                    ));

                    $loginRow = $statement->fetch(PDO::FETCH_ASSOC);

                    if ($loginRow != false) {
                        // Inicia sesión solo si el usuario existe en la base de datos
                        session_start();
                        // Almacenamos los datos del usuario en la sesión
                        $_SESSION['id_usuario'] = $loginRow['IdUsuario'];
                        $_SESSION['nombres'] = $loginRow['nombres'];
                        $_SESSION['apellidos'] = $loginRow['apellidos'];
                        $_SESSION['user'] = $loginRow['Usuario'];
                        $_SESSION['pass'] = $loginRow['Clave'];
                        $_SESSION['rol'] = $loginRow['Rol'];

                        $totalPermissionsCheck = new Permissions();
                        $totalModulePermissionsCheck = new ModulePermissions();

                        $_SESSION['permisosTotales'] = $totalPermissionsCheck->getPermissions($_SESSION['id_usuario']);
                        $_SESSION['permisosTotalesModulos'] = $totalModulePermissionsCheck->getPermissions($_SESSION['id_usuario']);

                        $this->dbConnect = NULL;
                        header("Location: ../pages/index.php");
                    }
                    else {
                        $this->dbConnect = NULL;
                        header("Location: ../pages/login.php");
                    }
                }
                else {
                    header("Location: ../pages/login.php?login=failed");
                }
        }
    }

    $login = new Login();
    $login->startSession();
?>
