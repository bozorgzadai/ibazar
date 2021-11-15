<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ali_Ai
 * Date: 7/15/2018
 * Time: 7:39 AM
 */

class ZtestController
{
    public function test(){
//        $tokens[0] = "e4gHNYaGJ40:APA91bFncW8QYPcfaynO7oIULq3xuI89pVXsYdjZeZEOG_M8yV1S-vly4IAUyzD3Vg9M82I1s6zw9P5FAnNPlbnez05JCFQ6vof0P8ZFdr21_B81Gem5mdHhFKBGZjO4ySy3jPSa3TtA";
//        $tokens[1] = "cRj2_qcuIHU:APA91bFL-rlB11m_3dN6bXzn6JkFPQfg8ZhojaSHucIJEDf2NxBjIZ-A9CCkHW-luMaQPbjhCZUsQis-IveACvsJYbXbA7WmdQNXpkFnKYzhdOjvRbOR-Dle9Enc8lbhRM15523OIKdN";
//        $tokens[2] = "dVwLZYuvY_c:APA91bHpwbD11NID8gVxTkezTXnRmxAEGct3t0sDpyw0IAm0dwuplfOjcLVt1QHmitfgWCI4szlp-XHEfEJClQUvXaQgvMV9w3R6Zwe_Qo2HzDhvReRzHbln5gak832sCAbv-1bEkOLS";
//        $tokens[3] = "12345678901234567890123456789012345678901234567890SHucIJEDf2NxBjIZ-A9CCkHW-luMaQPbjhCZUsQis-IveACvsJYbXbA7WmdQNXpkFnKYzhdOjvRbOR-Dle9Enc8lbhRM15523OIKdN";

        // insert message from employee
        $ticketSupportId = 8;
        $ticketTitle = "تست ۱";
        $userAccountParentId = 7;
        $message = "test 21";
        $currentTime = getCurrentDateTime();
        $ticketSupportRepliesId = WebservicesellersModel::insert_ticketSupportReplies($ticketSupportId, $userAccountParentId, $message, $currentTime);

        // get Tokens
        $userAccountFCMTokens = WebservicesellersModel::fetch_userAccountFCMToken_by_ticketSupportId($ticketSupportId);
        if($userAccountFCMTokens == null){
            echo("the account doesn't have any active login.");
            return;
        }

        $tokens = array();
        foreach($userAccountFCMTokens as $userAccountFCMToken){
            $tokens[] = $userAccountFCMToken['FCMToken'];
        }
        dump($tokens);

        $payload = array();
        $payload['notificationType'] = 'newSupportTicket';
        $payload['ticketSupportRepliesId'] = $ticketSupportRepliesId;
        $payload['ticketSupportId'] = $ticketSupportId;
        $payload['userAccountParentId'] = $userAccountParentId;
        $payload['ticketTitle'] = $ticketTitle;
        $payload['replyMessage'] = $message;
        $payload['creationTime'] = $currentTime;

        $webserviceSellersController = new WebservicesellersController();
        $webserviceSellersController->pushNotificationDataToUserAccountDevices($tokens, 'multiple', 'title', $message, $payload, TRUE, '');
    }








    public function fetchKeysAndValues()
    {
        $parentId = 2;
        $jobsCategoriesByParentId = WebservicesellersModel::fetch_jobsCategory_by_parentId($parentId);

        dump($jobsCategoriesByParentId);

        $keys = array_keys($jobsCategoriesByParentId[0]);
        dump($keys);

        for ($i = 0; $i < count($keys); $i++) {
            dump($keys[$i]);
        }

        dump($jobsCategoriesByParentId[0][$keys[0]]);
    }

    public function shopLatAndLng()
    {
        $db = Db::getInstance();
        $shops = $db->query("SELECT ID_Shp FROM tbl_shop");

        for ($i = 0; $i < 42; $i++) {
            //for($i = count($shops); $i >= 0; $i--){
            $lat = mt_rand(629511, 633206);
            $lng = mt_rand(671663, 677950);
            $lat = 32 + ($lat / 1000000);
            $lng = 51 + ($lng / 1000000);

            $db->modify("UPDATE tbl_shop SET Lat_Shp=:lat, Lng_Shp=:lng WHERE ID_Shp=:shopId", array(
                'lat' => $lat,
                'lng' => $lng,
                'shopId' => $shops[$i]['ID_Shp'],
            ));

            dump("success " . $i);
        }

        dump("success Final");

    }

    public function insertShopToCategory()
    {
        ini_set('max_execution_time', 9999999999);

        $db = Db::getInstance();
        $shops = $db->query("SELECT ID_Shp FROM tbl_shop");

        //$shopToCategory = $db->query("SELECT ID_Stc FROM tbl_shoptocat");
        //$shopsCount = count($shops);

        for ($i = 0; $i < count($shops); $i++) {
            //for($i = count($shops); $i >= 0; $i--){

            $mod = $i % 14;

            $categoryNumber = NULL;
            switch ($mod) {
                case 0:
                    $categoryNumber = 10101;
                    break;
                case 1:
                    $categoryNumber = 20102;
                    break;
                case 2:
                    $categoryNumber = 30101;
                    break;
                case 3:
                    $categoryNumber = 40101;
                    break;
                case 4:
                    $categoryNumber = 50201;
                    break;
                case 5:
                    $categoryNumber = 60101;
                    break;
                case 6:
                    $categoryNumber = 70102;
                    break;
                case 7:
                    $categoryNumber = 80104;
                    break;
                case 8:
                    $categoryNumber = 90102;
                    break;
                case 9:
                    $categoryNumber = 100102;
                    break;
                case 10:
                    $categoryNumber = 110101;
                    break;
                case 11:
                    $categoryNumber = 120103;
                    break;
                case 12:
                    $categoryNumber = 130102;
                    break;
                case 13:
                    $categoryNumber = 140102;
                    break;

                default:
                    $categoryNumber = 50201;
            }


            $db->insert("INSERT INTO tbl_shoptocat (ShopID_Stc, CatID_Stc)
                          VALUES (:ShopID_Stc, :CatID_Stc)", array(
                'ShopID_Stc' => $shops[$i]['ID_Shp'],
                'CatID_Stc' => $categoryNumber,
            ));

            /*$db->modify("UPDATE tbl_shoptocat SET CatID_Stc=:categoryNumber WHERE ID_Stc=:shopToCategoryId" , array(
                'categoryNumber' => $categoryNumber,
                'shopToCategoryId' => $shopToCategory[$i]['ID_Stc'],
            ));*/

            //dump("success " . $i);
        }

        dump("success Final");

    }
}