<?php

declare(strict_types=1);

namespace Tfe;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Tfe\Service\DAOService;
use Tfe\Service\Factory\DAOServiceFactory;

return [
    'service_manager' => array(
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'invokables' => [
            'Tfe\Service\SessionServiceInterface' => 'Tfe\Service\SessionService'
        ],
        'factories' => [
            DAOService::class => DAOServiceFactory::class
        ]
    ),

    'router' => [
        'routes' => [
            /*LOGIN*/
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'login',
                    ],
                ],
            ],
            'login-docente' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/login-docente',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'login',
                    ],
                ],
            ],
            'desconectar' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/desconectar',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'desconectar',
                    ],
                ],
            ],

            /*FIN LOGIN*/


            /*ESTUDIANTE*/
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/home',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'home',
                    ],
                ],
            ],
            'mi-expediente' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/mi-expediente',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'mi-expediente',
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
                'type' => Literal::class,
                'options' => [
                    'route' => '/trabajos-ofertados',
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

            /*FIN ESTUDIANTE*/

            /*DOCENTE*/
            'docente-alta-oferta' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/docente/alta-oferta',
                    'defaults' => [
                        'controller' => Controller\DocenteController::class,
                        'action' => 'alta-oferta',
                    ],
                ],
            ],

            'docente-trabajos-tutorizados' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/docente/trabajos-tutorizados',
                    'defaults' => [
                        'controller' => Controller\DocenteController::class,
                        'action' => 'trabajos-tutorizados',
                    ],
                ],
            ],


            'docente-guardar-tramitar-estudiante-oferta' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/docente/guardar-tramitar-oferta',
                    'defaults' => [
                        'controller' => Controller\DocenteController::class,
                        'action' => 'guardar-tramitar-estudiante-oferta',
                    ],
                ],
            ],


            'docente-solicitudes-deposito' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/docente/solicitudes-deposito',
                    'defaults' => [
                        'controller' => Controller\DocenteController::class,
                        'action' => 'solicitudes-deposito',
                    ],
                ],
            ],

            'docente-trabajos-calificados' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/docente/trabajos-calificados',
                    'defaults' => [
                        'controller' => Controller\DocenteController::class,
                        'action' => 'trabajos-calificados',
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
            Controller\DocenteController::class => Controller\Factory\MasterControllerFactory::class,
            Controller\MasterController::class => Controller\Factory\MasterControllerFactory::class,
            Controller\AuthController::class => Controller\Factory\MasterControllerFactory::class
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
            'tfe/index/index' => __DIR__ . '/../view/tfe/index/home.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
