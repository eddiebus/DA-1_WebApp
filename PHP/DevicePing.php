<?php
require_once('DBConnectService.php');

$conn = new DA1Database();
$conn->HandleDeviceMSG();

