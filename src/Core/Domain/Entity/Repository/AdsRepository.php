<?php declare(strict_types=1);


namespace Ads\Core\Domain\Entity\Repository;


use Ads\Core\Domain\DBAL\DB;
use Ads\Core\Domain\DBAL\Exception\DBALException;
use Ads\Core\Domain\Entity\Entity\Ads;
use Ads\Core\Domain\Entity\Exception\EntityLayerException;
use Ads\Core\Domain\Entity\Exception\EntityNotFoundException;
use Ads\Core\Domain\Entity\Exception\ValidationErrorException;

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
            if (!$this->db->inTransaction()) {
                $this->db->beginTransaction();
            }

            $row = $this->db->selectByIdForUpdate('advs', 'id', $id);
            if (!$row) {
                throw new EntityNotFoundException('Ads entity id = ' . $id . ' not found');
            }

            return Ads::fromState($row);
        } catch (DBALException $exception) {
            $this->db->rollback();
            throw new EntityLayerException('Error searching Ads entity with id = ' . $id, 0 , $exception);
        }
    }

    /**
     * @throws EntityLayerException
     */
    public function save(Ads $ads)
    {
        try {
            if (!$this->db->inTransaction()) {
                $this->db->beginTransaction();
            }

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
            $this->db->rollback();
            throw new EntityLayerException('Error saving Ads entity with id = ' . $ads->getId(), 0 , $exception);
        }
    }

    public function flush()
    {
        if ($this->db->inTransaction()) {
            $this->db->commit();
        }
    }

    /**
     * @throws EntityNotFoundException
     * @throws EntityLayerException
     */
    public function findRelevantAds(): Ads
    {
        try {
            if (!$this->db->inTransaction()) {
                $this->db->beginTransaction();
            }

            $result = $this->db->select('SELECT * FROM advs WHERE show_limit > show_count ORDER BY price DESC LIMIT 1 FOR UPDATE');
            if ($result) {
                return Ads::fromState(array_shift($result));
            } else {
                throw new EntityNotFoundException('Relevant ads not found', 0);
            }
        } catch (ValidationErrorException $e) {
            $this->db->rollback();
            throw new EntityLayerException('Error when try validate ads object from repository', 0, $e);
        }
    }
}