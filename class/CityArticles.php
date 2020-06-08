<?php


class CityArticles
{
    private int $id = 0;
    private int $articleCode;
    private int $cityId;
    private int $count;
    private int $price;
    private bool $change = true;

    /**
     * CityArticles constructor.
     * @param int $articleCode
     * @param int $cityId
     * @param int $count
     * @param int $price
     */
    public function __construct(int $articleCode = 0, int $cityId = 0, int $count = 0, int $price = 0)
    {
        $this->articleCode = $articleCode;
        $this->cityId = $cityId;
        $this->count = $count;
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isChange(): bool
    {
        return $this->change;
    }

    /**
     * @param bool $change
     */
    public function setChange(bool $change): void
    {
        $this->change = $change;
    }

    /**
     * @return int
     */
    public function getArticleCode(): int
    {
        return $this->articleCode;
    }

    /**
     * @param int $articleCode
     */
    public function setArticleCode(int $articleCode): void
    {
        $this->articleCode = $articleCode;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getCityId(): int
    {
        return $this->cityId;
    }

    /**
     * @param int $cityId
     */
    public function setCityId(int $cityId): void
    {
        $this->cityId = $cityId;
    }

    /**
     * @param CityArticles $obj
     * @return bool
     */
    public function equals(CityArticles $obj): bool
    {
        return $obj->getArticleCode() == $this->getArticleCode() &&
            $obj->getCityId() == $this->getCityId() &&
            $obj->getCount() == $this->getCount() &&
            $obj->getPrice() == $this->getPrice();

    }
}