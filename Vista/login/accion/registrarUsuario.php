<?php
require_once '../../../configuracion.php';

error_log("Datos recibidos en registro: " . print_r($_POST, true));

$auth = new AbmAuth();

// Llamamos al método que ahora devuelve un array con más información
$resultado = $auth->registrarYLogin($_POST);

if ($resultado['success']) {
    // ¡ÉXITO! El usuario ya está logueado gracias a $session->iniciar()
    // Puedes redirigir a donde quieras que vaya un usuario recién registrado
    header("Location: ../../../index.php");  // o ../dashboard.php, etc.
    exit;
}

// ----------------- ERRORES -----------------
if ($resultado['error'] === 'email_duplicado') {
    header("Location: ../login.php?email_duplicado=1");
    exit;
}

if ($resultado['error'] === 'creacion_fallida') {
    header("Location: ../login.php?error_registro=1");
    exit;
}

// Error genérico (por si acaso)
header("Location: ../login.php?error=1");
exit;