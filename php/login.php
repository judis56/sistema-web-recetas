<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$data = file_get_contents("php://input");
$objData = json_decode($data);
/*
$objData->usuario = 'Judis';
$objData->clave = 'judith';*/

include("conexiones.php");

$result = $conn->query("SELECT * FROM usuarios WHERE correo='".$objData->usuario."' and clave='".$objData->clave."'");
$reg= $result->num_rows;

$outp .='{"nombre":"",';
$outp .='"ap":"",';
$outp .='"am":"",';
$outp .='"usuario":"",';
$outp .='"clave":""}';

if ($reg > 0) {
    $outp = "";
    while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
        if ($outp != "") {
            $outp .= ",";
        }
        $outp .= '{"nombre":"' . $rs['nombre'] . '",';
        $outp .= '"ap":"' . $rs['ap'] . '",';
        $outp .= '"am":"' . $rs['am'] . '",';
        $outp .= '"usuario":"' . $rs['correo'] . '",';
        $outp .= '"clave":"' . $rs['clave'] . '",';
        $outp .= '"rol":"' . $rs['rol'] . '"}';
    }
    $outp = '{"registro":['.$outp.']}';
    echo($outp);  // Regresa el JSON al frontend
} else {
    echo $reg;
}
$conn->close();


?>


