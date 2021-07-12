<?php
declare(strict_types=1);

namespace Ads\Share\Route;


class RouteMatch
{

    private array $routeParams;
    private string $controller;
    private string $controllerMethod;

    /**
     * RouteParams constructor.
     * @param array $routeParams
     */
    public function __construct(array $routeParams)
    {
        $this->routeParams = $routeParams;
        $info = explode(':', $this->routeParams['_controller']);
        $this->controller = $info[0];
        $this->controllerMethod = $info[1];
    }


    public function getController(): string
    {
        return $this->controller;
    }

    public function getControllerMethod(): string
    {
        return $this->controllerMethod;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getParam(string $name)
    {
        return $this->routeParams[$name];
    }


    /**
     * @param string $name
     * @return int
     */
    public function getIntParam(string $name): int
    {
        return (int)$this->routeParams[$name];
    }
}