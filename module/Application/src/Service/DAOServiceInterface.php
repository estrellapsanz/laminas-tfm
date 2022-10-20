<?php

namespace Application\Service;

use Application\Model\Entity\Estudiante;
use Application\Model\Entity\Oferta;

interface DAOServiceInterface
{
    /** @return Estudiante */
    public function getEstudianteDAO();


    /** @return Oferta */
    public function getOfertaDAO();


}
