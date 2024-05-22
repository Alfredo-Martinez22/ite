<?php
$servername = "localhost";
$username = "root"; // Cambia esto si tienes otro nombre de usuario
$password = ""; // Cambia esto si tienes una contraseña establecida
$dbname = "tienda";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener los datos del formulario
$name = $_POST['name'];
$price = $_POST['price'];
$whatsapp_link = $_POST['whatsapp_link'];
$type = $_POST['type'];

// Validar que se proporcionen todos los campos requeridos
if (empty($name) || empty($price) || empty($whatsapp_link) || empty($type)) {
    die("Error: Todos los campos son requeridos.");
}

// Validar el tipo de producto
if ($type != "producto" && $type != "alimento") {
    die("Error: Tipo de producto no válido.");
}

// Verificar que se haya cargado una imagen
if (!isset($_FILES['image']) || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
    die("Error: No se ha proporcionado ninguna imagen o ha ocurrido un error al cargarla.");
}

// Determinar la carpeta de destino según el tipo de producto
if ($type == "producto") {
    $target_dir = "productos/";
} else {
    $target_dir = "alimentos/";
}

// Obtener el nombre de la imagen y generar una ruta de destino única
$image = $target_dir . basename($_FILES['image']['name']);

// Mover la imagen subida a la carpeta de destino
if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
    die("Error: Hubo un problema al subir la imagen.");
}

// Preparar la consulta SQL utilizando declaraciones preparadas para evitar inyección SQL
if ($type == "producto") {
    $sql = "INSERT INTO productos (nombre, precio, imagen, whatsapp_link) VALUES (?, ?, ?, ?)";
} else {
    $sql = "INSERT INTO alimentos (nombre, precio, imagen, whatsapp_link) VALUES (?, ?, ?, ?)";
}

// Preparar la declaración
$stmt = $conn->prepare($sql);
// Vincular los parámetros
$stmt->bind_param("ssss", $name, $price, $image, $whatsapp_link);

// Ejecutar la declaración
if ($stmt->execute()) {
    // Redireccionar a la página principal después de un breve retraso
    echo "<script>
            setTimeout(function() {
                document.location.href = 'index.html';
            }, 3000);
          </script>";
    // Mostrar un mensaje de éxito con animación de desvanecimiento
    echo "<div style='background-color: #4CAF50; color: white; padding: 10px; margin-top: 20px; text-align: center;'>
            Nuevo registro creado exitosamente. Redirigiendo a la página principal en 3 segundos...
          </div>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
