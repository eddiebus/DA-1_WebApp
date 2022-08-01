<?php
require_once('DBConnectService.php');


$conn = new DA1Database();
echo $conn->HandleGetMSG();