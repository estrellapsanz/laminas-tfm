<?php

namespace Tfe\Model\Entity;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;


class Parametros extends MasterEntity
{
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResulPrototype = null)
    {
        return parent::__construct('TFM_PARAMETROS', $adapter, $databaseSchema, $selectResulPrototype);
    }

    /**
     * @param $nombre_parametro
     * @return mixed
     */
    public function dameParametroNombre($nombre_parametro)
    {

        $query = "SELECT VALOR FROM TFM_PARAMETROS WHERE NOMBRE=:P_NOMBRE";

        $rs = $this->executeQueryRow($query, [':P_NOMBRE' => $nombre_parametro]);

        if (!empty($rs))
            return $rs['VALOR'];
        else
            return null;
    }


}
