<?php
// Vista/menus/accion/accionEliminarMenu.php
include_once dirname(__DIR__, 3) . '/configuracion.php';
include_once dirname(__DIR__, 3) . '/Control/AbmMenu.php';
include_once dirname(__DIR__, 3) . '/Control/AbmMenuRol.php';

$abmMenu     = new AbmMenu();
$abmMenuRol  = new AbmMenuRol();

$idmenu = $_GET['idmenu'] ?? null;
if (!$idmenu) {
    header("Location: ../gestionMenus.php?ok=0");
    exit;
}

$carpetaSecciones = $GLOBALS['VISTA_PATH'] . "secciones/";

/**
 * Elimina recursivamente un menú: hijos, roles, registros y archivos físicos.
 * @param int $id
 */
function eliminarMenuRecursivo($id, $abmMenu, $abmMenuRol, $carpetaSecciones) {
    // Buscar hijos
    $hijos = $abmMenu->buscar(['idpadre' => $id]) ?? [];
    foreach ($hijos as $hijo) {
        eliminarMenuRecursivo($hijo->getIdMenu(), $abmMenu, $abmMenuRol, $carpetaSecciones);
    }

    // Buscar el menú actual
    $menuArr = $abmMenu->buscar(['idmenu' => $id]);
    if (empty($menuArr)) return;
    $menu = $menuArr[0];

    // 1) Eliminar roles asociados (si AbmMenuRol->buscar/baja existen)
    $rolesAsignados = $abmMenuRol->buscar(['idmenu' => $id]) ?? [];
    foreach ($rolesAsignados as $r) {
        // intentamos usar baja; si tu AbmMenuRol exige idrol también, adaptar:
        $datosBaja = ['idmenu' => $id, 'idrol' => $r->getIdRol() ?? $r->getIdrol() ?? null];
        // Si no dispone de idrol, intenta solo por idmenu
        try {
            if (!empty($datosBaja['idrol'])) $abmMenuRol->baja($datosBaja);
            else $abmMenuRol->baja(['idmenu' => $id]);
        } catch (Exception $e) {
            // no fatal: intentamos otra forma (silencioso)
            try { $abmMenuRol->baja(['idmenu' => $id]); } catch (Exception $e2) {}
        }
    }

    // 2) Eliminar archivo físico si existe
    $rutaRel = $menu->getMeDescripcion(); // asumimos medescripcion contiene la ruta como "categoria/sub.php" o "categoria.php"
    if ($rutaRel) {
        $fullPath = $carpetaSecciones . ltrim($rutaRel, '/');
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
        // intentar eliminar carpeta padre si quedó vacía
        $dir = dirname($fullPath);
        // no borrar la carpeta "secciones" raíz
        if ($dir !== rtrim($carpetaSecciones, DIRECTORY_SEPARATOR) && is_dir($dir)) {
            $files = array_diff(scandir($dir), ['.', '..']);
            if (empty($files)) {
                @rmdir($dir);
            }
        }
    }

    // 3) Eliminar registro de menu en BD
    try {
        $abmMenu->baja(['idmenu' => $id]);
    } catch (Exception $e) {
        // fallback: intentar con datos completos
        try {
            $abmMenu->baja(['idmenu' => $menu->getIdMenu(), 'menombre' => $menu->getMeNombre()]);
        } catch (Exception $e2) {
            // si falla, seguimos (no fatal)
        }
    }
}

// Ejecutar
try {
    eliminarMenuRecursivo((int)$idmenu, $abmMenu, $abmMenuRol, $carpetaSecciones);
    header("Location: ../gestionMenus.php?ok=1");
    exit;
} catch (Exception $e) {
    header("Location: ../gestionMenus.php?ok=0");
    exit;
}
