<?php

namespace Tfe\Controller;

use Laminas\Http\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Tfe\Service\DAOServiceInterface;
use Tfe\Service\SessionServiceInterface;

class MasterController extends AbstractActionController
{

    /** @var DAOServiceInterface $daoService */
    protected $daoService;

    /** @var SessionServiceInterface $sesion */
    protected $sesion;

    /**
     * @params DAOServiceInterface $daoService
     * @params SessionServiceInterface $sessionService
     */

    public function __construct(DAOServiceInterface $daoService, SessionServiceInterface $sessionService)
    {
        $this->daoService = $daoService;
        $this->sesion = $sessionService;

        //todo borrar cuando se implemente el AuthController
        $this->sesion->getSesion();
    }

    public function onDispatch(MvcEvent $e)
    {
        /** @var Request $request */
        return parent::onDispatch($e);
    }


}
