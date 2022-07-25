
<?php
/*
Class that handles connection to azure database.
Has set functions for manipulating data
*/ 

//DB Info (Azure) 
$dbAddress = "uod-thinkocean.database.windows.net";
$dbName = "DA1_DB";
$dbUserName = "UODThinkOceanAdmin";
$dbPassword = "taAdmin!";

$LogTableName = "idk";

function CheckPostMessage(): bool
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        return TRUE;
    }
    else {
        return FALSE;
    }

}

class DA1Database{

    //Database Connection
    private $dbConn = null;
    public function __construct()
    {
        global $dbAddress;
        global $dbName;
        global $dbUserName;
        global $dbPassword;
        try {
            $db = new PDO(
            "sqlsrv:server=$dbAddress;Database=$dbName",
            $dbUserName, 
            $dbPassword);
        }
        catch (PDOException $error) {
            echo nl2br("PDO Error: ".$error->getMessage());
        }
        catch (Exception $error){
            echo nl2br("General Connection Error: ".$error->getMessage());
        }
        
        //Set Default_TimeZone
        date_default_timezone_set('UTC');
    }

    public function LogRequest(){
        if (!$this->dbConn)
        {
            return;
        }
        else
        {
            $currentDateTime = date('Y-m-d H:i:s');
            $message = file_get_contents('php://input');
            $insertQuery = "
            INSERT INTO [dbo].[Logs] (TimeSent,MessageType,Message)
            VALUES ('$currentDateTime','Post','$message');;
            ";
        }
        $result =  $this->dbConn->query($insertQuery);
        if (!$result)
        {
            echo nl2br("PDO Error: Query failed.");
        }
    }
    public function AddDevice(){
    }

    public function RemoveDevice(){
    }

    public function AddDevicePing(){
    }
}
