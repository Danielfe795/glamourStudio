<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Glamour Studio</title>
    <link rel="stylesheet" href="/Components/footer.html">
    <link rel="stylesheet" href="./Components/header.html">
    <link rel="stylesheet" href="./css/User/home.css">
</head>
<body>
    

   <!-- Slider de imágenes promocionales -->
   <section class="slider-frame">
    <ul>
        <li><img src="./img/1.webp" alt="Promo 1"></li>
        <li><img src="./img/2.webp" alt="Promo 2"></li>
        <li><img src="./img/3_11zon.webp" alt="Promo 3"></li>
        <li><img src="./img/4.webp" alt="Promo 4"></li>
        <li><img src="./img/5.webp" alt="Promo 5"></li>
        <li><img src="./img/6_11zon.webp" alt="Promo 5"></li>

    </ul>
</section>



    <!-- Sección de productos -->
    <main class="productos-container">
        <div class="product-grid">
            <?php
            // Incluir la conexión a la base de datos
            include './php/conexion.php';

            // Consulta para obtener los primeros 4 productos
            $sql = "SELECT idProducto, nombre, descripcion, precio, imagen FROM producto LIMIT 4";
            $result = $conn->query($sql);

            // Verificar si hay productos
            if ($result->num_rows > 0) {
                // Bucle para mostrar cada producto inicialmente
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="product-card">
                        <div class="cart-icon-container">
                            <img class="icon-productos" src="./img/icono-header-cart.png" alt="Carrito" onclick="window.location.href='./pages/User/carrito.html'">
                        </div>
                        <img src="<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                        <h3><?php echo htmlspecialchars($row['nombre']); ?></h3>
                        <p>Precio: $<?php echo number_format($row['precio'], 2); ?></p>
                        <button class="btn" onclick="window.location.href='./pages/User/detail.php?id=<?php echo $row['idProducto']; ?>'">Ver más</button>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No se encontraron productos.</p>";
            }

            // Cerrar la conexión
            $conn->close();
            ?>
        </div>
    </main>

    <!-- Botón para cargar más productos -->
    <div class="button-container">
        <button id="btn-ver-mas" class="btn-vermas-productos">Ver más</button>
    </div>
    
    <script src="./js/User/Main.js"></script>



    <script>
        // Función para cargar más productos dinámicamente
        let offset = 4; // Número de productos iniciales cargados

        document.getElementById('btn-ver-mas').addEventListener('click', function() {
            fetch('./php/cargar_mas_productos.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'offset=' + offset
            })
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    // Incrementa el offset para la próxima carga
                    offset += data.length;

                    // Añade los nuevos productos a la página
                    const productGrid = document.querySelector('.product-grid');
                    data.forEach(producto => {
                        const productCard = document.createElement('div');
                        productCard.classList.add('product-card');

                        productCard.innerHTML = ` 
                            <div class="cart-icon-container">
                                <img class="icon-productos" src="./img/icono-header-cart.png" alt="Carrito" onclick="window.location.href='./pages/User/carrito.html'">
                            </div>
                            <img src="${producto.imagen}" alt="${producto.nombre}">
                            <h3>${producto.nombre}</h3>
                            <p>Precio: $${parseFloat(producto.precio).toLocaleString()}</p>
                            <button class="btn" onclick="window.location.href='./pages/User/detail.php?id=${producto.idProducto}'">Ver más</button>
                        `;

                        productGrid.appendChild(productCard);
                    });
                } else {
                    // Oculta el botón si no hay más productos
                    document.getElementById('btn-ver-mas').style.display  = 'none';
                }
            })
            .catch(error => console.error('Error al cargar más productos:', error));
        });
    </script>
</body>
<footer></footer>
</html>
