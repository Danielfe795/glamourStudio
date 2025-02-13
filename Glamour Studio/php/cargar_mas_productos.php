<?php

include 'conexion.php';


$offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;


$sql = "SELECT idProducto, nombre, descripcion, precio, imagen FROM producto LIMIT 4 OFFSET $offset";
$result = $conn->query($sql);


$productos = [];


while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode($productos);


$conn->close();
?>
