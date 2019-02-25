<?php
    require_once("config.php");
    /**
     * Clase conexion
     */
    class ConectionDB
    {
        protected $dbConect;

        public function ConectionDB()
        {
            try {
                $this->dbConnect = new PDO("mysql:host=localhost;dbname=satpro", DB_USER, DB_PASSWORD);
                $this->dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->dbConnect->exec("SET CHARACTER SET utf8");
                return $this->dbConnect;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }
    }
?>
