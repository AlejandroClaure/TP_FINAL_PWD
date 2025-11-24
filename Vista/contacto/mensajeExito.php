<?php
require_once __DIR__ . '/../../configuracion.php';
include_once $GLOBALS['VISTA_PATH'] . 'estructura/cabecera.php';
?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="alert alert-success shadow p-4 rounded-3 text-center">
                <h3 class="mb-3">¡Mensaje enviado con éxito! 🎉</h3>

                <p class="fs-5">
                    Gracias por ponerte en contacto con nosotros.  
                    <strong>Te responderemos pronto.</strong>
                </p>

                <hr>

                <a href="<?= $GLOBALS['VISTA_URL']; ?>contacto/contacto.php" class="btn btn-primary mt-3">
                    Volver al formulario
                </a>
            </div>

        </div>
    </div>
</div>

<?php include_once $GLOBALS['VISTA_PATH'] . 'estructura/pie.php'; ?>
