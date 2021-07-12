<?php
declare(strict_types=1);

namespace Ads\Core\ServiceProvider;


use Pimple\Container;

interface IServiceProvider
{
    public function boot(Container $container): void;
}