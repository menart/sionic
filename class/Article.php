<?php


class Article
{
    private int $id = 0;
    private string $name = "";
    private int $code = 0;
    private float $weight = 0.0;
    private bool $change = true;

    /**
     * Article constructor.
     * @param int $code
     */
    public function __construct(int $code = 0)
    {
        $this->code = $code;
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
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        if ($this->name != $name) {
            $this->name = $name;
            $this->change = true;
        }
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight(float $weight)
    {
        if ($this->weight != $weight) {
            $this->weight = $weight;
            $this->change = true;
        }
    }

    /**
     * @return array
     */
    public function getFullValue(): array
    {
        return array($this->code, $this->name, $this->weight);
    }

    /**
     * @return bool
     */
    public function isChange(): bool
    {
        return $this->change;
    }

    /**
     * @param bool $isChange
     */
    public function setChange(bool $change): void
    {
        $this->change = $change;
    }

    /**
     * @param Article $obj
     * @return bool
     */
    public function equals(Article $obj): bool
    {
        return
            $obj->getCode() == $this->getCode();
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code)
    {
        $this->code = $code;
    }
}