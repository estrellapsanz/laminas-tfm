<?php

namespace Tfe\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\Factory\FactoryInterface;

class DAOServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Adapter $adapter */
        $adapter = $container->get(Adapter::class);
        return new $requestedName($adapter);
    }
}
