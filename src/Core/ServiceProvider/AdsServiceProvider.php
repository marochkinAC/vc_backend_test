<?php


namespace Ads\Core\ServiceProvider;


use Ads\Core\Domain\DBAL\DB;
use Ads\Core\Domain\Entity\Repository\AdsRepository;
use Ads\Core\Domain\Services\AdsService\AdsService;
use Ads\Core\Domain\Services\RelevantAdsService\RelevantAdsService;
use Pimple\Container;

class AdsServiceProvider implements IServiceProvider
{
    public function boot(Container $container): void
    {
        $container[AdsRepository::class] = function () use ($container) {
            return new AdsRepository($container[DB::class]);
        };

        $container[AdsService::class] = function () use ($container) {
            return new AdsService($container[AdsRepository::class]);
        };

        $container[RelevantAdsService::class] = function () use ($container) {
            return new RelevantAdsService($container[AdsRepository::class]);
        };
    }
}