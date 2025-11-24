<form action="accion/accionProcesarContacto.php" method="POST">
    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Mensaje</label>
        <textarea name="mensaje" class="form-control" required></textarea>
    </div>

    <!-- reCAPTCHA -->
    <div class="g-recaptcha" data-sitekey="6LcWHhMsAAAAAB2BJkH34eq-U93EBHhhfQXxt4NF"></div>

    <button type="submit" class="btn btn-primary mt-3">Enviar</button>
</form>
