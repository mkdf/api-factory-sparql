<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace APIF\Sparql;

use APIF\Core\Service\SwaggerAddonManagerInterface;
use APIF\Sparql\Addon\SparqlAddon;
use Laminas\Mvc\MvcEvent;

class Module
{
    public function getConfig() : array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event)
    {
        // Initialisation
        $addonManager = $event->getApplication()->getServiceManager()->get(SwaggerAddonManagerInterface::class);
        $addonManager->registerAddon($event->getApplication()->getServiceManager()->get(SparqlAddon::class));
    }
}
