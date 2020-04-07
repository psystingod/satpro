<?php
require '../../../pdfs/fpdf.php';
require_once("../../../php/config.php");
if(!isset($_SESSION))
{
    session_start();
}
$host = DB_HOST;
$user = DB_USER;
$password = DB_PASSWORD;
$database = $_SESSION['db'];
$mysqli = new mysqli($host, $user, $password, $database);

//$codigo = $_GET['id'];
$desde = $_POST['lDesde'];
$hasta = $_POST['lHasta'];
$codigoCliente = $_GET['codigoCliente'];
$anulada = 0;
//$detallado = $_POST['lDetallado'];
$tipoServicio = $_GET["tipoServicio"];

// SQL query para traer datos del servicio de cable de la tabla impuestos
$query = "SELECT valorImpuesto FROM tbl_impuestos WHERE siglasImpuesto = 'IVA'";
// Preparación de sentencia
$statement = $mysqli->query($query);
//$statement->execute();
while ($result = $statement->fetch_assoc()) {
    $iva = floatval($result['valorImpuesto']);
}

// SQL query para traer datos del servicio de cable de la tabla impuestos
$query = "SELECT valorImpuesto FROM tbl_impuestos WHERE siglasImpuesto = 'CESC'";
// Preparación de sentencia
$statement = $mysqli->query($query);
//$statement->execute();
while ($result = $statement->fetch_assoc()) {
    $cesc = floatval($result['valorImpuesto']);
}

function abonos(){
    global $desde, $hasta, $codigoCliente,$tipoServicio,$anulada,$mysqli;

            $pdf = new FPDF();

            $pdf->AddPage('L','Letter');
            $pdf->SetFont('Arial','',6);
            $pdf->Cell(260,6,utf8_decode("Página ".str_pad($pdf->pageNo(),4,"0",STR_PAD_LEFT)),0,1,'R');
            $pdf->Image('../../../images/logo.png',10,10, 20, 18);

            $pdf->Ln(15);

            /*$pdf->SetFont('Arial','B',13);
            $pdf->Cell(190,6,utf8_decode('F-3'),0,1,'R');
            $pdf->Ln();
            $pdf->Cell(190,6,utf8_decode('PAGARÉ SIN PROTESTO'),0,1,'C');
            $pdf->Ln(10);*/

            $pdf->SetFont('Arial','B',11);

            date_default_timezone_set('America/El_Salvador');

            //echo strftime("El año es %Y y el mes es %B");
            putenv("LANG='es_ES.UTF-8'");
            setlocale(LC_ALL, 'es_ES.UTF-8');
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 4, utf8_decode('CABLE VISION POR SATELITE, S.A DE C.V.'), 0, 1, 'L');
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(190, 4, utf8_decode('LISTADO DE ABONOS APLICADOS'), 0, 1, 'L');
            if ($_SESSION['db'] == 'satpro_sm'){
                $pdf->Cell(190, 4, utf8_decode('SUCURSAL SAN MIGUEL'), 0, 1, 'L');

            }elseif ($_SESSION['db'] == 'satpro'){
                $pdf->Cell(190, 4, utf8_decode('SUCURSAL USULUTÁN'), 0, 1, 'L');
            }else{
                $pdf->Cell(190, 4, utf8_decode('SUCURSAL DE PRUEBA'), 0, 1, 'L');
            }
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(190, 4, utf8_decode('DESDE '.$desde.' HASTA '.$hasta), 0, 1, 'L');
            $pdf->Ln(2);

            $pdf->SetFont('Arial','B',6.5);
            $pdf->Cell(20,5,utf8_decode('N°'),1,0,'L');
            $pdf->Cell(20,5,utf8_decode('N° de recibo'),1,0,'L');
            $pdf->Cell(20,5,utf8_decode('Fecha'),1,0,'L');
            $pdf->Cell(70,5,utf8_decode('Cliente'),1,0,'L');
            $pdf->Cell(20,5,utf8_decode('Mes que abonó'),1,0,'L');
            $pdf->Cell(10,5,utf8_decode('Día cob'),1,0,'L');
            $pdf->Cell(20,5,utf8_decode('Tipo serv'),1,0,'L');
            $pdf->Cell(20,5,utf8_decode('Abonado'),1,0,'L');
            $pdf->Cell(20,5,utf8_decode('Anticipo'),1,0,'L');
            $pdf->Cell(15,5,utf8_decode('Impuesto'),1,0,'L');
            $pdf->Cell(20,5,utf8_decode('Total recibo'),1,1,'L');
            $pdf->Ln(3);

            $pdf->Cell(35,1,utf8_decode('TOTAL VENTAS MANUALES:'),0,0,'L');
            $pdf->Cell(18,1,utf8_decode(""),0,1,'L');
            $pdf->Ln(3);

                $query1 = "SELECT codigoCobrador, nombreCobrador FROM tbl_cobradores";
                // Preparación de sentencia
                $statement1 = $mysqli->query($query1);
                $controlCobrador="";
                $counter2=1;
                while ($cobradores = $statement1->fetch_assoc()) {//RECORRIDO DE TODOS LOS COBRADORES
                    $cobradorR = $cobradores["codigoCobrador"];

                    if($tipoServicio === "A") {
                        //var_dump($detallado."ENTRAMOS");
                        //SQL para todas las zonas de cobro
                        if ($_POST["lCobrador"] === "todos" && $_POST["lColonia"] === "todas") {
                            $query = "SELECT * FROM tbl_abonos WHERE anulada= '".$anulada."' AND cobradoPor= '".$cobradorR."' AND fechaAbonado BETWEEN '".$desde."' AND '".$hasta."' ORDER BY numeroRecibo ASC";
                            $resultado = $mysqli->query($query);

                        }elseif ($_POST["lCobrador"] === "todos" && $_POST["lColonia"] != "todas") {
                            $query = "SELECT * FROM tbl_abonos WHERE codigoCobrador= '".$cobradorR."' AND anulada= '".$anulada."' AND fechaAbonado BETWEEN '".$desde."' AND '".$hasta."' ORDER BY numeroRecibo ASC";
                            $resultado = $mysqli->query($query);
                        }
                    }elseif ($tipoServicio == "C") {
                        if ($_POST["lCobrador"] === "todos" && $_POST["lColonia"] === "todas") {
                            $query = "SELECT * FROM tbl_abonos WHERE anulada= '".$anulada."' AND tipoServicio= '".$tipoServicio."' AND cobradoPor= '".$cobradorR."' AND fechaAbonado BETWEEN '".$desde."' AND '".$hasta."' ORDER BY numeroRecibo ASC";
                            $resultado = $mysqli->query($query);
                        }elseif ($_POST["lCobrador"] == "todos" && $_POST["lColonia"] != "todas") {
                            $query = "SELECT * FROM tbl_abonos WHERE cobradoPor= '".$cobradorR."' AND tipoServicio= '".$tipoServicio."' AND anulada= '".$anulada."' AND fechaAbonado BETWEEN '".$desde."' AND '".$hasta."' ORDER BY numeroRecibo ASC";
                            $resultado = $mysqli->query($query);
                        }
                    }elseif ($tipoServicio == "I") {
                        if ($_POST["lCobrador"] === "todos" && $_POST["lColonia"] === "todas") {
                            $query = "SELECT * FROM tbl_abonos WHERE anulada= '".$anulada."' AND tipoServicio= '".$tipoServicio."' AND cobradoPor= '".$cobradorR."' AND fechaAbonado BETWEEN '".$desde."' AND '".$hasta."' ORDER BY numeroRecibo ASC";
                            $resultado = $mysqli->query($query);
                        }elseif ($_POST["lCobrador"] === "todos" && $_POST["lColonia"] != "todas") {
                            $query = "SELECT * FROM tbl_abonos WHERE cobradoPor= '".$cobradorR."' AND tipoServicio= '".$tipoServicio."' AND anulada= '".$anulada."' AND fechaAbonado BETWEEN '".$desde."' AND '".$hasta."' ORDER BY numeroRecibo ASC";
                            $resultado = $mysqli->query($query);
                        }
                    }

                    while($row = $resultado->fetch_assoc())
                    {
                        if ($row["cobradoPor"]==$cobradores["codigoCobrador"] && $controlCobrador != $cobradores["codigoCobrador"]) {
                            $pdf->SetFont('Arial','B',7);
                            $pdf->Cell(190,3,utf8_decode($cobradores['nombreCobrador']),0,1,'L');
                            $controlCobrador=$cobradores['codigoCobrador'];
                        }
                        if ($row["tipoServicio"] == "C") {
                            $query2 = "SELECT dia_cobro FROM clientes WHERE cod_cliente= ".$row['codigoCliente'];
                            // Preparación de sentencia
                            $statement2 = $mysqli->query($query2);
                            //$statement->execute();
                            while ($result2 = $statement2->fetch_assoc()) {
                                $diaCobro = $result2['dia_cobro'];
                            }
                        }
                        elseif ($row["tipoServicio"]  == "I") {
                            $query2 = "SELECT dia_corbo_in FROM clientes WHERE cod_cliente= ".$row['codigoCliente'];
                            // Preparación de sentencia
                            $statement2 = $mysqli->query($query2);
                            //$statement->execute();
                            while ($result2 = $statement2->fetch_assoc()) {
                                $diaCobro = $result2['dia_corbo_in'];
                            }
                        }

                        $pdf->Ln(3);
                        $pdf->SetFont('Arial','',6.5);
                        $pdf->Cell(10,1,utf8_decode(''),0,0,'L');
                        $pdf->Cell(10,1,utf8_decode($row['idAbono']),0,0,'L');
                        $pdf->Cell(20,1,utf8_decode($row['numeroRecibo']),0,0,'L');
                        $pdf->Cell(20,1,utf8_decode($row['fechaAbonado']),0,0,'L');
                        $pdf->Cell(70,1,utf8_decode(strtoupper($row['codigoCliente']."  ".$row['nombre'])),0,0,'L');
                        $pdf->Cell(20,1,utf8_decode($row['mesCargo']),0,0,'L');
                        $pdf->Cell(10,1,utf8_decode($diaCobro),0,0,'L');
                        $pdf->Cell(20,1,utf8_decode($row['tipoServicio']),0,0,'L');

                        if ($row['tipoServicio'] == "C") {
                            $pdf->Cell(20,1,utf8_decode(number_format($row['cuotaCable'],2)),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode("0.00"),0,0,'L');
                            $pdf->Cell(15,1,utf8_decode(number_format($row['totalImpuesto'],2)),0,0,'L');
                            $pdf->Cell(20,1,utf8_decode(number_format(doubleval($row['cuotaCable'])+doubleval($row['totalImpuesto']),2)),0,1,'L');
                        }

                        if ($counter2 > 31){
                            $pdf->AddPage('L','Letter');
                            $pdf->SetFont('Arial','',6);
                            $pdf->Cell(260,6,utf8_decode("Página ".str_pad($pdf->pageNo(),4,"0",STR_PAD_LEFT)),0,1,'R');
                            $pdf->Image('../../../images/logo.png',10,10, 20, 18);

                            $pdf->Ln(15);

                            $pdf->SetFont('Arial','B',11);

                            date_default_timezone_set('America/El_Salvador');

                            //echo strftime("El año es %Y y el mes es %B");
                            putenv("LANG='es_ES.UTF-8'");
                            setlocale(LC_ALL, 'es_ES.UTF-8');
                            $pdf->SetFont('Arial', 'B', 9);
                            $pdf->Cell(190, 4, utf8_decode('CABLE VISION POR SATELITE, S.A DE C.V.'), 0, 1, 'L');
                            $pdf->SetFont('Arial', 'B', 7);
                            $pdf->Cell(190, 4, utf8_decode('LISTADO DE ABONOS APLICADOS'), 0, 1, 'L');
                            if ($_SESSION['db'] == 'satpro_sm'){
                                $pdf->Cell(190, 4, utf8_decode('SUCURSAL SAN MIGUEL'), 0, 1, 'L');

                            }elseif ($_SESSION['db'] == 'satpro'){
                                $pdf->Cell(190, 4, utf8_decode('SUCURSAL USULUTÁN'), 0, 1, 'L');
                            }
                            $pdf->SetFont('Arial', '', 7);
                            $pdf->Cell(190, 4, utf8_decode('DESDE '.$desde.' HASTA '.$hasta), 0, 1, 'L');
                            $pdf->Ln(2);

                            $pdf->SetFont('Arial','B',6.5);
                            $pdf->Cell(20,5,utf8_decode('N°'),1,0,'L');
                            $pdf->Cell(20,5,utf8_decode('N° de recibo'),1,0,'L');
                            $pdf->Cell(20,5,utf8_decode('Fecha'),1,0,'L');
                            $pdf->Cell(70,5,utf8_decode('Cliente'),1,0,'L');
                            $pdf->Cell(20,5,utf8_decode('Mes que abonó'),1,0,'L');
                            $pdf->Cell(10,5,utf8_decode('Día cob'),1,0,'L');
                            $pdf->Cell(20,5,utf8_decode('Tipo serv'),1,0,'L');
                            $pdf->Cell(20,5,utf8_decode('Abonado'),1,0,'L');
                            $pdf->Cell(20,5,utf8_decode('Anticipo'),1,0,'L');
                            $pdf->Cell(15,5,utf8_decode('Impuesto'),1,0,'L');
                            $pdf->Cell(20,5,utf8_decode('Total recibo'),1,1,'L');
                            $pdf->Ln(3);

                            $pdf->Cell(35,1,utf8_decode('TOTAL VENTAS MANUALES:'),0,0,'L');
                            $pdf->Cell(18,1,utf8_decode(""),0,1,'L');
                            $counter2=1;
                            //$pdf->Ln(3);
                        }
                        $counter2++;

                    }
                    $pdf->Ln(1);

                }

            $pdf->Cell(185,4,utf8_decode(''),0,0,'R');
            $pdf->Cell(75,4,utf8_decode(''),"",1,'R');

            //$pdf->AddPage('L','Letter');
            $pdf->SetFont('Arial','B',8);

            $pdf->Cell(237,4,utf8_decode('TOTALES GENERALES'),'',1,'R');
            $pdf->SetFont('Arial','',8);
            //TOTAL INTERNET
            $pdf->Cell(180,4,utf8_decode('TOTAL INTERNET: '),0,0,'R');
            $pdf->Cell(20,4,number_format(""),"T",0,'L');
            $pdf->Cell(20,4,number_format(""),"T",0,'L');
            $pdf->Cell(15,4,number_format(""),"T",0,'L');
            $pdf->Cell(25,4,"+ ".number_format(""),"T",1,'L');

            //TOTAL CABLE
            $pdf->Cell(180,4,utf8_decode('TOTAL CABLE: '),0,0,'R');
            $pdf->Cell(20,4,number_format(""),"T",0,'L');
            $pdf->Cell(20,4,number_format(""),"T",0,'L');
            $pdf->Cell(15,4,number_format(""),"T",0,'L');
            $pdf->Cell(25,4,"+ ".number_format(""),"T",1,'L');

            //TOTAL IMPUESTO
            $pdf->Cell(180,4,utf8_decode('TOTAL GENERAL: '),"",0,'R');
            $pdf->Cell(20,4,number_format(""),"T",0,'L');
            $pdf->Cell(20,4,number_format(""),"T",0,'L');
            $pdf->Cell(15,4,utf8_decode(""),"T",0,'L');
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(25,4,"= ".number_format(""),"T",1,'L');

            //TOTAL MANUAL
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(180,4,utf8_decode('TOTAL MANUALES: '),"",0,'R');
            $pdf->Cell(20,4,'',"T",0,'L');
            $pdf->Cell(20,4,'',"T",0,'L');
            $pdf->Cell(15,4,'',"T",0,'L');
            $pdf->Cell(25,4,"+ ".utf8_decode(""),"T",1,'L');

            //TOTAL
            $pdf->Cell(180,4,utf8_decode('TOTAL GENERAL + MANUALES: '),"",0,'R');
            $pdf->Cell(20,4,'',"T",0,'L');
            $pdf->Cell(20,4,'',"T",0,'L');
            $pdf->Cell(15,4,'',"T",0,'L');
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(25,4,"= ".utf8_decode(""),"T",1,'L');

    /* close connection */
    mysqli_close($mysqli);
    $pdf->Output();

}

abonos();

?>
