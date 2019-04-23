<?php
include "../php/connection.php";

date_default_timezone_set('America/El_Salvador');
$day = date("Y-m-d");
echo $day;
echo "<br>";
$dayOfWeek = date("l", strtotime($day));
echo $dayOfWeek;
echo "<br>";
$nuevafecha = strtotime( '+2 day', strtotime($day));
$nuevafecha = date ('Y-m-d', $nuevafecha);
echo $nuevafecha;
echo "<br>";
$dayOfWeek2 = date("l", strtotime($nuevafecha));
echo $dayOfWeek2;
if (date("l", strtotime($day) == "Monday")) {
    echo "Ahora es Lunes";
}
echo "<br>";
switch (date("l", strtotime($day))) {
    case 'Monday':
        echo $day."<br>";
        echo date("Y-m-d", strtotime( '+1 day', strtotime($day)))."<br>";
        echo date("Y-m-d", strtotime( '+2 day', strtotime($day)))."<br>";
        echo date("Y-m-d", strtotime( '+3 day', strtotime($day)))."<br>";
        echo date("Y-m-d", strtotime( '+4 day', strtotime($day)))."<br>";
        echo date("Y-m-d", strtotime( '+5 day', strtotime($day)))."<br>";
        break;

    default:
        // code...
        break;
}
echo "<br><br><br>";
switch (date("l", strtotime($day))) {
    case 'Monday':
        echo $day."<br>";
        for ($i=1; $i <= 5; $i++) {
            echo date("Y-m-d", strtotime( '+'.$i.'day', strtotime($day)))."<br>";
        }
        break;
    case 'Tuesday':
        echo $day."<br>";
        for ($i=1; $i <= 4; $i++) {
            echo date("Y-m-d", strtotime( '+'.$i.'day', strtotime($day)))."<br>";
        }
        break;
    case 'Wednesday':
        echo $day."<br>";
        for ($i=1; $i <= 3; $i++) {
            echo date("Y-m-d", strtotime( '+'.$i.'day', strtotime($day)))."<br>";
        }
        break;
    case 'Thursday':
        echo $day."<br>";
        for ($i=1; $i <= 2; $i++) {
            echo date("Y-m-d", strtotime( '+'.$i.'day', strtotime($day)))."<br>";
        }
        break;
    case 'Friday':
        echo $day."<br>";
        for ($i=1; $i <= 1; $i++) {
            echo date("Y-m-d", strtotime( '+'.$i.'day', strtotime($day)))."<br>";
        }
        break;
    case 'Friday':
        echo $day."<br>";
        for ($i=1; $i <= 1; $i++) {
            echo date("Y-m-d", strtotime( '+'.$i.'day', strtotime($day)))."<br>";
        }
        break;
    default:
        // code...
        break;
}
/*$query = "SELECT hora FROM prueba WHERE fecha = :fecha";
$statement = $this->dbConnect->prepare($query);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $key) {
    $key['hora'];
}

$nuevafecha = strtotime ( '+2 day' , strtotime ( $fecha ) ) ;
$nuevafecha = date ( 'Y-m-j' , $nuevafecha );*/

?>
