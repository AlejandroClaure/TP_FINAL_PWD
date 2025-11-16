<?php
include_once 'configuracion.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php include_once $GLOBALS['VISTA_URL'] . 'estructura/cabecera.php'; ?>

<main class="mt-5 pt-5">
   <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-indicators">
         <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                 class="active" aria-current="true" aria-label="Slide 1"></button>
         <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                 aria-label="Slide 2"></button>
         <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                 aria-label="Slide 3"></button>
      </div>

      <div class="carousel-inner">
         <div class="carousel-item active">
            <img src="Vista/imagenes/iphone_17_pro_promo-scaled.jpg"
                 class="d-block w-100" alt="Promoción iPhone 17 Pro">
         </div>
         <div class="carousel-item">
            <img src="Vista/imagenes/samsungF54.png"
                 class="d-block w-100" alt="Samsung F54">
         </div>
         <div class="carousel-item">
            <img src="Vista/imagenes/samsungS25.jpg"
                 class="d-block w-100" alt="Samsung S25">
         </div>
      </div>

      <button class="carousel-control-prev" type="button"
              data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
         <span class="carousel-control-prev-icon" aria-hidden="true"></span>
         <span class="visually-hidden">Anterior</span>
      </button>
      <button class="carousel-control-next" type="button"
              data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
         <span class="carousel-control-next-icon" aria-hidden="true"></span>
         <span class="visually-hidden">Siguiente</span>
   </div>
   </button>
   <?php include_once 'Vista/producto/albumProductos.php'; ?>
   
</main>

<?php include_once 'Vista/estructura/pie.php'; ?>
