<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pruebaglamour";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos enviados
$correo = $_SESSION['correo'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];

// Actualizar los detalles del usuario en la base de datos
$sql = "UPDATE usuario SET nombre = ?, apellido = ?, telefono = ?, direccion = ? WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $nombre, $apellido, $telefono, $direccion, $correo);

if ($stmt->execute()) {
    // Si la actualización fue exitosa, redirigir al usuario al panel
    header("location: ./pages/User/MyAccount.php");
} else {
    echo "Error al actualizar los detalles: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
