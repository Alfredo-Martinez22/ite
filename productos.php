<?php
header('Content-Type: application/json');

// Configuración de la base de datos
$servername = "localhost";
$username = "root"; // Cambia esto por tu usuario de MySQL
$password = ""; // Cambia esto por tu contraseña de MySQL
$dbname = "tienda";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL
$sql = "SELECT nombre, precio, imagen, whatsapp_link FROM productos";
$result = $conn->query($sql);

$productos = array();

if ($result->num_rows > 0) {
    // Obtener datos de cada fila
    while($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}

$conn->close();

// Devolver datos en formato JSON
echo json_encode($productos);
?>
