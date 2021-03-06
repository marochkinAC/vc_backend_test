<?php declare(strict_types=1);


namespace Ads\Core\ApiRequests;


use Ads\Share\ParamFetcher\Exception\ParamFetcherException;
use Ads\Share\ParamFetcher\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AddAdsRequest
 * Класс представляющий параметры для запроса к add методу API
 * @package Ads\Core\ApiRequests
 */
class AddAdsRequest
{
    public string $text;
    public float $price;
    public int $limit;
    public string $banner;

    /**
     * @param Request $request
     * @return self
     * @throws RequestValidationError
     */
    public static function fromRequest(Request $request): self
    {
        try {
            $paramFetcher = ParamFetcher::fromRequestPost($request);
            $addRequest = new self();
            $addRequest->limit = $paramFetcher->getRequiredInt('limit');
            $addRequest->price = $paramFetcher->getRequiredFloat('price');
            $addRequest->text = $paramFetcher->getRequiredString('text');
            $addRequest->banner = $paramFetcher->getRequiredString('banner');
            return $addRequest;
        } catch (ParamFetcherException $exception) {
            throw new RequestValidationError($exception->getMessage(), 0, $exception);
        }
    }
}