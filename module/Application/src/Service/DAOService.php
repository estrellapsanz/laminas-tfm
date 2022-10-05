<?php

namespace Application\Service;

use Application\Model\Entity\Estudiante;
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
}
