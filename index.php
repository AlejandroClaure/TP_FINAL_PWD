<?php
include_once 'configuracion.php';
include_once $GLOBALS['VISTA_PATH'].'estructura/cabecera.php';
?>
<main class="mt-5 pt-5">

<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">

   <!-- Indicadores -->
   <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
   </div>

   <!-- Imágenes -->
   <div class="carousel-inner">
      <div class="carousel-item active">
         <img src="Vista/imagenes/iphone_17_pro_promo-scaled.jpg" class="d-block w-100" alt="">
      </div>
      <div class="carousel-item">
         <img src="Vista/imagenes/samsungF54.png" class="d-block w-100" alt="">
      </div>
      <div class="carousel-item">
         <img src="Vista/imagenes/samsungS25.jpg" class="d-block w-100" alt="">
      </div>
   </div>

   <!-- Controles -->
   <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
   </button>

   <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
   </button>

</div>

<?php include_once 'Vista/producto/albumProductos.php'; ?>

</main>

<?php include_once $GLOBALS['VISTA_PATH'] . 'estructura/pie.php'; ?>

