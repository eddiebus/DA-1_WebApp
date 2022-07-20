<?php
include 'dbInfo.php';

function CheckPostMessage()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return true;
    }
    else
    {
        return false;
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
        $query = "INSERT INTO";

        date_default_timezone_set('UTC');
        $currentTime = date('Y-m-d H:i:s');
    }
    else
    {
        echo "Server Connection Error";
    }

}
?>