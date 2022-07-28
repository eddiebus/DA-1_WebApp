<?php
require_once('DBConnectService.php');

$conn = new DA1Database();

if ($conn->HandleDeviceMSG())
{
    echo http_response_code(200);
}
else
{
    echo http_response_code(500);
}

