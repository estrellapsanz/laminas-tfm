<?php

namespace Tfe\Model\Entity;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;


class Oferta extends MasterEntity
{
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResulPrototype = null)
    {
        return parent::__construct('TFM_OFERTAS', $adapter, $databaseSchema, $selectResulPrototype);
    }

    /**
     * @param $cod_oferta
     * @param $estado
     * @return bool|int
     */
    public function updateEstado($cod_oferta, $estado)
    {
        $set = ['ESTADO' => $estado];
        $where = ['COD_OFERTA' => $cod_oferta];
        try {
            return $this->update($set, $where) >= 0;
        } catch (\Exception $e) {
            return -false;
        }
    }

    /**
     * @param $cod_area
     * @return array
     * @example Devuelve las ofertas asociadas a un docente que imparte en un área concreta o ofertas asociadas a un área por el docente,
     * junto con la información del docente.
     */
    public function getOfertasArea($cod_area)
    {
        $query = "SELECT O1.*, ALU.USUARIO_ESTUDIANTE, ALU.ESTADO AS ESTADO_ESTUDIANTE, ALU.OBSERVACIONES_TRAMITACION
         FROM (
         SELECT  O.DESCRIPCION, O.ESTADO, UPPER(O.TITULO) as TITULO, A.NOMBRE_AREA,
                 A.COD_AREA, D.APELLIDO1,D.APELLIDO2, D.NOMBRE,
                 D.USUARIO, P.NOMBRE_PLAN, P.COD_PLAN, O.COD_OFERTA
         FROM
             TFM_OFERTAS O ,
             TFM_DOCENTE_PLAN DP,
             TFM_AREA A,
             TFM_DOCENTE D,
             TFM_PLANES P
         WHERE
                 O.USUARIO_DOCENTE=D.USUARIO AND
                 O.ESTADO<>'Anulada' AND
                 D.USUARIO=DP.USUARIO_DOCENTE AND
                 DP.COD_PLAN=P.COD_PLAN AND
                 P.COD_AREA=A.COD_AREA AND
                 A.COD_AREA=:P_COD_AREA AND
                 P.VIGENTE='S' )
         O1 LEFT JOIN
         TFM_ESTUDIANTE_OFERTA ALU
        ON
        O1.COD_OFERTA=ALU.COD_OFERTA
        ";
        return $this->executeQueryArray($query, [':P_COD_AREA' => $cod_area]);
    }

    /**
     * Devuelve las ofertas propuestas por un estudiante
     * @param $usuario
     * @return \Laminas\Db\ResultSet\ResultSetInterface|null
     */
    public function getMisOfertasPropuestas($usuario)
    {

        $query = "SELECT OO.*, 
                    CONCAT(D.NOMBRE,' ',D.APELLIDO1,' ',D.APELLIDO2) AS NOMBRE_DOCENTE,
                    D.USUARIO AS USUARIO_DOCENTE
                    FROM (
                      SELECT O.*,O.ESTADO AS ESTADO_OFERTA, ALU.COD_PLAN, P.NOMBRE_PLAN, ALU.ESTADO AS ESTADO_ESTUDIANTE , ALU.OBSERVACIONES_TRAMITACION
                      FROM 
                          TFM_OFERTAS O, TFM_ESTUDIANTE_OFERTA ALU, TFM_PLANES P
                      WHERE     
                          O.COD_OFERTA=ALU.COD_OFERTA AND
                          ALU.COD_PLAN=P.COD_PLAN AND
                          O.USUARIO_CREACION=:P_USER AND
                          ALU.ESTADO<>'Anulado' AND 
                          O.ESTADO<>'Anulada'
                      ) 
                          OO 
                  LEFT JOIN  TFM_DOCENTE D  ON 
                  OO.USUARIO_DOCENTE=D.USUARIO
                  ORDER BY OO.COD_OFERTA DESC";

        try {
            return $this->executeQueryArray($query, [':P_USER' => $usuario]);

        } catch (\Exception $e) {
            //var_dump($e->getMessage());
            //die;
            return null;
        }
    }


    /**
     * @param $curso
     * @param $cod_oferta
     * @param $estado
     * @param $usuario
     * @return int|void
     */
    public function insertOferta($curso, $area, $titulo, $subtitulo, $descripcion, $usuario_creacion, $usuario_estudiante = false)
    {

        $data = [
            'CURSO_ACADEMICO' => $curso,
            'COD_AREA' => $area,
            'TITULO' => $titulo,
            'SUBTITULO' => $subtitulo,
            'DESCRIPCION' => $descripcion,
            'ESTADO' => $usuario_estudiante ? 'Pendiente' : 'Validada',
            'USUARIO_CREACION' => $usuario_creacion,
            'USUARIO_DOCENTE' => $usuario_estudiante ? null : $usuario_creacion
        ];

        try {
            $exito = $this->insert($data);

            if ($exito) {
                return $this->select(
                    ['CURSO_ACADEMICO' => $curso,
                        'TITULO' => $titulo,
                        'USUARIO_CREACION' => $usuario_creacion,
                        'ESTADO' => $usuario_estudiante ? 'Pendiente' : 'Validada',
                    ])->toArray()[0]['COD_OFERTA'];
            } else
                return -1;

        } catch (\Exception $e) {
            return -1;
        }
    }

    public function getOferta($cod_oferta)
    {
        $query = "SELECT OFERTAS.*, CONCAT(D.NOMBRE,' ',D.APELLIDO1,' ',D.APELLIDO2) NOMBRE_DOCENTE
                FROM (SELECT * FROM TFM_OFERTAS O WHERE O.COD_OFERTA=:P_COD) OFERTAS 
                LEFT JOIN TFM_DOCENTE D ON OFERTAS.USUARIO_DOCENTE=D.USUARIO ";
        return $this->executeQueryRow($query, [':P_COD' => $cod_oferta]);
    }

    public function updateOferta($cod_oferta, $titulo, $subtitulo, $descripcion, $area)
    {

        $set = [
            'COD_AREA' => $area,
            'TITULO' => $titulo,
            'SUBTITULO' => $subtitulo,
            'DESCRIPCION' => $descripcion
        ];

        $where = ['COD_OFERTA' => $cod_oferta];
        return $this->update($set, $where) >= 0;

    }

    public function updateDocenteOferta($cod_oferta, $docente)
    {

        $set = ['USUARIO_DOCENTE' => $docente];

        $where = ['COD_OFERTA' => $cod_oferta];
        return $this->update($set, $where) >= 0;

    }

    public function deleteOferta($cod_oferta)
    {
        try {
            $rs = $this->delete(['COD_OFERTA' => $cod_oferta]);
            return $rs > 0;
        } catch (\Exception $e) {
            return false;
        }
    }


}
