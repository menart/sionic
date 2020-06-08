CREATE TABLE `tArticle` (
  `ArticleId` int unsigned NOT NULL AUTO_INCREMENT,
  `ArticleName` varchar(255) NOT NULL,
  `ArticleCode` int unsigned NOT NULL,
  `ArticleWeight` double DEFAULT '0',
  PRIMARY KEY (`ArticleId`),
  UNIQUE KEY `ArticleId_UNIQUE` (`ArticleId`),
  UNIQUE KEY `ArticleCode_UNIQUE` (`ArticleCode`)
) ENGINE=InnoDB AUTO_INCREMENT=33787 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `tChangePart` (
  `ChangePartId` int NOT NULL AUTO_INCREMENT,
  `ChangeParArticleCode` int DEFAULT NULL,
  `ChangeParMarkId` int DEFAULT NULL,
  `ChangeParModelId` int DEFAULT NULL,
  `ChangeParTypeCarId` int DEFAULT NULL,
  PRIMARY KEY (`ChangePartId`)
) ENGINE=InnoDB AUTO_INCREMENT=27345 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `tCity` (
  `cityId` int unsigned NOT NULL AUTO_INCREMENT,
  `cityName` varchar(255) NOT NULL,
  PRIMARY KEY (`cityId`),
  UNIQUE KEY `idCity_UNIQUE` (`cityId`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `tMark` (
  `markId` int unsigned NOT NULL AUTO_INCREMENT,
  `markName` varchar(255) NOT NULL,
  PRIMARY KEY (`markId`),
  UNIQUE KEY `markId_UNIQUE` (`markId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `tModel` (
  `modelId` int unsigned NOT NULL AUTO_INCREMENT,
  `modelName` varchar(255) NOT NULL,
  PRIMARY KEY (`modelId`),
  UNIQUE KEY `markId_UNIQUE` (`modelId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `tTypeCar` (
  `typeCarId` int unsigned NOT NULL AUTO_INCREMENT,
  `typeCarName` varchar(255) NOT NULL,
  PRIMARY KEY (`typeCarId`),
  UNIQUE KEY `markId_UNIQUE` (`typeCarId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `tCityArticles` (
  `CityArticlesId` int unsigned NOT NULL AUTO_INCREMENT,
  `CityArticlesArticleCode` int DEFAULT '0',
  `CityArticlesCityId` int DEFAULT NULL,
  `CityArticlesCount` int DEFAULT '0',
  `CityArticlesPrice` int DEFAULT '0',
  PRIMARY KEY (`CityArticlesId`),
  UNIQUE KEY `CityArticlesId_UNIQUE` (`CityArticlesId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
