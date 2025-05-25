<?php

     header("Content-Type:text/html; charset=utf-8");

     $serverName="127.0.0.1\\MSSQLSERVER01";

     $connectionInfo=array("Database"=>"final_p", "UID"=>"sa", "PWD"=>"ANIDANGEl123","CharacterSet" => "UTF-8");


     $conn=sqlsrv_connect($serverName,$connectionInfo);
?>