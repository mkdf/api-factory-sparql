<?php


namespace APIF\Sparql\Addon;


use APIF\Core\Service\SwaggerAddonInterface;
use APIF\Sparql\Controller\IndexController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver;

class SparqlAddon implements SwaggerAddonInterface
{
    private $active = true;
    public function __construct() {

    }
    public function getController() {
        return IndexController::class;
    }
    public function hasFeature() {
        return true;
    }
    public function getLabel() {
        return 'Sparql';
    }
    public function getTag() {
        return 'sparql';
    }
    public function getDescription() {
        return 'Perform a SPARQL query against the data';
    }
    public function getBody() {
        $renderer = new PhpRenderer();

        $resolver = new Resolver\AggregateResolver();
        $renderer->setResolver($resolver);
        $map = new Resolver\TemplateMapResolver([
            'swaggeraddonmain'      => __DIR__ . '/../../view/apif/sparql/partial/swaggeraddonmain.phtml',
        ]);
        $stack = new Resolver\TemplatePathStack([
            'script_paths' => [
                __DIR__ . '/../../view'
            ],
        ]);
        // Attach resolvers to the aggregate:
        $resolver
            ->attach($map)    // this will be consulted first, and is the fastest lookup
            ->attach($stack)  // filesystem-based lookup
            ->attach(new Resolver\RelativeFallbackResolver($map)) // allow short template names
            ->attach(new Resolver\RelativeFallbackResolver($stack));
        $model = new ViewModel(['tag' => $this->getTag()]);
        $model->setTemplate('swaggeraddonmain');
        $model->setTerminal(true);
        return $renderer->render($model);
    }
    public function isActive() {
        return $this->active;
    }
    public function setActive($bool) {
        $this->active = !!$bool;
    }
}