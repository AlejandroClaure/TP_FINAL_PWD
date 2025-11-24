<?php
require_once __DIR__ . '/../../../configuracion.php';

$objContacto = new ContactoControl();

$res = $objContacto->procesarFormulario($_POST);

if ($res['success']) {
    header("Location: ../mensajeExito.php");
    exit();
} else {
    // mostrar error en la misma vista
    echo "<div class='alert alert-danger text-center mt-5'>
            {$res['msg']}
          </div>";
}
?>