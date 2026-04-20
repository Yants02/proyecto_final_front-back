<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "sistema_gestion";
    $puerto = 3306;

    $conn = new mysqli($host, $user, $pass, $db, $puerto);

    if($conn->connect_errno){
        die("Fallo la conexión: " . $conn->connect_error);
    }
?>