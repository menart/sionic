<?php


class MySqlDB implements DB
{
    public static $update = 0;
    public static $insert = 0;
    public static $select = 0;
    private $dbLink;

    /**
     * MySqlDB constructor.
     * @param String $host
     * @param String $dbname
     * @param String $user
     * @param String $pwd
     */
    public function __construct(string $host, string $dbname, string $user, string $pwd)
    {
        $dsn = "mysql:host=$host;dbname=$dbname";
        $this->dbLink = new PDO($dsn, $user, $pwd);
    }

    public function getArticle($code): Article
    {
        $article = new Article($code);
        $sql = array('ArticleId', 'ArticleName', 'ArticleWeight');
        $where = "ArticleCode = ?";
        $result = $this->selectQuery($sql, 'tArticle', array($code), $where);
        if (count($result) > 0) {
            $article->setId($result[0]['ArticleId']);
            $article->setName($result[0]['ArticleName']);
            $article->setWeight($result[0]['ArticleWeight']);
            $article->setChange(false);
        }
        return $article;
    }

    public function selectQuery($sqlField, $table, $param = array(), $where = ''): array
    {
        $sql = "select " . implode(",", $sqlField) . " from $table";
        if (!empty($where))
            $sql .= " where $where";
        $query = $this->dbLink->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $query->execute($param);
        $this::$select++;
        return $query->fetchAll();
    }

    public function getOnlyName($className, $name): OnlyName
    {
        $onlyName = new $className($name);
        $sql = array($className . 'Id');
        $result = $this->selectQuery($sql, 't' . $className, array($name), $className . 'Name like ?');
        if (count($result) > 0) {
            $onlyName->setId($result[0][$className . "Id"]);
            $onlyName->setChange(false);
        }
        return $onlyName;
    }

    public function getChangePart($code, $markId, $modelId, $typeCarId)
    {
        $changePart = new ChangePart($code, $markId, $modelId, $typeCarId);
        $sql = array('ChangePartId');
        $where = ' ChangePartModelid = ' . $markId . ' and ChangePartMarkId=' . $modelId . ' and ChangePartTypeCarId=' . $typeCarId . ' and ChangePartArticleCode = ?';
        $result = $this->selectQuery($sql, 'tChangePart', array($code), $where);
        if (count($result) > 0) {
            $changePart->setId($result[0]['ChangePartId']);
        }
        return $changePart;
    }

    public function getCityArticles($code, $cityId, $count, $price)
    {
        $cityArticles = new CityArticles($code, $cityId, $count, $price);
        $sql = array('CityArticlesId');
        $where = " and CityArticlesCityId = $cityId and CityArticlesCount = $count and CityArticlesPrice = $price  and CityArticlesArticleCode = ?";
        $result = $this->selectQuery($sql, 'tCityArticles', array($code), $where);
        if (count($result) > 0) {
            $cityArticles->setId($result[0]['CityArticlesId']);
        }
        return $cityArticles;
    }

    public function saveArticle(Article $article)
    {
        $sql = array('ArticleCode', 'ArticleName', 'ArticleWeight');
        if ($article->getId() == 0) {
            $article->setId($this->insertQuery($sql, $article->getFullValue(), 'tArticle'));
        } else
            if ($article->isChange()) {
                $this->updateQuery($sql,
                    $article->getFullValue(),
                    'tArticle',
                    " articleId=" . $article->getId());
            }
    }

    public function insertQuery($fields, $value, $table)
    {
        $sql = "insert into $table (" . implode(",", $fields) . ") values (:" . implode(",:", $fields) . ")";
        $this->execQuery($sql, $fields, $value);
        $this::$insert++;
        return $this->dbLink->lastInsertId();
    }

    public function execQuery($sql, $fields, $value)
    {
        $query = $this->dbLink->prepare($sql);
        for ($i = 0; $i < count($fields); $i++) {
            $query->bindParam($fields[$i], $value[$i]);
        }
        if (!$query->execute()) echo $sql . "\n" . var_dump($query->errorInfo()) . "\n";
    }

    public function updateQuery($fields, $value, $table, $where)
    {
        $sql = "update $table set ";
        foreach ($fields as $field) $sql .= "$field = :$field";
        $sql .= " where $where";
        $this->execQuery($sql, $fields, $value);
        $this::$update++;
    }

    public function saveOnlyName(OnlyName $onlyName)
    {
        $className = get_class($onlyName);
        $sql = array($className . 'Name');
        if ($onlyName->getId() == 0) {
            $onlyName->setId($this->insertQuery($sql, array($onlyName->getName()), 't' . $className));
        } else
            if ($onlyName->isChange())
                $this->updateQuery($sql,
                    array($onlyName->getName()),
                    't' . $className,
                    ' ' . $className . 'Id = ' . $onlyName->getId());
    }

    public function saveChangePart(ChangePart $changePart)
    {
        $sql = array('ChangePartArticleCode', 'ChangePartMarkId', 'ChangePartModelId', 'ChangePartTypeCarId');
        $value = array(
            $changePart->getArticleCode(),
            $changePart->getMarkId(),
            $changePart->getModelId(),
            $changePart->getTypeCarId());
        if ($changePart->getId() == 0) {
            $changePart->setId($this->insertQuery($sql, $value, 'tChangePart'));
        }
    }

    public function saveCityArticles(CityArticles $cityArticles)
    {
        $sql = array("CityArticlesArticleCode", "CityArticlesCityId", "CityArticlesCount", "CityArticlesPrice");
        $value = array(
            $cityArticles->getArticleCode(),
            $cityArticles->getCityId(),
            $cityArticles->getCount(),
            $cityArticles->getPrice());
        if ($cityArticles->getId() == 0) {
            $cityArticles->setId($this->insertQuery($sql, $value, 'tCityArticles'));
        }
    }

    public function getDataForFront(int $page, string $order, int $count): DataFront
    {
        $dataFront = new DataFront();
        $dataFront->page = $page;
        $dataFront->recCount = $count;
        $countRow = ['count(*) as countArticle'];
        $result = $this->selectQuery($countRow, 'tArticle');
        if (count($result) > 0) {
            $pageCount = $result[0]['countArticle'];
            if ($count > 0 && $pageCount % $count > 0)
                $pageCount = (int)($pageCount / $count) + 1;
            else
                $pageCount = (int)($pageCount / $count);
            $dataFront->pageCount = $pageCount;
        }
        $sql = 'select ArticleId, ArticleName, ArticleCode, ArticleWeight,';
        $joinSql = '';
        $whitelist = array('ArticleId', 'ArticleName', 'ArticleCode', 'ArticleWeight');
        array_push($dataFront->fieldList, ['ArticleId', 'ИД'], ['ArticleName', 'Наименование'], ['ArticleCode', 'Код'], ['ArticleWeight', 'Вес']);
        $listCity = $this->getList('City');
        if (!empty($listCity)) {
            foreach ($listCity as $city) {
                $sql .= 'tCityArticles' . $city->getId() . '.CityArticlesCount as count' . $city->getId() . ',';
                $sql .= 'tCityArticles' . $city->getId() . '.CityArticlesPrice as price' . $city->getId() . ',';
                $joinSql .= ' left join tCityArticles as tCityArticles' . $city->getId() . ' ';
                $joinSql .= 'on (tCityArticles' . $city->getId() . '.CityArticlesArticleCode = tArticle.ArticleCode ';
                $joinSql .= 'and tCityArticles' . $city->getId() . '.CityArticlesCityId = ' . $city->getId() . ')';
                array_push($dataFront->fieldList, ['count' . $city->getId(), 'Количество ' . $city->getName()]);
                array_push($dataFront->fieldList, ['price' . $city->getId(), 'Цена ' . $city->getName()]);
                array_push($whitelist,'count' . $city->getId(),'price' . $city->getId());
            }
        }
        array_push($dataFront->fieldList, ['noOredr', 'Взаимозаменяемости']);
        $sql = substr($sql, 0, -1);
        if($order != 'noOrder' && !in_array($order,$whitelist)){
            $order = 'ArticleId';
        }
        $dataFront->order = $order;
        $sql .= ' from tArticle ' . $joinSql . ' order by '.$order.' limit :start, :end';
        $query = $this->dbLink->prepare($sql);
        $start = $page * $count;
        $end = ($page + 1) * $count;
        $query->bindParam(':start', $start,PDO::PARAM_INT);
        $query->bindParam(':end', $end,PDO::PARAM_INT);
        $query->execute();
        $sqlChangePart = 'select CONCAT(tMark.markName,"-",tModel.modelName,"-",tTypeCar.typeCarName) as usageStr';
        $sqlChangePart .= '	from tChangePart ';
        $sqlChangePart .= 'left join tMark on (tChangePart.ChangePartMarkId=tMark.MarkId) ';
        $sqlChangePart .= 'left join tModel on (tChangePart.ChangePartModelId=tModel.ModelId) ';
        $sqlChangePart .= 'left join tTypeCar on (tChangePart.ChangeParttypeCarId=tTypeCar.typeCarId) ';
        $sqlChangePart .= 'where ChangePartArticleCode = :ChangePartArticleCode';
        while ($row = $query->fetch(PDO::FETCH_ASSOC)){
            $queryChangePart = $this->dbLink->prepare($sqlChangePart);
            $queryChangePart->bindParam(':ChangePartArticleCode', $row['ArticleCode'],PDO::PARAM_INT);
            $queryChangePart->execute();
            $usage = '';
            while ($usageRow = $queryChangePart->fetch()){
                $usage .= $usageRow[0].'|';
            }
            array_push($dataFront->fieldData, array_merge($row,array('noOrder' => $usage)));
        }
        return $dataFront;
    }

    public function getList($className): array
    {
        $sql = array();
        $returnArray = array();
        $class = new ReflectionClass($className);

        foreach ($class->getProperties(ReflectionProperty::IS_PRIVATE) as $field) {
            if (strcmp($field->getName(), 'change')) {
                array_push($sql, $className . ucfirst($field->getName()));
            }
        }

        if (!empty($class->getParentClass())) {

            foreach ($class->getParentClass()->getProperties(ReflectionProperty::IS_PRIVATE) as $field) {
                if (strcmp($field->getName(), 'change')) {
                    array_push($sql, $className . ucfirst($field->getName()));
                }
            }
        }

        $result = $this->selectQuery($sql, 't' . $className);
        if (count($result) > 0) {
            foreach ($result as $row) {
                $obj = $class->newInstance();
                foreach ($class->getProperties(ReflectionProperty::IS_PRIVATE) as $field) {
                    if (strcmp($field->getName(), 'change')) {
                        $fieldName = ucfirst($field->getName());
                        $methodName = 'set' . $fieldName;
                        if (method_exists($obj, $methodName)) {
                            $obj->$methodName($row[$className . $fieldName]);
                        }
                        $obj->setChange(false);
                    }
                }
                if (!empty($class->getParentClass())) {
                    foreach ($class->getParentClass()->getProperties(ReflectionProperty::IS_PRIVATE) as $field) {
                        if (strcmp($field->getName(), 'change')) {
                            $fieldName = ucfirst($field->getName());
                            $methodName = 'set' . $fieldName;
                            if (method_exists($obj, $methodName)) {
                                $obj->$methodName($row[$className . $fieldName]);
                            }
                            $obj->setChange(false);
                        }
                    }
                }
                array_push($returnArray, $obj);
            }
        }
        return $returnArray;
    }
}


