<?php
include 'dbInfo.php';
require_once('DBCOnnectService.php')

function CheckPostMessage(): bool
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}

if (!CheckPostMessage())
{
    echo "HTTP Request is not type 'POST'";
}
else {
    $db = null;
    try {
        $db = new PDO("sqlsrv:server=$dbAddress;Database=$dbName", $dbUserName, $dbPassword);

        echo "PDO Connection Success"."<br>";

        date_default_timezone_set('UTC');
        $currentDateTime = date("Y-m-d h:i:s");
        $message = file_get_contents('php://input');

        $insertQuery = "
        INSERT INTO [dbo].[Logs] (TimeSent,MessageType,Message)
        VALUES ('$currentDateTime','Post','$message');;
        ";

        $result =  $db->query($insertQuery);

        if (!$result)
        {
            echo "Statement Error"."<br>";
            echo "$insertQuery";
        }
        else{
            echo "ok";
            echo implode($_POST);
        }
    }
    catch (PDOException $error) {
        echo "PDO Error: ".$error->getMessage()."<br>";
    }
    catch (Exception $error){
        echo "General Connection Error: ".$error->getMessage()."<br>";
    }

}
?>