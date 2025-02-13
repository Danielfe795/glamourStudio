<?php
// Incluir el archivo de conexión
require_once "../../php/conexion.php";

// Definir variables e inicializarlas con valores vacíos
$documento = $tipoDocumento = $nombre = $apellido = $correo = $contraseña = $confirm_contraseña = "";
$documento_err = $correo_err = $contraseña_err = $confirm_contraseña_err = "";

// Procesar los datos cuando el formulario sea enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar la cédula
    if (empty(trim($_POST["documento"]))) {
        $documento_err = "Por favor ingresa un documento.";
    } else {
        $sql = "SELECT documento FROM Usuario WHERE documento = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $param_documento);
            $param_documento = trim($_POST["documento"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $documento_err = "Este documento ya está registrado.";
                } else {
                    $documento = trim($_POST["documento"]);
                }
            } else {
                echo "Algo salió mal al verificar la cédula.";
            }
        }
        $stmt->close();
    }

    // Validar el tipo de documento
    if (empty($_POST["tipoDocumento"])) {
        $tipoDocumento = "";
    } else {
        $tipoDocumento = $_POST["tipoDocumento"];
    }

    // Validar el correo
    if (empty(trim($_POST["correo"]))) {
        $correo_err = "Por favor ingresa un correo.";
    } else {
        $sql = "SELECT correo FROM Usuario WHERE correo = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_correo);
            $param_correo = trim($_POST["correo"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $correo_err = "Este correo ya está registrado.";
                } else {
                    $correo = trim($_POST["correo"]);
                }
            } else {
                echo "Algo salió mal al verificar el correo.";
            }
        }
        $stmt->close();
    }

    // Validar la contraseña
    if (empty(trim($_POST["contraseña"]))) {
        $contraseña_err = "Por favor ingresa una contraseña.";
    } elseif (strlen(trim($_POST["contraseña"])) < 6) {
        $contraseña_err = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $contraseña = trim($_POST["contraseña"]);
    }

    // Validar la confirmación de la contraseña
    if (empty(trim($_POST["confirm_contraseña"]))) {
        $confirm_contraseña_err = "Por favor confirma tu contraseña.";
    } else {
        $confirm_contraseña = trim($_POST["confirm_contraseña"]);
        if (empty($contraseña_err) && ($contraseña != $confirm_contraseña)) {
            $confirm_contraseña_err = "Las contraseñas no coinciden.";
        }
    }

    // Validar nombre y apellido
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);

    // Comprobar si no hay errores antes de insertar los datos
    if (empty($documento_err) && empty($correo_err) && empty($contraseña_err) && empty($confirm_contraseña_err)) {
        $sql = "INSERT INTO Usuario (documento, tipoDocumento, nombre, apellido, correo, contrasena) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("isssss", $param_documento, $param_tipoDocumento, $param_nombre, $param_apellido, $param_correo, $param_contraseña);
            $param_documento = $documento;
            $param_tipoDocumento = $tipoDocumento;
            $param_nombre = $nombre;
            $param_apellido = $apellido;
            $param_correo = $correo;
            $param_contraseña = password_hash($contraseña, PASSWORD_DEFAULT);
            if ($stmt->execute()) {
                header("location: login.php");
            } else {
                echo "Algo salió mal al registrar el usuario.";
            }
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="../../css/User/loginYsignUp.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Registro de Usuario</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <!-- Documento -->
                <input type="text" name="documento" placeholder="Número de Documento" value="<?php echo $documento; ?>">
                <span><?php echo $documento_err; ?></span>

                <!-- Tipo de Documento -->
                <select name="tipoDocumento" class="tipoDocumento">
                    <option value="" disabled selected>Selecciona el tipo de documento</option>
                    <option value="Cédula de Ciudadanía">Cédula de Ciudadanía</option>
                    <option value="Cédula de Extranjería">Cédula de Extranjería</option>
                    <option value="Pasaporte">Pasaporte</option>
                </select>

                <!-- Nombre -->
                <input type="text" name="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>">

                <!-- Apellido -->
                <input type="text" name="apellido" placeholder="Apellido" value="<?php echo $apellido; ?>">

                <!-- Correo -->
                <input type="email" name="correo" placeholder="Correo Electrónico" value="<?php echo $correo; ?>">
                <span><?php echo $correo_err; ?></span>

                <!-- Contraseña -->
                <div class="password-container">
                    <input type="password" name="contraseña" placeholder="Contraseña">
                    <span><?php echo $contraseña_err; ?></span>
                </div>

                <!-- Confirmar Contraseña -->
                <div class="password-container">
                    <input type="password" name="confirm_contraseña" placeholder="Confirma tu Contraseña">
                    <span><?php echo $confirm_contraseña_err; ?></span>
                </div>

                <!-- Botón -->
                <button type="submit">Registrar</button>
            </form>
            <p class="sing-up">¿Ya tienes cuenta? <a href="login.php">Inicia Sesión</a></p>
        </div>
        <div class="image-container">
            <img src="../../img/FondoSalon.png" alt="Fondo">
        </div>
    </div>
</body>
</html>
