<?php


namespace Ads\Core\Domain\Services\AdsService;


use Ads\Core\Domain\Entity\Entity\Ads;
use Ads\Core\Domain\Entity\Exception\EntityLayerException;
use Ads\Core\Domain\Entity\Exception\EntityNotFoundException;
use Ads\Core\Domain\Entity\Exception\ValidationErrorException;
use Ads\Core\Domain\Entity\Repository\AdsRepository;
use Ads\Core\Domain\Services\AdsService\Exception\AdsNotFoundException;
use Ads\Core\Domain\Services\AdsService\Exception\ValidationParamsException;
use Ads\Core\Domain\Services\ServicesLayerException;


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
     * @throws ValidationParamsException - при ошибке валидации
     * @throws ServicesLayerException - при любой другой ошибке
     */
    public function addAds(string $text, string $banner, int $limit, float $price): Ads
    {
        try {
            $ads = new Ads($text, $price, $limit, $banner);
            $this->adsRepository->save($ads);
            $this->adsRepository->flush();
            return $ads;
        } catch (ValidationErrorException $e) {
            throw new ValidationParamsException($e->getMessage(), 0, $e);
        } catch (EntityLayerException $e) {
            throw new ServicesLayerException('Error when try to add Ads', 0, $e);
        }
    }


    /**
     * @param int $id - id объявления
     * @param string|null $text - заголовок объявления
     * @param string|null $banner - ссылка на баннер
     * @param int|null $limit - лимит показов
     * @param float|null $price - цена одного показа
     * @return Ads - обновленная entity
     * @throws AdsNotFoundException - если сущность не найдена в репозитории
     * @throws ValidationParamsException - если произошли ошибки валидации
     * @throws ServicesLayerException - при любой другой ошибке
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
            if (isset($text)) {
                $ads->setText($text);
            }
            if (isset($banner)) {
                $ads->setBanner($banner);
            }
            if (isset($limit)) {
                $ads->setLimit($limit);
            }
            if (isset($price)) {
                $ads->setPrice($price);
            }
            $this->adsRepository->save($ads);
            $this->adsRepository->flush();
            return $ads;
        } catch (ValidationErrorException $e) {
            throw new ValidationParamsException($e->getMessage(), 0, $e);
        } catch (EntityNotFoundException $e) {
            throw new AdsNotFoundException('Ads with id ' . $id . ' not found', 0, $e);
        } catch (EntityLayerException $e) {
            throw new ServicesLayerException('Error when try to update Ads', 0, $e);
        }
    }
}