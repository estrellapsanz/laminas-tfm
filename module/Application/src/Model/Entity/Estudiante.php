<?php

namespace Application\Model\Entity;

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
        $query = "SELECT EXP.COD_PLAN, P.NOMBRE_PLAN,E.DOCUMENTO, 
                  E.NOMBRE, E.APELLIDO1, E.APELLIDO2, E.USUARIO
                  FROM 
                  TFM_ESTUDIANTE E , 
                  TFM_EXPEDIENTE EXP,
                  TFM_PLANES P
                  WHERE 
                  E.USUARIO=:P_USUARIO AND 
                  E.DOCUMENTO=EXP.DOCUMENTO_ESTUDIANTE AND
                  EXP.COD_PLAN=P.COD_PLAN";
        return $this->executeQueryRow($query, [':P_USUARIO' => $usuario]);
    }


}
