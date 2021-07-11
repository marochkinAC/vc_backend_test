<?php declare(strict_types=1);


namespace Ads\Core\Domain\Entity\Entity;

/**
 * Class Ads
 * Класс представляющий сущность рекламного объявления в предметной области
 * @package Ads\Core\Domain\Entity\Entity
 */
class Ads
{
    /**
     * @var int - id в репозитории
     */
    private int $id;
    /**
     * @var string - заголовок рекламного объявления
     */
    private string $text;
    /**
     * @var float - стоимость одного показа
     */
    private float $price;
    /**
     * @var int - лимит показов
     */
    private int $limit;
    /**
     * @var int - текущее количество показов
     */
    private int $show_count;
    /**
     * @var string - ссылка на баннер
     */
    private string $banner;

    /**
     * Ads constructor.
     * @param int $id
     * @param string $text
     * @param float $price
     * @param int $limit
     * @param string $banner
     */
    public function __construct(int $id, string $text, float $price, int $limit, string $banner)
    {
        $this->id = $id;
        $this->text = $text;
        $this->price = $price;
        $this->limit = $limit;
        $this->banner = $banner;
        $this->show_count = 0;
    }

    /**
     * @return int
     */
    public function getShowCount(): int
    {
        return $this->show_count;
    }

    /**
     * @param int $show_count
     */
    public function setShowCount(int $show_count): void
    {
        $this->show_count = $show_count;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return string
     */
    public function getBanner(): string
    {
        return $this->banner;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @param string $banner
     */
    public function setBanner(string $banner): void
    {
        $this->banner = $banner;
    }

    public static function fromState(array $state): Ads
    {
        $ads = new self(
            (int)$state['id'],
            $state['text'],
            (float)$state['price'],
            (int)$state['show_limit'],
            $state['banner']
        );
        $ads->setShowCount($state['show_count']);
        return $ads;
    }
}