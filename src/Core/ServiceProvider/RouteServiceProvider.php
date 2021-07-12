<?php
declare(strict_types=1);

namespace Ads\Core\ServiceProvider;

use Ads\Share\Route\Route;
use Pimple\Container;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\PhpFileLoader;

class RouteServiceProvider implements IServiceProvider
{
    public function boot(Container $container): void
    {
        $container[Route::class] = function () {
            $fileLocator = new FileLocator(CONFIG);
            $loader = new PhpFileLoader($fileLocator);
            $routeCollection = $loader->load('routes.php');
            return new Route($routeCollection);
        };
    }
}