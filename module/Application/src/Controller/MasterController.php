<?php

namespace Application\Controller;

use Application\Service\DAOServiceInterface;
use Laminas\Http\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;


class MasterController extends AbstractActionController
{
   // const LAYOUT = 'layout/layout';

    /** @var DAOServiceInterface $daoService */
    protected $daoService;


    /**
     * @params DAOServiceInterface $daoService
     */
    public function __construct(DAOServiceInterface $daoService)
    {
        $this->daoService = $daoService;
    }

    public function onDispatch(MvcEvent $e)
    {
        /** @var Request $request */
        //$request = $this->getRequest();
        //$this->layout()->{Constantes::PRUEBAS} = $this->isDev($request->getUri());
        return parent::onDispatch($e);
    }


}
