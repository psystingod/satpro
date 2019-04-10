<?php
$n = "004800";
$len = strlen($n);
$concatenate = "";
for ($i=0; $i < $len; $i++) {
    if ($n[$i] == "0") {
        $add = $n[$i];
        $concatenate .= $add;
    }else {
        break;
    }
}
//$n = intval($n);
echo var_dump($concatenate);
?>
