<?php
    require_once("config.php");
    /**
     * Clase conexion
     */
    class ConectionDB
    {
        protected $dbConnect;

        public function ConectionDB($db)
        {
            try {
                $this->dbConnect = new PDO("mysql:host=localhost;dbname=".$db, DB_USER, DB_PASSWORD);
                $this->dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->dbConnect->exec("SET CHARACTER SET utf8");
                return $this->dbConnect;

            } /*catch (Exception $e) {
                print "<h1>" . "No existe la sucursal que solicita." . "</h1>";
<<<<<<< HEAD
                print "<a href='../pages/login.php'>" . "<h2>Volver atrÃ¡s</h2>" . "</a>";
                die();
            }*/catch (Exception $e) {
                print "!ErrorÂ¡: " . $e->getMessage() . "</br>";
=======
                print "<a href='../pages/login.php'>" . "<h2>Volver atrás</h2>" . "</a>";
                die();
            }*/catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
>>>>>>> 5217f37d1bac7a0cef4ccc090dacd613611b185a
                die();
            }
        }
    }
?>
