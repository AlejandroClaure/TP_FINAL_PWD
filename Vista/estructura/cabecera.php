<?php
include_once dirname(__DIR__, 2) . '/configuracion.php';

// Iniciar sesión solo si aún no existe
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Crear manejador de sesión
$session = new Session();
$usuario = $session->getUsuario(); // usuario logueado o null

// Obtener roles del usuario logueado (si lo hay)
$rolesUsuario = [];
if ($usuario) {
    $rolesUsuario = (new AbmUsuarioRol())->rolesDeUsuario($usuario->getIdUsuario());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tienda Online</title>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

   <link rel="stylesheet" href="<?= $GLOBALS['CSS_URL']; ?>cabecera.css">
   <link rel="stylesheet" href="<?= $GLOBALS['CSS_URL']; ?>pie.css">
   <link rel="stylesheet" href="<?= $GLOBALS['CSS_URL']; ?>carrito.css">
   <link rel="stylesheet" href="<?= $GLOBALS['CSS_URL']; ?>albumProductos.css">
</head>

<body>
<header>
   <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
      <div class="container">

         <!-- Logo -->
         <a class="navbar-brand logo" href="<?= $GLOBALS['BASE_URL']; ?>">
            <img src="<?= $GLOBALS['IMG_URL']; ?>logo.png"
                 alt="Logo" width="50" height="50"
                 class="me-1 rounded-circle">
            Tienda Online
         </a>

         <!-- Botón menú responsive -->
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                 data-bs-target="#navbarNav1">
            <span class="navbar-toggler-icon"></span>
         </button>

         <div class="collapse navbar-collapse" id="navbarNav1">
            <ul class="navbar-nav ms-auto">

               <li class="nav-item">
                  <a class="nav-link" href="<?= $GLOBALS['VISTA_URL']; ?>producto/producto.php">Productos</a>
               </li>

               <li class="nav-item">
                  <a class="nav-link" href="<?= $GLOBALS['VISTA_URL']; ?>compra/carrito.php">Carrito</a>
               </li>

               <li class="nav-item">
                  <a class="nav-link" href="<?= $GLOBALS['VISTA_URL']; ?>contacto/contacto.php">Contacto</a>
               </li>

               <!-- Solo ADMIN -->
               <?php if ($usuario && in_array("admin", $rolesUsuario)): ?>
               <li class="nav-item">
                  <a class="nav-link text-warning fw-bold"
                     href="<?= $GLOBALS['VISTA_URL']; ?>roles/panelRoles.php">
                     <i class="fa fa-users-cog"></i> Administrar Roles
                  </a>
               </li>
               <?php endif; ?>

               <!-- Si el usuario está logueado -->
               <?php if ($usuario): ?>
               
                  <li class="nav-item">
                     <a class="nav-link text-primary fw-bold" href="<?= $GLOBALS['VISTA_URL']; ?>login/paginaSegura.php">
                        <i class="fa fa-user"></i>
                        <?= htmlspecialchars($usuario->getUsNombre()); ?>
                     </a>
                  </li>

                  <li class="nav-item">
                     <a class="nav-link text-danger fw-bold"
                        href="<?= $GLOBALS['VISTA_URL']; ?>login/accion/cerrarSesion.php">
                        <i class="fa fa-sign-out-alt"></i> Cerrar sesión
                     </a>
                  </li>

               <!-- Si NO está logueado -->
               <?php else: ?>
               
                  <li class="nav-item">
                     <a class="nav-link" href="<?= $GLOBALS['VISTA_URL']; ?>login/login.php">
                        <i class="fa fa-sign-in-alt"></i> Login
                     </a>
                  </li>

               <?php endif; ?>

            </ul>
         </div>
      </div>
   </nav>
</header>
