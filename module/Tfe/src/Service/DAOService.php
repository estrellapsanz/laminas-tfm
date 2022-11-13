<?php

namespace Tfe\Service;

use Laminas\Db\Adapter\Adapter;
use Tfe\Model\Entity\Area;
use Tfe\Model\Entity\DatosAcademicos;
use Tfe\Model\Entity\Deposito;
use Tfe\Model\Entity\Docente;
use Tfe\Model\Entity\Estudiante;
use Tfe\Model\Entity\EstudianteOferta;
use Tfe\Model\Entity\Oferta;
use Tfe\Model\Entity\Parametros;

class DAOService implements DAOServiceInterface
{
    /** @var Adapter */
    private $dbAdapter;

    /**
     * @var Area
     */

    private $areaDAO;
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
     * @var Docente
     */
    private $docenteDAO;

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
        if (!$this->parametrosDAO)
            $this->parametrosDAO = new Parametros($this->dbAdapter);
        return $this->parametrosDAO;
    }

    /**
     * @return Deposito
     */
    public function getDepositoDAO()
    {
        if (!$this->depositoDAO)
            $this->depositoDAO = new Deposito($this->dbAdapter);
        return $this->depositoDAO;
    }

    /**
     * @return Docente
     */
    public function getDocenteDAO()
    {
        if (!$this->docenteDAO)
            $this->docenteDAO = new Docente($this->dbAdapter);
        return $this->docenteDAO;
    }

    /**
     * @return Area
     */
    public function getAreasDAO()
    {
        if (!$this->areaDAO)
            $this->areaDAO = new Area($this->dbAdapter);
        return $this->areaDAO;
    }
}
