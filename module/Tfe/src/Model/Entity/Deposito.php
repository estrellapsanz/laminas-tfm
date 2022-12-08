<?php

namespace Tfe\Model\Entity;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;


class Deposito extends MasterEntity
{
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResulPrototype = null)
    {
        return parent::__construct('TFM_SOLICITUD_DEFENSA', $adapter, $databaseSchema, $selectResulPrototype);
    }

    /**
     * @param $cod_solicitud
     * @param $estado
     * @return int
     */
    public function updateEstado($curso, $cod_solicitud, $cod_oferta, $estado)
    {
        $set = ['ESTADO' => $estado];
        $where = ['COD_OFERTA' => $cod_oferta, 'COD_SOLICITUD' => $cod_solicitud, 'CURSO_ACADEMICO' => $curso];

        try {
            $rs = $this->update($set, $where);
            return $rs;
        } catch (\Exception $e) {
            return -1;
        }
    }

    /**
     * @param $curso
     * @param $cod_solicitud
     * @param $cod_oferta
     * @param $estado
     * @return int
     */

    public function updateObservaciones($curso, $cod_solicitud, $cod_oferta, $obs)
    {
        $set = ['OBSERVACIONES' => $obs];
        $where = ['COD_OFERTA' => $cod_oferta, 'COD_SOLICITUD' => $cod_solicitud, 'CURSO_ACADEMICO' => $curso];

        try {
            $rs = $this->update($set, $where);
            return $rs;
        } catch (\Exception $e) {
            return -1;
        }
    }


    /**
     * @param $curso
     * @param $cod_solicitud
     * @param $cod_oferta
     * @param $estado
     * @return int
     */
    public function updateNota($curso, $cod_solicitud, $cod_oferta, $nota)
    {

        $set = ['NOTA_FINAL' => $nota];
        $where = ['COD_OFERTA' => $cod_oferta, 'COD_SOLICITUD' => $cod_solicitud, 'CURSO_ACADEMICO' => $curso];

        try {
            $rs = $this->update($set, $where);
            return $rs;
        } catch (\Exception $e) {
            return -1;
        }
    }

    /**
     * @param $curso
     * @param $cod_oferta
     * @param $ruta_fichero
     * @param $usuario_creacion
     * @return int|mixed
     */
    public function insertDeposito($curso, $cod_oferta, $ruta_fichero, $usuario_creacion)
    {

        $data = ['CURSO_ACADEMICO' => $curso, 'COD_OFERTA' => $cod_oferta,
            'RUTA_FICHERO' => $ruta_fichero, 'USUARIO_ESTUDIANTE' => $usuario_creacion];
        try {

            $exito = $this->insert($data);

            if ($exito) {
                return $this->select(
                    ['CURSO_ACADEMICO' => $curso,
                        'COD_OFERTA' => $cod_oferta,
                        'USUARIO_ESTUDIANTE' => $usuario_creacion,
                        'RUTA_FICHERO' => $ruta_fichero
                    ])->toArray()[0]['COD_SOLICITUD'];
            } else
                return -1;

        } catch (\Exception $e) {
            return -1;
        }
    }


    /**
     * @param $curso
     * @param $usuario
     * @return void
     */

    public function getMisDepositos($curso, $usuario)
    {
        $query = "SELECT * , DEF.ESTADO AS ESTADO_DEPOSITO, CONCAT(D.NOMBRE, ' ', D.APELLIDO1, ' ',D.APELLIDO2) AS NOMBRE_DOCENTE
                  FROM 
                  TFM_SOLICITUD_DEFENSA DEF, TFM_OFERTAS OFE, TFM_ESTUDIANTE_OFERTA ALU, TFM_PLANES P, TFM_DOCENTE D
                  WHERE 
                      DEF.CURSO_ACADEMICO=:P_CURSO AND 
                      DEF.USUARIO_ESTUDIANTE=:P_USUARIO AND
                      DEF.COD_OFERTA=OFE.COD_OFERTA AND
                      OFE.COD_OFERTA=ALU.COD_OFERTA AND
                      ALU.COD_PLAN=P.COD_PLAN AND
                      OFE.USUARIO_DOCENTE=D.USUARIO";
        return $this->executeQueryArray($query, [':P_CURSO' => $curso, ':P_USUARIO' => $usuario]);
    }


    /**
     * @param $cod_sol
     * @return array
     */
    public function getSolicitudDeposito($cod_sol)
    {
        $query = "SELECT DEP.*, 
                  CONCAT(D.NOMBRE,' ',D.APELLIDO1,' ',D.APELLIDO2) NOMBRE_DOCENTE,
                  D.USUARIO as USUARIO_DOCENTE, 
                  O.TITULO, O.SUBTITULO, O.DESCRIPCION
                  FROM 
                      TFM_SOLICITUD_DEFENSA DEP,
                      TFM_DOCENTE D ,
                      TFM_OFERTAS O 
                  WHERE 
                    DEP.COD_SOLICITUD=:P_COD AND
                    DEP.COD_OFERTA=O.COD_OFERTA AND
                    O.USUARIO_DOCENTE=D.USUARIO";
        try {
            return $this->executeQueryRow($query, [':P_COD' => $cod_sol]);
        } catch (\Exception $e) {
            return false;
        }
    }

}
