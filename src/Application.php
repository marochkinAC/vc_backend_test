<?php
declare(strict_types=1);

namespace Ads;


//use Ads\Request\Request;
use Ads\Share\Response\JsonResponse;
use Ads\Share\Route\Route;
use Exception;
use Pimple\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Application
{
    private Container $container;
    private static ?Application $instance = null;


    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }

    public static function i(): self
    {
        if (static::$instance === null) {
            static::$instance = new static();
            static::$instance->container = new Container();
            static::$instance->bootstrap();
        }
        return static::$instance;

    }

    public function run(Request $request): Response
    {
        /**
         * @var Route $router
         */
        $router = $this->container[Route::class];
        return $router->run($request);
    }

    private function bootstrap()
    {
        $providers = require ROOT . '/config/providers.php';
        foreach ($providers as $providerClassName) {
            /**
             * @var Ads\Core\ServiceProvider\IServiceProvider $provider
             */
            $provider = new $providerClassName();
            $provider->boot($this->container);
        }
    }

    public function getInstance(string $className)
    {
        return $this->container[$className];
    }

    public static function resolve(string $className)
    {
        return static::$instance->getInstance($className);
    }
}