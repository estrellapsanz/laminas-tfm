<?php

namespace Tfe\Model\Entity;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;


class Area extends MasterEntity
{
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResulPrototype = null)
    {
        return parent::__construct('TFM_AREA', $adapter, $databaseSchema, $selectResulPrototype);
    }

    /**
     * @return array
     */
    public function getAreas()
    {
        $query = "SELECT DISTINCT 
                  A.COD_AREA, A.NOMBRE_AREA
                  FROM 
                  TFM_AREA A
                ORDER BY A.NOMBRE_AREA ASC
               ";
        return $this->executeQueryArray($query);
    }


}
