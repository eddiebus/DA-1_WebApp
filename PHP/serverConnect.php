<?php
//Get Database Info + Login

include "dbInfo.php";
//Login to database
$db = null;
try {
    $db = new PDO("sqlsrv:server=$dbAddress;Database=$dbName", $dbUserName, $dbPassword);

    if ($db)
    {
        echo "PDO Connection Success"."<br>";
    }
}
catch (PDOException $error) {
    echo "PDO Error: ".$error->getMessage()."<br>";
}
catch (Exception $error){
    echo "General Connection Error: ".$error->getMessage()."<br>";
}





?>
