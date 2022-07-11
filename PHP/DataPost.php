<?php
$databaseDir = "Database/da-1_Data.db";



//Prepare Log SQL Statement
$db = new PDO($databaseDir);
$postString = implode("|", $_POST);
$query = $db->prepare('
INSERT INTO RequestLogs (Type,Message)
VALUES("POST",statement)
');
$query->bindParam('statement',$postString);
$query->execute();
//Echo Response code
/*
 * This is needed to communicate with Digital Matter's servers
 * If no response is given, devices will think data hasn't been handled
 */
echo http_response_code(200);

?>