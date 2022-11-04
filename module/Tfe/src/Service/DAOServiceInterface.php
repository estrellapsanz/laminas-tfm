<?php

namespace Tfe\Service;

use Tfe\Model\Entity\DatosAcademicos;
use Tfe\Model\Entity\Deposito;
use Tfe\Model\Entity\Estudiante;
use Tfe\Model\Entity\EstudianteOferta;
use Tfe\Model\Entity\Oferta;
use Tfe\Model\Entity\Parametros;

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
