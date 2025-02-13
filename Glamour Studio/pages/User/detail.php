<?php
session_start();
include '../../php/conexion.php';

// Obtener el ID del producto de la URL
$idProducto = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($idProducto > 0) {
    // Consulta para obtener los detalles del producto específico
    $consulta = "SELECT nombre, descripcion, precio, imagen, stock FROM producto WHERE idProducto = ?";
    $stmt = $conn->prepare($consulta);
    $stmt->bind_param("i", $idProducto);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc(); // Recuperar el producto
    } else {
        echo "<p>Producto no encontrado.</p>";
        exit;
    }

    // Consulta para obtener productos sugeridos
    $sugerenciasConsulta = "SELECT idProducto, nombre, precio, imagen FROM producto WHERE idProducto != ? LIMIT 4";
    $stmtSugerencias = $conn->prepare($sugerenciasConsulta);
    $stmtSugerencias->bind_param("i", $idProducto);
    $stmtSugerencias->execute();
    $sugerenciasResultado = $stmtSugerencias->get_result();
} else {
    echo "<p>ID de producto no válido.</p>";
    exit;
}

// Función para agregar al carrito
if (isset($_POST['add_to_cart'])) {
    $productoId = $_POST['producto_id'];
    $cantidad = 1;

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    if (isset($_SESSION['carrito'][$productoId])) {
        $_SESSION['carrito'][$productoId] += $cantidad;
    } else {
        $_SESSION['carrito'][$productoId] = $cantidad;
    }

    // Aquí se puede agregar un mensaje de confirmación si se desea
    $productoAgregado = true; // Variable para mostrar el mensaje de que el producto fue agregado
}

// Función para manejar "Comprar ahora"
if (isset($_POST['buy_now'])) {
    $productoId = $_POST['producto_id'];
    $cantidad = 1;

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    if (isset($_SESSION['carrito'][$productoId])) {
        $_SESSION['carrito'][$productoId] += $cantidad;
    } else {
        $_SESSION['carrito'][$productoId] = $cantidad;
    }

    // Redirigir al detalle del producto (esto puede seguir si es necesario)
    header('Location: ../../pages/User/carrito.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Producto - <?php echo htmlspecialchars($producto['nombre']); ?></title>
    <link rel="stylesheet" href="../../css/User/home.css">
    <link rel="stylesheet" href="../../css/User/detail.css">
</head>
<body>
    <header>
        <h1>Detalles del Producto</h1>
    </header>

    <div class="product-detail">
        <div class="product-gallery">
            <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="main-image">
        </div>

        <div class="product-info">
            <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>
            <p class="rating">4.8 
                <span class="stars">★★★★★</span> 
            </p>
            <p class="price">$<?php echo number_format($producto['precio'], 2); ?></p>
            <p class="delivery-info"><?php echo htmlspecialchars($producto['descripcion']); ?>
            <br><br>Envío gratis <br>
            Comprando dentro de la próxima hora</p>

            <p class="quantity">Cantidad: 1 unidad (+<?php echo htmlspecialchars($producto['stock']); ?> disponibles)</p>

            <!-- Mensaje de confirmación (opcional) -->
            <?php if (isset($productoAgregado) && $productoAgregado): ?>
                <p class="added-to-cart">Producto agregado al carrito con éxito.</p>
            <?php endif; ?>

            <!-- Formulario para agregar al carrito -->
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $idProducto; ?>">
                <input type="hidden" name="producto_id" value="<?php echo $idProducto; ?>">
                <button class="add-to-cart" type="submit" name="add_to_cart">Agregar al carrito</button>
            </form>

            <!-- Formulario para comprar ahora -->
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $idProducto; ?>">
                <input type="hidden" name="producto_id" value="<?php echo $idProducto; ?>">
                <button class="buy-now" type="submit" name="buy_now">Comprar ahora</button>
            </form>
        </div>
    </div>

    <!-- Sección de Sugerencias de Productos -->
    <div class="suggestions">
        <div class="product-grid">
            <?php while ($sugerencia = $sugerenciasResultado->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($sugerencia['imagen']); ?>" alt="<?php echo htmlspecialchars($sugerencia['nombre']); ?>">
                    <h3><?php echo htmlspecialchars($sugerencia['nombre']); ?></h3>
                    <p>$<?php echo number_format($sugerencia['precio'], 2); ?></p>
                    <button class="btn" onclick="window.location.href='detail.php?id=<?php echo $sugerencia['idProducto']; ?>'">Ver más</button>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <footer>
        <p>&copy; 2024 Glamour Studio. Todos los derechos reservados.</p>
    </footer>

    <script src="../../js/User/Main.js"></script>
</body>
</html>

<?php
$conn->close();
?>
