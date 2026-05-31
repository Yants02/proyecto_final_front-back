<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Por seguridad verifica que haya un usuario logueado
if (!isset($_SESSION['usuario_id'])) {
    die("Acceso denegado. Debes iniciar sesion.");
}


include '../../config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio_venta'];
    // $stock  = $_POST['stock'];
    $usuario_id = $_SESSION['usuario_id'];

    try {
        $sql = "INSERT INTO productos (nombre, precio_venta, usuario_id) 
                VALUES (:nombre, :precio, :usuario_id)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':precio' => $precio,
            // ':stock'  => $stock,
            ':usuario_id' => $usuario_id
        ]);
        header("Location: productos.php?mensaje=registrado");
        exit();

    } catch (PDOException $e) {
        echo "Error al guardar el producto: " . $e->getMessage();
    }
}
?>