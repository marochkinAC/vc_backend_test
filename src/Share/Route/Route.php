<?php
declare(strict_types=1);

namespace Ads\Share\Route;


use Ads\Share\Response\ErrorJsonResponse;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Route
{
    private RouteCollection $routeCollection;

    public function __construct(RouteCollection $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    public function run(Request $request): Response
    {
        try {
            $routeMatch = $this->match($request);
            $controller = $routeMatch->getController();
            $method = $routeMatch->getControllerMethod();
            return (new $controller($request, $routeMatch))->$method();
        } catch (ResourceNotFoundException $e) {
            return new ErrorJsonResponse('Not found', [], Response::HTTP_NOT_FOUND);
        } catch (MethodNotAllowedException $e) {
            return new ErrorJsonResponse('Method not allowed', []);
        } catch (Exception $e) {
            file_put_contents(ROOT . '/var/log.txt', $e->getMessage() . PHP_EOL, FILE_APPEND);
            return new ErrorJsonResponse('Internal error', [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function match(Request $request): RouteMatch
    {
        $requestContext = new RequestContext();
        $requestContext->fromRequest($request);
        $urlMatcher = new UrlMatcher($this->routeCollection, $requestContext);
        $result = $urlMatcher->match($request->getPathInfo());
        return new RouteMatch($result);
    }
}