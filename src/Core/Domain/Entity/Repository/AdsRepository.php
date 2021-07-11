<?php declare(strict_types=1);


namespace Ads\Core\Domain\Entity\Repository;


use Ads\Core\Domain\DBAL\DB;
use Ads\Core\Domain\DBAL\Exception\DBALException;
use Ads\Core\Domain\Entity\Entity\Ads;
use Ads\Core\Domain\Entity\Exception\EntityLayerException;
use Ads\Core\Domain\Entity\Exception\EntityNotFoundException;

/**
 * Class AdsRepository
 * @package Ads\Core\Domain\Entity\Repository
 */
class AdsRepository
{
    private DB $db;

    /**
     * AdsRepository constructor.
     * @param DB $db
     */
    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    /**
     * @throws EntityNotFoundException - when entity not found
     * @throws EntityLayerException - when error occurs
     */
    public function findById(int $id): Ads
    {
        try {
            $row = $this->db->selectById('advs', 'id', $id);
            if ($row) {
                return Ads::fromState($row);
            }
            throw new EntityNotFoundException('Ads entity id = ' . $id . ' not found');
        } catch (DBALException $exception) {
            throw new EntityLayerException('Error searching Ads entity with id = ' . $id, 0 , $exception);
        }
    }

    /**
     * @throws EntityLayerException
     */
    public function save(Ads $ads)
    {
        try {
            $data = [
                'show_limit' => $ads->getLimit(),
                'show_count' => $ads->getShowCount(),
                'banner' => $ads->getBanner(),
                'price' => $ads->getPrice(),
                'text' => $ads->getText(),
            ];

            if ($ads->getId() !== 0) {
                $this->db->updateById('advs', 'id', $ads->getId(), $data);
            } else {
                $id = $this->db->insertRow('advs', $data);
                $ads->setId($id);
            }
        } catch (DBALException $exception) {
            throw new EntityLayerException('Error saving Ads entity with id = ' . $ads->getId(), 0 , $exception);
        }
    }
}