<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Control de seguridad: debe estar logueado
if (!isset($_SESSION['usuario_id'])) {
    die("Acceso denegado.");
}

    include("../../config/conexion.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $usuario_id = $_SESSION['usuario_id'];

        try {
            $sql = "DELETE FROM productos WHERE id = :id AND usuario_id = :usuario_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':usuario_id' => $usuario_id
            ]);

            if ($stmt->rowCount() > 0) {
                header("Location: productos.php?mensaje=eliminado");
            } else {
                header("Location: productos.php?mensaje=error_id");
            }
            exit();

        } catch (PDOException $e) {
            // Si hay un error de base de datos cae aca
            echo "Error crítico: " . $e->getMessage();
        }
    }
?>