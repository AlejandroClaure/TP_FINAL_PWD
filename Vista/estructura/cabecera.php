<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/PWD_TPFinal/configuracion.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tienda Online - Grupo 3 PWD 2025</title>

   <!-- Bootstrap -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- Font Awesome (íconos redes y navbar) -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


   <!-- CSS personalizados -->
   <link rel="stylesheet" href="/Vista/css/cabecera.css">
   <link rel="stylesheet" href="/Vista/css/pie.css">
   <link rel="stylesheet" href="/Vista/css/albumProductos.css">
   <link rel="stylesheet" href="/Vista/css/carrito.css">
</head>

<body>
   <header>
      <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
         <div class="container">
            <a class="navbar-brand logo" href="#">
               <img src="https://images.pexels.com/photos/170809/pexels-photo-170809.jpeg"
                    alt="Logo Tienda Online" width="50" height="50"
                    class="me-1 rounded-circle">
               Tienda Online
            </a>

            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarNav1"
                    aria-controls="navbarNav1" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav1">
               <ul class="navbar-nav ms-auto">
                  <li class="nav-item"><a class="nav-link active" href="/PWD_TPFinal/index.php">Inicio</a></li>
                  <li class="nav-item"><a class="nav-link" href="/PWD_TPFinal/Vista/producto/producto.php">Productos</a></li>
                  <li class="nav-item"><a class="nav-link" href="/PWD_TPFinal/Vista/compra/carrito.php">Carrito</a></li>
                  <li class="nav-item"><a class="nav-link" href="/PWD_TPFinal/Vista/contacto/contacto.php">Contacto</a></li>
                  <li class="nav-item"><a class="nav-link" href="/PWD_TPFinal/Vista/login/login.php">Login</a></li>
               </ul>
            </div>
         </div>
      </nav>
   </header>