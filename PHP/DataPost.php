<?php
function CheckPostMessage()
{
    if (isset($_POST))
    {
        return true;
    }
    else
    {
        return false;
    }
}

if (CheckPostMessage() == false)
{
    echo "HTTP Request is not type 'POST'";
}

echo "Hello Post";
?>