<?php

namespace Application\Service;

use Application\Model\Entity\DatosAcademicos;
use Application\Model\Entity\Deposito;
use Application\Model\Entity\Estudiante;
use Application\Model\Entity\EstudianteOferta;
use Application\Model\Entity\Oferta;
use Application\Model\Entity\Parametros;
use Laminas\Db\Adapter\Adapter;

class DAOService implements DAOServiceInterface
{
    /** @var Adapter */
    private $dbAdapter;

    /**
     * @var Estudiante
     */
    private $estudianteDAO;

    /**
     * @var Oferta
     */
    private $ofertaDAO;

    /**
     * @var EstudianteOferta
     */
    private $estudianteOfertaDAO;


    /**
     * @var DatosAcademicos
     */
    private $datosAcademicosDAO;

    /**
     * @var Parametros
     */
    private $parametrosDAO;
    /**
     * @var Deposito
     */
    private $depositoDAO;

    /**
     * DAOService constructor.
     * @param Adapter $dbAdapter
     */
    public function __construct($dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    /** @return Estudiante */

    public function getEstudianteDAO()
    {
        if (!$this->estudianteDAO)
            $this->estudianteDAO = new Estudiante($this->dbAdapter);
        return $this->estudianteDAO;
    }

    /**
     * @return Oferta
     */
    public function getOfertaDAO()
    {
        if (!$this->ofertaDAO)
            $this->ofertaDAO = new Oferta($this->dbAdapter);
        return $this->ofertaDAO;
    }

    /**
     * @return EstudianteOferta
     */
    public function getEstudianteOfertaDAO()
    {
        if (!$this->estudianteOfertaDAO)
            $this->estudianteOfertaDAO = new EstudianteOferta($this->dbAdapter);
        return $this->estudianteOfertaDAO;
    }

    /**
     * @return DatosAcademicos
     */
    public function getDatosAcademicosDAO()
    {
        if (!$this->datosAcademicosDAO)
            $this->datosAcademicosDAO = new DatosAcademicos($this->dbAdapter);
        return $this->datosAcademicosDAO;
    }

    /**
     * @return Parametros
     */
    public function getParametrosDAO()
    {
        if (!$this->getParametrosDAO)
            $this->getParametrosDAO = new Parametros($this->dbAdapter);
        return $this->getParametrosDAO;
    }

    /**
     * @return Deposito
     */
    public function getDepositoDAO()
    {
        if (!$this->getDepositoDAO)
            $this->getDepositoDAO = new Deposito($this->dbAdapter);
        return $this->getDepositoDAO;
    }
}
