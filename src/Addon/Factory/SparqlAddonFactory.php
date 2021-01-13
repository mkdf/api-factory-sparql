<?php


namespace APIF\Sparql\Addon\Factory;


use APIF\Sparql\Addon\SparqlAddon;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class SparqlAddonFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get("Config");
        return new SparqlAddon();
    }
}