<?php
session_start();
include '../../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_prod = $_POST['id_producto'];
    
    $stmt = $conn->prepare("SELECT nombre FROM productos WHERE id = ?");
    $stmt->execute([$id_prod]);
    $nombre = $stmt->fetchColumn();

    $_SESSION['compra_temp']['productos'][] = [
        'id_prod'  => $id_prod,
        'nombre_producto' => $nombre,
        'cantidad' => $_POST['cantidad'],
        'precio_costo'   => $_POST['precio_costo']
    ];

    header("Location: ver_detalle_compra.php");
}
?>