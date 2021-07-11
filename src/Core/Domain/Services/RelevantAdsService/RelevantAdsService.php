<?php declare(strict_types=1);


namespace Ads\Core\Domain\Services\RelevantAdsService;

use Ads\Core\Domain\DBAL\DB;
use Ads\Core\Domain\DBAL\Exception\DBALException;
use Ads\Core\Domain\Entity\Entity\Ads;
use Ads\Core\Domain\Services\RelevantAdsService\Exception\RelevantAdsNotFound;
use Ads\Core\Domain\Services\ServicesLayerException;

/**
 * Class RelevantAdsService
 * Сервис по открутке рекламных объявлений
 * @package Ads\Core\Domain\Services\RelevantAdsService
 */
class RelevantAdsService
{
    private DB $db;


    /**
     * RelevantAdsService constructor.
     * @param DB $db
     */
    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    /**
     * Метод для открутки рекламных объявлений
     * Возвращает текущее подходящее рекламное объявление
     * При этом автоматически увеличивает количество показов у релевантного объявления
     * @throws RelevantAdsNotFound
     * @throws ServicesLayerException
     */
    public function showRelevantAds(): Ads
    {
        try {
            $this->db->beginTransaction();
            //Используем блокировку записи внутри транзакции, чтобы избежать race condition
            $result = $this->db->select('SELECT * FROM advs WHERE show_limit > show_count ORDER BY price DESC LIMIT 1 FOR UPDATE');
            if ($result) {
                $ads = Ads::fromState(array_shift($result));
                $this->db->query("UPDATE advs SET show_count = show_count + 1 WHERE id = {$ads->getId()}");
                $this->db->commit();
                return $ads;
            } else {
                $this->db->rollback();
                throw new RelevantAdsNotFound('Relevant ads not found', 0);
            }
        } catch (DBALException $exception) {
            $this->db->rollback();
            throw new ServicesLayerException('Error when try show relevant ads', 0, $exception);
        }
    }

}