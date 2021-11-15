<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ali_Ai
 * Date: 7/4/2018
 * Time: 9:05 AM
 */

class WebservicesellersModel
{

    public static function fetch_userAccountSeller_by_countryCodeAndMobile($countryCode, $mobileNumber)
    {
        $db = Db::getInstance();
        $sellerData = $db->query("SELECT * FROM user_account_seller WHERE CountryCode_slr=:countryCode AND Mobile_slr=:mobileNumber", array(
            'countryCode' => $countryCode,
            'mobileNumber' => $mobileNumber,
        ));

        return $sellerData;
    }

    public static function insert_userAccountParent($userAccountTypeId)
    {
        $db = Db::getInstance();
        $userAccountParentId = $db->insert("INSERT INTO user_account_parent (userAccountTypeId)
                                        VALUES (:userAccountTypeId)", array(
            'userAccountTypeId' => $userAccountTypeId,
        ));

        return $userAccountParentId;
    }

    public static function insert_userAccountSeller($userAccountParentId, $countryCode, $mobileNumber)
    {
        $db = Db::getInstance();
        $sellerID = $db->insert("INSERT INTO user_account_seller (userAccountParentId, DateTime_slr, CountryCode_slr, Mobile_slr, gender)
                                        VALUES (:userAccountParentId, :dateTime, :countryCode, :mobileNumber, :gender)", array(
            'userAccountParentId' => $userAccountParentId,
            'dateTime' => time(), //Unix TimeStamp
            'countryCode' => $countryCode,
            'mobileNumber' => $mobileNumber,
            'gender' => 0,
        ));

        return $sellerID;
    }

    public static function insert_verifySeller($sellerID, $randomVerifyCode)
    {
        $db = Db::getInstance();
        $db->insert("INSERT INTO Tbl_Vfysellers (sellersID_Vfys, VerifyCode_Vfys, Verifyed_Vfys)
                          VALUES (:sellerID, :verifyCode, :setVerifiedFalse)", array(
            'sellerID' => $sellerID,
            'verifyCode' => $randomVerifyCode,
            'setVerifiedFalse' => 0,
        ));
    }

    public static function fetch_sellerVerify_by_userAccountSellerId($sellerID)
    {
        $db = Db::getInstance();
        $sellerVerifyData = $db->query("SELECT * FROM Tbl_Vfysellers WHERE sellersID_Vfys=:sellerID AND Verifyed_Vfys=0", array(
            'sellerID' => $sellerID,
        ));

        return $sellerVerifyData;
    }

    public static function update_verifySellerForSMS($verifySellerID)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE Tbl_Vfysellers SET DateTime_Vfys=:dateTime, Verifyed_Vfys=:verifiedSetTrue WHERE ID_Vfys=:verifySellerID", array(
            'dateTime' => time(),
            'verifiedSetTrue' => 1,
            'verifySellerID' => $verifySellerID,
        ));
    }

    public static function fetch_sellerProfile_by_userAccountSellerId($sellerID)
    {
        $db = Db::getInstance();
        $sellerProfile = $db->first("SELECT * FROM user_account_seller WHERE ID_Slr=:sellerID", array(
            'sellerID' => $sellerID,
        ));

        return $sellerProfile;
    }

    public static function update_sellerProfile($sellerID, $firstName, $lastName, $nationalCode, $gender, $fatherName, $email, $reagentCode)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE user_account_seller SET firstName=:firstName, lastName=:lastName, nationalCode=:nationalCode, gender=:gender, fatherName=:fatherName, email=:email, reagentCode=:reagentCode  WHERE ID_Slr=:sellerID", array(
            'sellerID' => $sellerID,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'nationalCode' => $nationalCode,
            'gender' => $gender,
            'fatherName' => $fatherName,
            'email' => $email,
            'reagentCode' => $reagentCode,
        ));
    }

    public static function upload_sellerProfileImage($sellerID, $fileName)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE user_account_seller SET imgName=:fileName WHERE ID_Slr=:sellerID", array(
            'sellerID' => $sellerID,
            'fileName' => $fileName,
        ));
    }

    public static function fetch_shopList_by_adminId($shopAdminId, $limitFrom)
    {
        $db = Db::getInstance();
        $shopLists = $db->query("SELECT ID_Shp, DateTime_Shp, Title_Shp, Address_Shp, imgShopLogo FROM tbl_shop WHERE AdminID_Shp=:shopAdminId ORDER BY DateTime_Shp DESC LIMIT $limitFrom,40", array(
            'shopAdminId' => $shopAdminId,
        ));

        return $shopLists;
    }

    public static function fetchs_settings()
    {
        $db = Db::getInstance();
        $settings = $db->query("SELECT * FROM tbl_settings");

        return $settings;
    }

    public static function fetch_shopInfo_by_shopId($shopId)
    {
        $db = Db::getInstance();
        $shopInfo = $db->query("SELECT * FROM tbl_shop WHERE 	ID_Shp=:shopId", array(
            'shopId' => $shopId,
        ));

        return $shopInfo;
    }

    public static function fetch_shopTels_by_shopId($shopId)
    {
        $db = Db::getInstance();
        $shopTels = $db->query("SELECT * FROM seller_tel_to_shop where shopID=:shopId", array(
            'shopId' => $shopId,
        ));

        return $shopTels;
    }

    public static function fetch_shopMobilesSupport_by_shopId($shopId)
    {
        $db = Db::getInstance();
        $shopMobilesSupport = $db->query("SELECT * FROM seller_mobile_support_to_shop where shopID=:shopId", array(
            'shopId' => $shopId,
        ));

        return $shopMobilesSupport;
    }

    public static function fetch_shopCategories_by_shopId($shopId)
    {
        $db = Db::getInstance();
        $shopCategories = $db->query("SELECT * FROM tbl_shoptocat where ShopID_Stc=:shopId", array(
            'shopId' => $shopId,
        ));

        return $shopCategories;
    }

    public static function fetch_shopImages_by_shopId($shopId)
    {
        $db = Db::getInstance();
        $shopImages = $db->query("SELECT * FROM tbl_imagetoshop where ShopID_Its=:shopId", array(
            'shopId' => $shopId,
        ));

        return $shopImages;
    }

    public static function insert_shop($shopAdminID, $shopTitle, $shopAddress, $shopLat, $shopLng, $shopWebsite, $shopEmail, $shopDesc, $shopLicenceNumber)
    {
        $db = Db::getInstance();
        $shopId = $db->insert("INSERT INTO tbl_shop (DateTime_Shp, Type_Shp, Title_Shp, Address_Shp, Lat_Shp, Lng_Shp, WebSite_Shp, Email_Shp, ServiceDescription_Shp, AdminID_Shp, LicenseNumber_Shp)
                                        VALUES (:dateTime_Shp, :type_Shp, :title_Shp, :address_Shp, :lat_Shp, :lng_Shp, :webSite_Shp, :email_Shp, :serviceDescription_Shp, :adminID_Shp, :licenseNumber_Shp)", array(
            'dateTime_Shp' => time(), //Unix TimeStamp
            'type_Shp' => 1,
            'title_Shp' => $shopTitle,
            'address_Shp' => $shopAddress,
            'lat_Shp' => $shopLat,
            'lng_Shp' => $shopLng,
            'webSite_Shp' => $shopWebsite,
            'email_Shp' => $shopEmail,
            'serviceDescription_Shp' => $shopDesc,
            'adminID_Shp' => $shopAdminID,
            'licenseNumber_Shp' => $shopLicenceNumber,
        ));

        return $shopId;
    }

    public static function update_shop($shopId, $shopAdminID, $shopTitle, $shopAddress, $shopLat, $shopLng, $shopWebsite, $shopEmail, $shopDesc, $shopLicenceNumber)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE tbl_shop SET DateTime_Shp=:dateTime_Shp, Type_Shp=:type_Shp, Title_Shp=:title_Shp, Address_Shp=:address_Shp, 
                            Lat_Shp=:lat_Shp, Lng_Shp=:lng_Shp, WebSite_Shp=:webSite_Shp, Email_Shp=:email_Shp, ServiceDescription_Shp=:serviceDescription_Shp, 
                            AdminID_Shp=:adminID_Shp, LicenseNumber_Shp=:licenseNumber_Shp
                            WHERE ID_Shp=:shopId", array(
            'shopId' => $shopId,
            'dateTime_Shp' => time(), //Unix TimeStamp
            'type_Shp' => 1,
            'title_Shp' => $shopTitle,
            'address_Shp' => $shopAddress,
            'lat_Shp' => $shopLat,
            'lng_Shp' => $shopLng,
            'webSite_Shp' => $shopWebsite,
            'email_Shp' => $shopEmail,
            'serviceDescription_Shp' => $shopDesc,
            'adminID_Shp' => $shopAdminID,
            'licenseNumber_Shp' => $shopLicenceNumber,
        ));
    }

    public static function insert_shopTels($shopId, $arrayShopTels)
    {
        $strValues = null;
        $data = array();
        for ($i = 0; $i < count($arrayShopTels); $i++) {
            $strValues = $strValues . "(:shopID_" . $i . ", :telNumber_" . $i . ", :showToBuyer_" . $i . "), ";
            $data["shopID_" . $i] = $shopId;
            $data["telNumber_" . $i] = $arrayShopTels[$i]["phoneNumber"];
            $data["showToBuyer_" . $i] = $arrayShopTels[$i]["showToBuyer"];
        }
        // The last item has an extra ', '
        $strValues = substr($strValues, 0, -2);

        $db = Db::getInstance();
        $db->insert("INSERT INTO seller_tel_to_shop (shopID, telNumber, showToBuyer)
                          VALUES " . $strValues, $data);
    }

    public static function insert_shopMobilesSupport($shopId, $arrayShopMobilesSupport)
    {
        $strValues = null;
        $data = array();
        for ($i = 0; $i < count($arrayShopMobilesSupport); $i++) {
            $strValues = $strValues . "(:shopID_" . $i . ", :mobileSupportNumber_" . $i . ", :showToBuyer_" . $i . "), ";
            $data["shopID_" . $i] = $shopId;
            $data["mobileSupportNumber_" . $i] = $arrayShopMobilesSupport[$i]["phoneNumber"];
            $data["showToBuyer_" . $i] = $arrayShopMobilesSupport[$i]["showToBuyer"];
        }
        // The last item has an extra ', '
        $strValues = substr($strValues, 0, -2);

        $db = Db::getInstance();
        $db->insert("INSERT INTO seller_mobile_support_to_shop (shopID, mobileSupportNumber, showToBuyer)
                          VALUES " . $strValues, $data);
    }

    public static function insert_shopToCategory($shopId, $shopCategories)
    {
        $strValues = null;
        $data = array();
        for ($i = 0; $i < count($shopCategories); $i++) {
            $strValues = $strValues . "(:shopId_" . $i . ", :categoryJobId_" . $i . "), ";
            $data["shopId_" . $i] = $shopId;
            $data["categoryJobId_" . $i] = $shopCategories[$i]['categoryJobID'];
        }
        // The last item has an extra ', '
        $strValues = substr($strValues, 0, -2);

        $db = Db::getInstance();
        $db->insert("INSERT INTO tbl_shoptocat (ShopID_Stc, CatID_Stc)
                          VALUES " . $strValues, $data);
    }

    public static function update_shopTels($arrayShopTels)
    {
        $strCase1 = null;
        $strCase2 = null;
        $strWhere = null;
        $data = array();

        for ($i = 0; $i < count($arrayShopTels); $i++) {
            $strCase1 = $strCase1 . "  when telToShopID=:telToShopID_" . $i . " then :telNumber_" . $i;
            $strCase2 = $strCase2 . "  when telToShopID=:telToShopID_" . $i . " then :showToBuyer_" . $i;
            $strWhere = $strWhere . ":telToShopID_" . $i . ", ";

            $data["telToShopID_" . $i] = $arrayShopTels[$i]["phoneToShopID"];
            $data["telNumber_" . $i] = $arrayShopTels[$i]["phoneNumber"];
            $data["showToBuyer_" . $i] = $arrayShopTels[$i]["showToBuyer"];
        }
        // The last item has an extra ', '
        $strWhere = substr($strWhere, 0, -2);

        $db = Db::getInstance();
        $db->modify("UPDATE seller_tel_to_shop
                            SET telNumber = CASE $strCase1
                                            END,
                                showToBuyer = CASE $strCase2
                                              END
                            WHERE telToShopID in ($strWhere)", $data);
    }

    public static function update_shopMobilesSupport($arrayShopMobilesSupport)
    {
        $strCase1 = null;
        $strCase2 = null;
        $strWhere = null;
        $data = array();

        for ($i = 0; $i < count($arrayShopMobilesSupport); $i++) {
            $strCase1 = $strCase1 . "  when mobileSupportToShopID=:mobileSupportToShopID_" . $i . " then :mobileSupportNumber_" . $i;
            $strCase2 = $strCase2 . "  when mobileSupportToShopID=:mobileSupportToShopID_" . $i . " then :showToBuyer_" . $i;
            $strWhere = $strWhere . ":mobileSupportToShopID_" . $i . ", ";

            $data["mobileSupportToShopID_" . $i] = $arrayShopMobilesSupport[$i]["phoneToShopID"];
            $data["mobileSupportNumber_" . $i] = $arrayShopMobilesSupport[$i]["phoneNumber"];
            $data["showToBuyer_" . $i] = $arrayShopMobilesSupport[$i]["showToBuyer"];
        }
        // The last item has an extra ', '
        $strWhere = substr($strWhere, 0, -2);

        $db = Db::getInstance();
        $db->modify("UPDATE seller_mobile_support_to_shop
                            SET mobileSupportNumber = CASE $strCase1
                                            END,
                                showToBuyer = CASE $strCase2
                                              END
                            WHERE mobileSupportToShopID in ($strWhere)", $data);
    }

    public static function update_shopToCategory($arrayShopCategories)
    {
        $strCase1 = null;
        $strWhere = null;
        $data = array();

        for ($i = 0; $i < count($arrayShopCategories); $i++) {
            $strCase1 = $strCase1 . "  when ID_Stc=:shopToCategoryID_" . $i . " then :categoryJobID_" . $i;
            $strWhere = $strWhere . ":shopToCategoryID_" . $i . ", ";

            $data["shopToCategoryID_" . $i] = $arrayShopCategories[$i]["shopToCategoryID"];
            $data["categoryJobID_" . $i] = $arrayShopCategories[$i]["categoryJobID"];
        }
        // The last item has an extra ', '
        $strWhere = substr($strWhere, 0, -2);

        $db = Db::getInstance();
        $db->modify("UPDATE tbl_shoptocat
                            SET CatID_Stc = CASE $strCase1
                                            END
                            WHERE ID_Stc in ($strWhere)", $data);
    }

    public static function delete_shopTels($arrayShopTels)
    {
        $strWhereIn = null;
        $data = array();

        for ($i = 0; $i < count($arrayShopTels); $i++) {
            $strWhereIn = $strWhereIn . ":telToShopID_" . $i . ", ";

            $data["telToShopID_" . $i] = $arrayShopTels[$i]["phoneToShopID"];
        }
        // The last item has an extra ', '
        $strWhereIn = substr($strWhereIn, 0, -2);

        $db = Db::getInstance();
        $db->modify("DELETE FROM seller_tel_to_shop WHERE (telToShopID) IN ($strWhereIn)", $data);
    }

    public static function delete_shopMobilesSupport($arrayShopMobilesSupport)
    {
        $strWhereIn = null;
        $data = array();

        for ($i = 0; $i < count($arrayShopMobilesSupport); $i++) {
            $strWhereIn = $strWhereIn . ":mobileSupportToShopID_" . $i . ", ";

            $data["mobileSupportToShopID_" . $i] = $arrayShopMobilesSupport[$i]["phoneToShopID"];
        }
        // The last item has an extra ', '
        $strWhereIn = substr($strWhereIn, 0, -2);

        $db = Db::getInstance();
        $db->modify("DELETE FROM seller_mobile_support_to_shop WHERE (mobileSupportToShopID) IN ($strWhereIn)", $data);
    }

    public static function delete_shopToCategory($shopCategories)
    {
        $strWhereIn = null;
        $data = array();

        for ($i = 0; $i < count($shopCategories); $i++) {
            $strWhereIn = $strWhereIn . ":shopToCategoryID_" . $i . ", ";

            $data["shopToCategoryID_" . $i] = $shopCategories[$i]["shopToCategoryID"];
        }
        // The last item has an extra ', '
        $strWhereIn = substr($strWhereIn, 0, -2);

        $db = Db::getInstance();
        $db->modify("DELETE FROM tbl_shoptocat WHERE (ID_Stc) IN ($strWhereIn)", $data);
    }

    public static function upload_shopLogo_by_shopId($shopId, $fileName)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE tbl_shop SET imgShopLogo=:fileName WHERE ID_Shp=:shopId", array(
            'shopId' => $shopId,
            'fileName' => $fileName,
        ));
    }

    public static function upload_shopImages_by_shopId($shopId, $fileName)
    {
        $db = Db::getInstance();
        $db->insert("INSERT INTO tbl_imagetoshop (ShopID_Its, ImageName_Its)
                          VALUES (:shopId, :fileName)", array(
            'shopId' => $shopId,
            'fileName' => $fileName,
        ));
    }

    public static function fetch_jobsCategory_by_jobsCategoryId($jobsCategoryId)
    {
        $db = Db::getInstance();
        $jobsCategories = $db->first("SELECT * FROM tbl_category WHERE ID_Cgy=:jobsCategoryId", array(
            'jobsCategoryId' => $jobsCategoryId,
        ));

        return $jobsCategories;
    }

    public static function fetch_jobsCategory_by_parentId($parentId)
    {
        $db = Db::getInstance();
        $jobsCategoriesByParentId = $db->query("SELECT * FROM tbl_category WHERE 	ParnetID_Cgy=:parentId", array(
            'parentId' => $parentId,
        ));

        return $jobsCategoriesByParentId;
    }

    public static function fetch_categoryProductAndServiceLevel1_by_parentId($parentId)
    {
        $db = Db::getInstance();
        $categoryProductAndServiceLevel1ByParentId = $db->query("SELECT * FROM category_product_and_service_level1 WHERE categoryProductAndServiceLevel0Id=:parentId", array(
            'parentId' => $parentId,
        ));

        return $categoryProductAndServiceLevel1ByParentId;
    }

    public static function fetch_categoryProductAndServiceLevel0_by_categoryJobsId($categoryJobsId)
    {
        $db = Db::getInstance();
        $categoryProductAndServiceLevel0 = $db->query("SELECT category_jobs_to_category_product_and_service_level0.categoryProductAndServiceLevel0Id, title FROM category_jobs_to_category_product_and_service_level0
            INNER JOIN category_product_and_service_level0 ON category_jobs_to_category_product_and_service_level0.categoryProductAndServiceLevel0Id = category_product_and_service_level0.categoryProductAndServiceLevel0Id 
            WHERE categoryJobsID=:categoryJobsId", array(
            'categoryJobsId' => $categoryJobsId,
        ));

        return $categoryProductAndServiceLevel0;
    }

    public static function fetch_categoryProductAndServiceLevel0_by_shopId($shopId)
    {
        $db = Db::getInstance();
        $categoryProductAndServiceLevel0 = $db->query("SELECT category_product_and_service_level0.categoryProductAndServiceLevel0Id, title, isService FROM tbl_shop
                    INNER JOIN tbl_shoptocat ON tbl_shoptocat.ShopID_Stc = tbl_shop.ID_Shp
                    INNER JOIN category_jobs_to_category_product_and_service_level0 ON category_jobs_to_category_product_and_service_level0.categoryJobsID = tbl_shoptocat.CatID_Stc
                    INNER JOIN category_product_and_service_level0 ON category_product_and_service_level0.categoryProductAndServiceLevel0Id = category_jobs_to_category_product_and_service_level0.categoryProductAndServiceLevel0Id                   
                    WHERE tbl_shop.ID_Shp = :shopId
                    
                    UNION ALL
                    
                    SELECT category_product_and_service_level0.categoryProductAndServiceLevel0Id, title, isService FROM shop_to_category_product_and_service_level0
                    INNER JOIN category_product_and_service_level0 ON category_product_and_service_level0.categoryProductAndServiceLevel0Id = shop_to_category_product_and_service_level0.categoryProductAndServiceLevel0Id                   
                    WHERE shop_to_category_product_and_service_level0.shopId = :shopId", array(
            'shopId' => $shopId,
        ));

        return $categoryProductAndServiceLevel0;
    }

    public static function fetch_productModel_by_barcodeNumber($barcodeNumber)
    {
        $db = Db::getInstance();
        $productModel = $db->first("SELECT * FROM product_model WHERE barcodeNumber=:barcodeNumber", array(
            'barcodeNumber' => $barcodeNumber,
        ));

        return $productModel;
    }

    public static function fetch_serviceName_by_serviceNameId($serviceNameId)
    {
        $db = Db::getInstance();
        $serviceName = $db->first("SELECT * FROM service_name WHERE serviceNameId=:serviceNameId", array(
            'serviceNameId' => $serviceNameId,
        ));

        return $serviceName;
    }

    public static function fetch_productModel_by_barcodeNumberAndShopId($barcodeNumber, $shopId)
    {
        $db = Db::getInstance();
        $productModel = $db->first("SELECT * FROM product_model 
                                            INNER JOIN shop_to_product_model ON product_model.productModelId = shop_to_product_model.productModelId 
                                            WHERE barcodeNumber=:barcodeNumber AND shopId=:shopId", array(
            'barcodeNumber' => $barcodeNumber,
            'shopId' => $shopId,
        ));

        return $productModel;
    }

    public static function fetch_serviceName_by_serviceNameIdAndShopId($serviceNameId, $shopId)
    {
        $db = Db::getInstance();
        $serviceName = $db->first("SELECT * FROM service_name 
                                            INNER JOIN shop_to_service_name ON service_name.serviceNameId = shop_to_service_name.serviceNameId 
                                            WHERE service_name.serviceNameId=:serviceNameId AND shopId=:shopId", array(
            'serviceNameId' => $serviceNameId,
            'shopId' => $shopId,
        ));

        return $serviceName;
    }

    public static function fetch_productModel_by_productModelId($productModelId)
    {
        $db = Db::getInstance();
        $productModel = $db->first("SELECT * FROM product_model WHERE productModelId=:productModelId", array(
            'productModelId' => $productModelId,
        ));

        return $productModel;
    }

    public static function fetch_productModel_by_productModelIdAndShopId($productModelId, $shopId)
    {
        $db = Db::getInstance();
        $productModel = $db->first("SELECT * FROM product_model 
                                            INNER JOIN shop_to_product_model ON product_model.productModelId = shop_to_product_model.productModelId 
                                            WHERE product_model.productModelId=:productModelId AND shopId=:shopId", array(
            'productModelId' => $productModelId,
            'shopId' => $shopId,
        ));

        return $productModel;
    }

    public static function fetch_categoryProductAndServiceLevel1_by_categoryProductAndServiceLevel1Id($categoryProductAndServiceLevel1Id)
    {
        $db = Db::getInstance();
        $categoryProductAndServiceTitle = $db->first("SELECT title FROM category_product_and_service_level1 WHERE categoryProductAndServiceLevel1Id=:categoryProductAndServiceLevel1Id", array(
            'categoryProductAndServiceLevel1Id' => $categoryProductAndServiceLevel1Id,
        ));

        return $categoryProductAndServiceTitle;
    }

    public static function fetch_brandNameAndOriginCountry_by_productProducingCountryId($productProducingCountryId)
    {
        $db = Db::getInstance();
        $productBrandNameAndOriginCountry = $db->first("SELECT brandName, originCountryName FROM product_producing_country 
                                                INNER JOIN product_brand ON product_producing_country.productBrandID=product_brand.productBrandID 
                                                INNER JOIN product_origin_country ON product_brand.productOriginCountryID=product_origin_country.productOriginCountryID 
                                                WHERE productProducingCountryId=:productProducingCountryId", array(
            'productProducingCountryId' => $productProducingCountryId,
        ));

        return $productBrandNameAndOriginCountry;
    }

    public static function fetch_productProducingCountryName_by_productProducingCountryId($productProducingCountryId)
    {
        $db = Db::getInstance();
        $productProducingCountryName = $db->first("SELECT originCountryName FROM product_producing_country INNER JOIN product_origin_country ON product_producing_country.productOriginCountryId=product_origin_country.productOriginCountryId WHERE productProducingCountryId=:productProducingCountryId", array(
            'productProducingCountryId' => $productProducingCountryId,
        ));

        return $productProducingCountryName;
    }

    public static function fetch_productDefaultPictures_by_productModelId($productModelId)
    {
        $db = Db::getInstance();
        $productDefaultPictures = $db->query("SELECT picture FROM product_default_picture WHERE productModelId=:productModelId", array(
            'productModelId' => $productModelId,
        ));

        return $productDefaultPictures;
    }

    public static function fetch_serviceDefaultPictures_by_serviceNameId($serviceNameId)
    {
        $db = Db::getInstance();
        $serviceDefaultPictures = $db->query("SELECT picture FROM service_default_picture WHERE serviceNameId=:serviceNameId", array(
            'serviceNameId' => $serviceNameId,
        ));

        return $serviceDefaultPictures;
    }

    public static function fetch_shopProductSpecificPictures_by_shopToProductModelId($shopToProductModelId)
    {
        $db = Db::getInstance();
        $shopProductSpecificPictures = $db->query("SELECT picture FROM shop_product_specific_picture WHERE shopToProductModelId=:shopToProductModelId", array(
            'shopToProductModelId' => $shopToProductModelId,
        ));

        return $shopProductSpecificPictures;
    }

    public static function fetch_shopServiceSpecificPictures_by_shopToServiceNameId($shopToServiceNameId)
    {
        $db = Db::getInstance();
        $shopServiceSpecificPictures = $db->query("SELECT picture FROM shop_service_specific_picture WHERE shopToServiceNameId=:shopToServiceNameId", array(
            'shopToServiceNameId' => $shopToServiceNameId,
        ));

        return $shopServiceSpecificPictures;
    }

    public static function fetch_productSingleValueAttributes_by_productModelId($productModelId)
    {
        $db = Db::getInstance();
        $productSingleValueAttributes = $db->query("SELECT AttributeName, product_model_single_value_attribute.value FROM product_model_single_value_attribute
                                    INNER JOIN product_and_service_attribute ON product_model_single_value_attribute.productAndServiceAttributeId = product_and_service_attribute.productAndServiceAttributeId 
                                    WHERE productModelId=:productModelId", array(
            'productModelId' => $productModelId,
        ));

        return $productSingleValueAttributes;
    }

    public static function fetch_serviceSingleValueAttributes_by_serviceNameId($serviceNameId)
    {
        $db = Db::getInstance();
        $serviceSingleValueAttributes = $db->query("SELECT AttributeName, service_single_value_attribute.value FROM service_single_value_attribute
                                    INNER JOIN product_and_service_attribute ON service_single_value_attribute.productAndServiceAttributeId = product_and_service_attribute.productAndServiceAttributeId 
                                    WHERE serviceNameId=:serviceNameId", array(
            'serviceNameId' => $serviceNameId,
        ));

        return $serviceSingleValueAttributes;
    }

    public static function fetch_productMultipleValueAttributes_by_productModelId($productModelId)
    {
        $db = Db::getInstance();
        $productMultipleValueAttributes = $db->query("SELECT AttributeName, product_and_service_multiple_value_attribute.value FROM product_model_to_product_and_service_multiple_value_attribute 
                                    INNER JOIN product_and_service_multiple_value_attribute ON product_model_to_product_and_service_multiple_value_attribute.productAndServiceMultipleValueAttributeId = product_and_service_multiple_value_attribute.productAndServiceMultipleValueAttributeId 
                                    INNER JOIN product_and_service_attribute ON product_and_service_multiple_value_attribute.productAndServiceAttributeId = product_and_service_attribute.productAndServiceAttributeId 
                                    WHERE productModelId=:productModelId", array(
            'productModelId' => $productModelId,
        ));

        return $productMultipleValueAttributes;
    }

    public static function fetch_serviceMultipleValueAttributes_by_serviceNameId($serviceNameId)
    {
        $db = Db::getInstance();
        $serviceMultipleValueAttributes = $db->query("SELECT AttributeName, product_and_service_multiple_value_attribute.value FROM service_to_product_and_service_multiple_value_attribute 
                                    INNER JOIN product_and_service_multiple_value_attribute ON service_to_product_and_service_multiple_value_attribute.productAndServiceMultipleValueAttributeId = product_and_service_multiple_value_attribute.productAndServiceMultipleValueAttributeId 
                                    INNER JOIN product_and_service_attribute ON product_and_service_multiple_value_attribute.productAndServiceAttributeId = product_and_service_attribute.productAndServiceAttributeId 
                                    WHERE serviceNameId=:serviceNameId", array(
            'serviceNameId' => $serviceNameId,
        ));

        return $serviceMultipleValueAttributes;
    }

    public static function fetch_productModelToProductColor_by_productModelId($productModelId)
    {
        $db = Db::getInstance();
        $productModelColors = $db->query("SELECT product_color.* FROM product_model_to_product_color 
                          INNER JOIN product_color ON product_model_to_product_color.productColorId = product_color.productColorId
                          WHERE product_model_to_product_color.productModelId = :productModelId", array(
            'productModelId' => $productModelId,
        ));

        return $productModelColors;
    }

    public static function insert_productForSpecificShop($shopId, $productModelId, $shopProductSpecificDesc, $hasSpecificPicture)
    {
        $db = Db::getInstance();
        $shopToProductModelId = $db->insert("INSERT IGNORE INTO shop_to_product_model (shopId, productModelId, shopProductSpecificDesc, hasSpecificPicture)
                          VALUES (:shopId, :productModelId, :shopProductSpecificDesc, :hasSpecificPicture)", array(
            'shopId' => $shopId,
            'productModelId' => $productModelId,
            'shopProductSpecificDesc' => $shopProductSpecificDesc,
            'hasSpecificPicture' => $hasSpecificPicture,
        ));

        return $shopToProductModelId;
    }

    public static function insert_serviceForSpecificShop($shopId, $serviceNameId, $shopServiceSpecificDesc, $hasSpecificPicture, $servicePrice, $serviceQuantity, $serviceLimitOrderQuantity)
    {
        $db = Db::getInstance();
        $shopToServiceNameId = $db->insert("INSERT IGNORE INTO shop_to_service_name (shopId, serviceNameId, shopServiceSpecificDesc, hasSpecificPicture, price, quantity, limitOrderQuantity)
                          VALUES (:shopId, :serviceNameId, :shopServiceSpecificDesc, :hasSpecificPicture, :price, :quantity, :limitOrderQuantity)", array(
            'shopId' => $shopId,
            'serviceNameId' => $serviceNameId,
            'shopServiceSpecificDesc' => $shopServiceSpecificDesc,
            'hasSpecificPicture' => $hasSpecificPicture,
            'price' => $servicePrice,
            'quantity' => $serviceQuantity,
            'limitOrderQuantity' => $serviceLimitOrderQuantity,
        ));

        return $shopToServiceNameId;
    }

    public static function update_stockPriceAndShopProduct_by_stockPriceId($stockPriceId, $productPrice, $productQuantity, $productLimitOrderQuantity, $shopProductSpecificDesc, $hasSpecificPicture)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE stock_price, shop_to_product_model 
                    SET price=:price, quantity=:quantity, limitOrderQuantity=:limitOrderQuantity, shopProductSpecificDesc =:shopProductSpecificDesc , hasSpecificPicture=:hasSpecificPicture
                    WHERE stockPriceId=:stockPriceId AND stock_price.shopToProductModelId = shop_to_product_model.shopToProductModelId", array(
            'stockPriceId' => $stockPriceId,
            'price' => $productPrice,
            'quantity' => $productQuantity,
            'limitOrderQuantity' => $productLimitOrderQuantity,
            'shopProductSpecificDesc' => $shopProductSpecificDesc,
            'hasSpecificPicture' => $hasSpecificPicture,
        ));
    }

    public static function update_stockColorPriceAndShopProduct_by_stockColorPriceId($stockColorPriceId, $productColorId, $productPrice, $productQuantity, $productLimitOrderQuantity, $shopProductSpecificDesc, $hasSpecificPicture)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE stock_color_price, shop_to_product_model 
                    SET productColorId=:productColorId, price=:price, quantity=:quantity, limitOrderQuantity=:limitOrderQuantity, shopProductSpecificDesc=:shopProductSpecificDesc , hasSpecificPicture=:hasSpecificPicture
                    WHERE stockColorPriceId=:stockColorPriceId AND stock_color_price.shopToProductModelId = shop_to_product_model.shopToProductModelId", array(
            'stockColorPriceId' => $stockColorPriceId,
            'productColorId' => $productColorId,
            'price' => $productPrice,
            'quantity' => $productQuantity,
            'limitOrderQuantity' => $productLimitOrderQuantity,
            'shopProductSpecificDesc' => $shopProductSpecificDesc,
            'hasSpecificPicture' => $hasSpecificPicture,
        ));
    }

    public static function update_stockClothColorSizePriceAndShopProduct_by_stockClothColorSizePriceId($stockClothColorSizePriceId, $productColorId, $clothSizeTableValueId, $productPrice, $productQuantity, $productLimitOrderQuantity, $shopProductSpecificDesc, $hasSpecificPicture)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE stock_cloth_color_size_price, shop_to_product_model 
                    SET productColorId=:productColorId, clothSizeTableValueId=:clothSizeTableValueId, price=:price, quantity=:quantity, 
                        limitOrderQuantity=:limitOrderQuantity, shopProductSpecificDesc=:shopProductSpecificDesc , hasSpecificPicture=:hasSpecificPicture
                    WHERE stockClothColorSizePriceId=:stockClothColorSizePriceId AND stock_cloth_color_size_price.shopToProductModelId = shop_to_product_model.shopToProductModelId", array(
            'stockClothColorSizePriceId' => $stockClothColorSizePriceId,
            'productColorId' => $productColorId,
            'clothSizeTableValueId' => $clothSizeTableValueId,
            'price' => $productPrice,
            'quantity' => $productQuantity,
            'limitOrderQuantity' => $productLimitOrderQuantity,
            'shopProductSpecificDesc' => $shopProductSpecificDesc,
            'hasSpecificPicture' => $hasSpecificPicture,
        ));
    }

    public static function update_serviceForSpecificShop_by_shopToServiceNameId($shopToServiceNameId, $shopServiceSpecificDesc, $hasSpecificPicture, $servicePrice, $serviceQuantity, $serviceLimitOrderQuantity)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE shop_to_service_name SET shopServiceSpecificDesc=:shopServiceSpecificDesc, hasSpecificPicture=:hasSpecificPicture ,price=:servicePrice, quantity=:serviceQuantity, limitOrderQuantity=:limitOrderQuantity
                            WHERE shopToServiceNameId=:shopToServiceNameId", array(
            'shopToServiceNameId' => $shopToServiceNameId,
            'shopServiceSpecificDesc' => $shopServiceSpecificDesc,
            'hasSpecificPicture' => $hasSpecificPicture,
            'servicePrice' => $servicePrice,
            'serviceQuantity' => $serviceQuantity,
            'limitOrderQuantity' => $serviceLimitOrderQuantity,
        ));
    }

    public static function fetch_shopToProductModel_by_shopIdAndProductModelId($shopId, $productModelId)
    {
        $db = Db::getInstance();
        $shopToProductModel = $db->first("SELECT * FROM shop_to_product_model WHERE shopId=:shopId AND productModelId=:productModelId", array(
            'shopId' => $shopId,
            'productModelId' => $productModelId,
        ));

        return $shopToProductModel;
    }

    public static function fetch_shopToServiceName_by_shopIdAndServiceNameId($shopId, $serviceNameId)
    {
        $db = Db::getInstance();
        $shopToServiceName = $db->first("SELECT * FROM shop_to_service_name WHERE shopId=:shopId AND serviceNameId=:serviceNameId", array(
            'shopId' => $shopId,
            'serviceNameId' => $serviceNameId,
        ));

        return $shopToServiceName;
    }

    public static function insert_stockPrice($shopToProductModelId, $productQuantity, $productLimitOrderQuantity, $productPrice)
    {
        $db = Db::getInstance();
        $db->insert("INSERT INTO stock_price (shopToProductModelId, quantity, limitOrderQuantity, price)
                          VALUES (:shopToProductModelId, :quantity, :limitOrderQuantity, :price)", array(
            'shopToProductModelId' => $shopToProductModelId,
            'quantity' => $productQuantity,
            'limitOrderQuantity' => $productLimitOrderQuantity,
            'price' => $productPrice,
        ));
    }

    public static function insert_stockColorPrice($shopToProductModelId, $productColorId, $productQuantity, $limitOrderQuantity, $productPrice)
    {
        $db = Db::getInstance();
        $db->insert("INSERT INTO stock_color_price (shopToProductModelId, productColorId, quantity, limitOrderQuantity, price)
                          VALUES (:shopToProductModelId, :productColorId, :quantity, :limitOrderQuantity, :price)", array(
            'shopToProductModelId' => $shopToProductModelId,
            'productColorId' => $productColorId,
            'quantity' => $productQuantity,
            'limitOrderQuantity' => $limitOrderQuantity,
            'price' => $productPrice,
        ));
    }

    public static function fetch_stockPrice_by_shopToProductModelId($shopToProductModelId)
    {
        $db = Db::getInstance();
        $shopToProductModel = $db->query("SELECT * FROM stock_price WHERE shopToProductModelId=:shopToProductModelId", array(
            'shopToProductModelId' => $shopToProductModelId,
        ));

        return $shopToProductModel;
    }

    public static function fetch_stockColorPrice_by_shopToProductModelId($shopToProductModelId)
    {
        $db = Db::getInstance();
        $shopStockColorPrice = $db->query("SELECT * FROM stock_color_price 
                                                  INNER JOIN product_color ON stock_color_price.productColorId = product_color.productColorId 
                                                  WHERE shopToProductModelId=:shopToProductModelId", array(
            'shopToProductModelId' => $shopToProductModelId,
        ));

        return $shopStockColorPrice;
    }

    public static function search_product_by_modelName($searchPhrase)
    {
        $db = Db::getInstance();
        $searchResult = $db->query("SELECT * FROM product_model WHERE modelName LIKE :searchPhrase", array(
            'searchPhrase' => "%$searchPhrase%",
        ));
        return $searchResult;
    }

    public static function search_serviceByName($searchPhrase)
    {
        $db = Db::getInstance();
        $searchResult = $db->query("SELECT * FROM service_name WHERE serviceName LIKE :searchPhrase", array(
            'searchPhrase' => "%$searchPhrase%",
        ));
        return $searchResult;
    }

    public static function searchProduct_by_modelNameAndShopId($searchPhrase, $shopId)
    {
        $db = Db::getInstance();
        $searchResult = $db->query("SELECT * FROM product_model 
                                            INNER JOIN shop_to_product_model ON product_model.productModelId = shop_to_product_model.productModelId 
                                            WHERE modelName LIKE :searchPhrase AND shopId=:shopId", array(
            'searchPhrase' => "%$searchPhrase%",
            'shopId' => $shopId,
        ));
        return $searchResult;
    }

    public static function searchService_by_nameAndShopId($searchPhrase, $shopId)
    {
        $db = Db::getInstance();
        $searchResult = $db->query("SELECT * FROM service_name 
                                            INNER JOIN shop_to_service_name ON service_name.serviceNameId = shop_to_service_name.serviceNameId 
                                            WHERE serviceName LIKE :searchPhrase AND shopId=:shopId", array(
            'searchPhrase' => "%$searchPhrase%",
            'shopId' => $shopId,
        ));
        return $searchResult;
    }

    public static function searchProductInShopGuild_by_modelNameAndShopId($searchPhrase, $shopId, $isSearchForUpdate)
    {
        // Table 'shop_to_category_product_and_service_level0' = one shop can sell specific product and service that not have in own shopCategory

        $findProductsSetForSaleInnerJoin = "";
        $findProductsSetForSaleWhere = "";
        if ($isSearchForUpdate == true) {
            $findProductsSetForSaleInnerJoin = "INNER JOIN shop_to_product_model ON product_model.productModelId = shop_to_product_model.productModelId";
            $findProductsSetForSaleWhere = " AND shop_to_product_model.shopId=:shopId";
        }
        $db = Db::getInstance();
        $searchResult = $db->query("SELECT product_model.*, isService FROM tbl_shop
                      INNER JOIN tbl_shoptocat ON tbl_shoptocat.ShopID_Stc = tbl_shop.ID_Shp
                      INNER JOIN category_jobs_to_category_product_and_service_level0 ON category_jobs_to_category_product_and_service_level0.categoryJobsID = tbl_shoptocat.CatID_Stc
                      INNER JOIN category_product_and_service_level0 ON category_product_and_service_level0.categoryProductAndServiceLevel0Id = category_jobs_to_category_product_and_service_level0.categoryProductAndServiceLevel0Id
                      INNER JOIN category_product_and_service_level1 ON category_product_and_service_level1.categoryProductAndServiceLevel0Id = category_product_and_service_level0.categoryProductAndServiceLevel0Id
                      INNER JOIN product_model ON product_model.categoryProductAndServiceLevel1Id = category_product_and_service_level1.categoryProductAndServiceLevel1Id
                      $findProductsSetForSaleInnerJoin
                      WHERE product_model.modelName LIKE :searchPhrase AND tbl_shop.ID_Shp=:shopId $findProductsSetForSaleWhere
                      
                      UNION ALL
                      
                      SELECT product_model.*, isService FROM shop_to_category_product_and_service_level0
                      INNER JOIN category_product_and_service_level0 ON category_product_and_service_level0.categoryProductAndServiceLevel0Id = shop_to_category_product_and_service_level0.categoryProductAndServiceLevel0Id
                      INNER JOIN category_product_and_service_level1 ON category_product_and_service_level1.categoryProductAndServiceLevel0Id = category_product_and_service_level0.categoryProductAndServiceLevel0Id
                      INNER JOIN product_model ON product_model.categoryProductAndServiceLevel1Id = category_product_and_service_level1.categoryProductAndServiceLevel1Id
                      $findProductsSetForSaleInnerJoin
                      WHERE product_model.modelName LIKE :searchPhrase AND shop_to_category_product_and_service_level0.shopId=:shopId $findProductsSetForSaleWhere", array(
            'searchPhrase' => "%$searchPhrase%",
            'shopId' => $shopId,
        ));
        return $searchResult;
    }

    public static function searchServiceInShopGuild_by_nameAndShopId($searchPhrase, $shopId, $isSearchForUpdate)
    {
        // Table 'shop_to_category_product_and_service_level0' = one shop can sell specific product and service that not have in own shopCategory

        $findServicesSetForSaleInnerJoin = "";
        $findServicesSetForSaleWhere = "";
        if ($isSearchForUpdate == true) {
            $findServicesSetForSaleInnerJoin = "INNER JOIN shop_to_service_name ON service_name.serviceNameId = shop_to_service_name.serviceNameId";
            $findServicesSetForSaleWhere = " AND shop_to_service_name.shopId=:shopId";
        }
        $db = Db::getInstance();
        $searchResult = $db->query("SELECT service_name.*, isService FROM tbl_shop
                      INNER JOIN tbl_shoptocat ON tbl_shoptocat.ShopID_Stc = tbl_shop.ID_Shp
                      INNER JOIN category_jobs_to_category_product_and_service_level0 ON category_jobs_to_category_product_and_service_level0.categoryJobsID = tbl_shoptocat.CatID_Stc
                      INNER JOIN category_product_and_service_level0 ON category_product_and_service_level0.categoryProductAndServiceLevel0Id = category_jobs_to_category_product_and_service_level0.categoryProductAndServiceLevel0Id
                      INNER JOIN category_product_and_service_level1 ON category_product_and_service_level1.categoryProductAndServiceLevel0Id = category_product_and_service_level0.categoryProductAndServiceLevel0Id
                      INNER JOIN service_name ON service_name.categoryProductAndServiceLevel1Id = category_product_and_service_level1.categoryProductAndServiceLevel1Id
                      $findServicesSetForSaleInnerJoin
                      WHERE service_name.serviceName LIKE :searchPhrase AND tbl_shop.ID_Shp=:shopId $findServicesSetForSaleWhere
                      
                      UNION ALL
                      
                      SELECT service_name.*, isService FROM shop_to_category_product_and_service_level0
                      INNER JOIN category_product_and_service_level0 ON category_product_and_service_level0.categoryProductAndServiceLevel0Id = shop_to_category_product_and_service_level0.categoryProductAndServiceLevel0Id
                      INNER JOIN category_product_and_service_level1 ON category_product_and_service_level1.categoryProductAndServiceLevel0Id = category_product_and_service_level0.categoryProductAndServiceLevel0Id
                      INNER JOIN service_name ON service_name.categoryProductAndServiceLevel1Id = category_product_and_service_level1.categoryProductAndServiceLevel1Id
                      $findServicesSetForSaleInnerJoin
                      WHERE service_name.serviceName LIKE :searchPhrase AND shop_to_category_product_and_service_level0.shopId=:shopId $findServicesSetForSaleWhere", array(
            'searchPhrase' => "%$searchPhrase%",
            'shopId' => $shopId,
        ));
        return $searchResult;
    }

    public static function fetch_productModel_by_categoryProductAndServiceLevel1Id($categoryProductAndServiceLevel1Id)
    {
        $db = Db::getInstance();
        $productModels = $db->query("SELECT * FROM product_model WHERE categoryProductAndServiceLevel1Id=:categoryProductAndServiceLevel1Id", array(
            'categoryProductAndServiceLevel1Id' => $categoryProductAndServiceLevel1Id,
        ));
        return $productModels;
    }

    public static function fetch_service_by_categoryProductAndServiceLevel1Id($categoryProductAndServiceLevel1Id)
    {
        $db = Db::getInstance();
        $productModels = $db->query("SELECT * FROM service_name WHERE categoryProductAndServiceLevel1Id=:categoryProductAndServiceLevel1Id", array(
            'categoryProductAndServiceLevel1Id' => $categoryProductAndServiceLevel1Id,
        ));
        return $productModels;
    }

    public static function fetch_productModelsInShop_by_categoryProductAndServiceLevel1Id($shopId, $categoryProductAndServiceLevel1Id)
    {
        // bottom method = same with this only add orderBy

        $db = Db::getInstance();
        $productModelsInShop = $db->query("SELECT * FROM product_model 
                                            INNER JOIN shop_to_product_model ON product_model.productModelId = shop_to_product_model.productModelId 
                                            WHERE categoryProductAndServiceLevel1Id=:categoryProductAndServiceLevel1Id AND shopId=:shopId", array(
            'categoryProductAndServiceLevel1Id' => $categoryProductAndServiceLevel1Id,
            'shopId' => $shopId,
        ));
        return $productModelsInShop;
    }

    public static function fetch_productModelsInShopWithPrice_by_categoryProductAndServiceLevel1Id($shopId, $orderBy, $categoryProductAndServiceLevel1Id)
    {
        if (strcmp($orderBy, "new") == 0) {
            $orderBy = 'ORDER BY shopToProductModelId DESC';
        } else if (strcmp($orderBy, "visitCount") == 0) {
            $orderBy = 'ORDER BY visitCount DESC';
        } else if (strcmp($orderBy, "sellCount") == 0) {
            $orderBy = 'ORDER BY sellCount DESC';
        } else if (strcmp($orderBy, "priceLessToHigh") == 0) {
            $orderBy = 'ORDER BY price ASC';
        } else if (strcmp($orderBy, "priceHighToLess") == 0) {
            $orderBy = 'ORDER BY price DESC';
        }

        $db = Db::getInstance();
        $productModelsInShop = $db->query("(SELECT product_model.*, shop_to_product_model.*, price, discount FROM product_model 
                        INNER JOIN shop_to_product_model ON product_model.productModelId = shop_to_product_model.productModelId 
                        INNER JOIN stock_price ON shop_to_product_model.shopToProductModelId = stock_price.shopToProductModelId
                        WHERE product_model.hasColor = 0 AND categoryProductAndServiceLevel1Id=:categoryProductAndServiceLevel1Id AND shopId=:shopId
                        GROUP BY product_model.productModelId)
                        
                        UNION ALL
                        
                        (SELECT product_model.*, shop_to_product_model.*, price, discount FROM product_model 
                        INNER JOIN shop_to_product_model ON product_model.productModelId = shop_to_product_model.productModelId 
                        INNER JOIN stock_color_price ON shop_to_product_model.shopToProductModelId = stock_color_price.shopToProductModelId
                        WHERE product_model.hasColor = 1 AND product_model.isCloth = 0 AND categoryProductAndServiceLevel1Id=:categoryProductAndServiceLevel1Id AND shopId=:shopId
                        GROUP BY product_model.productModelId)
                        
                        UNION ALL
                        
                        (SELECT product_model.*, shop_to_product_model.*, price, discount FROM product_model 
                        INNER JOIN shop_to_product_model ON product_model.productModelId = shop_to_product_model.productModelId 
                        INNER JOIN stock_cloth_color_size_price ON shop_to_product_model.shopToProductModelId = stock_cloth_color_size_price.shopToProductModelId
                        WHERE product_model.isCloth = 1 AND categoryProductAndServiceLevel1Id=:categoryProductAndServiceLevel1Id AND shopId=:shopId
                        GROUP BY product_model.productModelId)
                        $orderBy", array(
            'categoryProductAndServiceLevel1Id' => $categoryProductAndServiceLevel1Id,
            'shopId' => $shopId,
        ));

        return $productModelsInShop;
    }

    public static function fetch_serviceNamesInShop_by_categoryProductAndServiceLevel1Id($shopId, $orderBy, $categoryProductAndServiceLevel1Id)
    {
        if (strcmp($orderBy, "new") == 0) {
            $orderBy = 'ORDER BY shopToServiceNameId DESC';
        } else if (strcmp($orderBy, "visitCount") == 0) {
            $orderBy = 'ORDER BY visitCount DESC';
        } else if (strcmp($orderBy, "sellCount") == 0) {
            $orderBy = 'ORDER BY sellCount DESC';
        } else if (strcmp($orderBy, "priceLessToHigh") == 0) {
            $orderBy = 'ORDER BY price ASC';
        } else if (strcmp($orderBy, "priceHighToLess") == 0) {
            $orderBy = 'ORDER BY price DESC';
        }

        $db = Db::getInstance();
        $serviceNamesInShop = $db->query("SELECT * FROM service_name 
                                            INNER JOIN shop_to_service_name ON service_name.serviceNameId = shop_to_service_name.serviceNameId 
                                            WHERE categoryProductAndServiceLevel1Id=:categoryProductAndServiceLevel1Id AND shopId=:shopId
                                            $orderBy", array(
            'categoryProductAndServiceLevel1Id' => $categoryProductAndServiceLevel1Id,
            'shopId' => $shopId,
        ));
        return $serviceNamesInShop;
    }

    public static function insert_ticketSupport($ticketSubjectId, $ticketPriorityId, $userAccountParentId, $title, $creationTime, $isActive, $isSellerTicket)
    {
        $db = Db::getInstance();
        $ticketSupportId = $db->insert("INSERT INTO ticket_support (ticketSubjectId, ticketPriorityId, userAccountParentId, title, creationTime, isActive, isSellerTicket)
                          VALUES (:ticketSubjectId, :ticketPriorityId, :userAccountParentId, :title, :creationTime, :isActive, :isSellerTicket)", array(
            'ticketSubjectId' => $ticketSubjectId,
            'ticketPriorityId' => $ticketPriorityId,
            'userAccountParentId' => $userAccountParentId,
            'title' => $title,
            'creationTime' => $creationTime,
            'isActive' => $isActive,
            'isSellerTicket' => $isSellerTicket,
        ));

        return $ticketSupportId;
    }

    public static function insert_ticketSupportReplies($ticketSupportId, $userAccountParentId, $replyMessage, $creationTime)
    {
        $db = Db::getInstance();
        $ticketSupportRepliesId = $db->insert("INSERT INTO ticket_support_replies (ticketSupportId, userAccountParentId, replyMessage, creationTime)
                          VALUES (:ticketSupportId, :userAccountParentId, :replyMessage, :creationTime)", array(
            'ticketSupportId' => $ticketSupportId,
            'userAccountParentId' => $userAccountParentId,
            'replyMessage' => $replyMessage,
            'creationTime' => $creationTime,
        ));

        return $ticketSupportRepliesId;
    }

    public static function fetch_ticketSupport_by_userAccountParentId($userAccountParentId)
    {
        $db = Db::getInstance();
        $ticketSupport = $db->query("SELECT * FROM ticket_support
                      INNER JOIN ticket_priority ON ticket_support.ticketPriorityId = ticket_priority.ticketPriorityId
                      INNER JOIN ticket_subject ON ticket_support.ticketSubjectId = ticket_subject.ticketSubjectId  
                      WHERE userAccountParentId=:userAccountParentId 
                      ORDER BY creationTime DESC, ticket_support.ticketPriorityId DESC", array(
            'userAccountParentId' => $userAccountParentId,
        ));

        return $ticketSupport;
    }

    public static function fetch_ticketReplies_by_ticketSupportId($ticketSupportId)
    {
        $db = Db::getInstance();
        $ticketReplies = $db->query("SELECT * FROM ticket_support_replies WHERE ticketSupportId=:ticketSupportId ORDER BY creationTime", array(
            'ticketSupportId' => $ticketSupportId,
        ));

        return $ticketReplies;
    }

    public static function update_ticketSupportIsActive_by_ticketSupportId($ticketSupportId, $isActive)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE ticket_support SET isActive=:isActive WHERE ticketSupportId=:ticketSupportId", array(
            'ticketSupportId' => $ticketSupportId,
            'isActive' => $isActive,
        ));
    }

    public static function insert_userAccountFCMToken($userAccountParentId, $FCMToken)
    {
        $db = Db::getInstance();
        $userAccountFCMTokenId = $db->insert("INSERT INTO user_account_fcm_token (userAccountParentId, FCMToken)
                          VALUES (:userAccountParentId, :FCMToken)", array(
            'userAccountParentId' => $userAccountParentId,
            'FCMToken' => $FCMToken,
        ));

        return $userAccountFCMTokenId;
    }

    public static function fetch_userAccountFCMToken_by_ticketSupportId($ticketSupportId)
    {
        $db = Db::getInstance();
        $userAccountFCMTokens = $db->query("SELECT FCMToken FROM ticket_support
                        INNER JOIN user_account_fcm_token ON ticket_support.userAccountParentId = user_account_fcm_token.userAccountParentId
                        WHERE ticketSupportId = :ticketSupportId", array(
            'ticketSupportId' => $ticketSupportId,
        ));

        return $userAccountFCMTokens;
    }

    public static function fetch_userAccountFCMToken_shopAdminTokensAndShopName_by_shopId($shopId)
    {
        $db = Db::getInstance();
        $userAccountFCMTokens = $db->query("SELECT Title_Shp, FCMToken FROM tbl_shop
                        INNER JOIN user_account_seller ON tbl_shop.AdminID_Shp = user_account_seller.ID_Slr
                        INNER JOIN user_account_parent ON user_account_seller.userAccountParentId = user_account_parent.userAccountParentId
                        INNER JOIN user_account_fcm_token ON user_account_parent.userAccountParentId = user_account_fcm_token.userAccountParentId
                        WHERE tbl_shop.ID_Shp = :shopId", array(
            'shopId' => $shopId,
        ));

        return $userAccountFCMTokens;
    }

    public static function delete_userAccountFCMToken_by_FCMTokens($tokensShouldBeDelete)
    {
        $strWhereIn = null;
        $data = array();

        for ($i = 0; $i < count($tokensShouldBeDelete); $i++) {
            $strWhereIn = $strWhereIn . ":FCMToken_" . $i . ", ";

            $data["FCMToken_" . $i] = $tokensShouldBeDelete[$i];
        }
        // The last item has an extra ', '
        $strWhereIn = substr($strWhereIn, 0, -2);

        $db = Db::getInstance();
        $db->modify("DELETE FROM user_account_fcm_token WHERE (FCMToken) IN ($strWhereIn)", $data);
    }

    public static function delete_userAccountFCMToken_by_FCMToken($FCMToken)
    {
        $db = Db::getInstance();
        $db->modify("DELETE FROM user_account_fcm_token WHERE FCMToken=:FCMToken", array(
            'FCMToken' => $FCMToken,
        ));
    }

    public static function fetch_ticketSubject()
    {
        $db = Db::getInstance();
        $ticketSubject = $db->query("SELECT * FROM ticket_subject", array());

        return $ticketSubject;
    }


}