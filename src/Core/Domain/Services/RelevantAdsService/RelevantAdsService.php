<?php declare(strict_types=1);


namespace Ads\Core\Domain\Services\RelevantAdsService;

use Ads\Core\Domain\Entity\Entity\Ads;
use Ads\Core\Domain\Entity\Exception\EntityLayerException;
use Ads\Core\Domain\Entity\Exception\EntityNotFoundException;
use Ads\Core\Domain\Entity\Repository\AdsRepository;
use Ads\Core\Domain\Services\RelevantAdsService\Exception\RelevantAdsNotFound;
use Ads\Core\Domain\Services\ServicesLayerException;

/**
 * Class RelevantAdsService
 * Сервис по открутке рекламных объявлений
 * @package Ads\Core\Domain\Services\RelevantAdsService
 */
class RelevantAdsService
{
    private AdsRepository $adsRepository;

    /**
     * AdsService constructor.
     * @param AdsRepository $adsRepository
     */
    public function __construct(AdsRepository $adsRepository)
    {
        $this->adsRepository = $adsRepository;
    }

    /**
     * Метод для открутки рекламных объявлений
     * Возвращает текущее подходящее рекламное объявление
     * При этом автоматически увеличивает количество показов у релевантного объявления
     * @throws RelevantAdsNotFound - если не удается найти релевантное объявление
     * @throws ServicesLayerException
     */
    public function showRelevantAds(): Ads
    {
        try {
            $ads = $this->adsRepository->findRelevantAds();
            $ads->setShowCount($ads->getShowCount() + 1);
            $this->adsRepository->save($ads);
            $this->adsRepository->flush();
            return $ads;
        } catch (EntityNotFoundException $e) {
            throw new RelevantAdsNotFound('Relevant ads not found', 0, $e);
        } catch (EntityLayerException $e) {
            throw new ServicesLayerException('Error when try show relevant ads', 0, $e);
        }
    }

}