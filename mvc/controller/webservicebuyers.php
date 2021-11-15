<?php

class WebservicebuyersController
{

    private function echoError($message)
    {
        $arraySend = array();
        $arraySend['Status'] = "101";
        $arraySend['Status_Str'] = "Error";
        $arraySend['MSG'] = $message;

        echo json_encode($arraySend);
    }

    public function fetchUrlBase()
    {
        $urlBase = WebservicebuyersModel::fetch_urlBase();
        $arrayUrlBase = array();
        for ($i = 0; $i < count($urlBase); $i++) {
            $arrayUrlBase[$i]['ID'] = $urlBase[$i]['ID_Ubs'];
            $arrayUrlBase[$i]['UrlBase'] = $urlBase[$i]['UrlBase_Ubs'];
        }

        $arrayData = array();
        $arrayData['UrlBase'] = $arrayUrlBase;
        $arrayData['UrlCount'] = count($urlBase);

        $settings = WebservicesellersModel::fetchs_settings();
        for ($i = 0; $i < count($settings); $i++) {
            if ($settings[$i]['Name_Sig'] == 'TcpDomain') {
                $arrayData['TcpDomain'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'TcpPort') {
                $arrayData['TcpPort'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'HttpDomain') {
                $arrayData['HttpDomain'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'HttpPort') {
                $arrayData['HttpPort'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Protocol') {
                $arrayData['Protocol'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'HttpCommand') {
                $arrayData['HttpCommand'] = $settings[$i]['Value_Sig'];
            }
        }

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $arrayData;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    public function fetchBasicDataWhenBuyerAppStart()
    {
        if (isset($_GET['customerId']) && isset($_GET['userAccountParentId'])) {
            $customerId = $_GET['customerId'];
            $userAccountParentId = $_GET['userAccountParentId'];
        } else {
            WebservicebuyersController::echoError("bad_parameter");
            return;
        }

        $arrayCustomerData = null;
        if ($customerId != 0) {
            $arrayCustomerData = array();
            $customerData = WebservicebuyersModel::fetch_customerData_by_customerId($customerId);
            if ($customerData != null) {
                $arrayCustomerData['Name'] = $customerData[0]['Name_Cmr'];
                $arrayCustomerData['Family'] = $customerData[0]['Family_Cmr'];
                $arrayCustomerData['ReagentID'] = $customerData[0]['ReagentID_Cmr'];
                $arrayCustomerData['Image'] = $customerData[0]['Image_Cmr'];
            }
        }

        $ads = WebservicebuyersModel::fetch_ads();
        $arrayAds = array();
        for ($i = 0; $i < count($ads); $i++) {
            $arrayAds[$i]['ID'] = $ads[$i]['ID_Ad'];
            $arrayAds[$i]['ImageName'] = $ads[$i]['ImageName_Ad'];
            $arrayAds[$i]['Type'] = $ads[$i]['Type_Ad'];
            $arrayAds[$i]['DataID'] = $ads[$i] ['DataID_Ad'];
        }

        $brands = WebservicebuyersModel::fetch_brands();
        $arrayBrands = array();
        for ($i = 0; $i < count($brands); $i++) {
            $arrayBrands[$i]['ID'] = $brands[$i]['ID_Bnd'];
            $arrayBrands[$i]['Title'] = $brands[$i]['Title_Bnd'];
            $arrayBrands[$i]['ImageName'] = $brands[$i]['ImageName_Bnd'];
        }

        $categories = WebservicesellersModel::fetch_jobsCategory_by_parentId(0);
        $arrayCategories = array();
        for ($i = 0; $i < count($categories); $i++) {
            $arrayCategories[$i]['ID'] = $categories[$i]['ID_Cgy'];
            $arrayCategories[$i]['ParnetID'] = $categories[$i]['ParnetID_Cgy'];
            $arrayCategories[$i]['Name'] = $categories[$i]['Name_Cgy'];
            $arrayCategories[$i]['ImageName'] = $categories[$i]['ImageName_Cgy'];
        }

        $mottoes = WebservicebuyersModel::fetch_mottoes();
        $arrayMottoes = array();
        for ($i = 0; $i < count($mottoes); $i++) {
            $arrayMottoes[$i]['ID'] = $mottoes[$i]['ID_Mto'];
            $arrayMottoes[$i]['Type'] = $mottoes[$i]['Type_Mto'];
            $arrayMottoes[$i]['ImageName'] = $mottoes[$i]['ImageName_Mto'];
        }

        $arrayData = array();
        $settings = WebservicesellersModel::fetchs_settings();
        for ($i = 0; $i < count($settings); $i++) {
            if ($settings[$i]['Name_Sig'] == 'Last_Modified_Category') {
                $arrayData['Last_Modified_Category'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Upload_IP') {
                $arrayData['Upload_IP'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Upload_Port') {
                $arrayData['Upload_Port'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Ip') {
                $arrayData['Download_Ip'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Port') {
                $arrayData['Download_Port'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_Customers') {
                $arrayData['Download_Folder_Customers'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_Ad') {
                $arrayData['Download_Folder_Ad'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_Category') {
                $arrayData['Download_Folder_Category'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_Brand') {
                $arrayData['Download_Folder_Brand'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_Product') {
                $arrayData['Download_Folder_Product'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_Motto') {
                $arrayData['Download_Folder_Motto'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_Shop') {
                $arrayData['Download_Folder_Shop'] = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_Seller') {
                $arrayData['Download_Folder_Seller'] = $settings[$i]['Value_Sig'];
            }
        }

        $webservicePaymentController = new WebservicepaymentController();
        $latestPaymentCart = $webservicePaymentController->findLatestPaymentCart($userAccountParentId);
        if ($latestPaymentCart != null) {
            $countPaymentCartOrderItems = WebservicepaymentModel::fetch_countPaymentCartOrderItems_by_paymentCartId($latestPaymentCart['paymentCartId']);
            $arrayData['countPaymentCartOrderItems'] = $countPaymentCartOrderItems;
        } else {
            $arrayData['countPaymentCartOrderItems'] = 0;
        }

        if ($customerId != 0) {
            $arrayData['Profile'] = $arrayCustomerData;
        }
        $arrayData['AD'] = $arrayAds;
        $arrayData['Brand'] = $arrayBrands;
        $arrayData['Category'] = $arrayCategories;
        $arrayData['Motto'] = $arrayMottoes;

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $arrayData;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    public function fetchSpecialListFromCategory()
    {
        if (isset($_GET['parentCategoryId'])) {
            $parentCategoryId = $_GET['parentCategoryId'];
        } else {
            WebservicebuyersController::echoError("bad_parameter");
            return;
        }

        $categoryList = WebservicesellersModel::fetch_jobsCategory_by_parentId($parentCategoryId);
        $arrayCategoryList = array();
        for ($i = 0; $i < count($categoryList); $i++) {
            $arrayCategoryList[$i]['ID'] = $categoryList[$i]['ID_Cgy'];
            $arrayCategoryList[$i]['ParnetID'] = $categoryList[$i]['ParnetID_Cgy'];
            $arrayCategoryList[$i]['Name'] = $categoryList[$i]['Name_Cgy'];
            $arrayCategoryList[$i]['ImageName'] = $categoryList[$i] ['ImageName_Cgy'];
        }

        $arrayData = array();
        $arrayData['Category'] = $arrayCategoryList;

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $arrayData;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    public function fetchShopListCategory()
    {
        if (isset($_GET['type']) && isset($_GET['categoryId']) && isset($_GET['distance'])) {
            $type = $_GET['type'];
            $categoryId = $_GET['categoryId'];
            $distance = $_GET['distance'];
        } else {
            WebservicebuyersController::echoError("bad_parameter");
            return;
        }

        $lastShopId = null;
        if (isset($_GET['lastShopId'])) {
            $lastShopId = $_GET['lastShopId'];
        }

        $lat = null;
        $lng = null;
        if (isset($_GET['lat']) && isset($_GET['lng'])) {
            $lat = $_GET['lat'];
            $lng = $_GET['lng'];
        }
        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";

        $shopListCategory = WebservicebuyersModel::fetch_shopListCategory($type, $categoryId, $lastShopId, $distance, $lat, $lng);
        if ($shopListCategory != null) {
            $arrayShopListCategory = array();
            for ($i = 0; $i < count($shopListCategory); $i++) {
                $arrayShopListCategory[$i]['ID'] = $shopListCategory[$i]['ID_Shp'];
                $arrayShopListCategory[$i]['Title'] = $shopListCategory[$i]['Title_Shp'];
                $arrayShopListCategory[$i]['Address'] = $shopListCategory[$i]['Address_Shp'];
                $arrayShopListCategory[$i]['ImgName'] = $shopListCategory[$i]['imgShopLogo'];
                $arrayShopListCategory[$i]['Lat'] = $shopListCategory[$i]['Lat_Shp'];
                $arrayShopListCategory[$i]['Lng'] = $shopListCategory[$i]['Lng_Shp'];
                $arrayShopListCategory[$i]['WebSite'] = $shopListCategory[$i]['WebSite_Shp'];
                $arrayShopListCategory[$i]['Email'] = $shopListCategory[$i]['Email_Shp'];

                $shopTels = WebservicesellersModel::fetch_shopTels_by_shopId($shopListCategory[$i]['ID_Shp']);
                $arrayShopListCategory[$i]['Tell'] = $shopTels[0]['telNumber'];

                $shopScores = WebservicebuyersModel::fetch_shopScoreAvgAndCounts($shopListCategory[$i]['ID_Shp']);
                $arrayShopListCategory[$i]['Score'] = $shopScores[0]['average'];
                $arrayShopListCategory[$i]['VoteCount'] = $shopScores[0]['counts'];

                $shopMedals = WebservicebuyersModel::fetch_shopSumOfMedal($shopListCategory[0]['ID_Shp']);
                $arrayShopListCategory[$i]['Medal'] = $shopMedals[0]['sumMedal'];
            }

            $arrayData = array();
            $arrayData['Shops'] = $arrayShopListCategory;

            $arraySend['Data'] = $arrayData;
            $arraySend['MSG'] = "OK";
        } else {
            $arraySend['MSG'] = "No more data";
        }

        echo json_encode($arraySend);
    }

    public function signUpOrSignInCustomer()
    {
        if (isset($_POST['countryCode']) && isset($_POST['mobileNumber'])) {
            $countryCode = $_POST['countryCode'];
            $mobileNumber = $_POST['mobileNumber'];
        } else {
            WebservicebuyersController::echoError("bad_parameter");
            return;
        }
        $verifyCodeMustSend = null;

        $customerData = WebservicebuyersModel::fetch_customer_by_CountryCodeAndMobile($countryCode, $mobileNumber);
        if ($customerData == null) {
            // Happen when signUp
            $userAccountParentId = WebservicesellersModel::insert_userAccountParent(3); // 3 is 'buyer'
            $customerId = WebservicebuyersModel::insert_customerAndGetCustomerId($userAccountParentId, $countryCode, $mobileNumber);
            $verifyCodeMustSend = mt_rand(10000, 99999);
            WebservicebuyersModel::insert_verifyCustomer($customerId, $verifyCodeMustSend);
        } else {
            $customerVerifyData = WebservicebuyersModel::fetch_customerVerify_by_customerId($customerData[0]['ID_Cmr']);
            if ($customerVerifyData == null) {
                // Happen when signIn
                $verifyCodeMustSend = mt_rand(10000, 99999);
                WebservicebuyersModel::insert_verifyCustomer($customerData[0]['ID_Cmr'], $verifyCodeMustSend);
            } else {
                // Happen when resend SMS
                $verifyCodeMustSend = $customerVerifyData[0]['VerifyCode_Vfyc'];
            }
        }

        $smsMessage = "به آی بازار خوش آمدید. کد تایید شما : " . $verifyCodeMustSend;
        $result = sendSMSWithPanel($smsMessage, $countryCode . $mobileNumber);

        $arraySend = array();
        if ($result == 'SMS_Sent') {
            $arraySend['Status'] = "100";
            $arraySend['Status_Str'] = "OK";
            $arraySend['MSG'] = $result;
        } else {
            $arraySend['Status'] = "101";
            $arraySend['Status_Str'] = "Fault";
            $arraySend['MSG'] = $result;
        }

        echo json_encode($arraySend);
    }

    public function verifyCustomer()
    {
        if (isset($_POST['countryCode']) && isset($_POST['mobileNumber']) && isset($_POST['verifyCode'])) {
            $countryCode = $_POST['countryCode'];
            $mobileNumber = $_POST['mobileNumber'];
            $verifyCode = $_POST['verifyCode'];
        } else {
            WebservicebuyersController::echoError("bad_parameter");
            return;
        }
        $errorHappen = false;

        $customerData = WebservicebuyersModel::fetch_customer_by_CountryCodeAndMobile($countryCode, $mobileNumber);
        if ($customerData != null) {
            $customerVerifyData = WebservicebuyersModel::fetch_customerVerify_by_customerId($customerData[0]['ID_Cmr']);
            if ($customerVerifyData != null) {
                if ($customerVerifyData[0]['VerifyCode_Vfyc'] == $verifyCode) {
                    WebservicebuyersModel::update_verifyCustomerForSMS($customerVerifyData[0]['ID_Vfyc']);
                } else {
                    $errorHappen = true;
                }
            } else {
                $errorHappen = true;
            }
        } else {
            $errorHappen = true;
        }

        $arraySend = array();
        if ($errorHappen) {
            $arraySend['Status'] = "101";
            $arraySend['Status_Str'] = "Error";
            $arraySend['MSG'] = "NO";
        } else {
            $webservicePaymentController = new WebservicepaymentController();
            $paymentCart = $webservicePaymentController->getCurrentPaymentCart($customerData[0]['userAccountParentId']);

            WebservicepaymentModel::update_paymentInvoiceUserAccountParentId_by_paymentInvoiceId($customerData[0]['userAccountParentId'], $paymentCart['paymentInvoiceId']);
            $countPaymentCartOrderItems = WebservicepaymentModel::fetch_countPaymentCartOrderItems_by_paymentCartId($paymentCart['paymentCartId']);

            $arrayData = array();
            $arrayData['countPaymentCartOrderItems'] = $countPaymentCartOrderItems;
            $arrayData['CustomerId'] = $customerData[0]['ID_Cmr'];
            $arrayData['userAccountParentId'] = $customerData[0]['userAccountParentId'];
            $arrayData['Name'] = $customerData[0]['Name_Cmr'];
            $arrayData['Family'] = $customerData[0]['Family_Cmr'];
            $arrayData['ReagentID'] = $customerData[0]['ReagentID_Cmr'];
            $arrayData['Image'] = $customerData[0]['Image_Cmr'];

            $arraySend['Status'] = "100";
            $arraySend['Status_Str'] = "OK";
            $arraySend['Data'] = $arrayData;
            $arraySend['MSG'] = "OK";
        }
        echo json_encode($arraySend);
    }

    public function editProfile()
    {
        if (isset($_POST['customerId']) && isset($_POST['customerName']) && isset($_POST['customerFamily'])) {
            $customerId = $_POST['customerId'];
            $customerName = $_POST['customerName'];
            $customerFamily = $_POST['customerFamily'];
        } else {
            WebservicebuyersController::echoError("bad_parameter");
            return;
        }

        $customerReagent = 0;
        if (isset($_POST['customerReagent'])) {
            $customerReagent = $_POST['customerReagent'];
        }

        $customerImage = null;
        if (isset($_POST['customerImage'])) {
            $customerImage = $_POST['customerImage'];
        }

        $arraySend = array();

        $customerData = WebservicebuyersModel::fetch_customerData_by_customerId($customerId);
        if ($customerData != null) {
            WebservicebuyersModel::update_customerData($customerId, $customerName, $customerFamily, $customerReagent, $customerImage);

            $arraySend['Status'] = "100";
            $arraySend['Status_Str'] = "OK";
            $arraySend['MSG'] = "OK";
        } else {
            $arraySend['Status'] = "101";
            $arraySend['Status_Str'] = "No";
            $arraySend['MSG'] = "No";
        }

        echo json_encode($arraySend);
    }


    private function fetchProductModelsWithDiscountByShopId($shopId, $limitFrom)
    {
        $downloadIp = null;
        $downloadFolderProductDefaultLogo = null;
        $settings = WebservicesellersModel::fetchs_settings();
        for ($j = 0; $j < count($settings); $j++) {
            if ($settings[$j]['Name_Sig'] == 'Download_Ip') {
                $downloadIp = $settings[$j]['Value_Sig'];
            } else if ($settings[$j]['Name_Sig'] == 'Download_Folder_ProductDefaultLogo') {
                $downloadFolderProductDefaultLogo = $settings[$j]['Value_Sig'];
            }
        }

        $productModelsWithDiscount = WebservicebuyersModel::fetch_shopProductModelsWithDiscount_by_shopId($shopId, $limitFrom);
        $arrayProductModelWithDiscount = array();
        for ($i = 0; $i < count($productModelsWithDiscount); $i++) {
            $categoryProductAndServiceTitle = WebservicesellersModel::fetch_categoryProductAndServiceLevel1_by_categoryProductAndServiceLevel1Id($productModelsWithDiscount[$i]['categoryProductAndServiceLevel1Id']);
            $productBrandNameAndOriginCountry = WebservicesellersModel::fetch_brandNameAndOriginCountry_by_productProducingCountryId($productModelsWithDiscount[$i]['productProducingCountryId']);

            $arrayProductModelWithDiscount[$i]['productModelId'] = $productModelsWithDiscount[$i]['productModelId'];
            $arrayProductModelWithDiscount[$i]['productCategoryTitle'] = $categoryProductAndServiceTitle['title'];
            $arrayProductModelWithDiscount[$i]['productModelName'] = $productModelsWithDiscount[$i]['modelName'];
            $arrayProductModelWithDiscount[$i]['productBrandName'] = $productBrandNameAndOriginCountry['brandName'];
            $arrayProductModelWithDiscount[$i]['productPrice'] = $productModelsWithDiscount[$i]['price'];
            $arrayProductModelWithDiscount[$i]['productDiscount'] = $productModelsWithDiscount[$i]['discount'];
            $arrayProductModelWithDiscount[$i]['productDefaultLogo'] = $productModelsWithDiscount[$i]['productDefaultLogo'];
            $arrayProductModelWithDiscount[$i]['productDefaultLogoUrl'] = $downloadIp . $downloadFolderProductDefaultLogo . "/";
        }

        return $arrayProductModelWithDiscount;
    }

    private function fetchServiceNamesWithDiscountByShopId($shopId, $limitFrom)
    {
        $downloadIp = null;
        $downloadFolderServiceDefaultLogo = null;
        $settings = WebservicesellersModel::fetchs_settings();
        for ($j = 0; $j < count($settings); $j++) {
            if ($settings[$j]['Name_Sig'] == 'Download_Ip') {
                $downloadIp = $settings[$j]['Value_Sig'];
            } else if ($settings[$j]['Name_Sig'] == 'Download_Folder_ServiceDefaultLogo') {
                $downloadFolderServiceDefaultLogo = $settings[$j]['Value_Sig'];
            }
        }

        $serviceNamesWithDiscount = WebservicebuyersModel::fetch_shopServicesNameWithDiscount_by_shopId($shopId, $limitFrom);
        $arrayServiceNameWithDiscount = array();
        for ($i = 0; $i < count($serviceNamesWithDiscount); $i++) {
            $categoryProductAndServiceTitle = WebservicesellersModel::fetch_categoryProductAndServiceLevel1_by_categoryProductAndServiceLevel1Id($serviceNamesWithDiscount[$i]['categoryProductAndServiceLevel1Id']);

            $arrayServiceNameWithDiscount[$i]['serviceNameId'] = $serviceNamesWithDiscount[$i]['serviceNameId'];
            $arrayServiceNameWithDiscount[$i]['serviceCategoryTitle'] = $categoryProductAndServiceTitle['title'];
            $arrayServiceNameWithDiscount[$i]['serviceName'] = $serviceNamesWithDiscount[$i]['serviceName'];
            $arrayServiceNameWithDiscount[$i]['servicePrice'] = $serviceNamesWithDiscount[$i]['price'];
            $arrayServiceNameWithDiscount[$i]['serviceDiscount'] = $serviceNamesWithDiscount[$i]['discount'];
            $arrayServiceNameWithDiscount[$i]['serviceDefaultLogo'] = $serviceNamesWithDiscount[$i]['serviceDefaultLogo'];
            $arrayServiceNameWithDiscount[$i]['serviceDefaultLogoUrl'] = $downloadIp . $downloadFolderServiceDefaultLogo . "/";
        }

        return $arrayServiceNameWithDiscount;
    }

    public function fetchShopDetails()
    {
        if (isset($_GET['shopId'])) {
            $shopId = $_GET['shopId'];
        } else {
            WebservicebuyersController::echoError("bad_parameter");
            return;
        }

        $shopWithAdmin = WebservicebuyersModel::fetch_shopAndAdminOfThat_by_shopId($shopId);
        if ($shopWithAdmin == null) {
            $arraySend = array();
            $arraySend['Status'] = "101";
            $arraySend['Status_Str'] = "No";
            $arraySend['MSG'] = "No";

            echo json_encode($arraySend);
            return;
        }
        $arrayAdmin = array();
        $arrayAdmin['Name'] = $shopWithAdmin[0]['firstName'];
        $arrayAdmin['Family'] = $shopWithAdmin[0]['lastName'];
        $arrayAdmin['ImgAdmin'] = $shopWithAdmin[0]['imgName'];

        $arrayShop = array();
        $arrayShop['RegisterDate'] = $shopWithAdmin[0]['DateTime_Shp'];
        $arrayShop['GuildType'] = $shopWithAdmin[0]['Type_Shp'];
        $arrayShop['Title'] = $shopWithAdmin[0]['Title_Shp'];
        $arrayShop['Address'] = $shopWithAdmin[0]['Address_Shp'];
        $arrayShop['ImgName'] = $shopWithAdmin[0]['imgShopLogo'];
        $arrayShop['Lat'] = $shopWithAdmin[0]['Lat_Shp'];
        $arrayShop['Lng'] = $shopWithAdmin[0]['Lng_Shp'];
        $arrayShop['Website'] = $shopWithAdmin[0]['WebSite_Shp'];
        $arrayShop['Email'] = $shopWithAdmin[0]['Email_Shp'];
        $arrayShop['Services'] = $shopWithAdmin[0]['ServiceDescription_Shp'];
        $arrayShop['LicenseNumber'] = $shopWithAdmin[0]['LicenseNumber_Shp'];
        $arrayShop['Open'] = $shopWithAdmin[0]['Open_Shp'];

        $shopTels = WebservicesellersModel::fetch_shopTels_by_shopId($shopId);
        $arrayShop['ShopTel'] = $shopTels[0]['telNumber'];

        $shopScoreAndCounts = WebservicebuyersModel::fetch_shopScoreAvgAndCounts_by_shopId($shopId);
        $arrayShop['Score'] = $shopScoreAndCounts[0]['average'];
        $arrayShop['VoteCount'] = $shopScoreAndCounts[0]['counts'];

        $shopMedals = WebservicebuyersModel::fetch_shopMedal_by_shopId($shopId);
        $arrayShop['Medal'] = $shopMedals[0]['sumMedal'];

        $shopImages = WebservicebuyersModel::fetch_shopImages_by_shopId($shopId);
        $arrayShopImages = array();
        for ($i = 0; $i < count($shopImages); $i++) {
            $arrayShopImages[$i]['Image'] = $shopImages[$i]['ImageName_Its'];
        }

        $shopSellers = WebservicebuyersModel::fetch_sellersForTheShop_by_shopId($shopId);
        $arrayShopSellers = array();
        for ($i = 0; $i < count($shopSellers); $i++) {
            $arrayShopSellers[$i]['Name'] = $shopSellers[$i]['firstName'];
            $arrayShopSellers[$i]['Family'] = $shopSellers[$i]['lastName'];
            $arrayShopSellers[$i]['ImgStaff'] = $shopSellers[$i]['imgName'];
            $arrayShopSellers[$i]['Post'] = $shopSellers[$i]['Post_Sts'];
        }

        $shopCertificates = WebservicebuyersModel::fetch_shopCertificate_by_shopId($shopId);
        $arrayShopCertificates = array();
        for ($i = 0; $i < count($shopCertificates); $i++) {
            $arrayShopCertificates[$i]['Title'] = $shopCertificates[$i]['Title_Sets'];
            $arrayShopCertificates[$i]['ImgName'] = $shopCertificates[$i]['ImgName_Sets'];
        }

        $shopNotifies = WebservicebuyersModel::fetch_shopNotifies_by_shopId($shopId);
        $arrayShopNotifies = array();
        for ($i = 0; $i < count($shopNotifies); $i++) {
            $arrayShopNotifies[$i]['DateTime'] = $shopNotifies[$i]['DateTime_Nts'];
            $arrayShopNotifies[$i]['Title'] = $shopNotifies[$i]['Title_Nts'];
            $arrayShopNotifies[$i]['ImageName'] = $shopNotifies[$i]['ImageName_Nts'];
            $arrayShopNotifies[$i]['Msg'] = $shopNotifies[$i]['Msg_Nts'];
        }

        $shopComments = WebservicebuyersModel::fetch_shopComments_by_shopId($shopId);
        $arrayShopComments = array();
        for ($i = 0; $i < count($shopComments); $i++) {
            $arrayShopComments[$i]['CustomerName'] = $shopComments[$i]['Name_Cmr'];
            $arrayShopComments[$i]['CustomerFamily'] = $shopComments[$i]['Family_Cmr'];
            $arrayShopComments[$i]['DateTime'] = $shopComments[$i]['DateTime_Cts'];
            $arrayShopComments[$i]['Comment'] = $shopComments[$i]['Comment_Cts'];
            $arrayShopComments[$i]['Prog1'] = $shopComments[$i]['Prog1_Cts'];
            $arrayShopComments[$i]['Prog2'] = $shopComments[$i]['Prog2_Cts'];
            $arrayShopComments[$i]['Prog3'] = $shopComments[$i]['Prog3_Cts'];
        }

        $limit = "fixTenNumberFromFirst";
        $arrayProductModelWithDiscount = $this->fetchProductModelsWithDiscountByShopId($shopId, $limit);
        $arrayServiceNameWithDiscount = $this->fetchServiceNamesWithDiscountByShopId($shopId, $limit);

        $arrayShop['Image'] = $arrayShopImages;
        $arrayShop['Admin'] = $arrayAdmin;
        $arrayShop['Staff'] = $arrayShopSellers;
        $arrayShop['Certificate'] = $arrayShopCertificates;

        $arrayData['Info'] = $arrayShop;
        $arrayData['Notify'] = $arrayShopNotifies;
        $arrayData['Comment'] = $arrayShopComments;
        $arrayData['ShopProductsWithDiscount'] = $arrayProductModelWithDiscount;
        $arrayData['ShopServicesWithDiscount'] = $arrayServiceNameWithDiscount;

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $arrayData;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    public function fetchFullListProductModelsOrServiceNamesWithDiscount()
    {
        if (isset($_GET['shopId']) && isset($_GET['limitFrom']) && isset($_GET['isService'])) {
            $shopId = $_GET['shopId'];
            $isService = $_GET['isService'];
            $limitFrom = $_GET['limitFrom'];
        } else {
            WebservicebuyersController::echoError("bad_parameter");
            return;
        }

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";

        if ($isService == "0") {
            $arraySend['Data'] = $this->fetchProductModelsWithDiscountByShopId($shopId, $limitFrom);
        } else {
            $arraySend['Data'] = $this->fetchServiceNamesWithDiscountByShopId($shopId, $limitFrom);
        }

        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }


    public function fetchShopListWithLatAndLng()
    {
        if (isset($_GET['lat']) && isset($_GET['lng']) && isset($_GET['distance']) && isset($_GET['categoryId']) && isset($_GET['zoom'])) {
            $lat = $_GET['lat'];
            $lng = $_GET['lng'];
            $distance = $_GET['distance'];
            $categoryId = $_GET['categoryId'];
            $zoom = $_GET['zoom'];
        } else {
            WebservicebuyersController::echoError("bad_parameter");
            return;
        }

        $arraySend = array();

        $limitUntil = "";
        $overZoomOut = 0;
        if ($zoom == 15 || $zoom == 16 || $zoom == 17) {
            $limitUntil = 30;
        } else if ($zoom == 13 || $zoom == 14) {
            $limitUntil = 20;
        } else if ($zoom == 10 || $zoom == 11 || $zoom == 12) {
            $limitUntil = 10;
        } else if ($zoom == 8 || $zoom == 9) {
            $limitUntil = 5;
        } else if ($zoom < 8) {
            $arraySend['Status'] = "101";
            $arraySend['Status_Str'] = "NO";
            $arraySend['MSG'] = "مقدار بزرگ نمایی کمتر از حد مجاز میباشد.";
            $overZoomOut = 1;
        }

        if ($overZoomOut == 0) {
            if ($categoryId == 0) {
                $shopListWithLatAndLng = WebservicebuyersModel::fetch_shopList_by_LatAndLng($lat, $lng, $distance, $limitUntil);
            } else {
                $shopListWithLatAndLng = WebservicebuyersModel::fetch_shopListByCategoryId_by_LatAndLng($lat, $lng, $distance, $categoryId, $limitUntil);
            }

            $arraySend['Status'] = "100";
            $arraySend['Status_Str'] = "OK";
            if ($shopListWithLatAndLng != null) {
                $arraySend['Data'] = $shopListWithLatAndLng;
                $arraySend['MSG'] = "OK";
            } else {
                $arraySend['MSG'] = "فروشگاهی وجود ندارد.";
            }
        }

        echo json_encode($arraySend);
    }

    public function fetchProductAndServiceByCategoryProductAndServiceLevel1Id()
    {
        if (isset($_GET['shopId']) && isset($_GET['orderBy']) && isset($_GET['isFetchProduct']) && isset($_GET['categoryProductAndServiceLevel1Id'])) {
            $shopId = $_GET['shopId'];
            $orderBy = $_GET['orderBy']; // new / visitCount / sellCount / priceLessToHigh / priceHighToLess
            $isFetchProduct = $_GET['isFetchProduct']; // 0 or 1
            $categoryProductAndServiceLevel1Id = $_GET['categoryProductAndServiceLevel1Id'];
        } else {
            WebservicebuyersController::echoError("bad_parameter");
            return;
        }

        $downloadIp = null;
        $downloadFolderProductDefaultLogo = null;
        $downloadFolderServiceDefaultLogo = null;
        $settings = WebservicesellersModel::fetchs_settings();
        for ($j = 0; $j < count($settings); $j++) {
            if ($settings[$j]['Name_Sig'] == 'Download_Ip') {
                $downloadIp = $settings[$j]['Value_Sig'];
            } else if ($settings[$j]['Name_Sig'] == 'Download_Folder_ProductDefaultLogo') {
                $downloadFolderProductDefaultLogo = $settings[$j]['Value_Sig'];
            } else if ($settings[$j]['Name_Sig'] == 'Download_Folder_ServiceDefaultLogo') {
                $downloadFolderServiceDefaultLogo = $settings[$j]['Value_Sig'];
            }
        }

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = array();
        $arraySend['MSG'] = "OK";

        if ($isFetchProduct == 1) {
            $productModelsInShop = WebservicesellersModel::fetch_productModelsInShopWithPrice_by_categoryProductAndServiceLevel1Id($shopId, $orderBy, $categoryProductAndServiceLevel1Id);

            $arrayProductModelInShop = array();
            for ($i = 0; $i < count($productModelsInShop); $i++) {
                $categoryProductAndServiceTitle = WebservicesellersModel::fetch_categoryProductAndServiceLevel1_by_categoryProductAndServiceLevel1Id($productModelsInShop[$i]['categoryProductAndServiceLevel1Id']);
                $productBrandNameAndOriginCountry = WebservicesellersModel::fetch_brandNameAndOriginCountry_by_productProducingCountryId($productModelsInShop[$i]['productProducingCountryId']);

                $arrayProductModelInShop[$i]['productModelId'] = $productModelsInShop[$i]['productModelId'];
                $arrayProductModelInShop[$i]['productCategoryTitle'] = $categoryProductAndServiceTitle['title'];
                $arrayProductModelInShop[$i]['productModelName'] = $productModelsInShop[$i]['modelName'];
                $arrayProductModelInShop[$i]['productBrandName'] = $productBrandNameAndOriginCountry['brandName'];
                $arrayProductModelInShop[$i]['productPrice'] = $productModelsInShop[$i]['price'];
                $arrayProductModelInShop[$i]['productDiscount'] = $productModelsInShop[$i]['discount'];
                $arrayProductModelInShop[$i]['productDefaultLogo'] = $productModelsInShop[$i]['productDefaultLogo'];
                $arrayProductModelInShop[$i]['productDefaultLogoUrl'] = $downloadIp . $downloadFolderProductDefaultLogo . "/";
            }
            $arraySend['Data'] = $arrayProductModelInShop;

        } else {
            $serviceNamesInShop = WebservicesellersModel::fetch_serviceNamesInShop_by_categoryProductAndServiceLevel1Id($shopId, $orderBy, $categoryProductAndServiceLevel1Id);

            $arrayServiceNameInShop = array();
            for ($i = 0; $i < count($serviceNamesInShop); $i++) {
                $categoryProductAndServiceTitle = WebservicesellersModel::fetch_categoryProductAndServiceLevel1_by_categoryProductAndServiceLevel1Id($serviceNamesInShop[$i]['categoryProductAndServiceLevel1Id']);

                $arrayServiceNameInShop[$i]['serviceNameId'] = $serviceNamesInShop[$i]['serviceNameId'];
                $arrayServiceNameInShop[$i]['serviceCategoryTitle'] = $categoryProductAndServiceTitle['title'];
                $arrayServiceNameInShop[$i]['serviceName'] = $serviceNamesInShop[$i]['serviceName'];
                $arrayServiceNameInShop[$i]['servicePrice'] = $serviceNamesInShop[$i]['price'];
                $arrayServiceNameInShop[$i]['serviceDiscount'] = $serviceNamesInShop[$i]['discount'];
                $arrayServiceNameInShop[$i]['serviceDefaultLogo'] = $serviceNamesInShop[$i]['serviceDefaultLogo'];
                $arrayServiceNameInShop[$i]['serviceDefaultLogoUrl'] = $downloadIp . $downloadFolderServiceDefaultLogo . "/";
            }
            $arraySend['Data'] = $arrayServiceNameInShop;
        }

        echo json_encode($arraySend);
    }

}