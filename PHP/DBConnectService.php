
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
            $this->dbConn = new PDO(
            "sqlsrv:server=$dbAddress;Database=$dbName",
            $dbUserName, 
            $dbPassword);

            $this->CheckTables();
        }
        catch (PDOException $error) {
            echo nl2br("PDO Error: ".$error->getMessage());
        }
        catch (Exception $error){
            echo nl2br("General Connection Error: ".$error->getMessage());
        }
        
        //Set Default_TimeZone
        date_default_timezone_set('Europe/London');
    }
    public function __destruct()
    {
        $this->dbConn = null;
    }

    //Check setup of tables. If tables don't exist, create them
    private function CheckTables()
    {
        if (!$this->dbConn)
        {
            return;
        }

        //Logs Table
        $result = $this->dbConn->query(
            "SELECT 1 FROM [dbo].[Logs]"
        );
        //Checking if table exist = fail. create table
        if (!$result) {
            $this->dbConn->query(
                "CREATE TABLE [dbo].[Logs](
    [TimeSent] DATETIME NOT NULL PRIMARY KEY, 
    [MessageType] VARCHAR(10) NOT NULL, 
    [Message] TEXT NULL);"
            );
        }

        //Devices Table
        $result = $this->dbConn->query(
            "SELECT 1 FROM [dbo].[Devices]"
        );
        //Checking if table exist = fail. create table
        if (!$result) {
            $this->dbConn->query(
                "CREATE TABLE [dbo].[Devices](
	[IMEI] VARCHAR(25) NOT NULL PRIMARY KEY, 
    [Serial_Number] INT NOT NULL, 
    [Product_ID] INT NOT NULL);"
            );
        }


        //Device Location Ping Table
        $result = $this->dbConn->query(
            "SELECT 1 FROM [dbo].[LocatePing]"
        );
        //Checking if table exist = fail. create table

        if (!$result) {
            $this->dbConn->query(
                "CREATE TABLE [dbo].[LocatePing] (
    [TimeSent]  DATETIME     NOT NULL,
    [Device]    VARCHAR (25) NOT NULL,
	[GPSDate]  DATETIME NOT NULL,
    [Latitude]  FLOAT          NOT NULL,
    [Longitude] FLOAT          NOT NULL,
    [Altitude]  INT          NOT NULL,
    [Speed]     INT          NOT NULL,
    [SpeedAcc]  INT          NOT NULL,
    PRIMARY KEY CLUSTERED ([TimeSent] ASC),
    FOREIGN KEY ([Device]) REFERENCES [dbo].[Devices] ([IMEI]));"
            );
        }

    }

    //Check the capacity of tables. Remove entries if needed
    private function CheckCapacity()
    {

    }

    public function LogRequest(string $msgType){
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
            VALUES ('$currentDateTime','$msgType','$message');
            ";
        }
        $result =  $this->dbConn->query($insertQuery);
        if (!$result)
        {
            echo nl2br("PDO Error: Query failed.");
        }
    }

    public function HandleDeviceMSG()
    {
        $rawMsgBody = file_get_contents('php://input');
        $msgBody = json_decode(file_get_contents('php://input'));
        if ($msgBody == null)
        {
            return;
        }

        echo "Recieved Message: ".$rawMsgBody."<br>";
        $IMEI = $msgBody->{'IMEI'};
        $SerialNO = $msgBody->{'SerNo'};
        $ProductID = $msgBody->{'ProdId'};
        //Data from the device ping
        $Data = $msgBody->{'Records'}[0]->{'Fields'}[0];
        $pingDate = $msgBody->{'Records'}[0]->{'DateUTC'};

        $outputString = "";
        echo "IMEI:".$IMEI."<br>".
            "Serial Number:".$SerialNO."<br>".
            "Product ID:".$ProductID."<br>";


        //Check if device is already registered
        $selectDevice = "SELECT [IMEI] FROM [dbo].[Devices]
WHERE [IMEI] = '$IMEI';";
        $selectResult = $this->dbConn->query($selectDevice);

        //Device not listed. Add it.
        if (count($selectResult->fetchAll()) == 0)
        {
            $insertQueryDevice = "INSERT INTO [dbo].[Devices] (IMEI,Serial_Number,Product_ID)
VALUES ('$IMEI','$SerialNO','$ProductID');";
            $this->dbConn->query($insertQueryDevice);
        }


        $currentTime = date('Y-m-d H:i:s');
        $deviceGPSPingDate = $Data->{"GpsUTC"};
        $deviceLat = $Data->{"Lat"};
        $deviceLong = $Data->{"Long"};
        $deviceAlt = $Data->{'Alt'};
        $deviceSpeed = $Data->{'Spd'};
        $deviceSpdAcc = $Data->{'SpdAcc'};

        //Add this location ping to the database
        $insertQueryPing = "INSERT INTO [dbo].[LocatePing](
TimeSent,
Device,
GPSDate,
Latitude,
Longitude,
Altitude,
Speed,
SpeedAcc
)
VALUES (
'$currentTime',
'$IMEI',
'$deviceGPSPingDate',
'$deviceLat',
'$deviceLong',
'$deviceAlt',
'$deviceSpeed',
'$deviceSpdAcc'   
);";

        $this->dbConn->query($insertQueryPing);

        return true;
    }
}
