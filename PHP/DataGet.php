<?php
require_once('DBConnectService.php');

$jsonpRequest = null;
if (isset($_GET["callback"])){
    $jsonpRequest = true;
}
else
{
    $jsonpRequest = false;
}

$conn = new DA1Database();
$result = $conn->HandleGetMSG();

if ($jsonpRequest)
{
    echo $_GET["callback"].'('.json_encode($result).')';
}
else{
    echo json_encode($result);
}