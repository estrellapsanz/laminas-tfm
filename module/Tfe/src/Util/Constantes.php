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

    /*OFERTAS*/
    const OFERTA_ELIMINAR = "Eliminar";
    const ESTADO_OFERTA_ANULADA = "Anulada";
    const ESTADO_OFERTA_VALIDADA = "Validada";
    const ESTADO_OFERTA_VIGENTE = 'Vigente';
    const ESTADO_OFERTA_PENDIENTE = 'Pendiente';
    const ESTADO_OFERTA_DENEGADA = 'Denegada';

    /*ESTUDIANTE*/
    const ESTADO_ESTUDIANTE_ANULADO = "Anulado";
    const ESTADO_ESTUDIANTE_VALIDADO = "Validado";
    const ESTADO_ESTUDIANTE_DENEGADO = "Denegado";
    const ESTADO_ESTUDIANTE_PENDIENTE = "Pendiente";
    const ESTADO_CANCELADO = "Cancelado";

    /*DEPÓSITO*/
    const ESTADO_DEPOSITO_AUTORIZADO = 'Autorizado';
    const ESTADO_DEPOSITO_DENEGADO = 'Denegado';
    const ESTADO_DEPOSITO_CAMBIOS_SOLICITADOS = 'Cambios solicitados';
    const ESTADO_DEPOSITO_PENDIENTE = 'Pendiente';


    const ERROR_LOGIN_ERRONEO = "error_login_erroneo";
    const ERROR_NO_LOGIN_ERRONEO = "error_no_login_erroneo";
    const ERROR_SIN_PERMISOS = "error_sin_permisos";
    const ERROR_FALTAN_PARAMETROS = "error_faltan_parametros";
    const ERROR_PETICION_LOGIN = "error_peticion_login";

    /*RUTAS*/
    const CURRENT_URL = 'current_url';
    //ESTUDIANTE
    const RUTA_HOME_ESTUDIANTE = 'home';
    const RUTA_EXPEDIENTE_ESTUDIANTE = 'mi-expediente';
    const RUTA_PROPONER_OFERTA_ESTUDIANTE = 'propuesta-oferta';
    const RUTA_SOLICITAR_OFERTA_ESTUDIANTE = 'solicitud-deposito';
    const RUTA_TRABAJOS_OFERTADOS = 'trabajos-ofertados';
    //DOCENTE
    const RUTA_ALTA_OFERTA_DOCENTE = 'docente-alta-oferta';
    const RUTA_TRABAJOS_TUTORIZADOS_DOCENTE = 'docente-trabajos-tutorizados';
    const RUTA_SOLICITUDES_DEPOSITO_DOCENTE = 'docente-solicitudes-deposito';
    const RUTA_TRABAJOS_CALIFICADOS_DOCENTE = 'docente-trabajos-calificados';


    const RUTA_DEPOSITAR_ESTUDIANTE = 'solicitud-deposito';


    /*CONSTANTES PARA INFORMACIÓN DE USUARIO EN SESIÓN*/
    const NOMBRE_SESION = 'sesionTfm';
    const SESION_USUARIO = "usuario";
    const SESION_NOMBRE_USUARIO = "nombreUsuario";
    const SESION_USUARIO_DOCENTE = "usuarioDocente";
    const SESION_ESTUDIANTE = "estudiante";
    const SESION_DOCENTE = "docente";


    //NOMBRE SESION
    const SITE_TITULO = "Laminas-TFM";
    const NOMBRE_COOKIE = "uoc_login";
    const DOMINIO_COOKIE = ".uoc.es";


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
