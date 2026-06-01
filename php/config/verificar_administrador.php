<?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // si el usuario no esta logueado, lo manda al login
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: ../../login.php"); 
        exit();
    }
    
    if ($_SESSION['rol'] !== 'administrador') {
        header("Location: ../../index.php?error=no_autorizado");
        exit();
    }
?>