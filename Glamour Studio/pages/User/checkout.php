<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../../css/User/checkout.css">
    <link rel="stylesheet" href="../../css/User/home.css">
</head>
<body>
<header></header>
<div class="checkout-container">
    <h2>Finalizar compra</h2>
    <div class="checkout-content">
        <div class="billing-details">
            <h3>Detalles del cliente</h3>
            <form action="#" method="post">
                <label for="first-name">Nombre <span>*</span></label>
                <input type="text" id="first-name" name="first-name" required>

                <label for="last-name">Apellido <span>*</span></label>
                <input type="text" id="last-name" name="last-name" required>

                <label for="phone">Telefono <span>*</span></label>
                <input type="tel" id="phone" name="phone" required>

                <label for="email">Correo <span>*</span></label>
                <input type="email" id="email" name="email" required>

                <label for="text">Direccion <span>*</span></label>
                <input type="text" id="direccion" name="direccion" required>

                <label for="text">Información adicional <span></span></label>
                <input type="text" id="nota" name="nota">
            </form>
        </div>
        <div class="order-summary">
            <h3>Resumen</h3>
            <table>
                <tr>
                    <th>Productos</th>
                    <th>Subtotal</th>
                </tr>
                <tr>
                    <td>Red Sweatshirt × 1</td>
                    <td>$15.00</td>
                </tr>
                <tr>
                    <td>Subtotal</td>
                    <td>$15.00</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>$15.00</td>
                </tr>
            </table>
            <h4>Metodo de pago</h4>
            <div class="payment-methods">
                <div>
                    <input type="radio" id="epayco" name="payment-method" value="epayco" checked>
                    <label for="epayco"><img class="icon-productos" src="../../img/epayco-logo-fondo-oscuro.png" alt="icono-epayco" style="height: 30px; padding-top: 10px;"></label>
                </div>
                <div>
                    <input type="radio" id="efetivo" name="payment-method" value="efetivo" checked>
                    <label for="efetivo"><img class="icon-productos" src="https://cdn-icons-png.flaticon.com/512/2331/2331920.png" alt="icono-epayco" style="height: 80px; padding-top: 10px;"></label>
                </div>
            </div>
            <button type="submit" class="place-order-btn" onclick="window.location.href='../../pages/User/SuccessfulPayments.html'">Pagar</button>
        </div>
    </div>
</div>
<footer></footer>
</body>
<script src="../../js/User/Main.js"></script>
</html>
