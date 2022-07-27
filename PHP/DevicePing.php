<?php
require_once('DBConnectService.php');

$conn = new DA1Database();
$conn->HandleDeviceMSG();

echo http_response_code(200);