<?php

declare(strict_types=1);

namespace Application;

use Laminas\Db\Adapter\Adapter;
use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;

class Module
{
    public function  onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        try {
            $target = $e->getTarget();
            /** @var Adapter $dbInstance */
            $dbInstance = $target->getServiceManager()->get(Adapter::class);
            $dbInstance->getDriver()->getConnection()->connect();
        } catch (\Exception $e){
            var_dump('Database error connection:'.$e);
            die;
        }

    }

    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }
}
