<?php
require_once('DBConnectService.php');

$conn = new DA1Database();
$conn->LogRequest("Post");

echo http_response_code(200);

