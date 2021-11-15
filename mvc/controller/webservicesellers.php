<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ali_Ai
 * Date: 7/4/2018
 * Time: 8:44 AM
 */

class WebservicesellersController
{

    private function echoError($message)
    {
        $arraySend = array();
        $arraySend['Status'] = "101";
        $arraySend['Status_Str'] = "Error";
        $arraySend['MSG'] = $message;

        echo json_encode($arraySend);
    }

    public function signUp_SignIn_ResendSMS_ForSeller()
    {
        if (isset($_POST['countryCode']) && isset($_POST['mobileNumber']) && isset($_POST['FCMToken'])) {
            $countryCode = $_POST['countryCode'];
            $mobileNumber = $_POST['mobileNumber'];
            $FCMToken = $_POST['FCMToken'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }
        $verifyCodeMustSend = null;
        $userAccountParentId = null;

        $sellerData = WebservicesellersModel::fetch_userAccountSeller_by_countryCodeAndMobile($countryCode, $mobileNumber);
        if ($sellerData == null) {
            // Happen when signUp
            $userAccountParentId = WebservicesellersModel::insert_userAccountParent(2); // 2 is 'seller'
            $sellerID = WebservicesellersModel::insert_userAccountSeller($userAccountParentId, $countryCode, $mobileNumber);
            $verifyCodeMustSend = mt_rand(10000, 99999);
            WebservicesellersModel::insert_verifySeller($sellerID, $verifyCodeMustSend);
        } else {
            $sellerVerifyData = WebservicesellersModel::fetch_sellerVerify_by_userAccountSellerId($sellerData[0]['ID_Slr']);
            $userAccountParentId = $sellerData[0]['userAccountParentId'];
            if ($sellerVerifyData == null) {
                // Happen when signIn
                $verifyCodeMustSend = mt_rand(10000, 99999);
                WebservicesellersModel::insert_verifySeller($sellerData[0]['ID_Slr'], $verifyCodeMustSend);
            } else {
                // Happen when resend SMS
                $verifyCodeMustSend = $sellerVerifyData[0]['VerifyCode_Vfys'];
            }
        }
        WebservicesellersModel::insert_userAccountFCMToken($userAccountParentId, $FCMToken);

        $smsMessage = "به آی بازار خوش آمدید. کد تایید شما : " . $verifyCodeMustSend;
        $result = sendSMSWithPanel($smsMessage, $countryCode . $mobileNumber);
        //$result = 'SMS_Sent';

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

    public function verifySeller()
    {
        if (isset($_POST['countryCode'])) {
            $countryCode = $_POST['countryCode'];
            $mobileNumber = $_POST['mobileNumber'];
            $verifyCode = $_POST['verifyCode'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        $errorHappen = false;
        $wrongVerifyCode = false;

        $sellerData = WebservicesellersModel::fetch_userAccountSeller_by_countryCodeAndMobile($countryCode, $mobileNumber);
        if ($sellerData != null) {
            $sellerVerifyData = WebservicesellersModel::fetch_sellerVerify_by_userAccountSellerId($sellerData[0]['ID_Slr']);
            if ($sellerVerifyData != null) {
                if ($sellerVerifyData[0]['VerifyCode_Vfys'] == $verifyCode) {
                    WebservicesellersModel::update_verifySellerForSMS($sellerVerifyData[0]['ID_Vfys']);
                } else {
                    $errorHappen = true;
                    $wrongVerifyCode = true;
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
            if ($wrongVerifyCode) {
                $arraySend['MSG'] = "کد وارد شده نامعتبر است. لطفا دوباره تلاش نمایید.";
            } else {
                $arraySend['MSG'] = "NO";
            }
        } else {
            $arrayData = array();
            $arrayData['sellerID'] = $sellerData[0]['ID_Slr'];
            $arrayData['userAccountParentId'] = $sellerData[0]['userAccountParentId'];
            $arrayData['name'] = $sellerData[0]['firstName'];
            $arrayData['family'] = $sellerData[0]['lastName'];
            $arrayData['nationalID'] = $sellerData[0]['nationalCode'];
            $arrayData['gender'] = $sellerData[0]['gender'];
            $arrayData['image'] = $sellerData[0]['imgName'];
            $arrayData['fatherName'] = $sellerData[0]['fatherName'];
            $arrayData['email'] = $sellerData[0]['email'];
            $arrayData['reagentID'] = $sellerData[0]['reagentCode'];

            $arraySend['Status'] = "100";
            $arraySend['Status_Str'] = "OK";
            $arraySend['Data'] = $arrayData;
            $arraySend['MSG'] = "OK";
        }
        echo json_encode($arraySend);
    }

    public function fetchSellerProfileBySellerId()
    {
        // gender base on this ISO: (https://en.wikipedia.org/wiki/ISO/IEC_5218)
        if (isset($_GET['sellerID'])) {
            $sellerID = $_GET['sellerID'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        $sellerProfile = WebservicesellersModel::fetch_sellerProfile_by_userAccountSellerId($sellerID);

        $downloadIp = null;
        $downloadFolderSellerImage = null;
        $settings = WebservicesellersModel::fetchs_settings();
        for ($i = 0; $i < count($settings); $i++) {
            if ($settings[$i]['Name_Sig'] == 'Download_Ip') {
                $downloadIp = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_Seller') {
                $downloadFolderSellerImage = $settings[$i]['Value_Sig'];
            }
        }

        $arrayData['sellerProfile'] = $sellerProfile;
        $arrayData['imgSellerImageUrl'] = $downloadIp . $downloadFolderSellerImage;

        if ($sellerProfile != null) {
            $arraySend['Status'] = "100";
            $arraySend['Status_Str'] = "OK";
            $arraySend['Data'] = $arrayData;
            $arraySend['MSG'] = "OK";
        } else {
            $arraySend['Status'] = "101";
            $arraySend['Status_Str'] = "No";
            $arraySend['MSG'] = "There is no seller with this ID.";
        }

        echo json_encode($arraySend);
    }

    public function updateSellerProfile()
    {
        // gender base on this ISO: (https://en.wikipedia.org/wiki/ISO/IEC_5218)

        if (isset($_POST['sellerID']) && isset($_POST['firstName']) && isset($_POST['lastName']) &&
            isset($_POST['nationalCode']) && isset($_POST['gender']) && isset($_POST['fatherName']) &&
            isset($_POST['email']) && isset($_POST['reagentCode'])) {
            $sellerID = $_POST['sellerID'];
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $nationalCode = $_POST['nationalCode'];
            $gender = $_POST['gender'];
            $fatherName = $_POST['fatherName'];
            $email = $_POST['email'];
            $reagentCode = $_POST['reagentCode'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        $sellerProfile = WebservicesellersModel::fetch_sellerProfile_by_userAccountSellerId($sellerID);
        if ($sellerProfile != null) {
            WebservicesellersModel::update_sellerProfile($sellerID, $firstName, $lastName, $nationalCode, $gender, $fatherName, $email, $reagentCode);

            $arraySend['Status'] = "100";
            $arraySend['Status_Str'] = "OK";
            $arraySend['MSG'] = "Updated Successfully.";
        } else {
            $arraySend['Status'] = "101";
            $arraySend['Status_Str'] = "No";
            $arraySend['MSG'] = "There is no seller with this ID.";
        }

        echo json_encode($arraySend);
    }

    public function uploadSellerProfileImage()
    {
        $fileName = basename($_FILES['uploaded_file']['name']);
        $explode = explode('.', $fileName);
        $id = $explode[0];

        WebservicesellersModel::upload_sellerProfileImage($id, $fileName);

        $file_path = "image/seller/" . $fileName;
        if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {
            $arraySend['Status'] = "100";
            $arraySend['Status_Str'] = "OK";
            $arraySend['MSG'] = "image uploaded successfully to server.";
        } else {
            $arraySend['Status'] = "101";
            $arraySend['Status_Str'] = "No";
            $arraySend['MSG'] = "fail to upload image";
        }
        echo json_encode($arraySend);
    }

    public function fetchShopListByAdminId()
    {
        if (isset($_GET['shopAdminId']) && isset($_GET['limitFrom'])) {
            $shopAdminId = $_GET['shopAdminId'];
            $limitFrom = $_GET['limitFrom'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        $shopLists = WebservicesellersModel::fetch_shopList_by_adminId($shopAdminId, $limitFrom);
        $arrayData['shopLists'] = $shopLists;

        $downloadIp = null;
        $downloadFolderSeller = null;
        $settings = WebservicesellersModel::fetchs_settings();
        for ($i = 0; $i < count($settings); $i++) {
            if ($settings[$i]['Name_Sig'] == 'Download_Ip') {
                $downloadIp = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_Shop') {
                $downloadFolderSeller = $settings[$i]['Value_Sig'];
            }
        }
        $arrayData['imgShopLogoUrl'] = $downloadIp . $downloadFolderSeller;

        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $arrayData;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    public function fetchShopInfoByShopId()
    {
        if (isset($_GET['shopID'])) {
            $shopId = $_GET['shopID'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        $shopInfo = WebservicesellersModel::fetch_shopInfo_by_shopId($shopId);
        $shopTels = WebservicesellersModel::fetch_shopTels_by_shopId($shopId);
        $shopMobilesSupport = WebservicesellersModel::fetch_shopMobilesSupport_by_shopId($shopId);
        $shopImages = WebservicesellersModel::fetch_shopImages_by_shopId($shopId);

        $shopCategories = WebservicesellersModel::fetch_shopCategories_by_shopId($shopId);
        $shopCategoryLevel3OneItem = WebservicesellersModel::fetch_jobsCategory_by_jobsCategoryId($shopCategories[0]['CatID_Stc']);
        $shopCategoryLevel2 = WebservicesellersModel::fetch_jobsCategory_by_jobsCategoryId($shopCategoryLevel3OneItem['ParnetID_Cgy']);
        $shopCategoryLevel1 = WebservicesellersModel::fetch_jobsCategory_by_jobsCategoryId($shopCategoryLevel2['ParnetID_Cgy']);

        $arrayShopCategory = array();
        $arrayShopCategory[0] = $shopCategoryLevel1;
        $arrayShopCategory[1] = $shopCategoryLevel2;
        $arrayShopCategory[2] = $shopCategories;

        $downloadIp = null;
        $downloadFolderShopLogo = null;
        $downloadFolderShopImages = null;
        $settings = WebservicesellersModel::fetchs_settings();
        for ($i = 0; $i < count($settings); $i++) {
            if ($settings[$i]['Name_Sig'] == 'Download_Ip') {
                $downloadIp = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_Shop') {
                $downloadFolderShopLogo = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_Shop_Images') {
                $downloadFolderShopImages = $settings[$i]['Value_Sig'];
            }
        }

        $arrayData = array();
        $arrayData['shopInfo'] = $shopInfo[0];
        $arrayData['shopTells'] = $shopTels;
        $arrayData['shopMobilesSupport'] = $shopMobilesSupport;
        $arrayData['shopCategories'] = $arrayShopCategory;
        $arrayData['shopImages'] = $shopImages;
        $arrayData['shopLogoUrl'] = $downloadIp . $downloadFolderShopLogo;
        $arrayData['shopImagesUrl'] = $downloadIp . $downloadFolderShopImages . $shopId . "/";

        if ($shopInfo != null) {
            $arraySend['Status'] = "100";
            $arraySend['Status_Str'] = "OK";
            $arraySend['Data'] = $arrayData;
            $arraySend['MSG'] = "OK";
        } else {
            $arraySend['Status'] = "101";
            $arraySend['Status_Str'] = "Error";
            $arraySend['MSG'] = "فروشگاه مورد نظر موجود نمیباشد.";
        }

        echo json_encode($arraySend);
    }

    public function insertNewShop()
    {
        if (isset($_POST['shopAdminID']) && isset($_POST['shopTitle']) && isset($_POST['shopAddress']) &&
            isset($_POST['shopLat']) && isset($_POST['shopLng']) && isset($_POST['shopWebsite']) &&
            isset($_POST['shopEmail']) && isset($_POST['shopDesc']) && isset($_POST['shopLicenceNumber']) &&
            isset($_POST['shopTels']) && isset($_POST['shopMobilesSupport'])) {
            $shopAdminID = $_POST['shopAdminID'];
            $shopTitle = $_POST['shopTitle'];
            $shopAddress = $_POST['shopAddress'];
            $shopLat = $_POST['shopLat'];
            $shopLng = $_POST['shopLng'];
            $shopWebsite = $_POST['shopWebsite'];
            $shopEmail = $_POST['shopEmail'];
            $shopDesc = $_POST['shopDesc'];
            $shopLicenceNumber = $_POST['shopLicenceNumber'];
            $shopTels = $_POST['shopTels'];
            $shopMobilesSupport = $_POST['shopMobilesSupport'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        $shopCategories = NULL;
        if (isset($_POST['shopCategories'])) {
            $shopCategories = $_POST['shopCategories'];
        }

        $shopId = WebservicesellersModel::insert_shop($shopAdminID, $shopTitle, $shopAddress, $shopLat, $shopLng, $shopWebsite, $shopEmail, $shopDesc, $shopLicenceNumber);

        $arrayShopTels = json_decode($shopTels, true);
        WebservicesellersModel::insert_shopTels($shopId, $arrayShopTels);

        $arrayShopMobilesSupport = json_decode($shopMobilesSupport, true);
        WebservicesellersModel::insert_shopMobilesSupport($shopId, $arrayShopMobilesSupport);

        if ($shopCategories != null) {
            $shopCategories = json_decode($shopCategories, true);
            WebservicesellersModel::insert_shopToCategory($shopId, $shopCategories);
        }

        $arraySend = array();
        if ($shopId != null) {
            $arraySend['Status'] = "100";
            $arraySend['Status_Str'] = "OK";
            $arraySend['Data'] = $shopId;
            $arraySend['MSG'] = "فروشگاه با موفقیت ثبت گردید.";
        } else {
            $arraySend['Status'] = "101";
            $arraySend['Status_Str'] = "Error";
            $arraySend['MSG'] = "خطا در هنگام ثبت فروشگاه.";
        }

        echo json_encode($arraySend);
    }

    public function updateShop()
    {
        if (isset($_POST['shopId']) && isset($_POST['shopAdminID']) && isset($_POST['shopCategories']) && isset($_POST['shopTitle']) && isset($_POST['shopAddress']) &&
            isset($_POST['shopLat']) && isset($_POST['shopLng']) && isset($_POST['shopWebsite']) &&
            isset($_POST['shopEmail']) && isset($_POST['shopDesc']) && isset($_POST['shopLicenceNumber']) &&
            isset($_POST['shopTels']) && isset($_POST['shopMobilesSupport'])) {
            $shopId = $_POST['shopId'];
            $shopAdminID = $_POST['shopAdminID'];
            $shopCategories = $_POST['shopCategories'];
            $shopTitle = $_POST['shopTitle'];
            $shopAddress = $_POST['shopAddress'];
            $shopLat = $_POST['shopLat'];
            $shopLng = $_POST['shopLng'];
            $shopWebsite = $_POST['shopWebsite'];
            $shopEmail = $_POST['shopEmail'];
            $shopDesc = $_POST['shopDesc'];
            $shopLicenceNumber = $_POST['shopLicenceNumber'];
            $shopTels = $_POST['shopTels'];
            $shopMobilesSupport = $_POST['shopMobilesSupport'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        WebservicesellersModel::update_shop($shopId, $shopAdminID, $shopTitle, $shopAddress, $shopLat, $shopLng, $shopWebsite, $shopEmail, $shopDesc, $shopLicenceNumber);

        $arrayShopTels = json_decode($shopTels, true);
        if ($arrayShopTels['addedItems'] != null) {
            WebservicesellersModel::insert_shopTels($shopId, $arrayShopTels['addedItems']);
        }
        if ($arrayShopTels['removedItems'] != null) {
            WebservicesellersModel::delete_shopTels($arrayShopTels['removedItems']);
        }
        if ($arrayShopTels['updatedItems'] != null) {
            WebservicesellersModel::update_shopTels($arrayShopTels['updatedItems']);
        }

        $arrayShopMobilesSupport = json_decode($shopMobilesSupport, true);
        if ($arrayShopMobilesSupport['addedItems'] != null) {
            WebservicesellersModel::insert_shopMobilesSupport($shopId, $arrayShopMobilesSupport['addedItems']);
        }
        if ($arrayShopMobilesSupport['removedItems'] != null) {
            WebservicesellersModel::delete_shopMobilesSupport($arrayShopMobilesSupport['removedItems']);
        }
        if ($arrayShopMobilesSupport['updatedItems'] != null) {
            WebservicesellersModel::update_shopMobilesSupport($arrayShopMobilesSupport['updatedItems']);
        }

        $shopCategories = json_decode($shopCategories, true);
        if ($shopCategories['addedItems'] != null) {
            WebservicesellersModel::insert_shopToCategory($shopId, $shopCategories['addedItems']);
        }
        if ($shopCategories['removedItems'] != null) {
            WebservicesellersModel::delete_shopToCategory($shopCategories['removedItems']);
        }
        if ($shopCategories['updatedItems'] != null) {
            WebservicesellersModel::update_shopToCategory($shopCategories['updatedItems']);
        }

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $shopId;
        $arraySend['MSG'] = "Shop updated successfully.";

        echo json_encode($arraySend);
    }

    public function uploadShopLogoByShopId()
    {
        $fileName = basename($_FILES['uploaded_file']['name']);
        $explode = explode('.', $fileName);
        $id = $explode[0];
        $ext = $explode[1];

        $milliseconds = round(microtime(true) * 1000);
        $fileName = $id . "_" . $milliseconds . "." . $ext;
        WebservicesellersModel::upload_shopLogo_by_shopId($id, $fileName);

        $file_path = "image/shopLogo/" . $fileName;
        if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {
            $arraySend['Status'] = "100";
            $arraySend['Status_Str'] = "OK";
            $arraySend['MSG'] = "uploadShopLogo: image uploaded successfully to server.";
        } else {
            $arraySend['Status'] = "101";
            $arraySend['Status_Str'] = "No";
            $arraySend['MSG'] = "uploadShopLogo: fail to upload image";
        }
        echo json_encode($arraySend);
    }

    public function uploadShopImagesByShopId()
    {
        // example: filename=id_number.jpg
        $fileName = basename($_FILES['uploaded_file']['name']);
        $explodeByUnderLine = explode('_', $fileName);
        $shopId = $explodeByUnderLine[0];
        $explodeByDot = explode('.', $explodeByUnderLine[1]);
        $imageNumber = $explodeByDot[0];
        $ext = $explodeByDot[1];

        $milliseconds = round(microtime(true) * 1000);
        $fileName = $imageNumber . "_" . $milliseconds . "." . $ext;
        WebservicesellersModel::upload_shopImages_by_shopId($shopId, $fileName);

        $directoryPath = getcwd() . "/image/shopImages/" . $shopId;
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath);
        }

        $file_path = "image/shopImages/" . $shopId . "/" . $fileName;
        if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {
            $arraySend['Status'] = "100";
            $arraySend['Status_Str'] = "OK";
            $arraySend['MSG'] = "uploadShopImages: image uploaded successfully to server.";
        } else {
            $arraySend['Status'] = "101";
            $arraySend['Status_Str'] = "No";
            $arraySend['MSG'] = "uploadShopImages: fail to upload image";
        }
        echo json_encode($arraySend);
    }

    public function fetchJobsCategoryByParentId()
    {
        if (isset($_GET['parentId'])) {
            $parentId = $_GET['parentId'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        $jobsCategoriesByParentId = WebservicesellersModel::fetch_jobsCategory_by_parentId($parentId);

        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $jobsCategoriesByParentId;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    public function fetchCategoryProductAndServiceLevel0ByCategoryJobsId()
    {
        if (isset($_GET['categoryJobsId'])) {
            $categoryJobsId = $_GET['categoryJobsId'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        $categoryProductAndServiceLevel0 = WebservicesellersModel::fetch_categoryProductAndServiceLevel0_by_categoryJobsId($categoryJobsId);

        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $categoryProductAndServiceLevel0;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    public function fetchCategoryProductAndServiceLevel0ByShopId()
    {
        if (isset($_GET['shopId'])) {
            $shopId = $_GET['shopId'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        $categoryProductAndServiceLevel0 = WebservicesellersModel::fetch_categoryProductAndServiceLevel0_by_shopId($shopId);

        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $categoryProductAndServiceLevel0;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    public function fetchCategoryProductAndServiceLevel1ByParentId()
    {
        if (isset($_GET['parentId'])) {
            $parentId = $_GET['parentId'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        $categoryProductAndServiceLevel1ByParentId = WebservicesellersModel::fetch_categoryProductAndServiceLevel1_by_parentId($parentId);

        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $categoryProductAndServiceLevel1ByParentId;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    public function searchProductAndServiceInGuildByName()
    {
        if (isset($_GET['searchPhrase']) && isset($_GET['shopId']) && isset($_GET['isSearchForUpdate'])) {
            $_GET["comeFromSearchInGuild"] = 1;
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }
        WebservicesellersController::searchProductAndServiceByNameOrCategoryProductAndServiceLevel1Id();
    }

    public function searchProductAndServiceByNameOrCategoryProductAndServiceLevel1Id()
    {
        $searchPhrase = null;
        $categoryProductAndServiceLevel1Id = null;
        if (isset($_GET['searchPhrase'])) {
            $searchPhrase = $_GET['searchPhrase'];
        } else if (isset($_GET['categoryProductAndServiceLevel1Id'])) {
            $categoryProductAndServiceLevel1Id = $_GET['categoryProductAndServiceLevel1Id'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        // shopId only use for update a product/service (this shop have this product/service or not)
        $shopId = NULL;
        if (isset($_GET['shopId'])) {
            $shopId = $_GET['shopId'];
        }

        $comeFromSearchInGuild = NULL;
        $isSearchForUpdate = NULL;
        if (isset($_GET['comeFromSearchInGuild']) && isset($_GET['isSearchForUpdate'])) {
            $comeFromSearchInGuild = $_GET['comeFromSearchInGuild'];
            $isSearchForUpdate = $_GET['isSearchForUpdate'];
        }


        if ($searchPhrase != null) {
            if ($shopId == null) {
                $productModels = WebservicesellersModel::search_product_by_modelName($searchPhrase);
                $serviceNames = WebservicesellersModel::search_serviceByName($searchPhrase);
            } else {
                if ($comeFromSearchInGuild == null) {
                    $productModels = WebservicesellersModel::searchproduct_by_modelNameAndShopId($searchPhrase, $shopId);
                    $serviceNames = WebservicesellersModel::searchservice_by_nameAndShopId($searchPhrase, $shopId);
                } else {
                    if ($isSearchForUpdate == 'true') {
                        $productModels = WebservicesellersModel::searchproductInShopGuild_by_modelNameAndShopId($searchPhrase, $shopId, true);
                        $serviceNames = WebservicesellersModel::searchserviceInShopGuild_by_nameAndShopId($searchPhrase, $shopId, true);
                    } else {
                        $productModels = WebservicesellersModel::searchproductInShopGuild_by_modelNameAndShopId($searchPhrase, $shopId, false);
                        $serviceNames = WebservicesellersModel::searchserviceInShopGuild_by_nameAndShopId($searchPhrase, $shopId, false);
                    }
                }
            }
        } else {
            if ($shopId == null) {
                $productModels = WebservicesellersModel::fetch_productModel_by_categoryProductAndServiceLevel1Id($categoryProductAndServiceLevel1Id);
                $serviceNames = WebservicesellersModel::fetch_service_by_categoryProductAndServiceLevel1Id($categoryProductAndServiceLevel1Id);
            } else {
                $productModels = WebservicesellersModel::fetch_productModelsInShop_by_categoryProductAndServiceLevel1Id($shopId, $categoryProductAndServiceLevel1Id);
                $serviceNames = WebservicesellersModel::fetch_serviceNamesInShop_by_categoryProductAndServiceLevel1Id($shopId, "new", $categoryProductAndServiceLevel1Id);
            }
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

        $arrayProductModel = array();
        for ($i = 0; $i < count($productModels); $i++) {
            $categoryProductAndServiceTitle = WebservicesellersModel::fetch_categoryProductAndServiceLevel1_by_categoryProductAndServiceLevel1Id($productModels[$i]['categoryProductAndServiceLevel1Id']);
            $productBrandNameAndOriginCountry = WebservicesellersModel::fetch_brandNameAndOriginCountry_by_productProducingCountryId($productModels[$i]['productProducingCountryId']);

            $arrayProductModel[$i]['productModelId'] = $productModels[$i]['productModelId'];
            $arrayProductModel[$i]['productCategoryTitle'] = $categoryProductAndServiceTitle['title'];
            $arrayProductModel[$i]['productModelName'] = $productModels[$i]['modelName'];
            $arrayProductModel[$i]['productBrandName'] = $productBrandNameAndOriginCountry['brandName'];
            $arrayProductModel[$i]['productDefaultLogo'] = $productModels[$i]['productDefaultLogo'];
            $arrayProductModel[$i]['productDefaultLogoUrl'] = $downloadIp . $downloadFolderProductDefaultLogo . "/";
        }

        $arrayServiceName = array();
        for ($i = 0; $i < count($serviceNames); $i++) {
            $categoryProductAndServiceTitle = WebservicesellersModel::fetch_categoryProductAndServiceLevel1_by_categoryProductAndServiceLevel1Id($serviceNames[$i]['categoryProductAndServiceLevel1Id']);

            $arrayServiceName[$i]['serviceNameId'] = $serviceNames[$i]['serviceNameId'];
            $arrayServiceName[$i]['serviceCategoryTitle'] = $categoryProductAndServiceTitle['title'];
            $arrayServiceName[$i]['serviceName'] = $serviceNames[$i]['serviceName'];
            $arrayServiceName[$i]['serviceDefaultLogo'] = $serviceNames[$i]['serviceDefaultLogo'];
            $arrayServiceName[$i]['serviceDefaultLogoUrl'] = $downloadIp . $downloadFolderServiceDefaultLogo . "/";
        }

        $arrayData = array();
        $arrayData['productModels'] = $arrayProductModel;
        $arrayData['serviceNames'] = $arrayServiceName;

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $arrayData;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    // complete for cloth
    public function fetchProductDetailsByBarcodeNumberOrProductModelIdWithShopId()
    {
        $barcodeNumber = null;
        $productModelId = null;
        if (isset($_GET['barcodeNumber'])) {
            $barcodeNumber = $_GET['barcodeNumber'];
        } else if (isset($_GET['productModelId'])) {
            $productModelId = $_GET['productModelId'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        $shopId = NULL;
        if (isset($_GET['shopId'])) {
            $shopId = $_GET['shopId'];
        }

        if ($barcodeNumber != null) {
            if ($shopId == null) {
                $productModelWithShopOrNot = WebservicesellersModel::fetch_productModel_by_barcodeNumber($barcodeNumber);
            } else {
                $productModelWithShopOrNot = WebservicesellersModel::fetch_productModel_by_barcodeNumberAndShopId($barcodeNumber, $shopId);
            }
        } else {
            if ($shopId == null) {
                $productModelWithShopOrNot = WebservicesellersModel::fetch_productModel_by_productModelId($productModelId);
            } else {
                $productModelWithShopOrNot = WebservicesellersModel::fetch_productModel_by_productModelIdAndShopId($productModelId, $shopId);
            }
        }
        if ($productModelWithShopOrNot == null) {
            $shopDoesNotHaveThisProduct = "این فروشگاه دارای این محصول نمیباشد.";
            if ($barcodeNumber != null) {
                if ($shopId == null) {
                    $this->echoError("بارکد مورد نظر یافت نشد!");
                } else {
                    $this->echoError($shopDoesNotHaveThisProduct);
                }
            } else {
                if ($shopId == null) {
                    $this->echoError("There is no product with this productModelId.");
                } else {
                    $this->echoError($shopDoesNotHaveThisProduct);
                }
            }
            return;
        }

        $categoryProductAndServiceTitle = WebservicesellersModel::fetch_categoryProductAndServiceLevel1_by_categoryProductAndServiceLevel1Id($productModelWithShopOrNot['categoryProductAndServiceLevel1Id']);
        $productDefaultPictures = WebservicesellersModel::fetch_productDefaultPictures_by_productModelId($productModelWithShopOrNot['productModelId']);

        $productBrandNameAndOriginCountry = WebservicesellersModel::fetch_brandNameAndOriginCountry_by_productProducingCountryId($productModelWithShopOrNot['productProducingCountryId']);
        $productProducingCountryName = WebservicesellersModel::fetch_productProducingCountryName_by_productProducingCountryId($productModelWithShopOrNot['productProducingCountryId']);

        $productSingleValueAttributes = WebservicesellersModel::fetch_productSingleValueAttributes_by_productModelId($productModelWithShopOrNot['productModelId']);
        $productMultipleValueAttributes = WebservicesellersModel::fetch_productMultipleValueAttributes_by_productModelId($productModelWithShopOrNot['productModelId']);
        $productAttributes = array_merge($productSingleValueAttributes, $productMultipleValueAttributes);

        $downloadIp = null;
        $downloadFolderProductDefaultPictures = null;
        $downloadFolderShopProductSpecificLogo = null;
        $downloadFolderShopProductSpecificPicture = null;
        $settings = WebservicesellersModel::fetchs_settings();
        for ($i = 0; $i < count($settings); $i++) {
            if ($settings[$i]['Name_Sig'] == 'Download_Ip') {
                $downloadIp = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_ProductDefaultPicture') {
                $downloadFolderProductDefaultPictures = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_ShopProductSpecificLogo') {
                $downloadFolderShopProductSpecificLogo = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_ShopProductSpecificPicture') {
                $downloadFolderShopProductSpecificPicture = $settings[$i]['Value_Sig'];
            }
        }

        $arrayBrand = array();
        $arrayBrand['brandName'] = $productBrandNameAndOriginCountry['brandName'];
        $arrayBrand['brandOriginCountryName'] = $productBrandNameAndOriginCountry['originCountryName'];
        $arrayBrand['productProducingCountryName'] = $productProducingCountryName['originCountryName'];

        $arrayData = array();
        $arrayData['productModelId'] = $productModelWithShopOrNot['productModelId'];
        $arrayData['categoryProductAndServiceTitle'] = $categoryProductAndServiceTitle['title'];
        $arrayData['productModelName'] = $productModelWithShopOrNot['modelName'];
        $arrayData['productBrand'] = $arrayBrand;
        $arrayData['productDefaultDesc'] = $productModelWithShopOrNot['productDefaultDesc'];
        $arrayData['productAttributes'] = $productAttributes;
        $arrayData['productDefaultPictures'] = $productDefaultPictures;
        $arrayData['productDefaultPicturesUrl'] = $downloadIp . $downloadFolderProductDefaultPictures . $productModelWithShopOrNot['productModelId'] . "/";

        if ($productModelWithShopOrNot['hasColor'] == 1) {
            $productModelColors = WebservicesellersModel::fetch_productModelToProductColor_by_productModelId($productModelId);
            $arrayData['productModelColors'] = $productModelColors;
        }

        if ($shopId != null) {
            // we have 'shopToProductModelId' when user set shopId
            $arrayData['shopToProductModelId'] = $productModelWithShopOrNot['shopToProductModelId'];

            if ($productModelWithShopOrNot['shopProductSpecificDesc'] != null) {
                $arrayData['shopProductSpecificDesc'] = $productModelWithShopOrNot['shopProductSpecificDesc'];
            }

            if ($productModelWithShopOrNot['shopProductSpecificLogo'] != null) {
                $arrayData['shopProductSpecificLogo'] = $productModelWithShopOrNot['shopProductSpecificLogo'];
                $arrayData['shopProductSpecificLogoUrl'] = $downloadIp . $downloadFolderShopProductSpecificLogo . "/";
            }

            if ($productModelWithShopOrNot['hasSpecificPicture'] != 0) {
                $shopProductSpecificPictures = WebservicesellersModel::fetch_shopProductSpecificPictures_by_shopToProductModelId($productModelWithShopOrNot['shopToProductModelId']);
                $arrayData['shopProductSpecificPictures'] = $shopProductSpecificPictures;
                $arrayData['shopProductSpecificPicturesUrl'] = $downloadIp . $downloadFolderShopProductSpecificPicture . $productModelWithShopOrNot['shopToProductModelId'] . "/";
            }

            if ($productModelWithShopOrNot['isCloth'] == 1) {
                // Table Cloth (color/size/quantity/price)
            } else {
                if ($productModelWithShopOrNot['hasColor'] == 1) {
                    $shopStockColorPrice = WebservicesellersModel::fetch_stockColorPrice_by_shopToProductModelId($productModelWithShopOrNot['shopToProductModelId']);
                    $arrayData['shopProductStock'] = $shopStockColorPrice;
                } else {
                    $shopStockPrice = WebservicesellersModel::fetch_stockPrice_by_shopToProductModelId($productModelWithShopOrNot['shopToProductModelId']);
                    $arrayData['shopProductStock'] = $shopStockPrice;
                }
            }
        } else {
            if ($productModelWithShopOrNot['isCloth'] == 1) {
                //$arrayData['size'] = $productSize;
            }
        }

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $arrayData;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    public function fetchServiceDetailsByServiceNameIdWithShopId()
    {
        if (isset($_GET['serviceNameId'])) {
            $serviceNameId = $_GET['serviceNameId'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        // Only use for update
        $shopId = NULL;
        if (isset($_GET['shopId'])) {
            $shopId = $_GET['shopId'];
        }

        if ($shopId == null) {
            $serviceNameWithShopOrNot = WebservicesellersModel::fetch_serviceName_by_serviceNameId($serviceNameId);
        } else {
            $serviceNameWithShopOrNot = WebservicesellersModel::fetch_serviceName_by_serviceNameIdAndShopId($serviceNameId, $shopId);
        }

        if ($serviceNameWithShopOrNot == null) {
            if ($shopId == null) {
                $this->echoError("خدمت مورد نظر یافت نشد!");
            } else {
                $this->echoError("این فروشگاه دارای این خدمت نمیباشد.");
                return;
            }
        }

        $categoryProductAndServiceTitle = WebservicesellersModel::fetch_categoryProductAndServiceLevel1_by_categoryProductAndServiceLevel1Id($serviceNameWithShopOrNot['categoryProductAndServiceLevel1Id']);
        $serviceDefaultPictures = WebservicesellersModel::fetch_serviceDefaultPictures_by_serviceNameId($serviceNameWithShopOrNot['serviceNameId']);

        $serviceSingleValueAttributes = WebservicesellersModel::fetch_serviceSingleValueAttributes_by_serviceNameId($serviceNameWithShopOrNot['serviceNameId']);
        $serviceMultipleValueAttributes = WebservicesellersModel::fetch_serviceMultipleValueAttributes_by_serviceNameId($serviceNameWithShopOrNot['serviceNameId']);
        $serviceAttributes = array_merge($serviceSingleValueAttributes, $serviceMultipleValueAttributes);

        $downloadIp = null;
        $downloadFolderServiceDefaultLogo = null;
        $downloadFolderShopServiceSpecificLogo = null;
        $downloadFolderServiceDefaultPicture = null;
        $downloadFolderShopServiceSpecificPicture = null;
        $settings = WebservicesellersModel::fetchs_settings();
        for ($i = 0; $i < count($settings); $i++) {
            if ($settings[$i]['Name_Sig'] == 'Download_Ip') {
                $downloadIp = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_ServiceDefaultLogo') {
                $downloadFolderServiceDefaultLogo = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_ShopServiceSpecificLogo') {
                $downloadFolderShopServiceSpecificLogo = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_ServiceDefaultPicture') {
                $downloadFolderServiceDefaultPicture = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_ShopServiceSpecificPicture') {
                $downloadFolderShopServiceSpecificPicture = $settings[$i]['Value_Sig'];
            }
        }

        $arrayData = array();
        $arrayData['serviceNameId'] = $serviceNameWithShopOrNot['serviceNameId'];
        $arrayData['categoryProductAndServiceTitle'] = $categoryProductAndServiceTitle['title'];
        $arrayData['serviceName'] = $serviceNameWithShopOrNot['serviceName'];
        $arrayData['serviceAttributes'] = $serviceAttributes;
        $arrayData['serviceDefaultDesc'] = $serviceNameWithShopOrNot['serviceDefaultDesc'];
        $arrayData['serviceDefaultLogo'] = $serviceNameWithShopOrNot['serviceDefaultLogo'];
        $arrayData['serviceDefaultLogoUrl'] = $downloadIp . $downloadFolderServiceDefaultLogo . "/";
        $arrayData['serviceDefaultPictures'] = $serviceDefaultPictures;
        $arrayData['serviceDefaultPicturesUrl'] = $downloadIp . $downloadFolderServiceDefaultPicture . $serviceNameWithShopOrNot['serviceNameId'] . "/";

        if ($shopId != null) {
            // we have 'shopToServiceNameId' when user set shopId
            $arrayData['shopToServiceNameId'] = $serviceNameWithShopOrNot['shopToServiceNameId'];
            $arrayData['servicePrice'] = $serviceNameWithShopOrNot['price'];
            $arrayData['serviceQuantity'] = $serviceNameWithShopOrNot['quantity'];
            $arrayData['serviceDiscount'] = $serviceNameWithShopOrNot['discount'];
            $arrayData['limitOrderQuantity'] = $serviceNameWithShopOrNot['limitOrderQuantity'];

            if ($serviceNameWithShopOrNot['shopServiceSpecificDesc'] != null) {
                $arrayData['shopServiceSpecificDesc'] = $serviceNameWithShopOrNot['shopServiceSpecificDesc'];
            }

            if ($serviceNameWithShopOrNot['shopServiceSpecificLogo'] != null) {
                $arrayData['shopServiceSpecificLogo'] = $serviceNameWithShopOrNot['shopServiceSpecificLogo'];
                $arrayData['shopServiceSpecificLogoUrl'] = $downloadIp . $downloadFolderShopServiceSpecificLogo . "/";
            }

            if ($serviceNameWithShopOrNot['hasSpecificPicture'] != 0) {
                $shopServiceSpecificPictures = WebservicesellersModel::fetch_shopServiceSpecificPictures_by_shopToServiceNameId($serviceNameWithShopOrNot['shopToServiceNameId']);
                $arrayData['shopServiceSpecificPictures'] = $shopServiceSpecificPictures;
                $arrayData['shopServiceSpecificPicturesUrl'] = $downloadIp . $downloadFolderShopServiceSpecificPicture . $serviceNameWithShopOrNot['shopToServiceNameId'] . "/";
            }
        }

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $arrayData;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    // complete for cloth
    public function insertProductForSpecificShop()
    {
        if (isset($_POST['shopId']) && isset($_POST['productModelId']) && isset($_POST['hasSpecificPicture']) && isset($_POST['productQuantity']) &&
            isset($_POST['productLimitOrderQuantity']) && isset($_POST['productPrice'])) {

            $shopId = $_POST['shopId'];
            $productModelId = $_POST['productModelId'];
            $hasSpecificPicture = $_POST['hasSpecificPicture'];
            $productQuantity = $_POST['productQuantity'];
            $productLimitOrderQuantity = $_POST['productLimitOrderQuantity'];
            $productPrice = $_POST['productPrice'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        $shopProductSpecificDesc = NULL;
        $productColorId = NULL;
        if (isset($_POST['shopProductSpecificDesc'])) {
            $shopProductSpecificDesc = $_POST['shopProductSpecificDesc'];
        }

        // Must be multiple color for feature
        if (isset($_POST['productColorId'])) {
            $productColorId = $_POST['productColorId'];
        }

        $shopToProductModelId = WebservicesellersModel::insert_productForSpecificShop($shopId, $productModelId, $shopProductSpecificDesc, $hasSpecificPicture);
        if ($shopToProductModelId == 0) {
            $shopToProductModel = WebservicesellersModel::fetch_shopToProductModel_by_shopIdAndProductModelId($shopId, $productModelId);
            $shopToProductModelId = $shopToProductModel['shopToProductModelId'];
        }

        if ($productColorId == null) {
            WebservicesellersModel::insert_stockPrice($shopToProductModelId, $productQuantity, $productLimitOrderQuantity, $productPrice);
        } else {
            WebservicesellersModel::insert_stockColorPrice($shopToProductModelId, $productColorId, $productQuantity, $productLimitOrderQuantity, $productPrice);
        }

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $shopToProductModelId;
        $arraySend['MSG'] = "محصول با موفقیت به فروشگاه اضافه گردید.";

        echo json_encode($arraySend);
    }

    public function insertServiceForSpecificShop()
    {
        if (isset($_POST['shopId']) && isset($_POST['serviceNameId']) && isset($_POST['hasSpecificPicture']) && isset($_POST['serviceQuantity']) &&
            isset($_POST['serviceLimitOrderQuantity']) && isset($_POST['servicePrice'])) {

            $shopId = $_POST['shopId'];
            $serviceNameId = $_POST['serviceNameId'];
            $hasSpecificPicture = $_POST['hasSpecificPicture'];
            $serviceQuantity = $_POST['serviceQuantity'];
            $servicePrice = $_POST['servicePrice'];
            $serviceLimitOrderQuantity = $_POST['serviceLimitOrderQuantity'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        $shopServiceSpecificDesc = NULL;
        if (isset($_POST['shopServiceSpecificDesc'])) {
            $shopServiceSpecificDesc = $_POST['shopServiceSpecificDesc'];
        }

        $shopToServiceNameId = WebservicesellersModel::insert_serviceForSpecificShop($shopId, $serviceNameId, $shopServiceSpecificDesc, $hasSpecificPicture, $servicePrice, $serviceQuantity, $serviceLimitOrderQuantity);
        if ($shopToServiceNameId == 0) {
            $shopToServiceName = WebservicesellersModel::fetch_shopToServiceName_by_shopIdAndServiceNameId($shopId, $serviceNameId);
            $shopToServiceNameId = $shopToServiceName['shopToServiceNameId'];
        }

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $shopToServiceNameId;
        $arraySend['MSG'] = "خدمت مورد نظر با موفقیت به فروشگاه اضافه گردید.";

        echo json_encode($arraySend);
    }

    public function updateStockAndShopProduct()
    {
        $stockPriceId = null;
        $stockColorPriceId = null;
        $productColorId = null;
        $stockClothColorSizePriceId = null;
        $clothSizeTableValueId = null;
        if (isset($_POST['stockPriceId'])) {
            $stockPriceId = $_POST['stockPriceId'];
        } else if (isset($_POST['stockColorPriceId']) && isset($_POST['productColorId'])) {
            $stockColorPriceId = $_POST['stockColorPriceId'];
            $productColorId = $_POST['productColorId'];
        } else if (isset($_POST['stockClothColorSizePriceId']) && isset($_POST['productColorId']) && isset($_POST['clothSizeTableValueId'])) {
            $stockClothColorSizePriceId = $_POST['stockClothColorSizePriceId'];
            $productColorId = $_POST['productColorId'];
            $clothSizeTableValueId = $_POST['clothSizeTableValueId'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        if (isset($_POST['productPrice']) && isset($_POST['productQuantity']) && isset($_POST['productLimitOrderQuantity']) &&
            isset($_POST['shopProductSpecificDesc']) && isset($_POST['hasSpecificPicture'])) {

            $productPrice = $_POST['productPrice'];
            $productQuantity = $_POST['productQuantity'];
            $productLimitOrderQuantity = $_POST['productLimitOrderQuantity'];
            $shopProductSpecificDesc = $_POST['shopProductSpecificDesc'];
            $hasSpecificPicture = $_POST['hasSpecificPicture'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        if ($stockPriceId != null) {
            WebservicesellersModel::update_stockPriceAndShopProduct_by_stockPriceId($stockPriceId, $productPrice, $productQuantity, $productLimitOrderQuantity, $shopProductSpecificDesc, $hasSpecificPicture);
        } else if ($stockColorPriceId != null) {
            WebservicesellersModel::update_stockColorPriceAndShopProduct_by_stockColorPriceId($stockColorPriceId, $productColorId, $productPrice, $productQuantity, $productLimitOrderQuantity, $shopProductSpecificDesc, $hasSpecificPicture);
        } else {
            WebservicesellersModel::update_stockClothColorSizePriceAndShopProduct_by_stockClothColorSizePriceId($stockClothColorSizePriceId, $productColorId, $clothSizeTableValueId, $productPrice, $productQuantity, $productLimitOrderQuantity, $shopProductSpecificDesc, $hasSpecificPicture);
        }

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['MSG'] = "محصول مورد نظر، با موفقیت ویرایش گردید.";

        echo json_encode($arraySend);
    }

    public function updateServiceForSpecificShop()
    {
        if (isset($_POST['shopToServiceNameId']) && isset($_POST['shopServiceSpecificDesc']) && isset($_POST['hasSpecificPicture'])
            && isset($_POST['serviceQuantity']) && isset($_POST['serviceLimitOrderQuantity']) && isset($_POST['servicePrice'])) {

            $shopToServiceNameId = $_POST['shopToServiceNameId'];
            $shopServiceSpecificDesc = $_POST['shopServiceSpecificDesc'];
            $hasSpecificPicture = $_POST['hasSpecificPicture'];
            $serviceQuantity = $_POST['serviceQuantity'];
            $servicePrice = $_POST['servicePrice'];
            $serviceLimitOrderQuantity = $_POST['serviceLimitOrderQuantity'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        WebservicesellersModel::update_serviceForSpecificShop_by_shopToServiceNameId($shopToServiceNameId, $shopServiceSpecificDesc, $hasSpecificPicture, $servicePrice, $serviceQuantity, $serviceLimitOrderQuantity);

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['MSG'] = "خدمت مورد نظر، با موفقیت ویرایش گردید.";

        echo json_encode($arraySend);
    }


    public function insertUserAccountFCMTokenWhenTokenRefreshed()
    {
        if (isset($_POST['userAccountParentId']) && isset($_POST['token'])) {
            $userAccountParentId = $_POST['userAccountParentId'];
            $token = $_POST['token'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        WebservicesellersModel::insert_userAccountFCMToken($userAccountParentId, $token);

        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    public function deleteFCMTokenWhenUserLogoutByFCMToken()
    {
        if (isset($_POST['FCMToken'])) {
            $FCMToken = $_POST['FCMToken'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        WebservicesellersModel::delete_userAccountFCMToken_by_FCMToken($FCMToken);

        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['MSG' . $FCMToken] = "OK";

        echo json_encode($arraySend);
    }

    public function fetchTicketsByUserAccountParentId()
    {
        if (isset($_GET['userAccountParentId'])) {
            $userAccountParentId = $_GET['userAccountParentId'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        $ticketSupport = WebservicesellersModel::fetch_ticketSupport_by_userAccountParentId($userAccountParentId);

        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $ticketSupport;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    public function fetchTicketSubject()
    {
        $ticketSubject = WebservicesellersModel::fetch_ticketSubject();

        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $ticketSubject;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    public function fetchTicketRepliesByTicketSupportId()
    {
        if (isset($_GET['ticketSupportId'])) {
            $ticketSupportId = $_GET['ticketSupportId'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        $ticketReplies = WebservicesellersModel::fetch_ticketReplies_by_ticketSupportId($ticketSupportId);

        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $ticketReplies;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    public function insertTicket()
    {
        if (isset($_POST['userAccountParentId']) && isset($_POST['ticketPriorityId']) && isset($_POST['ticketSubjectId']) && isset($_POST['title']) &&
            isset($_POST['message']) && isset($_POST['isSellerTicket'])) {
            $userAccountParentId = $_POST['userAccountParentId'];
            $ticketPriorityId = $_POST['ticketPriorityId'];
            $ticketSubjectId = $_POST['ticketSubjectId'];
            $title = $_POST['title'];
            $message = $_POST['message'];
            $isSellerTicket = $_POST['isSellerTicket'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        $currentTime = getCurrentDateTime();

        $ticketSupportId = WebservicesellersModel::insert_ticketSupport($ticketSubjectId, $ticketPriorityId, $userAccountParentId, $title, $currentTime, 1, $isSellerTicket);
        WebservicesellersModel::insert_ticketSupportReplies($ticketSupportId, $userAccountParentId, $message, $currentTime);

        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['MSG'] = "تیکت با موفقیت ثبت شد.";

        echo json_encode($arraySend);
    }

    public function updateTicketSupportIsActive()
    {
        if (isset($_POST['ticketSupportId']) && isset($_POST['isActive'])) {
            $ticketSupportId = $_POST['ticketSupportId'];
            $isActive = $_POST['isActive'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        WebservicesellersModel::update_ticketSupportIsActive_by_ticketSupportId($ticketSupportId, $isActive);

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['MSG'] = "وضعیت تیکت با موفقیت تغییر یافت.";

        echo json_encode($arraySend);
    }

    public function insertTicketReply()
    {
        if (isset($_POST['ticketSupportId']) && isset($_POST['isActive']) && isset($_POST['userAccountParentId']) && isset($_POST['replyMessage'])) {
            $ticketSupportId = $_POST['ticketSupportId'];
            $isActive = $_POST['isActive'];
            $userAccountParentId = $_POST['userAccountParentId'];
            $replyMessage = $_POST['replyMessage'];
        } else {
            WebservicesellersController::echoError("bad_parameter");
            return;
        }

        if ($isActive == 0) {
            WebservicesellersModel::update_ticketSupportIsActive_by_ticketSupportId($ticketSupportId, 1);
        }

        $currentTime = getCurrentDateTime();
        $ticketSupportRepliesId = WebservicesellersModel::insert_ticketSupportReplies($ticketSupportId, $userAccountParentId, $replyMessage, $currentTime);

        $arrayData = array();
        $arrayData['ticketSupportRepliesId'] = $ticketSupportRepliesId;
        $arrayData['creationTime'] = $currentTime;

        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $arrayData;
        $arraySend['MSG'] = "تیکت با موفقیت ثبت شد.";

        echo json_encode($arraySend);
    }


    public function pushNotificationDataToUserAccountDevices($tokens, $pushType, $title, $message, $payload, $isBackground, $imageUrl = '')
    {
        $firebase = new Firebase();
        $pushNotification = new PushNotification();

        $pushNotification->setTitle($title);
        $pushNotification->setMessage($message);
        $pushNotification->setIsBackground($isBackground);
        $pushNotification->setImage($imageUrl);
        // optional payload
        $pushNotification->setPayload($payload);

        $pushNotificationData = $pushNotification->getPushNotification();

        $response = '';
        if ($pushType == 'topic') {
            $response = $firebase->sendToTopic('global', $pushNotificationData);
        } else if ($pushType == 'individual') {
            $response = $firebase->send($tokens, $pushNotificationData);
        } else if ($pushType == 'multiple') {
            $response = $firebase->sendMultiple($tokens, $pushNotificationData);
        }

        // how to use response
        //https://developers.google.com/cloud-messaging/http
        //https://stackoverflow.com/questions/40518125/wich-fcm-registration-id-has-failed-when-targeted-for-multiple-registration-ids
        $arrayResponse = (array) json_decode($response);

        if($pushType != 'topic' && $arrayResponse['failure'] > 0){
            $results = $arrayResponse['results'];

            $tokensShouldBeDeleteFromDatabase = null;
            $tokensShouldBeResendToGoogleServer = null;
            for($i = 0; $i < count($results); $i++){
                if(isset($results[$i] -> error)){
                    if($results[$i] -> error == 'NotRegistered' || $results[$i] -> error == 'InvalidRegistration'){
                        $tokensShouldBeDeleteFromDatabase[] = $tokens[$i];
                    }else if($results[$i] -> error == 'Unavailable'){
                        // happen a few times -> must resend to google server
                        $tokensShouldBeResendToGoogleServer[] = $tokens[$i];
                    }
                }
            }

            if(count($tokensShouldBeDeleteFromDatabase) > 0){
                WebservicesellersModel::delete_userAccountFCMToken_by_FCMTokens($tokensShouldBeDeleteFromDatabase);
            }

            if(count($tokensShouldBeResendToGoogleServer) > 0){
                $this->pushNotificationDataToUserAccountDevices($tokensShouldBeResendToGoogleServer, $pushType, $title, $message, $payload, $isBackground, $imageUrl);
            }
        }
    }
}