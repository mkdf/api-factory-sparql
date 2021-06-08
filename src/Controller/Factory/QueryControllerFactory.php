<?php

namespace APIF\Sparql\Controller\Factory;

use APIF\Sparql\Controller\QueryController;
use APIF\Sparql\Repository\GraphRepositoryInterface;
use APIF\Core\Repository\APIFCoreRepositoryInterface;
use APIF\Sparql\Service\SPARQLQueryTypeInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class QueryControllerFactory implements FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get("Config");
        $repository = $container->get(GraphRepositoryInterface::class);
        $apif_repository = $container->get(APIFCoreRepositoryInterface::class);
        $sparqlTypeChecker = $container->get(SPARQLQueryTypeInterface::class);
        return new QueryController($repository,$apif_repository,$sparqlTypeChecker,$config);
    }
}