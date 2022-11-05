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
    public function actualizaEstado($cod_solicitud, $estado)
    {
        $set = ['ESTADO' => $estado];
        $where = ['COD_OFERTA' => $cod_solicitud];
        return $this->update($set, $where);
    }


    /**
     * @param $curso
     * @param $cod_oferta
     * @param $ruta_fichero
     * @param $usuario_creacion
     * @return int|mixed
     */
    public function insertaDeposito($curso, $cod_oferta, $ruta_fichero, $usuario_creacion)
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
            var_dump($e->getMessage());
            die;
            return -1;
        }
    }


    /**
     * @param $curso
     * @param $usuario
     * @return void
     */

    public function dameMisDepositos($curso, $usuario)
    {
        $query = "SELECT * FROM TFM_SOLICITUD_DEFENSA WHERE CURSO_ACADEMICO=:P_CURSO AND USUARIO_ESTUDIANTE=:P_USUARIO";

        return $this->executeQueryArray($query, [':P_CURSO' => $curso, ':P_USUARIO' => $usuario]);
    }

}
