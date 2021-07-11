<?php


namespace Ads\Core\ServiceProvider;


use Ads\Core\Domain\DBAL\DB;
use Ads\Core\Domain\Entity\Repository\AdsRepository;
use Ads\Core\Domain\Services\AdsService\AdsService;
use Pimple\Container;

class AdsServiceProvider implements IServiceProvider
{
    public function boot(Container $container)
    {
        $container[AdsRepository::class] = function () use ($container) {
            return new AdsRepository($container[DB::class]);
        };

        $container[AdsService::class] = function () use ($container) {
            return new AdsService($container[AdsRepository::class]);
        };
    }
}