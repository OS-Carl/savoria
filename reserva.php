<?php
// Comprobar si se envio el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtiene datos del formulario
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $guests = htmlspecialchars($_POST["guests"]);
    $date = htmlspecialchars($_POST["date"]);
    $time = htmlspecialchars($_POST["time"]);
    $special_requests = isset($_POST["special_requests"]) ? htmlspecialchars($_POST["special_requests"]) : "";
    
    // Formato fecha y hora
    $reservation_date = date('Y-m-d', strtotime($date));
    $reservation_time = $time;
    $formatted_date = date('l, F j, Y', strtotime($date));
    
    // Crear reserva
    $reservation_data = [
        'reservation_id' => uniqid(),
        'date_created' => date('Y-m-d H:i:s'),
        'customer' => [
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ],
        'reservation' => [
            'date' => $reservation_date,
            'time' => $reservation_time,
            'guests' => $guests,
            'special_requests' => $special_requests
        ]
    ];
    
    // Guarda reserva
    $reservations_file = 'data/reservations.json';
    
    // Crear  directorio si no existe
    if (!file_exists('data')) {
        mkdir('data', 0777, true);
    }
    
    // Obtiene reservas existentes
    $reservations = [];
    if (file_exists($reservations_file)) {
        $reservations_json = file_get_contents($reservations_file);
        $reservations = json_decode($reservations_json, true) ?: [];
    }
    
    // Nueva reserva
    $reservations[] = $reservation_data;
    
    // Guarda reservas en archivo
    file_put_contents($reservations_file, json_encode($reservations, JSON_PRETTY_PRINT));
    
    // Mensaje enviado
    $success = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmacion de reserva - Savoria Restaurant</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>Savoria</h1>
                <p>Sabores de otro nivel</p>
            </div>
            <nav>
                <ul>
                    <li><a href="index.html#home">Home</a></li>
                    <li><a href="index.html#menu">Menu</a></li>
                    <li><a href="index.html#order">Pedido</a></li>
                    <li><a href="index.html#reservation">Reservation</a></li>
                    <li><a href="index.html#reviews">Opiniones</a></li>
                    <li><a href="index.html#location">Ubicacion</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="confirmation-section">
        <div class="container">
            <?php if (isset($success) && $success): ?>
                <div class="confirmation-message">
                    <h2>Reserva confirmada</h2>
                    <p>Gracias por elegir Savoria Restaurant. Tu reserva esta confirmada.</p>
                    <p>ID de reserva: <strong><?php echo $reservation_data['reservation_id']; ?></strong></p>
                    
                    <div class="reservation-details">
                        <h3>Detalles de la reserva</h3>
                        <p><strong>Nombre:</strong> <?php echo $name; ?></p>
                        <p><strong>Fecha:</strong> <?php echo $formatted_date; ?></p>
                        <p><strong>Hora:</strong> <?php echo $time; ?></p>
                        <p><strong>Numero de comensales:</strong> <?php echo $guests; ?></p>
                        <?php if (!empty($special_requests)): ?>
                            <p><strong>Comentarios adicionales:</strong> <?php echo $special_requests; ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <p>Un correo de confirmacion se envio a <?php echo $email; ?>.</p>
                    <p>Si necesitas modificar o cancelar tu reserva, por favor contactanos al (123) 456-7890 con 24hs de antelacion.</p>
                    
                    <div class="button-container">
                        <a href="index.html" class="btn primary-btn">Volver al inicio</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="error-message">
                    <h2>Error al procesar la reserva</h2>
                    <p>Hubo un error al procesar la reserrva. Por favor vuelva a intentarlo.</p>
                    <div class="button-container">
                        <a href="index.html#reservation" class="btn primary-btn">Volver al formulario de reserva</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Savoria Restaurant</h3>
                    <p>Sirviendo platos excepcionales en un ambiente cálido y acogedor desde 2010.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Links</h3>
                    <ul>
                        <li><a href="index.html#home">Home</a></li>
                        <li><a href="index.html#menu">Menu</a></li>
                        <li><a href="index.html#order">Pedidos</a></li>
                        <li><a href="index.html#reservation">Reservas</a></li>
                        <li><a href="index.html#reviews">Opiniones</a></li>
                        <li><a href="index.html#location">Ubicacion</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contactanos</h3>
                    <p><i class="fas fa-map-marker-alt"></i>  Avenida del Sabor, Savoirville</p>
                    <p><i class="fas fa-phone"></i> (123) 456-7890</p>
                    <p><i class="fas fa-envelope"></i> info@savoriarestaurant.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Savoria Restaurant. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
