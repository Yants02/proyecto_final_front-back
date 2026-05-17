<?php
    include '../config/conexion.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Si el usuario está logueado, se invalida su token en la DB
    if (isset($_SESSION['usuario_id'])) {
        try {
            // conversion de token a null
            $sql = "UPDATE usuarios SET sesion_token = NULL WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id' => $_SESSION['usuario_id']]);
        } catch (PDOException $e) {
           
        }
    }

    // Se borran todas las variables de sesión del servidor
    $_SESSION = array();
    session_destroy();

    // Se borra la cookie del navegador 
    if (isset($_COOKIE['rm_token'])) {
        setcookie('rm_token', '', time() - 3600, "/");
    }

    header("Location: ../../login.php");
    exit();
?>