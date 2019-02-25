<?php
$dsn = 'mysql:host=localhost;dbname=satpro';
$username = 'root';
$password = '';

try{

    $con = new PDO($dsn, $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $ex) {

    echo 'No se pudo conectar a la base de datos '.$ex->getMessage();
}

$query1 = "SELECT * FROM tbl_usuario WHERE Usuario = '00-003'";
// prepare query for excecution
$stmt = $con->prepare($query1);
// Execute the query
$stmt->execute();
$result = $stmt->fetch();
$clave = "00-003";
echo var_dump($result['Usuario']);
echo var_dump($result['Clave']);
echo var_dump($clave);
echo var_dump(password_verify($clave, $result['Clave']));
