<?php
header('Content-Type: application/json');

spl_autoload_register(function ($name) {
    require('class/' . $name . '.php');
});

$db = new MySqlDB("localhost", "sionic", "root", "");

$page = $_GET['page'];
if (empty($page)) $page = 0;

$order = $_GET['order'];
if(empty($order)) $order = 'ArticleId';

$count = $_GET['count'];
if(empty($count)) $count = 25;

echo json_encode($db->getDataForFront($page,$order,$count));