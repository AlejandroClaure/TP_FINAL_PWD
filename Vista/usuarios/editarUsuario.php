<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFinal/configuracion.php";

$session = new Session();
if (!$session->activa()) {
    header("Location: ../login/login.php");
    exit;
}

$usuario = $session->getUsuario();

// Comprobar roles
$abmUsuarioRol = new AbmUsuarioRol();
$rolesUsuario = array_map('strtolower', $abmUsuarioRol->rolesDeUsuario($usuario->getIdUsuario()));
if (!in_array("cliente", $rolesUsuario)) {
    echo "<div class='alert alert-danger m-5'>Acceso denegado. Solo clientes pueden acceder.</div>";
    $piePath = "../estructura/pie.php";
    if (file_exists($piePath)) include_once $piePath;
    exit;
}

// Obtener datos exactos del usuario
$abmUsuario = new AbmUsuario();
$usuarioActual = $abmUsuario->buscar(['idusuario' => $usuario->getIdUsuario()])[0];

$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pasar los datos al ABM sin hashear la contraseña aquí
    $params = [
        'idusuario' => $usuarioActual->getIdUsuario(),
        'usnombre'  => trim($_POST['usnombre']),
        'usmail'    => trim($_POST['usmail']),
        'uspass'    => $_POST['uspass'] // ABM hará hash si no está vacío
    ];

    if ($abmUsuario->modificacion($params)) {
        $mensaje = "<div class='alert alert-success'>Datos actualizados correctamente.</div>";

        // Actualizar sesión si cambió el nombre o email
        $session->getUsuario()->setUsNombre($params['usnombre']);
        $session->getUsuario()->setUsMail($params['usmail']);
    } else {
        $mensaje = "<div class='alert alert-danger'>Ocurrió un error al actualizar los datos.</div>";
    }
}

// Incluir cabecera
$cabeceraPath = "../estructura/cabecera.php";
if (file_exists($cabeceraPath)) {
    include_once $cabeceraPath;
} else {
    echo "<div class='alert alert-warning'>Cabecera no encontrada.</div>";
}
?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title mb-4">Editar mi cuenta</h2>

            <?= $mensaje ?>

            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label for="usnombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="usnombre" name="usnombre" value="<?= htmlspecialchars($usuarioActual->getUsNombre()); ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="usmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="usmail" name="usmail" value="<?= htmlspecialchars($usuarioActual->getUsMail()); ?>" required>
                </div>

                <div class="col-12">
                    <label for="uspass" class="form-label">Nueva contraseña (opcional)</label>
                    <input type="password" class="form-control" id="uspass" name="uspass" placeholder="Dejar vacío para mantener la actual">
                </div>

                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a href="../../Vista/inicio.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$piePath = "../estructura/pie.php";
if (file_exists($piePath)) {
    include_once $piePath;
} else {
    echo "<div class='alert alert-warning'>Pie no encontrado.</div>";
}
?>
