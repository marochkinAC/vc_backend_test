<?php
declare(strict_types=1);

namespace Ads\Core\Controller;


use Ads\Core\ApiRequests\AddAdsRequest;
use Ads\Application;
use Ads\Core\ApiRequests\UpdateAdsRequest;
use Ads\Core\Domain\Entity\Exception\EntityLayerException;
use Ads\Core\Domain\Services\AdsService\AdsService;
use Ads\Core\Domain\Services\AdsService\Exception\AdsNotFoundException;
use Ads\Share\ParamFetcher\Exception\RequiredParamNotFound;
use Ads\Share\Response\ErrorJsonResponse;
use Ads\Share\Response\SuccessJsonResponse;
use Ads\Share\Route\RouteMatch;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class AdsController
 * @package Ads\Core\Controller
 */
class AdsController extends AbstractController
{
    private AdsService $adsService;


    public function __construct(Request $request, RouteMatch $routeMatch)
    {
        parent::__construct($request, $routeMatch);
        $this->adsService = Application::resolve(AdsService::class);
    }


    /**
     * @return Response
     * @throws EntityLayerException
     */
    public function add(): Response
    {
        try {
            $adsRequest = AddAdsRequest::fromRequest($this->request);
            $ads = $this->adsService->addAds(
                $adsRequest->text,
                $adsRequest->banner,
                $adsRequest->limit,
                $adsRequest->price
            );

            return new SuccessJsonResponse([
                'id' => $ads->getId(),
                'text' => $ads->getText(),
                'banner' => $ads->getBanner()
            ]);
        } catch (RequiredParamNotFound $e) {
            return new ErrorJsonResponse('Required param `' . $e->getParam() . '` not found', []);
        }
    }


    /**
     * @return Response
     * @throws EntityLayerException
     */
    public function update(): Response
    {
        $id = $this->routeMatch->getIntParam('id');
        try {
            $updateAdsRequest = UpdateAdsRequest::fromRequest($this->request);
            $ads = $this->adsService->updateAds(
                $id,
                $updateAdsRequest->text,
                $updateAdsRequest->banner,
                $updateAdsRequest->limit,
                $updateAdsRequest->price
            );
            return new SuccessJsonResponse([
                'id' => $ads->getId(),
                'text' => $ads->getText(),
                'banner' => $ads->getBanner()
            ]);
        } catch (AdsNotFoundException $e) {
            return new ErrorJsonResponse('Ads id=`' . $id . '` not found', []);
        }
    }
}