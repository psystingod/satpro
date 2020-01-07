<?php
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

$salida = "";
$query = "SELECT cod_cliente, nombre, direccion, telefonos, Mac_modem, Serie_modem, Dire_telefonia FROM clientes ORDER BY cod_cliente LIMIT 7";
 if (isset($_POST['consulta'])) {
 	$q = $mysqli->real_escape_string($_POST['consulta']);
	$query = "SELECT cod_cliente, nombre, direccion, telefonos, Mac_modem, Serie_modem, Dire_telefonia FROM clientes
	WHERE cod_cliente LIKE '%".$q."%' OR nombre LIKE '%".$q."%' OR direccion LIKE '%".$q."%' OR telefonos LIKE '%".$q."%' OR Mac_modem LIKE '%".$q."%' OR Serie_modem LIKE '%".$q."%' OR Dire_telefonia LIKE '%".$q."%' LIMIT 10";
 }

 $resultado = $mysqli->query($query);

 if ($resultado->num_rows > 0) {
 	$salida.="<br><table class='table table-striped table-responsive'>
			    <thead>
					<tr class='success'>
						<th>CODIGO</th>
						<th>NOMBRE</th>
						<th>DIRECCION</th>
                        <th>TELEFONOS</th>
                        <th>MAC</th>
                        <th>SERIE</th>
                        <th>NODO</th>
					</tr>
				</thead>
				<tbody>";
	while ($fila = $resultado->fetch_assoc()) {
		$salida.= "<tr>
			<td>"."<a class='btn btn-primary btn-sm' href=infoCliente.php?id={$fila['cod_cliente']} target='_blank'>".$fila['cod_cliente']."<a></td>
			<td>".utf8_decode($fila['nombre'])."</td>
			<td>".utf8_decode($fila['direccion'])."</td>
            <td>".$fila['telefonos']."</td>
            <td>".$fila['Mac_modem']."</td>
            <td>".$fila['Serie_modem']."</td>
            <td>".$fila['Dire_telefonia']."</td>
		</tr>";
	}
	$salida.="</tbody></table>";
}else {
	$salida.="No se encontraron coincidencias";
}
echo $salida;
$mysqli->close();
