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

            $exito = $this->insert($data);

            if ($exito) {
                return $this->select(
                    ['CURSO_ACADEMICO' => $curso,
                        'TITULO' => $titulo,
                        'USUARIO_CREACION' => $usuario_creacion,
                        'ESTADO' => 'Pendiente'
                    ])->toArray()[0]['COD_OFERTA'];
            } else
                return -1;

        } catch (\Exception $e) {
            return -1;
        }
    }


    /**
     * @param $usuario
     * @return \Laminas\Db\ResultSet\ResultSetInterface|null
     */
    public function dameMisOfertasPropuestas($usuario)
    {

        $query = "SELECT OO.*, 
                    CONCAT(D.NOMBRE,' ',D.APELLIDO1,' ',D.APELLIDO2) AS NOMBRE_DOCENTE,
                    D.USUARIO AS USUARIO_DOCENTE
                    FROM (
                      SELECT O.*,O.ESTADO AS ESTADO_OFERTA, ALU.COD_PLAN, P.NOMBRE_PLAN, ALU.ESTADO AS ESTADO_ESTUDIANTE
                      FROM 
                          TFM_OFERTAS O, TFM_ESTUDIANTE_OFERTA ALU, TFM_PLANES P
                      WHERE     
                          O.COD_OFERTA=ALU.COD_OFERTA AND
                          ALU.COD_PLAN=P.COD_PLAN AND
                          O.USUARIO_CREACION=:P_USER 
                      ) 
                          OO 
                  LEFT JOIN  TFM_DOCENTE D  ON 
                  OO.USUARIO_DOCENTE=D.USUARIO";

        try {
            return $this->executeQueryArray($query, [':P_USER' => $usuario]);

        } catch (\Exception $e) {
            var_dump($e->getMessage());
            die;
            return null;
        }
    }

    /**
     * @param $cod_oferta
     * @param $estado
     * @return int
     */
    public function actualizaEstado($cod_oferta, $estado)
    {
        $set = ['ESTADO' => $estado];
        $where = ['COD_OFERTA' => $cod_oferta];
        return $this->update($set, $where);
    }


}
