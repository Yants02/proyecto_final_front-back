<?php
$host = "localhost";
$db   = "sistema_gestion";
$user = "root";
$pass = "";
$port = 3306; // O 3307 según lo que necesite Yotti

try {
    $dsn = "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";
    
    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Activa el try-catch para errores SQL
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve los datos en arrays limpios
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Seguridad contra inyección SQL
    ];

    // Creamos la conexión
    $conn = new PDO($dsn, $user, $pass, $opciones);

} catch (PDOException $e) {
    // Si algo falla, el programa salta directo aca
    die("Error crítico de conexión: " . $e->getMessage());
}
?>