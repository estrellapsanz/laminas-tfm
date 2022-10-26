<?php

/**
 *
 */

namespace Application\Util;


class Constantes
{
    const PRUEBAS = 'pruebasDev';
    const HOSTS_PRUEBAS = [
        'localhost',
    ];
    const FROM_URL = 'from_url_control';

    //LOGIN
    const ESTADO_OPERACION = 'estado_operacion';

    //MAILER
    const ESTADO_OPERACION_OK = "operacion_ok";
    const ESTADO_OPERACION_ERROR = "operacion_error";
    const ESTADO_OPERACION_NO_MATRICULADO = "estado_operacion_plan_no_matriculado";
    const USUARIO_NO_ENCONTRADO = 'usuario_no_encontrado';


    /* CONSTANTES PARA LAS OPERACIONES  */
    const ERROR_LOGIN_ERRONEO = "error_login_erroneo";
    const ERROR_NO_LOGIN_ERRONEO = "error_no_login_erroneo";
    const ERROR_SIN_PERMISOS = "error_sin_permisos";
    const ERROR_FALTAN_PARAMETROS = "error_faltan_parametros";
    const ERROR_PETICION_LOGIN = "error_peticion_login";
    const ESTADO_ACEPTADA = "Aceptada";
    const ESTADO_DENEGADA = "Denegada";
    const ESTADO_PENDIENTE = "Pendiente";
    const ESTADO_CANCELADO = "Cancelado";
    const ESTADO_TRAMITADA = 'Tramitada';

    /* ESTADOS */
    const NOMBRE_SESION = 'sesionTfm';
    const SESION_USUARIO = "usuario";
    const SESION_NOMBRE_USUARIO = "nombreUsuario";
    const SESION_USUARIO_ADMIN = "usuarioAdmin";
    const SESION_CLIENTE = "cliente";


    //NOMBRE SESION
    const SESION_MENU_CLIENTE = "menuCliente";
    const SITE_TLF = "telefono";
    const SITE_EMAIL = "correo";
    const SITE_TITULO = "Laminas-TFM";

    static function full_url()
    {
        $s = $_SERVER;
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true : false;
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
        $host = (isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST'])) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];
        return $protocol . '://' . $host . $port . $s['REQUEST_URI'];
    }

    static function rutaRoot()
    {
        return Constantes::full_host() . "/laminas-tfm/public";
    }

    //PRAMETROS DE SESION

    static function full_host()
    {
        $s = $_SERVER;
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true : false;
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
        $host = (isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST'])) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];
        return $protocol . '://' . $host . $port;
    }

    public static function rutaLogin()
    {
        return Constantes::full_host() . "/laminas-tfm/login";
    }

    //NOMBRE APP

    public static function rutaMailer()
    {
        return Constantes::full_host() . "/laminas-tfm/mailer";
    }


}
