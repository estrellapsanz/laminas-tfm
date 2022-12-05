<?php

namespace Tfe\Model\Entity;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;


class DatosAcademicos extends MasterEntity
{
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResulPrototype = null)
    {
        return parent::__construct('TFM_PLANES', $adapter, $databaseSchema, $selectResulPrototype);
    }

    /**
     * @param $cod_plan
     * @return array
     */
    public function getDatosPlan($cod_plan)
    {
        $query = "SELECT P.COD_PLAN, P.NOMBRE_PLAN, P.COD_AREA, A.NOMBRE_AREA FROM
                  TFM_PLANES P, TFM_AREA A WHERE P.COD_PLAN=:P_PLAN AND P.COD_AREA=A.COD_AREA";
        return $this->executeQueryRow($query, [':P_PLAN' => $cod_plan]);
    }


}
