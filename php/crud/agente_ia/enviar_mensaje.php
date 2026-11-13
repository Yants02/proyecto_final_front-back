<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// si el usuario no esta logueado, lo manda al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: agente_ia.php");
    exit();
}

date_default_timezone_set('America/Argentina/Buenos_Aires');
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/respuesta_ia.php';

// limpiamos el mensaje y lo limitamos a 1000 caracteres
$mensaje = trim($_POST['mensaje'] ?? '');
$mensaje = mb_substr($mensaje, 0, 1000);

if ($mensaje === '') {
    header("Location: agente_ia.php");
    exit();
}

// Gemini puede tardar unos segundos: damos mas tiempo al script
set_time_limit(90);

$usuario_id = (int)$_SESSION['usuario_id'];
$rol        = (string)($_SESSION['rol'] ?? 'ventas');
$nombre     = (string)($_SESSION['nombre'] ?? 'Usuario');
$historial  = $_SESSION['chat_ia'] ?? [];

// Soltamos la sesion mientras esperamos a Gemini, asi no bloquea otras pestañas
session_write_close();

$respuesta = generar_respuesta_ia($mensaje, $historial, $conn, $usuario_id, $rol, $nombre);

// Volvemos a abrir la sesion para guardar la conversacion
session_start();

$_SESSION['chat_ia'][] = [
    'rol'   => 'usuario',
    'texto' => $mensaje,
    'hora'  => date('H:i')
];

$_SESSION['chat_ia'][] = [
    'rol'   => 'ia',
    'texto' => $respuesta['texto'],
    'error' => $respuesta['error'],
    'hora'  => date('H:i')
];

// guardamos solo los ultimos 50 mensajes para no llenar la sesion
$_SESSION['chat_ia'] = array_slice($_SESSION['chat_ia'], -50);

header("Location: agente_ia.php");
exit();
?>
