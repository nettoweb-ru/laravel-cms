<?php
namespace Netto\Registrars;

use Illuminate\Routing\ResourceRegistrar as OriginalRegistrar;
use Illuminate\Routing\Route;

class ResourceRegistrar extends OriginalRegistrar
{
    protected $resourceDefaults = ['index', 'create', 'store', 'edit', 'update', 'destroy', 'delete', 'list', 'toggle'];

    /**
     * @param $name
     * @param $base
     * @param $controller
     * @param $options
     * @return Route
     */
    protected function addResourceDelete($name, $base, $controller, $options): Route
    {
        $uri = $this->getResourceUri($name).'/delete';
        $action = $this->getResourceAction($name, $controller, 'delete', $options);

        return $this->router->post($uri, $action);
    }

    /**
     * @param $name
     * @param $base
     * @param $controller
     * @param $options
     * @return Route
     */
    protected function addResourceList($name, $base, $controller, $options): Route
    {
        $uri = $this->getResourceUri($name).'/list';
        $action = $this->getResourceAction($name, $controller, 'list', $options);

        return $this->router->get($uri, $action);
    }

    /**
     * @param $name
     * @param $base
     * @param $controller
     * @param $options
     * @return Route
     */
    protected function addResourceToggle($name, $base, $controller, $options): Route
    {
        $uri = $this->getResourceUri($name).'/toggle';
        $action = $this->getResourceAction($name, $controller, 'toggle', $options);

        return $this->router->post($uri, $action);
    }
}
