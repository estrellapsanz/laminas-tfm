<?php

/**
 *
 */

namespace Tfe\Util;


class Constantes
{


    /*ESTADO DE LAS OPERACIONES*/
    const ESTADO_OPERACION = 'estado_operacion';
    const ESTADO_OPERACION_OK = "operacion_ok";
    const ESTADO_OPERACION_ERROR = "operacion_error";


    /* CONSTANTES PARA LAS ACCIONES  */
    const ACCION_ANULAR_OFERTA = 'anular';
    const ACCION_SOLICITAR_OFERTA = 'solicitar';

    /*CONSTANTES PARA BASE DE DATOS*/
    const PARAMETRO_CURSO_ACADEMICO = 'CURSO_ACADEMICO';
    const ESTADO_OFERTA_ANULADA = "Anulada";
    const ESTADO_OFERTA_VALIDADA = "Validada";
    const ESTADO_OFERTA_VIGENTE = 'Vigente';
    const  ESTADO_OFERTA_PENDIENTE = 'Pendiente';
    const ESTADO_ESTUDIANTE_ANULADO = "Anulado";
    const ESTADO_DENEGADA = "Denegada";
    const ESTADO_ESTUDIANTE_PENDIENTE = "Pendiente";
    const ESTADO_CANCELADO = "Cancelado";


    const ERROR_LOGIN_ERRONEO = "error_login_erroneo";
    const ERROR_NO_LOGIN_ERRONEO = "error_no_login_erroneo";
    const ERROR_SIN_PERMISOS = "error_sin_permisos";
    const ERROR_FALTAN_PARAMETROS = "error_faltan_parametros";
    const ERROR_PETICION_LOGIN = "error_peticion_login";

    /*RUTAS*/
    const CURRENT_URL = 'current_url';
    const RUTA_HOME_ESTUDIANTE = 'home';
    const RUTA_PERFIL_ESTUDIANTE = 'mi-perfil';
    const RUTA_PROPONER_OFERTA_ESTUDIANTE = 'propuesta-oferta';
    const RUTA_SOLICITAR_OFERTA_ESTUDIANTE = 'solicitud-deposito';
    const RUTA_TRABAJOS_OFERTADOS = 'trabajos-ofertados';


    const RUTA_DEPOSITAR_ESTUDIANTE = 'solicitud-deposito';


    /*CONSTANTES PARA INFORMACIÓN DE USUARIO EN SESIÓN*/
    const NOMBRE_SESION = 'sesionTfm';
    const SESION_USUARIO = "usuario";
    const SESION_NOMBRE_USUARIO = "nombreUsuario";
    const SESION_USUARIO_DOCENTE = "usuarioDocentes";
    const SESION_ESTUDIANTE = "estudiante";
    const SESION_DOCENTE = "docente";


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
        $protocol = Constantes . phpsubstr($sp, 0, strpos($sp, '/'));
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
        $protocol = Constantes . phpsubstr($sp, 0, strpos($sp, '/'));
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
