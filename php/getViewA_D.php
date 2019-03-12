<?php
    require_once('connection.php');
    /**
     * Clase para capturar los datos de la solicitud
     */
    class getViewA_D extends ConectionDB
    {
        public function getViewA_D()
        {
            parent::__construct ();
        }
        public function ReportesFinales()
        {
            try {
                $query = "SELECT r.IdReportead as Idreportead, r.FechaEnvio as Fecha, e.Nombres as NombreEmpleado, d.NombreDepartamento, b.NombreBodega from tbl_reportead as r inner join tbl_empleado as e on r.IdEmpleadoEnvio=e.IdEmpleado
                inner join tbl_departamento as d on r.IdDepartamento=d.IdDepartamento inner join tbl_bodega as b on r.IdBodega=b.IdBodega where r.State=1";
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
                $query = "SELECT r.IdReportead as Idreportead, r.FechaEnvio as Fecha, e.Nombres as NombreEmpleado, d.NombreDepartamento, b.NombreBodega from tbl_reportead as r inner join tbl_empleado as e on r.IdEmpleadoEnvio=e.IdEmpleado
                inner join tbl_departamento as d on r.IdDepartamento=d.IdDepartamento inner join tbl_bodega as b on r.IdBodega=b.IdBodega where r.State=0";
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

        public function detalleAsignacionesPendientes($a)
        {
            try {
                $query = "SELECT r.IdReportead as Idreportead, r.FechaEnvio as Fecha, e.Nombres as NombreEmpleado, e.Codigo as CodigoEmpleado, d.NombreDepartamento,
                 b.NombreBodega, a.Codigo as CodigoArticulo, a.NombreArticulo, d1.Cantidad, r.ComentarioEnvio from tbl_reportead as r inner join tbl_empleado as e on r.IdEmpleadoEnvio=e.IdEmpleado
                inner join tbl_departamento as d on r.IdDepartamento=d.IdDepartamento inner join tbl_bodega as b on
                 r.IdBodega=b.IdBodega inner join tbl_detallead as d1 on d1.IdReporteAD=r.IdReporteAd inner join tbl_articulo as a
                on a.IdArticulo=d1.IdArticulo where d1.IdReporteAD='".$a."'";
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
         public function detalleAsignacionesRealizadas($a)
        {
            try {
                $query = "SELECT r.IdReportead as Idreportead, r.FechaEnvio as FechaEnvia, e.Nombres as NombreEmpleadoEnvia, e.Codigo as CodigoEmpleadoEnvia,
                        r.FechaRecibe as FechaRecibe, e1.Nombres as NombreEmpleadoRecibe, e1.Codigo as CodigoEmpleadoRecibe,
                        d.NombreDepartamento as DepartamentoRecibe, b.NombreBodega, a.Codigo as CodigoArticulo, a.NombreArticulo, d1.Cantidad,
                        r.ComentarioEnvio, r.ComentarioRecibe from tbl_reportead as r inner join tbl_empleado as e on r.IdEmpleadoEnvio=e.IdEmpleado INNER join
                        tbl_empleado as e1 on e1.IdEmpleado=r.IdEmpleadoRecibe inner join tbl_departamento as d on r.IdDepartamento=d.IdDepartamento inner join tbl_bodega as b on
                        r.IdBodega=b.IdBodega inner join tbl_detallead as d1 on d1.IdReporteAD=r.IdReporteAd inner join tbl_articulo as a
                        on a.IdArticulo=d1.IdArticulo where d1.IdReporteAD='".$a."'";
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
         public function InventarioDepartamento()
        {
            try {
                // SQL query para traer los datos de los productos
                $query = "SELECT AD.IdArticuloDepartamento, AD.Codigo, AD.NombreArticulo, AD.Cantidad, D.NombreDepartamento FROM satpro.tbl_articulodepartamento as AD inner join tbl_departamento as D on AD.IdDepartamento=D.IdDepartamento";
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
        public function InventarioDepartamentoFiltrado()
       {
           try {
               // SQL query para traer los datos de los productos
               $query = "SELECT AD.IdArticuloDepartamento, AD.Codigo, AD.NombreArticulo, AD.Cantidad, D.NombreDepartamento FROM satpro.tbl_articulodepartamento as AD inner join tbl_departamento as D on AD.IdDepartamento=D.IdDepartamento where AD.Cantidad > 0";
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
       public function InventarioEmpleado()
      {
          try {
              // SQL query para traer los datos de los productos
              $query = "SELECT ae.IdArticuloEmpleado, ae.CodigoEmpleado, ae.NombreEmpleado, ad.NombreArticulo, ae.IdArticuloDepartamento, ae.Comentario,
              ae.Cantidad, ae.FechaEntregado, d.NombreDepartamento from tbl_articuloempleado as ae inner join tbl_articulodepartamento as ad on ae.IdArticuloDepartamento=ad.IdArticuloDepartamento
              inner join tbl_departamento as d on d.IdDepartamento=ae.IdDepartamento";
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
      public function getEncargado()
     {
         try {
             // SQL query para traer los datos de los productos
             $query = "SELECT d.IdDepartamento, d.CodigoDepartamento, d.NombreDepartamento, e.Codigo, e.Nombres as Encargado
             FROM tbl_departamento as d left join tbl_empleado as e on d.IdEmpleado=e.IdEmpleado where d.State=0";
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
     public function getHistorial()
    {
        try {
            // SQL query para traer los datos de los productos
            $query = "SELECT h.FechaHora as FechaHora, h.Tipo_Movimiento as Tipo_Movimiento, h.Descripcion as
            descr, e.Nombres, e.Apellidos, d.NombreDepartamento as NombreDepartamento FROM tbl_historialRegistros as h inner join
             tbl_empleado as e on h.IdEmpleado=e.IdEmpleado inner join tbl_departamento as d on d.IdDepartamento=e.IdDepartamento";
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
    public function ReportesFinalesDB()
   {
       try {
           $query = "SELECT r.IdReportedb as Idreportead, r.FechaEnvio as Fecha, e.Nombres as NombreEmpleado, d.NombreDepartamento, b.NombreBodega from tbl_reportedb as r inner join tbl_empleado as e on r.IdEmpleadoEnvio=e.IdEmpleado
           inner join tbl_departamento as d on r.IdDepartamento=d.IdDepartamento inner join tbl_bodega as b on r.IdBodega=b.IdBodega where r.State=1";
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
   public function ReportesPendientesDB()
   {
       try {
           $query = "SELECT r.IdReportedb as Idreportead, r.FechaEnvio as Fecha, e.Nombres as NombreEmpleado, d.NombreDepartamento, b.NombreBodega from tbl_reportedb as r inner join tbl_empleado as e on r.IdEmpleadoEnvio=e.IdEmpleado
           inner join tbl_departamento as d on r.IdDepartamento=d.IdDepartamento inner join tbl_bodega as b on r.IdBodega=b.IdBodega where r.State=0";
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

   public function detalleAsignacionesPendientesDB($a)
   {
       try {
           $query = "SELECT r.IdReportedb as Idreportedb, r.FechaEnvio as Fecha, e.Nombres as NombreEmpleado, e.Codigo as CodigoEmpleado, d.NombreDepartamento,
           b.NombreBodega, a.Codigo as CodigoArticulo, a.NombreArticulo, d1.Cantidad, r.ComentarioEnvio from tbl_reportedb as r inner join tbl_empleado as e on r.IdEmpleadoEnvio=e.IdEmpleado
           inner join tbl_departamento as d on r.IdDepartamento=d.IdDepartamento inner join tbl_bodega as b on
           r.IdBodega=b.IdBodega inner join tbl_detalledb as d1 on d1.IdReportedb=r.IdReportedb inner join tbl_articulodepartamento as a
                       on a.IdArticulodepartamento=d1.IdArticulo where d1.IdReportedb='".$a."'";
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

   public function detalleAsignacionesRealizadasDB($a)
  {
      try {
          $query = "SELECT e.Nombres as EmpleadoEnvia, e.Codigo as CodigoEnvia, d.NombreDepartamento as DepartamentoEnvia, FechaEnvio,
          e1.Nombres as EmpleadoRecibe, e1.Codigo as CodigoRecibe, b.NombreBodega as BodegaRecibe, FechaRecibe, ad.Codigo as CodigoArticulo,
          ad.NombreArticulo as NombreArticulo, de.Cantidad as Cantidad, r.ComentarioEnvio, r.ComentarioRecibe
            from tbl_reportedb as r inner join tbl_departamento as d on r.IdDepartamento=d.IdDepartamento inner join
          tbl_bodega as b on r.IdBodega=b.IdBodega inner join tbl_empleado as e on r.IdEmpleadoEnvio=e.IdEmpleado inner join tbl_empleado as e1
          on r.IdEmpleadoRecibe=e1.IdEmpleado inner join tbl_detalledb as de on r.IdReporteDB=de.IdReporteDB inner join tbl_articulodepartamento as ad
          on ad.IdArticuloDepartamento=de.IdArticulo where r.IdReporteDB='".$a."' ";
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
