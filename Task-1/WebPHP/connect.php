<?php

$sever = 'localhost';
$user = 'root';
$pass = '';
$database = 'webtest';

$connect = new mysqli($sever, $user, $pass, $database);

if ($connect) {
    $connect->set_charset("utf8");
}

?>