<?php
// Comprobar envio de formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $address = isset($_POST["address"]) ? htmlspecialchars($_POST["address"]) : "";
    $order_type = htmlspecialchars($_POST["order_type"]);
    $special_instructions = isset($_POST["special_instructions"]) ? htmlspecialchars($_POST["special_instructions"]) : "";
    
    // Menu precios
    $prices = [
        'bruschetta' => 8.99,
        'rabas' => 12.99,
        'dip_de_espinacas_y_alcachofas' => 10.99,
        'salmon' => 24.99,
        'bife_de_chorizo' => 32.99,
        'risotto' => 18.99,
        'tiramisu' => 8.99,
        'volcan_de_chocolate' => 9.99,
        'budin' => 7.99,
        'cocteles' => 12.99,
        'cerveza' => 7.99,
        'vino' => 9.99
    ];
    
    // Menu catalogo
    $item_names = [
        'bruschetta' => 'Bruschetta',
        'rabas' => 'Rabas',
        'dip_de_espinacas_y_alcachofas' => 'Dip de Espinacas y Alcachofas',
        'salmon' => 'Salmon grillado',
        'bife_de_chorizo' => 'Bife de chorizo',
        'risotto' => 'Risotto',
        'tiramisu' => 'Tiramisu',
        'volcan_de_chocolate' => 'Volcan de chocolate',
        'budin' => 'Budin',
        'cocteles' => 'Cocteles de autor',
        'cerveza' => 'Cerveza',
        'vino' => 'Vino'
    ];
    
    // Procesar orden de productos
    $order_items = [];
    $total = 0;
    
    if (isset($_POST["items"]) && is_array($_POST["items"])) {
        foreach ($_POST["items"] as $item_id => $quantity) {
            $quantity = intval($quantity);
            if ($quantity > 0 && isset($prices[$item_id])) {
                $price = $prices[$item_id];
                $item_total = $price * $quantity;
                $total += $item_total;
                
                $order_items[] = [
                    'name' => $item_names[$item_id],
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $item_total
                ];
            }
        }
    }
    
    // Calcular impuestos (8%)
    $tax = $total * 0.08;
    $grand_total = $total + $tax;
    
    // Create order data
    $order_data = [
        'order_id' => uniqid(),
        'date' => date('Y-m-d H:i:s'),
        'customer' => [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address
        ],
        'order_type' => $order_type,
        'items' => $order_items,
        'subtotal' => $total,
        'tax' => $tax,
        'total' => $grand_total,
        'special_instructions' => $special_instructions
    ];
    
    // Guardar orden
    $orders_file = 'data/orders.json';
    
    // Crear directorio si no existe
    if (!file_exists('data')) {
        mkdir('data', 0777, true);
    }
    
    // Obtener pedidos existentes
    $orders = [];
    if (file_exists($orders_file)) {
        $orders_json = file_get_contents($orders_file);
        $orders = json_decode($orders_json, true) ?: [];
    }
    
    // Nueva orden
    $orders[] = $order_data;
    
    // Save orders back to file
    file_put_contents($orders_file, json_encode($orders, JSON_PRETTY_PRINT));
    
    // Mensaje enviado
    $success = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmacion de orden - Savoria Restaurant</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>Savoria</h1>
                <p>Platos de otro nivel</p>
            </div>
            <nav>
                <ul>
                    <li><a href="../index.html#home">Home</a></li>
                    <li><a href="../index.html#menu">Menu</a></li>
                    <li><a href="../index.html#order">Pedidos</a></li>
                    <li><a href="../index.html#reservation">Reservacion</a></li>
                    <li><a href="../index.html#reviews">Opiniones</a></li>
                    <li><a href="../index.html#location">Ubicacion</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="confirmation-section">
        <div class="container">
            <?php if (isset($success) && $success): ?>
                <div class="confirmation-message">
                    <h2>¡Gracias por su pedido!</h2>
                    <p>Recibimos tu orden y la estamos procesando.</p>
                    <p>Order ID: <strong><?php echo $order_data['order_id']; ?></strong></p>
                    
                    <div class="order-details">
                        <h3>Detalles del pedido</h3>
                        <div class="customer-info">
                            <p><strong>Nombre:</strong> <?php echo $name; ?></p>
                            <p><strong>Email:</strong> <?php echo $email; ?></p>
                            <p><strong>Telefono:</strong> <?php echo $phone; ?></p>
                            <?php if ($order_type == 'delivery'): ?>
                                <p><strong>Direccion de entrega:</strong> <?php echo $address; ?></p>
                            <?php else: ?>
                                <p><strong>Tipo de pedido:</strong> Pickup</p>
                            <?php endif; ?>
                            <?php if (!empty($special_instructions)): ?>
                                <p><strong>Comentarios adicionales:</strong> <?php echo $special_instructions; ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <h4>Productos pedidos</h4>
                        <table class="order-items-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $item): ?>
                                    <tr>
                                        <td><?php echo $item['name']; ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td>$<?php echo number_format($item['total'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">Subtotal</td>
                                    <td>$<?php echo number_format($total, 2); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Impuestos (8%)</td>
                                    <td>$<?php echo number_format($tax, 2); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3"><strong>Total</strong></td>
                                    <td><strong>$<?php echo number_format($grand_total, 2); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <p>Un correo de confirmacion se envio a <?php echo $email; ?>.</p>
                    <p>Si tiene alguna pregunta, contactenos al (123) 456-7890.</p>
                    
                    <div class="button-container">
                        <a href="../index.html" class="btn primary-btn">Regresar al inicio</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="error-message">
                    <h2>Error al procesar la orden</h2>
                    <p>Hubo un error al procesar su orden. Por favor intente nuevamente.</p>
                    <div class="button-container">
                        <a href="index.html#order" class="btn primary-btn">Regresar al formulario de pedidos</a>
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
                        <li><a href="/index.html#home">Home</a></li>
                        <li><a href="/index.html#menu">Menu</a></li>
                        <li><a href="/index.html#order">Pedidos</a></li>
                        <li><a href="/index.html#reservation">Reservaciones</a></li>
                        <li><a href="/index.html#reviews">Opiniones</a></li>
                        <li><a href="/index.html#location">Ubicacion</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contactanos</h3>
                    <p><i class="fas fa-map-marker-alt"></i> Avenida del Sabor, Savoirville</p>
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