<?php

namespace Application\Model\Entity;

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
     * @return array
     */
    public function actualizarEstadoEstudiante($cod_oferta, $estado, $usuario)
    {
        //var_dump($cod_oferta, $estado, $usuario);
        //die;
        $set = ['ESTADO' => $estado];
        $where = ['COD_OFERTA' => $cod_oferta, 'USUARIO_ESTUDIANTE' => $usuario];
        try {
            return $this->update($set, $where);

        } catch (\Exception $e) {
            var_dump($e);
            die;
            return -1;
        }
    }

    /**
     * @param $cod_oferta
     * @param $estado
     * @param $usuario
     * @return int
     */
    public function insertaEstudianteOferta($curso, $cod_oferta, $estado, $usuario, $cod_plan)
    {
        /*echo('<pre>');
        var_dump($curso, $cod_oferta, $estado, $usuario, $cod_plan);
        echo('</pre>');
        die;*/
        $data = ['CURSO_ACADEMICO' => $curso, 'COD_OFERTA' => $cod_oferta, 'ESTADO' => $estado, 'USUARIO_ESTUDIANTE' => $usuario,
            'COD_PLAN' => $cod_plan];
        try {
            return $this->insert($data);

        } catch (\Exception $e) {
            var_dump($e->getMessage());
            die;
            return -1;
        }
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
     * @param $cod_plan
     * @param $usuario
     * @param $estado
     * @return \Laminas\Db\ResultSet\ResultSetInterface|null
     */
    public function dameAsociacionEstudianteOferta($curso, $cod_plan, $usuario, $estado = null)
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

            echo('<pre>');
            var_dump($e);
            echo('</pre>');
            die;
            return null;
        }


    }

}
