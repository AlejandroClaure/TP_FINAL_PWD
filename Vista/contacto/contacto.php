<?php
require_once __DIR__ . '/../../configuracion.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once $GLOBALS['VISTA_PATH'] . 'estructura/cabecera.php';
?>

<div class="container py-5 mt-5">
    <h2 class="text-center mb-4">Formulario de Contacto</h2>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php include_once $GLOBALS['VISTA_PATH'] . 'contacto/formContacto.php'; ?>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php include_once $GLOBALS['VISTA_PATH'] . 'estructura/pie.php'; ?>
