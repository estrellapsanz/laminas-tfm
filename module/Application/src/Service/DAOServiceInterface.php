<?php

namespace Application\Service;

use Application\Model\Entity\DatosAcademicos;
use Application\Model\Entity\Deposito;
use Application\Model\Entity\Estudiante;
use Application\Model\Entity\EstudianteOferta;
use Application\Model\Entity\Oferta;
use Application\Model\Entity\Parametros;

interface DAOServiceInterface
{
    /** @return Estudiante */
    public function getEstudianteDAO();


    /** @return Oferta */
    public function getOfertaDAO();

    /**
     * @return EstudianteOferta
     */
    public function getEstudianteOfertaDAO();

    /**
     * @return DatosAcademicos
     */
    public function getDatosAcademicosDAO();

    /**
     * @return Parametros
     */
    public function getParametrosDAO();

    /**
     * @return Deposito
     */
    public function getDepositoDAO();

}
