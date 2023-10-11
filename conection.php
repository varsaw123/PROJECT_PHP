<?php
$host = "localhost";
$user = "postgres";
$pass = "manish";
$db = "project_01";
$port = 5432;

$con = pg_connect("host=$host user=$user password=$pass dbname=$db port=$port") or die("Could not connect to server\n");

if (!$con) {
    echo "Error: Unable to open database\n";
} 
?>