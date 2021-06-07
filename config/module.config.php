<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace APIF\Sparql;

use APIF\Sparql\Controller\Factory\IndexControllerFactory;
use APIF\Sparql\Controller\Factory\QueryControllerFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'query' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/query/:id/sparql',
                    'defaults' => [
                        'controller' => Controller\QueryController::class
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\QueryController::class  => QueryControllerFactory::class,
            Controller\IndexController::class => IndexControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'aliases' => [
            Repository\GraphRepositoryInterface::class => Repository\GraphRepository::class,
            Service\SPARQLQueryTypeInterface::class => Service\SPARQLQueryType::class,
        ],
        'factories' => [
            Repository\GraphRepository::class => Repository\Factory\GraphRepositoryFactory::class,
            Addon\SparqlAddon::class => Addon\Factory\SparqlAddonFactory::class,
            Service\SPARQLQueryType::class => Service\Factory\SPARQLQueryTypeFactory::class,
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
