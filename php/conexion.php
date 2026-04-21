<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "sistema_gestion";
    $puerto = 3306;
    // $puerto = 3307; // El 3307 lo usa Yotti. Si de casualidad lo dejo activado comenten la linea 7 y descomenten el 3306

    $conn = new mysqli($host, $user, $pass, $db, $puerto);

    if($conn->connect_errno){
        die("Fallo la conexión: " . $conn->connect_error);
    }
?>