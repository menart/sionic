<?php


class ChangePart
{
    private int $id = 0;
    private int $articleCode;
    private int $markId;
    private int $modelId;
    private int $typeCarId;
    private bool $change = true;

    /**
     * ChangePart constructor.
     * @param int $articleCode
     * @param int $markId
     * @param int $modelId
     * @param int $typeCarId
     */
    public function __construct(int $articleCode = 0, int $markId = 0, int $modelId = 0, int $typeCarId = 0)
    {
        $this->articleCode = $articleCode;
        $this->markId = $markId;
        $this->modelId = $modelId;
        $this->typeCarId = $typeCarId;
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
    public function getMarkId(): int
    {
        return $this->markId;
    }

    /**
     * @param int $markId
     */
    public function setMarkId(int $markId): void
    {
        $this->markId = $markId;
    }

    /**
     * @return int
     */
    public function getModelId(): int
    {
        return $this->modelId;
    }

    /**
     * @param int $modelId
     */
    public function setModelId(int $modelId): void
    {
        $this->modelId = $modelId;
    }

    /**
     * @return int
     */
    public function getTypeCarId(): int
    {
        return $this->typeCarId;
    }

    /**
     * @param int $typeCarId
     */
    public function setTypeCarId(int $typeCarId): void
    {
        $this->typeCarId = $typeCarId;
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


    public function equals(ChangePart $obj): bool
    {
        return
            $obj->getArticleCode() == $this->getArticleCode() &&
            $obj->getMarkId() == $this->getMarkId() &&
            $obj->getModelId() == $this->getModelId() &&
            $obj->getTypeCarId() == $this->getTypeCarId();
    }
}