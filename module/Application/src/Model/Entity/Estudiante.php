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

    /**
     * @param $usuario
     * @return array
     */
    public function damePerfilEstudiante($usuario)
    {
        $query = "SELECT EXP.COD_PLAN, EXP.NUMORD, P.NOMBRE_PLAN,E.DOCUMENTO, 
                  E.NOMBRE, E.APELLIDO1, E.APELLIDO2, E.USUARIO, E.TELEFONO1,
                  A.COD_AREA, A.NOMBRE_AREA
                  FROM 
                  TFM_ESTUDIANTE E , 
                  TFM_EXPEDIENTE EXP,
                  TFM_PLANES P, 
                  TFM_AREA A
                  WHERE 
                  E.USUARIO=:P_USUARIO AND 
                  E.DOCUMENTO=EXP.DOCUMENTO_ESTUDIANTE AND
                  EXP.COD_PLAN=P.COD_PLAN AND
                  P.COD_AREA=A.COD_AREA";
        return $this->executeQueryArray($query, [':P_USUARIO' => $usuario]);
    }

    /**
     * @param $plan
     * @param $expediente
     * @return array
     */
    public function dameAsignaturasEstudiante($plan, $expediente)
    {

        $query = "SELECT 
                  LIN.CURSO_ACADEMICO, 
                  ROUND(LIN.NOTA_NUMERICA,2) NOTA_NUMERICA,
                  LIN.NOTA_ALFANUMERICA,
                  A.COD_ASIGNATURA, A.NOMBRE_ASIGNATURA, 
                  LIN.CERRADO
                  FROM 
                  TFM_ASIGNATURA A,
                  TFM_LINEAS_MATRICULA LIN
                  WHERE 
                  LIN.COD_PLAN=:P_COD_PLAN AND
                  LIN.EXP_NUMORD=:P_EXP_NUMORD AND
                  LIN.COD_ASIGNATURA=A.COD_ASIGNATURA
                ";
        return $this->executeQueryArray($query, [':P_COD_PLAN' => $plan, ':P_EXP_NUMORD' => $expediente]);
    }


}
