<?php
// Incluimos todas las clases necesarias
include_once __DIR__ . '/AbmUsuario.php';
include_once __DIR__ . '/../Modelo/Usuario.php';
include_once __DIR__ . '/../Modelo/Rol.php';
include_once __DIR__ . '/AbmUsuarioRol.php';

class Session
{
    private $usuario;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si ya existe sesión, cargo usuario y roles
        if (isset($_SESSION['idusuario'])) {
            $abmUsuario = new AbmUsuario();
            $usuarios = $abmUsuario->buscar(['idusuario' => $_SESSION['idusuario']]);
            if (!empty($usuarios)) {
                $this->usuario = $usuarios[0];

                // Cargar roles como array de datos para sesión
                $this->usuario->cargarRoles();
                $_SESSION['roles'] = array_map(function ($rol) {
                    return $rol->getRoDescripcion();
                }, $this->usuario->getRoles());
            }
        }
    }

    /**
     * Inicia sesión si usuario y contraseña son correctos
     */
    public function iniciar($nombreUsuario, $psw)
    {
        $abmUsuario = new AbmUsuario();
        $usuarios = $abmUsuario->buscar(['usnombre' => $nombreUsuario]);

        if (count($usuarios) !== 1) {
            return false; // evita duplicados o inexistentes
        }

        $usuario = $usuarios[0];

        if (!password_verify($psw, $usuario->getUsPass())) {
            return false;
        }

        $_SESSION['idusuario'] = $usuario->getIdUsuario();
        $this->usuario = $usuario;

        $usuario->cargarRoles();
        $_SESSION['roles'] = array_map(fn($rol) => $rol->getRoDescripcion(), $usuario->getRoles());

        return true;
    }


    /**
     * Valida si la sesión está activa
     */
    public function validar()
    {
        return isset($_SESSION['idusuario']);
    }

    /**
     * Alias de validar() para compatibilidad con paginaSegura.php
     */
    public function activa()
    {
        return $this->validar();
    }

    /**
     * Devuelve el usuario logueado
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Cierra sesión
     */
    public function cerrar()
    {
        session_destroy();
        $_SESSION = [];
        $this->usuario = null;
    }

    /**
     * Verifica si el usuario tiene determinado rol
     */
    public function tieneRol($rolDescripcion)
    {
        return isset($_SESSION['roles']) && in_array($rolDescripcion, $_SESSION['roles']);
    }
}
