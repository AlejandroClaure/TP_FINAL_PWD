<form action="<?php echo $GLOBALS['VISTA_URL']; ?>contacto/accion/accionProcesarContacto.php" method="POST">
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

    <!-- reCAPTCHA v2 checkbox -->
    <div class="mb-3">
        <div class="g-recaptcha" data-sitekey="6LfrKxMsAAAAABxuGzZ5pVvUedr_ka1GKkpRbxCI"></div>
    </div>

    <button type="submit" class="btn btn-success w-100">Enviar Mensaje</button>
</form>
