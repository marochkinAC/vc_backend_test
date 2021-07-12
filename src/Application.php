<?php
declare(strict_types=1);

namespace Ads;


use Ads\Core\ServiceProvider\IServiceProvider;
use Ads\Share\Route\Route;
use Exception;
use Pimple\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Application
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
        if (self::$instance === null) {
            self::$instance = new self();
            self::$instance->container = new Container();
            self::$instance->bootstrap();
        }
        return self::$instance;

    }

    public function run(Request $request): Response
    {
        /**
         * @var Route $router
         */
        $router = $this->container[Route::class];
        return $router->run($request);
    }

    private function bootstrap(): void
    {
        $providers = require ROOT . '/config/providers.php';
        foreach ($providers as $providerClassName) {
            /**
             * @var IServiceProvider
             */
            $provider = new $providerClassName();
            $provider->boot($this->container);
        }
    }

    /**
     * @param string $className
     * @return mixed
     */
    public function getInstance(string $className)
    {
        return $this->container[$className];
    }

    /**
     * @param string $className
     * @return mixed
     */
    public static function resolve(string $className)
    {
        return self::$instance->getInstance($className);
    }
}