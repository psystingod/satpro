<?php
    require_once('connection.php');
    /**
     * Clase para capturar los datos de la solicitud
     */
    class ReportsInfo extends ConectionDB
    {
        public function ReportsInfo()
        {
            parent::__construct ();
        }
        public function ReportesFinales()
        {
            try {
                // SQL query para traer nombre de las categorías
                $query = "SELECT r.IdReporte, e1.Nombres as NombreEmpleadoOrigen, r.FechaOrigen, e.Nombres as NombreEmpleadoDestino,
                 r.FechaDestino, r.Razon FROM tbl_reporte as r inner join tbl_empleado as e on r.IdEmpleadoDestino=e.IdEmpleado
                inner join tbl_empleado as e1 on e1.IdEmpleado=r.IdEmpleadoOrigen where r.state = 0";
                // Preparación de sentencia
                $statement = $this->dbConnect->prepare($query);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                return $result;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }
        public function ReportesPendientes()
        {
            try {
                // SQL query para traer nombre de las categorías
                $query = "SELECT r.IdReporte, e.Nombres as NombreEmpleado, r.FechaOrigen, b.NombreBodega as BodegaOrigen, r.Razon FROM tbl_reporte as r inner join tbl_empleado as e on r.IdEmpleadoOrigen=e.IdEmpleado
                    inner join tbl_bodega as b on b.IdBodega = IdBodegaSaliente where r.state = 1";
                // Preparación de sentencia
                $statement = $this->dbConnect->prepare($query);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                return $result;

            } catch (Exception $e) {
                print "!Error¡: " . $e->getMessage() . "</br>";
                die();
            }
        }
    }
?>
