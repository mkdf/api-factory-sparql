<?php

namespace APIF\Sparql\Repository\Factory;

use APIF\Sparql\Repository\GraphRepository;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class GraphRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get("Config");
        return new GraphRepository($config);
    }
}