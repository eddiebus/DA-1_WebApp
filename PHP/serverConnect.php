<?php
//My Server Web Address
$serverName = "da1devicedb.database.windows.net";
$connectionOptions = array(
    //Database name
    "Database" => "DA1_Data",
    //User Name
    "Uid" => "da1Admin",
    "PWD" => "Da1device!"
);

$conn = sqlsrv_connect($serverName,$connectionOptions);
if ($conn == false)
{
    echo "Server Connection Success!";
}
else
{
    echo "Server Connection Error";
}

?>
