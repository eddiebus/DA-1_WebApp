
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
 
class DA1Database{

    //Database Connection
    private $dbConn = null;
    public function __construct()
    {
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
        if (!$db)
        {
            return;
        }
        else
        {
            $insertQuery = "
            INSERT INTO [dbo].[Logs] (TimeSent,MessageType,Message)
            VALUES ('$currentDateTime','Post','$message');;
            ";
        }

        $result =  $db->query($insertQuery);
        if (!$result)
        {
            echo nl2br("PDO Error: Query failed.");
        }
    }

    public function AddDevice()
    {

    }

    public function RemoveDevice(){
        
    }

    public function AddDevicePing(){

    }
}

?>