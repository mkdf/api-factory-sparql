<?php


namespace APIF\Sparql\Service\Factory;

use APIF\Sparql\Service\SPARQLQueryType;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class SPARQLQueryTypeFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get("Config");
        return new SPARQLQueryType($container);
    }
}