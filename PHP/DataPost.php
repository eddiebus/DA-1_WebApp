<?php
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
}
?>