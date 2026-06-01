<?php
    include __DIR__ . '/../../config/verificar_administrador.php';
    include '../../config/conexion.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
       
        try {
            
            $sql = "DELETE FROM usuarios WHERE id = :id";

            $stmt = $conn->prepare($sql);

            $stmt->execute(['id' => $id]);

            if ($stmt->rowCount() > 0) {
                header("Location: usuarios.php?mensaje=eliminado");
            } else {
                header("Location: usuarios.php?mensaje=error_id");
            }
            exit();
            
        } catch (PDOException $e) {
            echo "Error al eliminar el usuario: " . $e -> getMessage();
        }
    }

?>