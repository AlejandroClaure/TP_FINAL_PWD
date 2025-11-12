<?php
include_once '../../configuracion.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php include_once '../estructura/cabecera.php'; ?>
<main class="mt-5 pt-5">
    <div class="container mt-5">
        <div class="row">
            <!-- Shopping Cart Items -->
            <div class="col-md-8">
                <h3>Mi carrito</h3>

                <!-- Articulo1 -->
                <div class="cart-item">
                    <div class="d-flex">
                        <img src="https://via.placeholder.com/60" alt="Product" class="product-img me-3">
                        <div>
                            <h6>Product Name 1</h6>
                            <p class="mb-0">1 item</p>
                        </div>
                    </div>
                    <span>$25.99</span>
                </div>

                <!-- Cart Item 2 -->
                <div class="cart-item">
                    <div class="d-flex">
                        <img src="https://via.placeholder.com/60" alt="Product" class="product-img me-3">
                        <div>
                            <h6>Product Name 2</h6>
                            <p class="mb-0">2 items</p>
                        </div>
                    </div>
                    <span>$19.99</span>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-md-4">
                <div class="cart-summary">
                    <h5>Summary</h5>
                    <ul class="list-unstyled">
                        <li class="d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span>$45.98</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Shipping:</span>
                            <span>$5.00</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span class="total-price">Total:</span>
                            <span class="total-price">$50.98</span>
                        </li>
                    </ul>
                    <button class="btn btn-checkout" disabled>Proceed to Checkout</button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once '../estructura/pie.php'; ?>