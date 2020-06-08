delete from `sionic`.`tArticle` where ArticleId > 0;
ALTER TABLE `sionic`.`tArticle` AUTO_INCREMENT = 0 ;
delete from `sionic`.`tChangePart` where ChangePartId > 0
ALTER TABLE `sionic`.`tChangePart` AUTO_INCREMENT = 0 ;
delete from `sionic`.`tCity` where cityId > 0
ALTER TABLE `sionic`.`tCity` AUTO_INCREMENT = 0;
delete from `sionic`.`tCityArticles` where CityArticlesId > 0
ALTER TABLE `sionic`.`tCityArticles` AUTO_INCREMENT = 0;
delete from `sionic`.`tMark` where markId > 0
ALTER TABLE `sionic`.`tMark` AUTO_INCREMENT = 0;
delete from `sionic`.`tModel` where modelId > 0
ALTER TABLE `sionic`.`tModel` AUTO_INCREMENT = 0;
delete from `sionic`.`tTypeCar` where typeCarId > 0
ALTER TABLE `sionic`.`tTypeCar` AUTO_INCREMENT = 0;