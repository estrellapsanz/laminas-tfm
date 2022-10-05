<?php

namespace Application\Model\Entity;

use Exception;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;


class Estudiante extends MasterEntity
{
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResulPrototype = null)
    {
        return parent::__construct('XXX', $adapter, $databaseSchema, $selectResulPrototype);
    }

    public function getEstudiante($usuario)
    {
        $query = "SELECT * FROM TFM_ESTUDIANTE WHERE USUARIO=:P_USUARIO";
        return $this->executeQueryRow($query,[':P_USUARIO'=>$usuario]);
    }


}
