<?php


class DataFront
{
    public int $page = 0;
    public int $pageCount = 0;
    public int $recCount = 25;
    public string $order;
    public array $fieldList = array();
    public array $fieldData = array();
}