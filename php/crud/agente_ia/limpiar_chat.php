<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// si el usuario no esta logueado, lo manda al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../../login.php");
    exit();
}

// borra la conversacion actual
unset($_SESSION['chat_ia']);

header("Location: agente_ia.php");
exit();
?>
