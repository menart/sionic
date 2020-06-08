<?php


interface DB
{
    public function getArticle($code):Article;
    public function getOnlyName($className,$name):OnlyName;
}