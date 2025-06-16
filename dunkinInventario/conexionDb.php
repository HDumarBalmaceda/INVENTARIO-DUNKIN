<?php
$server = "localhost"; // Cambiar si es necesario
$usuario = "root";        // Usuario de la base de datos
$contraseña = "";            // Contraseña de la base de datos
$bd = "DunkinDb";      // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($server, $usuario, $contraseña, $bd);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
