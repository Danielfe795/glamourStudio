<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está logueado
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../index.php");
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

// Obtener el correo del usuario logueado
$correo = $_SESSION['correo'];

// Consultar los detalles del usuario desde la base de datos
$sql = "SELECT * FROM usuario WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

// Si el usuario existe, obtenemos sus datos
$user = $result->fetch_assoc();

if (!$user) {
    header("location: signUp.php");
}

$nombre = $user['nombre'] ;
$apellido = $user['apellido'];
$documento = $user['documento'];
$tipoDocumento = $user['tipoDocumento'];
$telefono = $user['telefono'];
$direccion = $user['direccion'];
$barrio = $user['barrio'];
$ciudad = $user['ciudad'];
$codigoPostal = $user['codigoPostal'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario</title>
    <link rel="stylesheet" href="../../css/User/MyAccount.css">
    <link rel="stylesheet" href="../../css/User/home.css">
</head>
<body>
    <header></header>

    <div class="container">
        <div class="sidebar">
            <button class="menu-btn" onclick="showContent('escritorio')">Escritorio</button>
            <button class="menu-btn" onclick="showContent('pedidos')">Pedidos</button>
            <button class="menu-btn" onclick="showContent('Servicios')">Servicios</button>
            <button class="menu-btn" onclick="showContent('cuenta')">Detalles de la cuenta</button>
            <button class="menu-btn" onclick="showContent('salir')">Salir</button>
        </div>

        <div class="content">
            <div id="escritorio" class="content-section">
                <h2>Bienvenido, <?php echo $nombre; ?> (cliente)</h2>
                <p>Desde el panel de su cuenta, puede ver sus pedidos recientes, administrar sus direcciones de envío y facturación, y editar su contraseña y los detalles de su cuenta.</p>
            </div>

            <div id="pedidos" class="content-section" style="display:none;">
                <h2>Pedidos</h2>
                <p>Aquí puedes ver y gestionar tus pedidos recientes.</p>
            </div>

            <div id="Servicios" class="content-section" style="display:none;">
                <h2>Servicios</h2>
                <p>Accede a tus servicios activos, finalizados, etc.</p>
            </div>

            <div id="cuenta" class="content-section" style="display:none;">
                <h2>Detalles del cliente</h2>
                <form id="detalles-cuenta" method="POST" action="../../php/updateDetails.php">
                    <label for="nombre">Nombre <span style="color:red;">*</span></label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required><br>

                    <label for="apellido">Apellido <span style="color:red;">*</span></label>
                    <input type="text" id="apellido" name="apellido" value="<?php echo $apellido; ?>" required><br>

                    <label for="documento">Documento <span style="color:red;">*</span></label>
                    <input type="number" id="documento" name="documento" value="<?php echo $documento; ?>" required><br>

                    <label for="tipoDocumento">Tipo de Documento <span style="color:red;">*</span></label>
                    <select id="tipoDocumento" name="tipoDocumento" required>
                        <option value="C.C" <?php echo $tipoDocumento == 'C.C' ? 'selected' : ''; ?>>C.C</option>
                        <option value="T.I" <?php echo $tipoDocumento == 'T.I' ? 'selected' : ''; ?>>T.I</option>
                        <option value="Cédula de extranjería" <?php echo $tipoDocumento == 'Cédula de extranjería' ? 'selected' : ''; ?>>Cédula de extranjería</option>
                        <option value="Pasaporte" <?php echo $tipoDocumento == 'Pasaporte' ? 'selected' : ''; ?>>Pasaporte</option>
                    </select><br><br>

                    <label for="telefono">Teléfono <span style="color:red;">*</span></label>
                    <input type="tel" id="telefono" name="telefono" value="<?php echo $telefono; ?>" required><br>

                    <label for="correo">Correo <span style="color:red;">*</span></label>
                    <input type="email" id="correo" name="correo" value="<?php echo $correo; ?>" required readonly><br>

                    <label for="direccion">Dirección <span style="color:red;">*</span></label>
                    <input type="text" id="direccion" name="direccion" value="<?php echo $direccion; ?>" required><br>

                    <label for="barrio">Barrio <span style="color:red;">*</span></label>
                    <input type="text" id="barrio" name="barrio" value="<?php echo $barrio; ?>" required><br>

                    <label for="ciudad">Ciudad <span style="color:red;">*</span></label>
                    <input type="text" id="ciudad" name="ciudad" value="<?php echo $ciudad; ?>" required><br>

                    <label for="codigoPostal">Código Postal <span style="color:red;">*</span></label>
                    <input type="number" id="codigoPostal" name="codigoPostal" value="<?php echo $codigoPostal; ?>" required><br>

                    <button type="submit">Guardar cambios</button>
                </form>
            </div>

            <div id="salir" class="content-section" style="display:none;">
                <h2>Gracias por visitarnos</h2>
                <p>Has cerrado sesión con éxito.</p>
            </div>
        </div>
    </div>

    <footer></footer>

    <script src="../../js/User/Main.js"></script>
    <script src="../../js/User/MyAccount.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
