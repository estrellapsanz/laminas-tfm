<?php

namespace Application\Controller;

use Application\Service\DAOServiceInterface;
use Application\Service\SessionServiceInterface;
use Laminas\Http\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;


class MasterController extends AbstractActionController
{
    // const LAYOUT = 'layout/layout';

    /** @var DAOServiceInterface $daoService */
    protected $daoService;

    /** @var SessionServiceInterface $sesion */
    protected $sesion;

    /**
     * @params DAOServiceInterface $daoService
     */
    //public function __construct(DAOServiceInterface $daoService, SessionServiceInterface $sessionService)
    public function __construct(DAOServiceInterface $daoService)
    {
        $this->daoService = $daoService;
        //  $this->sesion = $sessionService;
    }

    public function onDispatch(MvcEvent $e)
    {
        /** @var Request $request */
        return parent::onDispatch($e);
    }


}
