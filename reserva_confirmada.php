<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Tu reserva ha sido confirmada!</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/confirmacion.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="img/logo.png" alt="Logo Savoria">
        </div>
        <h1>¡Tu reserva ha sido confirmada!</h1>
    </header>

    <main>
        <section class="mensaje-confirmacion">
            <p>Gracias por reservar en Savoria Restaurant.</p>
            <p>Recibimos tu solicitud y tu mesa está confirmada para:</p>

            <?php

if (isset($_GET['fecha']) && isset($_GET['hora'])) {
    $fechaConfirmada = htmlspecialchars($_GET['fecha']);
    $horaConfirmada = htmlspecialchars($_GET['hora']);
    echo "<p><strong>Fecha:</strong> " . $fechaConfirmada . "</p>";
    echo "<p><strong>Hora:</strong> " . $horaConfirmada . "</p>";
} else {
    echo "<p>Te contactaremos pronto con los detalles de tu reserva.</p>";
}
?>

            <p>¡Te esperamos!</p>
        </section>

        <section class="informacion-contacto">
            <h2>Información de contacto</h2>
            <p>Si necesitas realizar algún cambio en tu reserva, por favor contáctanos:</p>
            <ul>
                <li>Teléfono: (123) 456-7890</li>
                <li>Email:  info@savoriarestaurant.com</li>
            </ul>
        </section>

        <div class="volver-inicio">
            <a href="../index.html">Volver a la página de inicio</a>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Savoria Restaurant. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
