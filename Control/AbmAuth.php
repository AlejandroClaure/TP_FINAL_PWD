<?php
require_once '../../../configuracion.php';

class AbmAuth {

    public function registrarYLogin($data)
{
    $abmUsuario = new AbmUsuario();

    // 1) Validar si el mail ya existe
    $existe = $abmUsuario->buscar(["usmail" => $data['usmail']]);

    if (count($existe) > 0) {
        error_log("Registro fallido: email ya registrado - " . $data['usmail']);
        return [
            'success' => false,
            'error'   => 'email_duplicado'
        ];
    }

    // 2) Crear el usuario
    $idUsuario = $abmUsuario->registrar($data);

    if (!$idUsuario) {
        error_log("No se pudo crear el usuario en la base de datos");
        return [
            'success' => false,
            'error'   => 'creacion_fallida'
        ];
    }

    error_log("Usuario creado con ID: $idUsuario");

    // 3) Asignar rol de cliente (idrol = 2)
    $abmUR = new AbmUsuarioRol();
    $rolAsignado = $abmUR->asignarRol($idUsuario, 2);

    if (!$rolAsignado) {
        error_log("ADVERTENCIA: No se pudo asignar el rol al usuario $idUsuario");
        // No lo consideramos error crítico, el usuario ya existe y puede loguearse
    } else {
        error_log("Rol cliente asignado correctamente");
    }

    // 4) INICIAR SESIÓN AUTOMÁTICAMENTE
    $session = new Session();

    // IMPORTANTE: usar el nombre de usuario y contraseña TAL CUAL se guardaron
    // Si en registrar() haces hash de la contraseña, aquí debes usar la contraseña SIN hash
    // (asumiendo que el método iniciar() del Session ya hace la verificación con hash)
    $loginOk = $session->iniciar($data['usnombre'], $data['uspass']);

    if (!$loginOk) {
        error_log("Login automático falló después del registro (usuario: {$data['usnombre']})");
        // Aunque sea raro, si falla el login automático, devolvemos error
        return [
            'success' => false,
            'error'   => 'login_fallido'
        ];
    }

    error_log("Login automático exitoso para el usuario: {$data['usnombre']}");
    //Tod o bienn
    return [
        'success' => true
    ];
}

}