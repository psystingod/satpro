<?php

require_once("../../../php/config.php");

$host = DB_HOST;
$user = DB_USER;
$password = DB_PASSWORD;
$database = DB_NAME;

$mysqli = new mysqli($host, $user, $password, $database);

$username = $_GET['username'];
$startFrom = $_GET['startFrom'];
$param = $_GET['param'];
$paramServicio = $_GET['paramServicio'];

// validate - https://developer.hyvor.com/php/input-validation-with-php
$username = trim(htmlspecialchars($username));
$startFrom = filter_var($startFrom, FILTER_VALIDATE_INT);

// make username search friendly
$like = '%' . strtolower($username) . '%'; // search for a the username, case-insensitive (see strtolower() here and MYSQL lower() function in the query)
if ($paramServicio == 1) {
	if ($param == 1) {
		$statement = $mysqli -> prepare('
			SELECT cod_cliente, nombre, direccion FROM clientes
			WHERE lower(cod_cliente) LIKE ?
			ORDER BY INSTR(cod_cliente, ?), cod_cliente
			LIMIT 6 OFFSET ?
		');
	}else if ($param == 2) {
		$statement = $mysqli -> prepare('
			SELECT cod_cliente, nombre, direccion FROM clientes
			WHERE lower(nombre) LIKE ?
			ORDER BY INSTR(nombre, ?), nombre
			LIMIT 6 OFFSET ?
		');
	}else if ($param == 3) {
		$statement = $mysqli -> prepare('
			SELECT cod_cliente, nombre, direccion FROM clientes
			WHERE lower(direccion) LIKE ?
			ORDER BY INSTR(direccion, ?), direccion
			LIMIT 6 OFFSET ?
		');
	}
}

// open new mysql prepared statement
/*$statement = $mysqli -> prepare('
	SELECT cod_cliente, nombre, numero_dui, mac_modem, serie_modem, direccion, tecnologia FROM clientes
	WHERE lower(cod_cliente) LIKE ?
	ORDER BY INSTR(cod_cliente, ?), cod_cliente
	LIMIT 6 OFFSET ?
');*/

if (
	// $mysqli -> prepare returns false on failure, stmt object on success
	$statement &&
	// bind_param returns false on failure, true on success
	$statement -> bind_param('ssi', $like, $username, $startFrom ) &&
	// execute returns false on failure, true on success
	$statement -> execute() &&
	// same happens in store_result
	$statement -> store_result() &&
	// same happens here
	$statement -> bind_result($codigo, $nombre, $direccion);
	//$statement -> bind_result($codigo, $nombre, $dui, $mac, $serie, $direccion, $tecnologia)
) {
	// I'm in! everything was successful.

	// new array to store data
	$array = [];


	while ($statement -> fetch()) {
		$array[] = [
			'codigo' => $codigo,
			'nombre' => $nombre,
			'direccion' => $direccion
		];
	}

	echo json_encode($array);
	exit();


}
