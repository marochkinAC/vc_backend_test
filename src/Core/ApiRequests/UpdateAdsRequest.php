<?php declare(strict_types=1);


namespace Ads\Core\ApiRequests;


use Ads\Share\ParamFetcher\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UpdateAdsRequest
 * Класс представляющий параметры для запроса к update методу API
 * @package Ads\Core\ApiRequests
 */
class UpdateAdsRequest
{
    public ?string $text;
    public ?float $price;
    public ?int $limit;
    public ?string $banner;

    /**
     * @param Request $request
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        $paramFetcher = ParamFetcher::fromRequestPost($request);
        $addRequest = new self();
        $addRequest->limit = $paramFetcher->getOptionalInt('limit');
        $addRequest->price = $paramFetcher->getOptionalFloat('price');
        $addRequest->text = $paramFetcher->getOptionalString('text');
        $addRequest->banner = $paramFetcher->getOptionalString('banner');
        return $addRequest;
    }

}