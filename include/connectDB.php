<?php
$host = 'localhost';
$user = 'root';
$password = '';

$con = mysql_connect($host, $user, $password);
if (!$con) 
{
    die('Could not connect: ' . mysql_error());
}

mysql_select_db("coulib", $con);

function db_uery($sql)
{
    $rs = mysql_query($sql);
    if(!$rs)
    {
        $msg = "Could not fetch data.";
    }
 else
{
     return $rs;
}
    
    
}

?>
