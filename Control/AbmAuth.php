<?php

class AbmAuth {

    public function registrarYLogin($param) {

        $abmUsuario = new AbmUsuario();
        $idUsuario = $abmUsuario->registrar($param);

        if (!$idUsuario) {
            return false; // No se creó el usuario
        }

        // Asigno rol cliente = idrol 2
        $abmUR = new AbmUsuarioRol();
        $abmUR->asignarRol($idUsuario, 2);

        // Iniciar sesión
        $session = new Session();
        $loginOK = $session->iniciar($param['usnombre'], $param['uspass']);

        return $loginOK; 
    }
}