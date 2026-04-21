<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio_venta'];
    $stock  = $_POST['stock'];

    try {
        $sql = "INSERT INTO productos (nombre, precio_venta, stock) 
                VALUES (:nombre, :precio, :stock)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre,
            'precio' => $precio,
            'stock'  => $stock
        ]);
        header("Location: productos.php?mensaje=registrado");
        exit();

    } catch (PDOException $e) {
        echo "Error al guardar el producto: " . $e->getMessage();
    }
}
?>