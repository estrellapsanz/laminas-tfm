<?php

namespace Tfe\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Tfe\Service\DAOService;
use Tfe\Service\SessionServiceInterface;

class MasterControllerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var DAOService $daoService */
        $daoService = $container->get(DAOService::class);

        /** @var SessionServiceInterface $sessionService */
        $sessionService = $container->get(SessionServiceInterface::class);
        return new $requestedName($daoService, $sessionService);
    }
}
