<?php

namespace Tfe\Service;

use Laminas\Session\Container;
use Tfe\Util\Constantes;

class SessionService implements SessionServiceInterface
{
    /**
     * @var Container $sesion
     */
    private $sesion;

    public function getUser()
    {
        return $this->offsetGet(Constantes::SESION_USUARIO);
    }

    public function offsetGet($key)
    {
        return $this->getSesion()->offsetGet($key);
    }

    function getSesion()
    {

        if (!$this->sesion) {
            $this->sesion = new Container(Constantes::NOMBRE_SESION);

        }
        return $this->sesion;
    }

    public function offsetUnset($key)
    {
        var_dump($key);
        $this->getSesion()->offsetUnset($key);
    }

    public function isLogueado()
    {
        return $this->getSesion()->offsetExists(Constantes::SESION_USUARIO);
    }

    public function offsetExists($key)
    {
        return $this->getSesion()->offsetExists($key);
    }

    public function setEstadoOperacion($value)
    {
        $this->offsetSet(Constantes::ESTADO_OPERACION, $value);
    }

    public function offsetSet($key, $value)
    {
        $this->getSesion()->offsetSet($key, $value);
    }


    public function setUrlInSession($url)
    {
        $this->offsetSet(Constantes::CURRENT_URL, $url);
    }

    public function getUrlInSession()
    {
        return $this->offsetGet(Constantes::CURRENT_URL);
    }

    public function regenerateId()
    {
        $this->getSesion()->getManager()->regenerateId();
    }


}