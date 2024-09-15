<?php
    // Fichero: conexionbd.php
    // Conexión a BBDD MySql
    $bd = new mysqli($sql_host, $sql_usuario, $sql_pass, $sql_db);
    if ($bd->connect_error) { die('Error de Conexión ('.$mysqli->connect_errno.') '.$mysqli->connect_error); }
    mysqli_set_charset($bd,"utf8");
?>