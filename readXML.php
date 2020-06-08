<?php
error_reporting(E_ALL & ~E_NOTICE);

include_once('myFunc.php');

spl_autoload_register(function ($name) {
    require('class/' . $name . '.php');
});

$db = new MySqlDB("localhost", "sionic", "root", "");

$listCity = $db->getList('City');
$listMark = $db->getList('Mark');
$listModel = $db->getList('Model');
$listTypeCar = $db->getList('TypeCar');
$listArticle = $db->getList('Article');
$listChangePartDB = $db->getList('ChangePart');
$listCityArticles = $db->getList('CityArticles');

$files = glob('data/*.xml');
$i = 0;

foreach ($files as $file) {
    $readFileXML = file_get_contents($file);
    echo $file . "\n";
    $unsortedXML = new SimpleXMLElement($readFileXML);

    $classifier = get_object_vars(get_object_vars($unsortedXML)["Классификатор"]);
    //Имя города
    $nameCity = trim($classifier["Наименование"]);
    $nameCity = mb_substr($nameCity, 15, mb_strlen($nameCity) - 16);
    $city = search_array(new City($nameCity), $listCity);

    if (!$city) {
        $city = $db->getOnlyName("City", $nameCity);
        $db->saveOnlyName($city);
        array_push($listCity, $city);
    }

    if (stripos($file, 'import')) {

        echo date('D, d M Y H:i:s') . ' - читаем import город: ' . $city->getName() . "\n";

        $articles = get_object_vars(get_object_vars($unsortedXML)["Каталог"])["Товары"];

        foreach ($articles as $key => $articleObj) {
            if ($key == "Товар") {
                $articleXML = get_object_vars($articleObj);
                $code = trim($articleXML["Код"]);
                $articleDB = search_array(new Article($code), $listArticle);
                if (!$articleDB) {
                    $articleDB = $db->getArticle($code);
                    $find_article = false;
                }else
                    $find_article = true;

                $articleDB->setName($articleXML["Наименование"]);
                $articleDB->setWeight($articleXML["Вес"]);
                if ($articleXML["Взаимозаменяемости"] != null) {
                    foreach ($articleXML["Взаимозаменяемости"] as $index => $changePartObj) {
                        $changePartXML = get_object_vars($changePartObj);

                        $markName = $changePartXML["Марка"];
                        $mark = search_array(new Mark($markName), $listMark);
                        if (!$mark) {
                            $mark = $db->getOnlyName("Mark", $markName);
                            $db->saveOnlyName($mark);
                            array_push($listMark, $mark);
                        }

                        $modelName = $changePartXML["Модель"];
                        $model = search_array(new Model($modelName), $listModel);
                        if (!$model) {
                            $model = $db->getOnlyName("Model", $modelName);
                            $db->saveOnlyName($model);
                            array_push($listModel, $model);
                        }

                        $typeCarName = $changePartXML["КатегорияТС"];
                        $typeCar = search_array(new TypeCar($typeCarName), $listTypeCar);
                        if (!$typeCar) {
                            $typeCar = $db->getOnlyName("TypeCar", $typeCarName);
                            $db->saveOnlyName($typeCar);
                            array_push($listTypeCar, $typeCar);
                        }

                        $changePartDB = search_array(new ChangePart($code, $mark->getId(), $model->getId(), $typeCar->getId()), $listChangePartDB);
                        if (!$changePartDB) {
                            $changePartDB = $db->getChangePart($code, $mark->getId(), $model->getId(), $typeCar->getId());
                            $db->saveChangePart($changePartDB);
                            array_push($listChangePartDB, $changePartDB);
                        }

                    }
                }
                if ($articleDB->isChange()) $db->saveArticle($articleDB);
                if (!$find_article) array_push($listArticle, $articleDB);
                if ($i++ % 1000 == 0) {
                    echo date('D, d M Y H:i:s') . " - Строк обработано: $i select: " . MySqlDB::$select . " insert: " . MySqlDB::$insert . " update: " . MySqlDB::$update . "\n";
                }
            }
        }
    }
    if (stripos($file, 'offers')) {

        echo date('D, d M Y H:i:s') . ' - читаем offers город: ' . $city->getName() . "\n";

        $articles = get_object_vars(get_object_vars($unsortedXML)["ПакетПредложений"])["Предложения"];

        foreach ($articles as $key => $articleObj) {
            if ($key == "Предложение") {
                $articleXML = get_object_vars($articleObj);
                $code = trim($articleXML["Код"]);
                $articleDB = search_array(new Article($code), $listArticle);
                if (!$articleDB) {
                    $articleDB = $db->getArticle($code);
                    $find_article = false;
                } else
                    $find_article = true;

                $articleDB->setName($articleXML["Наименование"]);
                $count = $articleXML["Количество"];

                $price = get_object_vars(get_object_vars($articleXML["Цены"])["Цена"][0])["ЦенаЗаЕдиницу"];
                //var_dump(get_object_vars($articleXML["Цены"])["Цена"][0]);

                $cityArticles = search_array(new CityArticles($code, $city->getId(), $count, $price), $listCityArticles);
                if (!$cityArticles) {
                    $cityArticles = $db->getCityArticles($code, $city->getId(), $count, $price);
                    $db->saveCityArticles($cityArticles);
                    array_push($listCityArticles, $cityArticles);
                }
                if ($articleDB->isChange())
                    $db->saveArticle($articleDB);
                if (!$find_article)
                    array_push($listArticle, $articleDB);
                if ($i++ % 1000 == 0) {
                    echo date('D, d M Y H:i:s') . " - Строк обработано: $i select: " . MySqlDB::$select . " insert: " . MySqlDB::$insert . " update: " . MySqlDB::$update . "\n";
                }
            }
        }

    }
}
echo date('D, d M Y H:i:s') . " - Закончили \n";