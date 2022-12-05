<?php

namespace Tfe\Model\Entity;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;


class EstudianteOferta extends MasterEntity
{
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResulPrototype = null)
    {
        return parent::__construct('TFM_ESTUDIANTE_OFERTA', $adapter, $databaseSchema, $selectResulPrototype);
    }

    /**
     * @param $cod_oferta
     * @param $estado
     * @param $usuario
     * @return bool|void
     */
    public function updateEstadoEstudiante($cod_oferta, $estado, $usuario)
    {
        $set = ['ESTADO' => $estado];
        $where = ['COD_OFERTA' => $cod_oferta, 'USUARIO_ESTUDIANTE' => $usuario];
        try {
            return $this->update($set, $where) >= 0;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $cod_oferta
     * @param $estado
     * @param $usuario
     * @return bool
     */
    public function updateObservacionesEstudiante($cod_oferta, $obs, $usuario)
    {
        $set = ['OBSERVACIONES_TRAMITACION' => $obs];
        $where = ['COD_OFERTA' => $cod_oferta, 'USUARIO_ESTUDIANTE' => $usuario];
        try {
            return $this->update($set, $where) >= 0;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $curso
     * @param $cod_plan
     * @param $usuario
     * @param $estado
     * @return \Laminas\Db\ResultSet\ResultSetInterface|null
     */
    public function getAsociacionEstudianteOferta($curso, $cod_plan, $usuario, $estado = null)
    {

        $query = 'SELECT * FROM TFM_ESTUDIANTE_OFERTA WHERE CURSO_ACADEMICO=:P_CURSO AND COD_PLAN=:P_COD_PLAN AND USUARIO_ESTUDIANTE=:P_USUARIO';
        $params = [
            ':P_CURSO' => $curso,
            ':P_COD_PLAN' => $cod_plan,
            ':P_USUARIO' => $usuario
        ];
        if (!empty($estado)) {
            $query = 'SELECT * FROM TFM_ESTUDIANTE_OFERTA WHERE CURSO_ACADEMICO=:P_CURSO AND COD_PLAN=:P_COD_PLAN AND USUARIO_ESTUDIANTE=:P_USUARIO AND
                      ESTADO=:P_ESTADO';
            $params = [
                ':P_ESTADO' => $estado
            ];
        }

        try {
            return $this->executeQueryRow($query, $params);

        } catch (\Exception $e) {

            /*echo('<pre>');
            var_dump($e);
            echo('</pre>');
            die;*/
            return null;
        }
    }


    /**
     * Devuelve las ofertas asociadas a un estudiante que aún no han sido depositadas
     * @param $usuario
     * @param $curso
     * @return array
     */
    public function getMisOfertasVigentes($usuario)
    {

        $query_OLD = "SELECT OFERTAS.*, DEF.COD_SOLICITUD FROM
    (SELECT ALU.COD_PLAN, UPPER(O.TITULO) AS TITULO, UPPER(O.DESCRIPCION) AS DESCRIPCION,
            D.USUARIO AS USUARIO_DOCENTE, CONCAT(D.NOMBRE,' ', D.APELLIDO1, ' ', D.APELLIDO2) AS NOMBRE_DOCENTE,
            CONCAT(E.NOMBRE,' ', E.APELLIDO1, ' ', E.APELLIDO2) AS NOMBRE_ESTUDIANTE,
            P.NOMBRE_PLAN, P.COD_AREA, A.NOMBRE_AREA, O.COD_OFERTA, E.USUARIO AS USUARIO_ESTUDIANTE
     FROM
         TFM_ESTUDIANTE_OFERTA ALU,
         TFM_OFERTAS O,
         TFM_PLANES P,
         TFM_DOCENTE D,
         TFM_ESTUDIANTE E,
         TFM_AREA A
     WHERE
             E.USUARIO=ALU.USUARIO_ESTUDIANTE AND
             ALU.USUARIO_ESTUDIANTE=:P_USUARIO AND
             P.COD_AREA=A.COD_AREA AND
             ALU.ESTADO='Validado' AND
             ALU.COD_OFERTA=O.COD_OFERTA AND
             O.ESTADO='Validada' AND
             ALU.COD_PLAN=P.COD_PLAN AND
             O.USUARIO_DOCENTE=D.USUARIO
    ) OFERTAS LEFT JOIN TFM_SOLICITUD_DEFENSA DEF ON
                OFERTAS.COD_OFERTA=DEF.COD_OFERTA AND
                (DEF.ESTADO=NULL) AND
                DEF.CURSO_ACADEMICO=(SELECT VALOR FROM TFM_PARAMETROS WHERE NOMBRE='CURSO_ACADEMICO')";


        $query = "SELECT ALU.COD_PLAN, UPPER(O.TITULO) AS TITULO, UPPER(O.DESCRIPCION) AS DESCRIPCION,
            D.USUARIO AS USUARIO_DOCENTE, CONCAT(D.NOMBRE,' ', D.APELLIDO1, ' ', D.APELLIDO2) AS NOMBRE_DOCENTE,
            CONCAT(E.NOMBRE,' ', E.APELLIDO1, ' ', E.APELLIDO2) AS NOMBRE_ESTUDIANTE,
            P.NOMBRE_PLAN, P.COD_AREA, A.NOMBRE_AREA, O.COD_OFERTA, E.USUARIO AS USUARIO_ESTUDIANTE
     FROM
         TFM_ESTUDIANTE_OFERTA ALU,
         TFM_OFERTAS O,
         TFM_PLANES P,
         TFM_DOCENTE D,
         TFM_ESTUDIANTE E,
         TFM_AREA A
     WHERE
             E.USUARIO=ALU.USUARIO_ESTUDIANTE AND
             ALU.USUARIO_ESTUDIANTE=:P_USUARIO AND
             P.COD_AREA=A.COD_AREA AND
             ALU.ESTADO='Validado' AND
             ALU.COD_OFERTA=O.COD_OFERTA AND
             O.ESTADO='Validada' AND
             ALU.COD_PLAN=P.COD_PLAN AND
             O.USUARIO_DOCENTE=D.USUARIO AND
             NOT EXISTS (SELECT * FROM TFM_SOLICITUD_DEFENSA WHERE COD_OFERTA=O.COD_OFERTA)
     ";

        return $this->executeQueryArray($query, [':P_USUARIO' => $usuario]);


    }


    /**
     * @param $usuario
     * @param $cod_oferta
     * @return bool
     */
    public function existeAsociacion($usuario, $cod_oferta = null, $cod_plan = null)
    {

        if (!empty($cod_oferta) && empty($cod_plan))
            $where = ['COD_OFERTA' => $cod_oferta, 'USUARIO_ESTUDIANTE' => $usuario];

        else if (empty($cod_oferta) && !empty($cod_plan))
            $where = ['COD_PLAN' => $cod_plan, 'USUARIO_ESTUDIANTE' => $usuario, 'ESTADO' => ['Validado', 'Pendiente']];

        try {
            return !empty($this->select($where)->toArray());

        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * @param $curso
     * @param $cod_oferta
     * @param $estado
     * @param $usuario
     * @param $cod_plan
     * @return bool
     */
    public function insertEstudianteOferta($curso, $cod_oferta, $estado, $usuario, $cod_plan)
    {
        $data = ['CURSO_ACADEMICO' => $curso, 'COD_OFERTA' => $cod_oferta, 'ESTADO' => $estado, 'USUARIO_ESTUDIANTE' => $usuario,
            'COD_PLAN' => $cod_plan];
        try {
            return $this->insert($data) > 0;

        } catch (\Exception $e) {
            // var_dump($e->getMessage());
            // die;
            return false;
        }
    }


}
