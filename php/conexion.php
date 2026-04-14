<?php
    $host = "localhost";
    $pass = "";
    $user = "root";
    $db ="ejemplo";
    $puerto = 3306;

    $conn = new mysqli($host, $user, $pass, $db, $puerto);

    if($conn ->connect_errno){
        echo "fallo la conexion"
    }

?>