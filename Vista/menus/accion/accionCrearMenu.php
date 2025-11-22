<?php
// Vista/menus/accion/accionCrearMenu.php

require_once dirname(__DIR__, 3) . "/configuracion.php";
require_once $GLOBALS['CONTROL_PATH'] . "AbmMenu.php";
require_once $GLOBALS['CONTROL_PATH'] . "AbmMenuRol.php";

$abmMenu = new AbmMenu();
$abmMenuRol = new AbmMenuRol();

// ------------------------
// DATOS DEL FORMULARIO
// ------------------------
$menombre = trim($_POST["menombre"] ?? "");
$tipo     = $_POST["tipo"] ?? "raiz";
$idPadre  = $_POST["idpadre"] ?? null;

if ($menombre === "") {
    header("Location: ../gestionMenus.php?ok=0");
    exit;
}

// ------------------------
// GENERAR SLUG
// ------------------------
$slug = strtolower(trim($menombre));
$slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
$slug = trim($slug, "-");

// =============================================================
// RUTAS
// =============================================================
// $ruta       = ruta WEB (lo que se guarda en la BD)
// $rutaFisica = ruta física en el disco bajo /Vista/secciones/
// =============================================================

// MENÚ PRINCIPAL
if ($tipo === "raiz") {
    $ruta       = $slug . ".php";                 // web
    $rutaFisica = "secciones/" . $slug . ".php";  // física
}

// SUBCATEGORÍA
if ($tipo === "sub" && $idPadre) {
    $padre = $abmMenu->buscar(['idmenu' => $idPadre])[0];

    // melink del padre (ej: celulares.php o celulares/apple.php)
    $padreRuta = $padre->getMeLink();
    $padreRuta = str_replace(".php", "", $padreRuta);

    $ruta       = $padreRuta . "/" . $slug . ".php";          // web
    $rutaFisica = "secciones/" . $ruta;                       // física
}

// ------------------------
// CREAR ARCHIVO FÍSICO
// ------------------------
$fullPath = $GLOBALS['VISTA_PATH'] . $rutaFisica;

$dir = dirname($fullPath);
if (!is_dir($dir)) mkdir($dir, 0777, true);

// ------------------------
// CONTENIDO DEL ARCHIVO
// ------------------------
$contenido = <<<PHP
<?php
require_once \$_SERVER['DOCUMENT_ROOT'] . "/PWD_TPFinal/configuracion.php";

include_once \$GLOBALS['VISTA_PATH'] . "estructura/cabecera.php";

\$abmProducto = new AbmProducto();

// Prefijo de categoría
\$prefijoCategoria = strtolower(str_replace("/", "_", str_replace(".php","", "$ruta"))) . "_";

// Listar productos
\$productos = \$abmProducto->listar("prodeshabilitado IS NULL");

// Normalizar nombres
function normalizar_nombre_img(\$nombre) {
    \$tmp = mb_strtolower(trim(\$nombre), "UTF-8");
    \$tmp = iconv("UTF-8","ASCII//TRANSLIT", \$tmp) ?: \$tmp;
    \$tmp = preg_replace("/[^a-z0-9 ]+/", "", \$tmp);
    return preg_replace("/\\s+/", "_", trim(\$tmp));
}

\$imgBaseUrl = \$GLOBALS['VISTA_URL'] . "imagenes/productos/";
\$imgDir     = \$GLOBALS['VISTA_PATH'] . "imagenes/productos/";

// Filtrar productos
\$productosFiltrados = [];
foreach (\$productos as \$p) {
    \$nombreBD = strtolower(\$p->getProNombre());
    if (!str_starts_with(\$nombreBD, \$prefijoCategoria)) continue;

    \$partes = explode("_", \$nombreBD);
    \$nombreReal = end(\$partes);

    \$productosFiltrados[] = [
        "obj"        => \$p,
        "nombreReal" => \$nombreReal,
        "nombreImg"  => normalizar_nombre_img(\$nombreReal)
            ];
        }
        ?>
                            <div class='container mt-4 pt-4'>
    <h1 class='mb-4'><?= htmlspecialchars("$menombre") ?></h1>

    <div class='row g-3'>
        <?php if (empty(\$productosFiltrados)): ?>
            <p class='text-muted'>No hay productos en esta sección.</p>
        <?php else: ?>
            <?php foreach (\$productosFiltrados as \$prod):
                \$p = \$prod["obj"];
                \$nombreReal = \$prod["nombreReal"];

                \$imagenBD = \$p->getProImagen();
                \$imagenURL = (!\$imagenBD || !file_exists(\$imgDir . \$imagenBD))
                              ? \$imgBaseUrl . "no-image.jpeg"
                              : \$imgBaseUrl . \$imagenBD;

                \$precio = (float) \$p->getProPrecio();
                \$stock  = (int) \$p->getProCantStock();
            ?>
            <div class='col-md-4 col-lg-3'>
                <div class='card shadow-sm h-100'>
                    <img src='<?= \$imagenURL ?>' class='card-img-top' alt='<?= htmlspecialchars(\$nombreReal) ?>'>
                    <div class='card-body'>
                        <h5 class='card-title'><?= htmlspecialchars(\$nombreReal) ?></h5>
                        <p class='text-success fw-bold fs-5'>\$<?= number_format(\$precio, 2, ',', '.') ?></p>

                        <p class='small text-muted'>
                        <?= nl2br(htmlspecialchars(\$p->getProDetalle())) ?>
                        </p>

                            <p class='text-muted'>Stock: <?= \$stock ?></p>

                            <a href='<?= \$GLOBALS['VISTA_URL'] ?>compra/accion/agregarCarrito.php?id=<?= \$p->getIdProducto() ?>'
                            class='btn btn-warning w-100 <?= \$stock <= 0 ? "disabled" : "" ?>'>
                            <?= \$stock > 0 ? "Agregar al carrito" : "Sin stock" ?>
                            </a>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include_once \$GLOBALS['VISTA_PATH'] . "estructura/pie.php"; ?>
PHP;

file_put_contents($fullPath, $contenido);

// ------------------------
// GUARDAR MENÚ EN BD
// ------------------------
$datos = [
    "idmenu"         => null,
    "menombre"       => $menombre,
    "melink"         => $ruta,   // SOLO ruta web
    "medescripcion"  => $ruta,
    "idpadre"        => ($tipo === "sub") ? $idPadre : null,
    "medeshabilitado" => 0
];

$idNuevoMenu = $abmMenu->alta($datos);

// ------------------------
// PERMISOS PARA TODOS LOS ROLES
// ------------------------
foreach ([1, 2, 3, 4, 5] as $r) {
    $abmMenuRol->alta(["idmenu" => $idNuevoMenu, "idrol" => $r]);
}

header("Location: ../gestionMenus.php?ok=1");
exit;
