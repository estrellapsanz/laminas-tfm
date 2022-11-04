<?php

namespace Tfe\Service;


interface SessionServiceInterface
{
    function getSesion();

    public function getUser();

    public function offsetSet($key, $value);

    public function offsetExists($key);

    public function offsetGet($key);

    public function offsetUnset($key);

    public function isLogueado();

    public function setEstadoOperacion($value);


}
