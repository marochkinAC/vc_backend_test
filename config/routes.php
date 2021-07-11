<?php


use Ads\Core\Controller\AdsController;
use Ads\Core\Controller\RelevantAdsController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;


return function (RoutingConfigurator $routes) {
    $routes->add('addAds', '/ads')
        ->controller(AdsController::class . ':add')
        ->methods(['POST']);

    $routes->add('relevant', '/ads/relevant')
        ->controller(RelevantAdsController::class . ':showRelevant')
        ->methods(['GET']);

    $routes->add('updateAds', '/ads/{id}')
        ->controller(AdsController::class . ':update')
        ->methods(['POST'])
        ->requirements(['id' => '\d+']);
};


