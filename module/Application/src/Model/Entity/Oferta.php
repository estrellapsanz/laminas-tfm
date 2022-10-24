<?php

namespace Application\Model\Entity;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;


class Oferta extends MasterEntity
{
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResulPrototype = null)
    {
        return parent::__construct('TFM_OFERTAS', $adapter, $databaseSchema, $selectResulPrototype);
    }

    /**
     * @param $cod_area
     * @return array
     * @example Devuelve las ofertas asociadas a un área concreta, junto con la inforamción del docente.
     */
    public function dameOfertasArea($cod_area)
    {
        $query = "SELECT O1.*, ALU.USUARIO_ESTUDIANTE, ALU.ESTADO AS ESTADO_ESTUDIANTE
         FROM (
         SELECT  O.DESCRIPCION, O.ESTADO, UPPER(O.TITULO) as TITULO, A.NOMBRE_AREA,
                 A.COD_AREA, D.APELLIDO1,D.APELLIDO2, D.NOMBRE,
                 D.USUARIO, P.NOMBRE_PLAN, P.COD_PLAN, O.COD_OFERTA
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
                 P.VIGENTE='S' )
         O1 LEFT JOIN
         TFM_ESTUDIANTE_OFERTA ALU
        ON
        O1.COD_OFERTA=ALU.COD_OFERTA
        ";
        return $this->executeQueryArray($query, [':P_COD_AREA' => $cod_area]);
    }


    /**
     * @param $curso
     * @param $cod_oferta
     * @param $estado
     * @param $usuario
     * @return int|void
     */
    public function insertaOferta($curso, $titulo, $descripcion, $usuario_creacion)
    {

        //todo ver que pasa con el estado
        $data = ['CURSO_ACADEMICO' => $curso, 'TITULO' => $titulo, 'DESCRIPCION' => $descripcion,
            'ESTADO' => 'Pendiente', 'USUARIO_CREACION' => $usuario_creacion];
        try {
            return $this->insert($data);

        } catch (\Exception $e) {
            var_dump($e);
            die;
            return -1;
        }
    }


    /**
     * @param $usuario
     * @return \Laminas\Db\ResultSet\ResultSetInterface|null
     */
    public function dameMisOfertasPropuestas($usuario)
    {

        $where = ['USUARIO_CREACION' => $usuario];
        try {
            return $this->select($where);

        } catch (\Exception $e) {
            return null;
        }
    }


}
