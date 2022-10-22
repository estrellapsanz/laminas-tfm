<?php

namespace Application\Service;

use Application\Model\Filtro;
use Application\Model\User;

interface SessionServiceInterface
{
    function getSesion();

    public function getUser();

    public function getUserAdmin();

    public function offsetSet($key, $value);

    public function offsetExists($key);

    public function offsetGet($key);

    public function offsetUnset($key);

    public function regenerateId();

    public function getSesionId();

    public function setUrlInSession($url);

    public function getUrlInSession();

    public function setEstadoOperacion($value);

    public function setMenuActive($menu);

    public function setMenuActiveAdmin($menu);

    public function isAdmin();

    public function setIsAdmin($isAdmin);

    public function getLoginUserAdmin();

    public function setLoginUserAdmin($user);

    /** @return User */
    public function getUserToLog();

    /**
     * Para mantener activo el filtro si se esta en la misma vista
     * @param bool $filtro_solicitudes
     * @param bool $filtro_reparto
     */
    public function setVistaFiltro($filtro_solicitudes = false, $filtro_reparto = false);

    public function setParamsFiltro($post = []);

    /**
     * @return Filtro
     */
    public function getFiltro();
}
