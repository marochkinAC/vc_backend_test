<?php
declare(strict_types=1);

namespace Ads\Core\Controller;

use Ads\Share\Route\RouteMatch;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractController
 * Родительский контроллер для всех методов API
 * @package Ads\Core\Controller
 */
abstract class AbstractController
{
    protected Request $request;
    protected RouteMatch $routeMatch;

    /**
     * AbstractController constructor.
     * @param Request $request
     * @param RouteMatch $routeMatch
     */
    public function __construct(Request $request, RouteMatch $routeMatch)
    {
        $this->request = $request;
        $this->routeMatch = $routeMatch;
    }
}