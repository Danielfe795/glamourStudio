<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Servicios - Glamour Studio</title>
    <link rel="stylesheet" href="../../css/User/Services.css">
    <link rel="stylesheet" href="../../css/User/home.css">
</head>
<body>
    <header></header>

    <!-- Sección de introducción -->
    <section class="intro">
        <div class="container">
            <h2>Bienvenido a la experiencia de belleza</h2>
            <p>Ofrecemos una amplia gama de servicios personalizados para resaltar tu belleza. Descubre lo mejor en estilo, cuidado personal y bienestar en un solo lugar.</p>
        </div>
    </section>

    <!-- Sección de servicios -->
    <section class="service-panel">
        <div class="container">
            <h2>Nuestros Servicios</h2>
            <div class="service-container" id="service-container">
                <?php
                // Incluir la conexión a la base de datos
                include '../../php/conexion.php';

                $offset = 0;
                $limit = 10; // Mostrar 10 servicios inicialmente

                // Consulta para obtener los primeros 10 servicios
                $sql = "SELECT nombre, fechaAtencion, tipoServicio, imagen FROM servicio LIMIT $limit OFFSET $offset";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <div class="service-card">
                            <img src="<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                            <div class="service-info">
                                <h3><?php echo htmlspecialchars($row['nombre']); ?></h3>
                                <p><strong>Fecha de Atención:</strong> <?php echo htmlspecialchars($row['fechaAtencion']); ?></p>
                                <p><strong>Tipo de Servicio:</strong> <?php echo htmlspecialchars($row['tipoServicio']); ?></p>
                                <button class="book-btn" onclick="window.location.href='/pages/User/reservas.php'">Agendar</button>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No se encontraron servicios.</p>";
                }

                $conn->close();
                ?>
            </div>
            <!-- <div class="button-container">
                <button id="btn-ver-mas-servicios" class="btn-vermas-servicios">Ver más servicios</button>
            </div> -->
        </div>
    </section>

    <footer></footer>

    <script src="../../js/User/Main.js"></script>
</body>
</html>