<?php
declare(strict_types=1);

namespace Ads\Core\Controller;


use Ads\Application;
use Ads\Core\Domain\Services\RelevantAdsService\Exception\RelevantAdsNotFound;
use Ads\Core\Domain\Services\RelevantAdsService\RelevantAdsService;
use Ads\Core\Domain\Services\ServicesLayerException;
use Ads\Share\Response\ErrorJsonResponse;
use Ads\Share\Response\JsonResponse;
use Ads\Share\Response\SuccessJsonResponse;
use Ads\Share\Route\RouteMatch;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RelevantAdsController
 * @package Ads\Core\Controller
 */
class RelevantAdsController extends AbstractController
{

    private RelevantAdsService $relevantAdsService;

    /**
     * RelevantAdsController constructor.
     * @param Request $request
     * @param RouteMatch $routeMatch
     */
    public function __construct(Request $request, RouteMatch $routeMatch)
    {
        parent::__construct($request, $routeMatch);
        $this->relevantAdsService = Application::resolve(RelevantAdsService::class);
    }

    /**
     * @return JsonResponse
     * @throws ServicesLayerException
     */
    public function showRelevant(): JsonResponse
    {
        try {
            $ads = $this->relevantAdsService->showRelevantAds();
            return new SuccessJsonResponse([
                'id' => $ads->getId(),
                'text' => $ads->getText(),
                'banner' => $ads->getBanner()
            ]);
        } catch (RelevantAdsNotFound $e) {
            return new ErrorJsonResponse('Not found available ads', []);
        }
    }
}