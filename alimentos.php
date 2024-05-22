<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tienda";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los productos
$sql = "SELECT name, image, price, whatsapp_link FROM alimentos";
$result = $conn->query($sql);

$products = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    echo "0 resultados";
}
$conn->close();

// Devolver los productos en formato JSON
header('Content-Type: application/json');
echo json_encode($products);
?>
