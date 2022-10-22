<?php

declare(strict_types=1);

namespace Application;

use Application\Service\DAOService;
use Application\Service\Factory\DAOServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'service_manager' => array(
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        /*   'invokables' => [
               'Application\Service\SessionServiceInterface' => 'Application\Service\SessionService'
           ],*/
        'factories' => [
            DAOService::class => DAOServiceFactory::class
        ]
    ),

    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'mi-perfil' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/mi-perfil',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'mi-perfil',
                    ],
                ],
            ],
            'solicitud' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/solicitud',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'solicitud',
                    ],
                ],
            ],
            'trabajos-ofertados' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/trabajos-ofertados[/:exito]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'trabajos-ofertados',
                    ],
                ],
            ],

            'guardar-solicitud-oferta' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/guardar-solicitud-oferta',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'guardar-solicitud-oferta',
                    ],
                ],
            ],
            'propuesta-oferta' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/propuesta-oferta',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'propuesta-oferta',
                    ],
                ],
            ],
            'solicitud-deposito' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/solicitud-deposito',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'solicitud-deposito',
                    ],
                ],
            ],
            'application' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\MasterControllerFactory::class,
            Controller\MasterController::class => Controller\Factory\MasterControllerFactory::class
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
