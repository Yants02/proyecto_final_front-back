<?php 
    include("../conexion.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = mysqli_real_escape_string($conn, $_POST['id']);

        $sql = "DELETE FROM productos WHERE id = '$id';";

        if (mysqli_query($conn, $sql)) {
            if (mysqli_affected_rows($conn) > 0) {
                header("Location: productos.php?mensaje=eliminado");
            }
            else {
                header("Location: productos.php?mensaje=error_id");
            }
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
?>