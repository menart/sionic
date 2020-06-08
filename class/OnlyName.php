<?php


abstract class OnlyName
{
    private int $id;
    private string $name;
    private bool $change = true;

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
     * City constructor.
     * @param $name
     */
    public function __construct(string $name = '')
    {
        $this->id = 0;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId():int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * @param string  $name
     */
    public function setName(string $name): void
    {
        if(strcmp($this->name,$name)!=0){
            $this->name = $name;
            $this->change = true;
        }
    }

    public function equals(OnlyName $obj):bool
    {
        return strcmp(trim($obj->getName()),trim($this->getName())) == 0;
    }
}