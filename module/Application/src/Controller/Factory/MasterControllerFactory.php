<?php

namespace Application\Controller\Factory;

use Application\Service\DAOService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class MasterControllerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var DAOService $daoService */
        $daoService = $container->get(DAOService::class);
        return new $requestedName($daoService);
    }
}
