<?php
include 'dbInfo.php';

function CheckPostMessage()
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
    echo "Hello Post";

    $connectionOptions = array(
        //Database name
        "Database" => $dbName,
        //User Name
        "Uid" => $dbUserName,
        "PWD" => $dbPassword
    );

    $conn = sqlsrv_connect($dbAddress,$connectionOptions);
    if (!$conn)
    {
        echo "Server Connection Success!";
        $query = "
INSERT INTO [dbo].[Logs] (TimeSent,MessageType,Message)
VALUES (%s,'POST',%s)
";

        date_default_timezone_set('UTC');
        $currentTime = date('Y-m-d H:i:s');
        $messageJson = json_encode($_POST);

        $query = sprintf($query,$currentTime,$messageJson);

        $queryResult = sqlsrv_query($conn,$query);
        if (!$queryResult)
        {
            echo "SQL Qeury Failed!!!";
            echo(sqlsrv_errors());
        }
        else{
            echo http_response_code(200);
        }
    }
    else
    {
        echo "Server Connection Error";
    }

}
?>