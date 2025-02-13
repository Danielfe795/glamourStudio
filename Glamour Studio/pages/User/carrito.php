<?php
session_start();
include '../../php/conexion.php';

// Si el carrito está vacío, mostrar el mensaje
if (empty($_SESSION['carrito'])) {
    $carritoVacio = true; // Variable para saber si el carrito está vacío
    $totalCarrito = 0; // No hay productos en el carrito, así que el total es 0
} else {
    $carritoVacio = false;
    $totalCarrito = 0;

    // Si se solicita eliminar un producto del carrito
    if (isset($_GET['remove'])) {
        $productoIdEliminar = (int)$_GET['remove']; 
        if (isset($_SESSION['carrito'][$productoIdEliminar])) {
            unset($_SESSION['carrito'][$productoIdEliminar]);
        }
        header('Location: carrito.php');
        exit;
    }

    // Si se quiere actualizar la cantidad de un producto (incrementar o disminuir)
    if (isset($_POST['update_quantity'])) {
        $productoId = (int)$_POST['producto_id'];
        $nuevaCantidad = (int)$_POST['cantidad'];
        if ($nuevaCantidad > 0) {
            $_SESSION['carrito'][$productoId] = $nuevaCantidad;
        } else {
            unset($_SESSION['carrito'][$productoId]); // Eliminar si la cantidad es 0
        }
        header('Location: carrito.php');
        exit;
    }

    // Incremento o decremento de cantidad usando botones
    if (isset($_POST['increase_quantity'])) {
        $productoId = (int)$_POST['increase_quantity'];
        if (isset($_SESSION['carrito'][$productoId])) {
            $_SESSION['carrito'][$productoId]++;
        }
        header('Location: carrito.php');
        exit;
    }

    if (isset($_POST['decrease_quantity'])) {
        $productoId = (int)$_POST['decrease_quantity'];
        if (isset($_SESSION['carrito'][$productoId]) && $_SESSION['carrito'][$productoId] > 1) {
            $_SESSION['carrito'][$productoId]--;
        } elseif (isset($_SESSION['carrito'][$productoId])) {
            unset($_SESSION['carrito'][$productoId]); // Eliminar si es 1 y se presiona disminuir
        }
        header('Location: carrito.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="../../css/User/carrito.css">
    <link rel="stylesheet" href="../../css/User/home.css">
</head>
<body>
    <header></header>
    <div class="carrito-container">
        <div class="carrito">
            <h2><img src="../../img/icono-header-cart.png" alt="Icono de Carrito" class="carrito-icono"> Carrito</h2>
            <div class="productos">
                <?php if ($carritoVacio): ?>
                    <p>No hay productos en tu carrito.</p> <!-- Mensaje cuando el carrito está vacío -->
                <?php else: ?>
                    <h3><?php echo count($_SESSION['carrito']); ?> Productos</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Detalles de Productos</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Total</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach ($_SESSION['carrito'] as $productoId => $cantidad) {
                                $consulta = "SELECT nombre, precio, imagen FROM producto WHERE idProducto = ?";
                                $stmt = $conn->prepare($consulta);
                                $stmt->bind_param("i", $productoId);
                                $stmt->execute();
                                $resultado = $stmt->get_result();

                                if ($resultado->num_rows > 0) {
                                    $producto = $resultado->fetch_assoc();
                                    $precioTotal = $producto['precio'] * $cantidad;
                                    $totalCarrito += $precioTotal;
                                } else {
                                    continue;
                                }
                                ?>
                                <tr>
                                    <td>
                                        <div class="producto-detalle">
                                            <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                            <div class="info">
                                                <p><?php echo strlen($producto['nombre']) > 20 ? substr(htmlspecialchars($producto['nombre']), 0, 20) . "..." : htmlspecialchars($producto['nombre']); ?></p>
                                                <span class="precio-cel">$<?php echo number_format($producto['precio'], 2); ?></span>
                                                <span>Categoría</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="cantidad">
                                            <form method="POST" action="">
                                                <button class="boton-cantidad" type="submit" name="decrease_quantity" value="<?php echo $productoId; ?>">-</button>
                                                <input type="number" name="cantidad" value="<?php echo $cantidad; ?>" min="1">
                                                <input type="hidden" name="producto_id" value="<?php echo $productoId; ?>">
                                                <button class="boton-cantidad" type="submit" name="increase_quantity" value="<?php echo $productoId; ?>">+</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="precio-pc">$<?php echo number_format($producto['precio'], 2); ?></td>
                                    <td class="precio-pc">$<?php echo number_format($precioTotal, 2); ?></td>
                                    <td class="icono-pc">
                                        <a href="carrito.php?remove=<?php echo $productoId; ?>">
                                            <img src="../../img/basura.svg" alt="Eliminar" class="icono-basura">
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        <?php if (!$carritoVacio): ?>
            <div class="resumen">
                <h2>Resumen</h2>
                <div class="resumen-detalle">
                    <p>Productos <span><?php echo count($_SESSION['carrito']); ?></span></p>
                    <p>Subtotal <span>$<?php echo number_format($totalCarrito, 2); ?></span></p>
                    <p>Iva <span>$<?php echo number_format($totalCarrito * 0.19, 2); ?></span></p>
                    <p>Total <span>$<?php echo number_format($totalCarrito * 1.19, 2); ?></span></p>
                </div>
                <div class="cupon">
                    <label for="cupon">Cupon</label>
                    <input type="text" id="cupon" placeholder="RTS25545">
                    <button>Aplicar</button>
                    <button onclick="window.location.href='../../pages/User/checkout.php'">Pagar</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <footer></footer>
    <script src="../../js/User/Main.js"></script>
</body>
</html>

<?php
$conn->close();
?>
