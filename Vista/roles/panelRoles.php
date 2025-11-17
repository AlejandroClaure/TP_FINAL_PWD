<?php
include_once '../../configuracion.php';

$session = new Session();

// Solo usuarios logueados (idealmente admin)
if (!$session->activa()) {
    header("Location: ../login/login.php");
    exit;
}

$usuarioActual = $session->getUsuario();

// Controladores
$abmUsuario = new AbmUsuario();
$abmUsuarioRol = new AbmUsuarioRol();
$abmRol = new Rol();

// Procesar acciones (asignar / quitar)
if ($_POST && isset($_POST['accion'])) {

    if ($_POST['accion'] === 'asignar') {
        $abmUsuarioRol->asignarRol($_POST['idusuario'], $_POST['idrol']);
    }

    if ($_POST['accion'] === 'quitar') {
        $abmUsuarioRol->quitarRol($_POST['idusuario'], $_POST['idrol']);
    }

    header("Location: panelRoles.php");
    exit;
}

// Obtener usuarios y roles
$usuarios = $abmUsuario->buscar([]);
$roles = (new Rol())->listar("");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Roles</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Administración de Roles</h2>

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Roles actuales</th>
                    <th>Asignar nuevo rol</th>
                </tr>
                </thead>

                <tbody>

                <?php foreach ($usuarios as $u): ?>
                    <?php
                    $rolesUser = $abmUsuarioRol->rolesDeUsuario($u->getIdUsuario());
                    ?>
                    <tr>
                        <td><?= $u->getIdUsuario(); ?></td>
                        <td><?= $u->getUsNombre(); ?></td>
                        <td><?= $u->getUsMail(); ?></td>

                        <!-- Mostrar roles actuales -->
                        <td>
                            <?php if (empty($rolesUser)): ?>
                                <span class="badge bg-secondary">Sin roles</span>
                            <?php else: ?>
                                <?php foreach ($rolesUser as $rolDesc): ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="idusuario" value="<?= $u->getIdUsuario(); ?>">
                                        <input type="hidden" name="accion" value="quitar">

                                        <?php
                                        // Buscar idrol de este rol
                                        foreach ($roles as $r) {
                                            if ($r->getRoDescripcion() === $rolDesc) {
                                                $idRolActual = $r->getIdRol();
                                            }
                                        }
                                        ?>
                                        <input type="hidden" name="idrol" value="<?= $idRolActual; ?>">

                                        <span class="badge bg-primary">
                                            <?= $rolDesc ?>
                                            <button class="btn btn-sm btn-danger ms-1">x</button>
                                        </span>
                                    </form>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>

                        <!-- Asignar un nuevo rol -->
                        <td>
                            <form method="POST" class="d-flex gap-2">
                                <input type="hidden" name="accion" value="asignar">
                                <input type="hidden" name="idusuario" value="<?= $u->getIdUsuario(); ?>">

                                <select name="idrol" class="form-select" required>
                                    <option value="">Seleccionar...</option>
                                    <?php foreach ($roles as $r): ?>
                                        <option value="<?= $r->getIdRol(); ?>">
                                            <?= $r->getRoDescripcion(); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <button class="btn btn-success">Asignar</button>
                            </form>
                        </td>

                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>

        </div>
    </div>
</div>

</body>
</html>
