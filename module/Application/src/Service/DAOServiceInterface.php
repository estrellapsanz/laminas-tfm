<?php

namespace Application\Service;

use Application\Model\Entity\Estudiante;

interface DAOServiceInterface
{
    /** @return Estudiante */
    public function getEstudianteDAO();

}
