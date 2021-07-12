<?php


namespace Ads\Core\Domain\Services\AdsService;


use Ads\Core\Domain\Entity\Entity\Ads;
use Ads\Core\Domain\Entity\Exception\EntityLayerException;
use Ads\Core\Domain\Entity\Exception\EntityNotFoundException;
use Ads\Core\Domain\Entity\Repository\AdsRepository;
use Ads\Core\Domain\Services\AdsService\Exception\AdsNotFoundException;


/**
 * Class AdsService
 * @package Ads\Core\Domain\Services\AdsService
 */
class AdsService
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
     * Метод добавляет новое рекламное объявление
     * @param string $text
     * @param string $banner
     * @param int $limit
     * @param float $price
     * @return Ads - entity добавленного объявления
     * @throws EntityLayerException
     */
    public function addAds(string $text, string $banner, int $limit, float $price): Ads
    {
        $ads = new Ads($text, $price, $limit, $banner);
        $this->adsRepository->save($ads);
        return $ads;
    }


    /**
     * @param int $id - id объявления
     * @param string|null $text - заголовок объявления
     * @param string|null $banner - ссылка на баннер
     * @param int|null $limit - лимит показов
     * @param float|null $price - цена одного показа
     * @return Ads - обновленная entity
     * @throws AdsNotFoundException
     * @throws EntityLayerException
     */
    public function updateAds(
        int $id,
        ?string $text = null,
        ?string $banner = null,
        ?int $limit = null,
        ?float $price = null
    ): Ads
    {
        try {
            $ads = $this->adsRepository->findById($id);
            if ($text) {
                $ads->setText($text);
            }
            if ($banner) {
                $ads->setBanner($banner);
            }
            if ($limit) {
                $ads->setLimit($limit);
            }
            if ($price) {
                $ads->setPrice($price);
            }
            $this->adsRepository->save($ads);
            return $ads;
        } catch (EntityNotFoundException $e) {
            throw new AdsNotFoundException('Ads with id ' . $id . ' not found', 0, $e);
        }
    }
}