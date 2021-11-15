<?php

class WebservicebuyersModel
{

    public static function fetch_urlBase()
    {
        $db = Db::getInstance();
        $urlBase = $db->query("SELECT * FROM tbl_urlbase");

        return $urlBase;
    }

    public static function fetch_customerData_by_customerId($customerId)
    {
        $db = Db::getInstance();
        $customerData = $db->query("SELECT * FROM user_account_customer WHERE ID_Cmr=:customerId", array(
            'customerId' => $customerId,
        ));

        return $customerData;
    }

    public static function fetch_ads()
    {
        $db = Db::getInstance();
        $ads = $db->query("SELECT * FROM tbl_ad");

        return $ads;
    }

    public static function fetch_brands()
    {
        $db = Db::getInstance();
        $brands = $db->query("SELECT * FROM tbl_brand");

        return $brands;
    }

    public static function fetch_mottoes()
    {
        $db = Db::getInstance();
        $mottoes = $db->query("select * from tbl_motto");

        return $mottoes;
    }

    public static function fetch_customer_by_CountryCodeAndMobile($countryCode, $mobileNumber)
    {
        $db = Db::getInstance();
        $customerData = $db->query("SELECT * FROM user_account_customer WHERE CountryCode_Cmr=:countryCode AND Mobile_Cmr=:mobileNumber", array(
            'countryCode' => $countryCode,
            'mobileNumber' => $mobileNumber,
        ));

        return $customerData;
    }

    public static function fetch_customerVerify_by_customerId($customerId)
    {
        $db = Db::getInstance();
        $customerVerifyData = $db->query("SELECT * FROM Tbl_VfyCustomers WHERE CustomerID_Vfyc=:customerId AND (Verifyed_Vfyc is null)", array(
            'customerId' => $customerId,
        ));

        return $customerVerifyData;
    }

    public static function fetch_shopListCategory($type, $categoryId, $lastShopId, $distance, $lat, $lng)
    {
        $orderBy = null;
        $sumQuery = '';
        $mainQuery = 'ID_Shp, Title_Shp, Address_Shp, imgShopLogo, Lat_Shp, Lng_Shp, WebSite_Shp, Email_Shp
                      FROM Tbl_Shop left join Tbl_ShopToCat on ShopID_Stc=ID_Shp WHERE CatID_Stc=:categoryId ';

        if ($lastShopId != null) {
            $mainQuery = $mainQuery . 'AND id_shp<:lastShopId ';
        }

        if ($type == 1) {
            $orderBy = 'ORDER BY DateTime_Shp DESC';
        } else {
            if ($type == 2) {
                $sumQuery = 'IFNULL((select sum(Like_Lik) from Tbl_Like where ID_Shp=ShopID_Lik), 0) as sumScore, ';
            } else if ($type == 3) {
                $sumQuery = 'IFNULL((select sum(Medal_Mel) from Tbl_Medal where ID_Shp=ShopID_Mel), 0) as sumScore, ';
            } else if ($type == 4) {
                $sumQuery = 'IFNULL((select sum(Score_Scr) from Tbl_Score where ID_Shp=ShopID_Scr), 0) as sumScore, ';
            }
            $orderBy = 'ORDER BY sumScore DESC, DateTime_Shp DESC';
        }

        $queryLatLng = '';
        if ($lat != null) {
            $queryLatLng = 'and (6371 * 2 * ASIN(SQRT(POWER(SIN((:lat - abs(Tbl_Shop.Lat_Shp)) * pi()/180 / 2),2) + 
                                                        COS(:lat * pi()/180 ) * COS(abs(Tbl_Shop.Lat_Shp) *pi()/180) * 
                                                        POWER(SIN((:lng - Tbl_Shop.Lng_Shp) *pi()/180 / 2), 2) )))< :distance';
        }

        $db = Db::getInstance();
        $shopListCategory = $db->query("SELECT " . $sumQuery . $mainQuery . $queryLatLng . $orderBy . " LIMIT 50", array(
            'categoryId' => $categoryId,
            'lastShopId' => $lastShopId,
            'distance' => $distance,
            'lat' => $lat,
            'lng' => $lng,
        ));

        return $shopListCategory;
    }

    public static function fetch_shopScoreAvgAndCounts($shopId)
    {
        $db = Db::getInstance();
        $shopScores = $db->query("SELECT IFNULL(avg(Score_Scr),0)as average, COUNT(Score_Scr)as counts FROM Tbl_Score where shopid_scr=:shopId", array(
            'shopId' => $shopId,
        ));

        return $shopScores;
    }

    public static function fetch_shopSumOfMedal($shopId)
    {
        $db = Db::getInstance();
        $shopMedals = $db->query("SELECT ifnull(sum(Medal_Mel),0)as sumMedal FROM Tbl_Medal where shopid_mel=:shopId", array(
            'shopId' => $shopId,
        ));

        return $shopMedals;
    }

    public static function insert_customerAndGetCustomerId($userAccountParentId, $countryCode, $mobileNumber)
    {
        $db = Db::getInstance();
        $customerId = $db->insert("INSERT INTO user_account_customer (userAccountParentId, DateTime_Cmr, CountryCode_Cmr, Mobile_Cmr)
                                        VALUES (:userAccountParentId, :dateTime, :countryCode, :mobile)", array(
            'userAccountParentId' => $userAccountParentId,
            'dateTime' => time(), //Unix TimeStamp
            'countryCode' => $countryCode,
            'mobile' => $mobileNumber,
        ));

        return $customerId;
    }

    public static function insert_verifyCustomer($customerId, $randomVerifyCode)
    {
        $db = Db::getInstance();
        $db->insert("INSERT INTO Tbl_VfyCustomers (CustomerID_Vfyc, VerifyCode_Vfyc)
                          VALUES (:customerId, :verifyCode)", array(
            'customerId' => $customerId,
            'verifyCode' => $randomVerifyCode,
        ));
    }

    public static function update_verifyCustomerForSMS($verifyCustomerId)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE Tbl_VfyCustomers SET DateTime_Vfyc=:dateTime, Verifyed_Vfyc=:verifiedSetTrue WHERE ID_Vfyc=:verifyCustomerId", array(
            'dateTime' => time(),
            'verifiedSetTrue' => 1,
            'verifyCustomerId' => $verifyCustomerId,
        ));
    }

    public static function update_customerData($customerId, $customerName, $customerFamily, $customerReagent, $customerImage)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE user_account_customer SET Name_Cmr=:customerName, Family_Cmr=:customerFamily, ReagentID_Cmr=:customerReagent, image_Cmr=:customerImage WHERE ID_Cmr=:customerId", array(
            'customerId' => $customerId,
            'customerName' => $customerName,
            'customerFamily' => $customerFamily,
            'customerReagent' => $customerReagent,
            'customerImage' => $customerImage,
        ));
    }

    public static function fetch_shopAndAdminOfThat_by_shopId($shopId)
    {
        $db = Db::getInstance();
        $shopWithAdmin = $db->query("select * from Tbl_Shop left join user_account_seller on AdminID_Shp=ID_Slr where ID_Shp=:shopId", array(
            'shopId' => $shopId,
        ));

        return $shopWithAdmin;
    }

    public static function fetch_shopScoreAvgAndCounts_by_shopId($shopId)
    {
        $db = Db::getInstance();
        $shopScoreAndCounts = $db->query("SELECT avg(Score_Scr)as average,COUNT(Score_Scr)as counts FROM Tbl_Score where shopid_scr=:shopId", array(
            'shopId' => $shopId,
        ));

        return $shopScoreAndCounts;
    }

    public static function fetch_shopMedal_by_shopId($shopId)
    {
        $db = Db::getInstance();
        $shopMedals = $db->query("SELECT ifnull(sum(Medal_Mel),0)as sumMedal FROM Tbl_Medal where shopid_mel=:shopId", array(
            'shopId' => $shopId,
        ));

        return $shopMedals;
    }

    public static function fetch_shopImages_by_shopId($shopId)
    {
        $db = Db::getInstance();
        $shopImages = $db->query("select * from Tbl_ImageToShop where ShopID_Its=:shopId", array(
            'shopId' => $shopId,
        ));

        return $shopImages;
    }

    public static function fetch_sellersForTheShop_by_shopId($shopId)
    {
        $db = Db::getInstance();
        $shopSellers = $db->query("select * from Tbl_SellerToShop left join user_account_seller on SellersID_Sts=ID_Slr where ShopID_Sts=:shopId", array(
            'shopId' => $shopId,
        ));

        return $shopSellers;
    }

    public static function fetch_shopCertificate_by_shopId($shopId)
    {
        $db = Db::getInstance();
        $shopCertificates = $db->query("select * from Tbl_CertificateToShop where ShopID_Sets=:shopId", array(
            'shopId' => $shopId,
        ));

        return $shopCertificates;
    }

    public static function fetch_shopNotifies_by_shopId($shopId)
    {
        $db = Db::getInstance();
        $shopNotifies = $db->query("select * from tbl_notifytoshop where ShopID_Nts=:shopId", array(
            'shopId' => $shopId,
        ));

        return $shopNotifies;
    }

    public static function fetch_shopComments_by_shopId($shopId)
    {
        $db = Db::getInstance();
        $shopComments = $db->query("select * from Tbl_CommentToShop left join user_account_customer on CustomerID_Cts=ID_Cmr where ShopID_Cts=:shopId", array(
            'shopId' => $shopId,
        ));

        return $shopComments;
    }

    public static function fetch_shopList_by_LatAndLng($lat, $lng, $distance, $limitUntil)
    {
        if ($limitUntil != "") {
            $limitUntil = "LIMIT 0, " . $limitUntil;
        }

        $db = Db::getInstance();
        $shopListWithLatAndLng = $db->query("SELECT ID_Shp, Title_Shp, Lat_Shp, Lng_Shp, AVG(Score_Scr) AS average, 
                                6371 * 2 * ASIN(SQRT(POWER(SIN((:lat - abs(Tbl_Shop.Lat_Shp)) * pi()/180 / 2),2) + COS(:lat * pi()/180 ) * COS(abs(Tbl_Shop.Lat_Shp) *pi()/180) * POWER(SIN((:lng - Tbl_shop.Lng_Shp) * pi()/180 / 2), 2) )) as distance
                                
                                from Tbl_Shop 
                                INNER JOIN tbl_score ON tbl_shop.ID_Shp = tbl_score.ShopID_Scr
                                GROUP BY ShopID_Scr
                                HAVING distance < :distance
                                ORDER BY average DESC
                                $limitUntil", array(
            'lat' => $lat,
            'lng' => $lng,
            'distance' => $distance,
        ));

        return $shopListWithLatAndLng;
    }

    public static function fetch_shopListByCategoryId_by_LatAndLng($lat, $lng, $distance, $categoryId, $limitUntil)
    {
        if ($limitUntil != "") {
            $limitUntil = "LIMIT 0, " . $limitUntil;
        }

        if ($categoryId == 1) {
            $likeQuery = "'10%' AND CatID_Stc NOT LIKE '100%'";
        } else {
            $likeQuery = "'" . $categoryId . "0%'";
        }

        // put the distance here only for the code become more readable
        $queryLatLng = ' AND (6371 * 2 * ASIN(SQRT(POWER(SIN((:lat - abs(Tbl_Shop.Lat_Shp)) * pi()/180 / 2),2) + 
                                                        COS(:lat * pi()/180 ) * COS(abs(Tbl_Shop.Lat_Shp) *pi()/180) * 
                                                        POWER(SIN((:lng - Tbl_Shop.Lng_Shp) *pi()/180 / 2), 2) )))< :distance';

        $db = Db::getInstance();
        $shopListByCategoryIdWithLatAndLng = $db->query("SELECT CatID_Stc, ID_Shp, Title_Shp, Lat_Shp, Lng_Shp, ID_Scr, AVG(Score_Scr) AS average 
                                        FROM tbl_shoptocat
                                        INNER JOIN tbl_shop ON ShopID_Stc =ID_Shp
                                        INNER JOIN tbl_score ON tbl_shop.ID_Shp = tbl_score.ShopID_Scr  
                                        WHERE (CatID_Stc LIKE $likeQuery) $queryLatLng
                                        GROUP BY ShopID_Scr
                                        ORDER BY average DESC 
                                        $limitUntil", array(
            'lat' => $lat,
            'lng' => $lng,
            'distance' => $distance,
        ));

        return $shopListByCategoryIdWithLatAndLng;
    }

    public static function fetch_shopProductModelsWithDiscount_by_shopId($shopId, $limit)
    {
        if (strcmp($limit, "fixTenNumberFromFirst") == 0) {
            $limit = "LIMIT 0, 10";
        } else {
            $limit = "LIMIT ". $limit .", 50";
        }

        $db = Db::getInstance();
        $productModelsWithDiscount = $db->query("(SELECT product_model.*, shop_to_product_model.*, discount, price FROM product_model
                                INNER JOIN shop_to_product_model ON product_model.productModelId = shop_to_product_model.productModelId
                                INNER JOIN stock_price ON shop_to_product_model.shopToProductModelId = stock_price.shopToProductModelId
                                WHERE product_model.hasColor = 0 AND shop_to_product_model.shopId = :shopId AND discount != 0
                                GROUP BY product_model.productModelId)
                                
                                UNION ALL
                                
                                (SELECT product_model.*, shop_to_product_model.*, discount, price FROM product_model
                                INNER JOIN shop_to_product_model ON product_model.productModelId = shop_to_product_model.productModelId
                                INNER JOIN stock_color_price ON shop_to_product_model.shopToProductModelId = stock_color_price.shopToProductModelId
                                WHERE product_model.hasColor = 1 AND product_model.isCloth = 0 AND shop_to_product_model.shopId = :shopId AND discount != 0
                                GROUP BY product_model.productModelId)
                                
                                UNION ALL
                                
                                (SELECT product_model.*, shop_to_product_model.*, discount, price FROM product_model
                                INNER JOIN shop_to_product_model ON product_model.productModelId = shop_to_product_model.productModelId
                                INNER JOIN stock_cloth_color_size_price ON shop_to_product_model.shopToProductModelId = stock_cloth_color_size_price.shopToProductModelId
                                WHERE product_model.isCloth = 1 AND shop_to_product_model.shopId = :shopId AND discount != 0
                                GROUP BY product_model.productModelId)
                                $limit", array(
            'shopId' => $shopId,
        ));

        return $productModelsWithDiscount;
    }

    public static function fetch_shopServicesNameWithDiscount_by_shopId($shopId, $limit)
    {
        if (strcmp($limit, "fixTenNumberFromFirst") == 0) {
            $limit = "LIMIT 0, 10";
        } else {
            $limit = "LIMIT ". $limit .", 50";
        }

        $db = Db::getInstance();
        $serviceNames = $db->query("SELECT * FROM shop_to_service_name
                                    INNER JOIN service_name ON shop_to_service_name.serviceNameId = service_name.serviceNameId
                                    WHERE shop_to_service_name.shopId = :shopId AND discount != 0
                                    $limit", array(
            'shopId' => $shopId,
        ));

        return $serviceNames;
    }

}