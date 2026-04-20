<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $precio = $_POST['precio_venta'];
    $stock = $_POST['stock'];

    $sql = "INSERT INTO productos (nombre, precio_venta, stock) 
            VALUES ('$nombre', '$precio', '$stock')";

    if (mysqli_query($conn, $sql)) {
        header("Location: productos.php?mensaje=registrado");
    } else {
        echo "Error al guardar: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>