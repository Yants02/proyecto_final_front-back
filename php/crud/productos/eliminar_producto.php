<?php 

    include("../../config/conexion.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];

        try {
            $sql = "DELETE FROM productos WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['id' => $id]);

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