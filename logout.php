<?php
require 'conection.php';

session_start();

session_unset();

session_destroy();

header("Location: MY_PROJECT.html");
?>
