<?php include_once '../estructura/cabecera.php'; ?>
<div class="container py-5 mt-5">
    <h2 class="text-center mb-4">Formulario de Contacto</h2>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="accionProcesarContacto.php" method="POST">

                <!-- Nombre -->
                <div class="mb-3">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <!-- Mensaje -->
                <div class="mb-3">
                    <label class="form-label">Mensaje</label>
                    <textarea name="mensaje" class="form-control" rows="5" required></textarea>
                </div>

                <!-- reCAPTCHA -->
                <div class="mb-3">
                    <div class="g-recaptcha" data-sitekey="TU_SITE_KEY_AQUÍ"></div>
                </div>

                <button type="submit" class="btn btn-success w-100">Enviar Mensaje</button>
            </form>
        </div>
    </div>
</div>

<!-- Script reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php include_once '../estructura/pie.php'; ?>
