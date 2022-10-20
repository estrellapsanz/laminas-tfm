<?php

namespace Application\Model\Entity;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;


class Oferta extends MasterEntity
{
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResulPrototype = null)
    {
        return parent::__construct('XXX', $adapter, $databaseSchema, $selectResulPrototype);
    }

    /**
     * @param $cod_area
     * @return array
     */
    public function dameOfertasArea($cod_area)
    {
        $query = "SELECT  O.DESCRIPCION, O.ESTADO, UPPER(O.TITULO) as TITULO, A.NOMBRE_AREA,
                  A.COD_AREA, D.APELLIDO1,D.APELLIDO2, D.NOMBRE,
                  D.USUARIO, P.NOMBRE_PLAN, P.COD_PLAN
                  FROM 
                  TFM_OFERTAS O , 
                  TFM_DOCENTE_PLAN  DP, 
                  TFM_AREA A, 
                  TFM_DOCENTE D,
                  TFM_PLANES P
                  WHERE 
                  O.USUARIO_DOCENTE=D.USUARIO AND
                  D.USUARIO=DP.USUARIO_DOCENTE AND
                  DP.COD_PLAN=P.COD_PLAN AND
                  P.COD_AREA=A.COD_AREA AND
                  A.COD_AREA=:P_COD_AREA AND 
                  P.VIGENTE='S'
                  ";
        return $this->executeQueryArray($query, [':P_COD_AREA' => $cod_area]);
    }


}
