<?php
// Conectar a la base de datos
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

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $idProveedor = $_POST['idProveedor'];
    $nombre = $_POST['nombre'];
    $producto = $_POST['producto'];
    $precioUnidad = $_POST['precioUnidad'];
    $precioTotal = $_POST['precioTotal'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    // Insertar los datos en la base de datos (tabla proveedores)
    $sql = "INSERT INTO proveedor (idProveedor, nombre, producto, precioUnidad, precioTotal, direccion, telefono) 
            VALUES ('$idProveedor', '$nombre', '$producto', '$precioUnidad', '$precioTotal', '$direccion', '$telefono')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Nuevo proveedor registrado correctamente.</p>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Proveedores</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/Admin/proveedores.css">
    <link rel="stylesheet" href="../../css/Admin/Panel.css">

    <style>
        /* Estilos para asegurar la distribución del espacio en toda la pantalla */
        .providers-table th, .providers-table td {
            padding: 15px 20px;
            text-align: left;
        }

        .providers-table th {
            width: auto;
        }

        .providers-table th:nth-child(1), .providers-table td:nth-child(1) {
            width: 15%;
        }

        .providers-table th:nth-child(2), .providers-table td:nth-child(2) {
            width: 40%;
        }

        .providers-table th:nth-child(3), .providers-table td:nth-child(3) {
            width: 25%;
        }

        .providers-table th:nth-child(4), .providers-table td:nth-child(4) {
            width: 20%;
        }

        /* Estilos adicionales para el popup */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .popup-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
        }

        .popup-content h2 {
            margin-bottom: 20px;
        }

        .popup-content form label {
            display: block;
            margin-bottom: 10px;
        }

        .popup-content form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .popup-content form button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .popup-content form #close-popup {
            background-color: #e36eb8;
        }

        .products-list {
            list-style: none;
            padding: 0;
            margin-bottom: 20px;
        }

        .products-list li {
            background-color: #f4f4f4;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
        }

        .products-list li button {
            background-color: #da63b0;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="admin-panel">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <img src="../../img/logo.png" alt="Logo">
                <h2>Menú</h2>
            </div>
            <ul class="menu">
                <li><a href="/pages/Admin/proveedores.php">Proveedores</a></li>
                <li><a href="/pages/Admin/user.html">Usuarios</a></li>
                <li><a href="/pages/Admin/products.html">Productos</a></li>
                <li><a href="/pages/Admin/services.html">Servicios</a></li>
                <li><a href="/pages/User/login.html">Salir</a></li>
            </ul>
            <div class="user-info">
                <img src="../../img/icono-header-usuario.png" alt="User Profile">
                <p>Nancy Restrepo</p> 
                <small>nancyrestrepo@hotm...</small>
                <a href="#" class="edit-profile">✏️</a> 
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Proveedores</h1>
                <div class="search-bar">
                    <input type="text" placeholder="Buscar...">
                    <button id="new-provider-btn">+ Nuevo Registro</button>
                </div>
            </header>

            <!-- Providers Table -->
            <div class="providers-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Proveedor</th>
                            <th>Nombre de la Empresa</th>
                            <th>Fecha de Registro</th>
                            <th>Costos</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="id-copy">98765</span></td>
                            <td><img src="../../img/icono-header-usuario.png" alt="Provider Icon"> Proveedor ABC</td>
                            <td>MARCEL FRANCE</td>
                            <td>05/09/2024</td>
                            <td>$100.000</td>
                            <td>
                                <button class="edit">✏️</button>
                                <button class="delete">🗑️</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Popup for new provider registration -->
            <div class="popup" id="popup">
                <div class="popup-content">
                    <h2>Registrar Nuevo Proveedor</h2>
                    <form action="proveedores.php" method="POST">
                        <label for="nit">Nit de la Empresa:</label>
                        <input type="text" id="nit" name="nit" required>

                        <label for="empresa">Nombre de la Empresa:</label>
                        <input type="text" id="empresa" name="empresa" required>

                        <label for="producto">Producto Suministrado:</label>
                        <input type="text" id="producto" name="producto" required>

                        <label for="telefono">Teléfono del Proveedor:</label>
                        <input type="tel" id="telefono" name="telefono" required>

                        <label for="costos_unitarios">Costo Unitario:</label>
                        <input type="text" id="costos_unitarios" name="costos_unitarios" required>

                        <label for="costos_total">Costo Total:</label>
                        <input type="text" id="costos_total" name="costos_total" required>

                        <label for="direccion">Dirección del Proveedor:</label>
                        <input type="text" id="direccion" name="direccion" required>

                        <button type="submit">Registrar</button>
                        <button type="button" id="close-popup">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mostrar el popup al dar clic en 'Nuevo Registro'
        const popup = document.getElementById('popup');
        const newProviderBtn = document.getElementById('new-provider-btn');
        const closePopupBtn = document.getElementById('close-popup');

        newProviderBtn.addEventListener('click', () => {
            popup.style.display = 'flex';
        });

        closePopupBtn.addEventListener('click', () => {
            popup.style.display = 'none';
        });
    </script>
</body>
</html>
