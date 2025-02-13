<?php
// Iniciar la sesión
session_start();

// Redirigir a la página principal si ya está logueado
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: ../../index.php");
    exit;
}

require_once "../../php/conexion.php"; // Incluir la conexión a la base de datos

// Inicializar las variables
$correo = $contraseña = "";
$correo_err = $contraseña_err = "";

// Procesar los datos cuando el formulario sea enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar el correo
    if (empty(trim($_POST["correo"]))) {
        $correo_err = "Por favor ingresa tu correo.";
    } else {
        $correo = trim($_POST["correo"]);
    }

    // Validar la contraseña
    if (empty(trim($_POST["contraseña"]))) {
        $contraseña_err = "Por favor ingresa tu contraseña.";
    } else {
        $contraseña = trim($_POST["contraseña"]);
    }

    // Verificar las credenciales si no hay errores de validación
    if (empty($correo_err) && empty($contraseña_err)) {
        $sql = "SELECT documento, correo, contrasena, rol FROM pruebaglamour.usuario WHERE correo = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_correo);
            $param_correo = $correo;

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($documento, $correo_bd, $stored_password, $rol);

                    if ($stmt->fetch()) {
                        if (password_verify($contraseña, $stored_password)) {
                            $_SESSION["loggedin"] = true;
                            $_SESSION["documento"] = $documento;
                            $_SESSION["correo"] = $correo_bd;
                            $_SESSION["rol"] = $rol;

                            header("location: ../../index.php");
                            exit;
                        } else {
                            $contraseña_err = "La contraseña que ingresaste no es válida.";
                        }
                    }
                } else {
                    $correo_err = "No existe cuenta registrada con ese correo.";
                }
            } else {
                echo "Hubo un error al procesar tu solicitud. Por favor, intenta más tarde.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../../css/User/loginYsignUp.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Iniciar Sesión</h1>
            <p>Ingresa tus credenciales para acceder a tu cuenta</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div>
                    <label for="correo">Correo</label>
                    <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($correo); ?>">
                    <span><?php echo $correo_err; ?></span>
                </div>
                <div>
                    <label for="contraseña">Contraseña</label>
                    <input type="password" id="contraseña" name="contraseña">
                    <span><?php echo $contraseña_err; ?></span>
                </div>
                <div>
                    <button type="submit">Iniciar Sesión</button>
                </div>
                <p class="sing-up">¿No tienes una cuenta? <a href="signUp.php">Registrate</a></p>
            </form>
        </div>
        <div class="image-container">
            <img src="../../img/FondoSalon.png" alt="Imagen decorativa">
        </div>
    </div>
</body>
</html>
