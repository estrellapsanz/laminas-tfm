<?php

namespace Tfe\Model\Entity;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;


class Docente extends MasterEntity
{
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResulPrototype = null)
    {
        return parent::__construct('XXX', $adapter, $databaseSchema, $selectResulPrototype);
    }


    /**
     * @param $user
     * @return false|mixed
     */
    public function dameNombre($user)
    {

        $query = "SELECT * FROM TFM_DOCENTE WHERE USUARIO=:P_USER";
        $rs = $this->executeQueryRow($query, [':P_USER' => $user]);

        if (!empty($rs))
            return $rs['NOMBRE'] . ' ' . $rs['APELLIDO1'] . ' ' . $rs['APELLIDO2'];

        return null;

    }


    /**
     * @param $user
     * @return array
     */
    public function dameOfertasDocente($user)
    {

        $query = "    
                     SELECT OFE.*, ALU.COD_PLAN, ALU.ESTADO AS ESTADO_ESTUDIANTE, ALU.USUARIO_ESTUDIANTE, 
                     (SELECT A.NOMBRE_AREA FROM TFM_AREA A, TFM_PLANES P WHERE P.COD_PLAN=ALU.COD_PLAN AND P.COD_AREA=A.COD_AREA) AS NOMBRE_AREA,
                     (SELECT CONCAT(E.NOMBRE,' ',E.APELLIDO1,' ',E.APELLIDO2) FROM TFM_ESTUDIANTE E WHERE E.USUARIO=ALU.USUARIO_ESTUDIANTE) AS NOMBRE_ESTUDIANTE
                
                     FROM 
                    (
                        SELECT O.CURSO_ACADEMICO, O.TITULO, O.SUBTITULO, O.DESCRIPCION, O.COD_OFERTA, O.ESTADO
                        FROM 
                          TFM_OFERTAS O
                        WHERE 
                              O.USUARIO_DOCENTE=:P_USER 
                    )  OFE LEFT JOIN   TFM_ESTUDIANTE_OFERTA ALU ON 
                    OFE.COD_OFERTA=ALU.COD_OFERTA
                    ORDER BY OFE.CURSO_ACADEMICO DESC";
        return $this->executeQueryArray($query, [':P_USER' => $user]);

    }

}
