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
    public function insertaEstudianteOferta($curso, $cod_oferta, $estado, $usuario)
    {

        $data = ['CURSO_ACADEMICO' => $curso, 'COD_OFERTA' => $cod_oferta, 'ESTADO' => $estado, 'USUARIO_ESTUDIANTE' => $usuario];
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
     * @param $cod_oferta
     * @return bool
     */
    public function existeAsociacion($usuario, $cod_oferta)
    {

        $where = ['COD_OFERTA' => $cod_oferta, 'USUARIO_ESTUDIANTE' => $usuario];
        try {
            return !empty($this->select($where));

        } catch (\Exception $e) {
            return false;
        }
    }


}
