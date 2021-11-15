<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ali_Ai
 * Date: 9/1/2018
 * Time: 8:10 AM
 */

class WebservicepaymentController
{
    private function echoError($message)
    {
        $arraySend = array();
        $arraySend['Status'] = "101";
        $arraySend['Status_Str'] = "Error";
        $arraySend['MSG'] = $message;

        echo json_encode($arraySend);
    }

    public function findLatestPaymentCart($userAccountParentId)
    {
        $sessionId = session_id();
        $latestPaymentCart = null;

        if ($userAccountParentId != 0) {
            $latestPaymentCart = WebservicepaymentModel::fetch_paymentCart_by_invoiceUserAccountParentIdAndPaid($userAccountParentId, 0);
        }

        if ($latestPaymentCart != null) {
            // when the user had a paymentCart that not paid: right now we want to check that he/she create a new paymentCart before login
            $extraPaymentCart = WebservicepaymentModel::fetch_paymentCart_by_sessionIdAndUserAccountParentIdIsNull($sessionId);

            if ($extraPaymentCart != null) {
                $extraPaymentOrdersCartToStockPrice = WebservicepaymentModel::fetch_paymentOrderCartToStockPrice_by_paymentCartId($extraPaymentCart['paymentCartId']);
                $extraPaymentOrdersCartToStockColorPrice = WebservicepaymentModel::fetch_PaymentOrderCartToStockColorPrice_By_PaymentCartId($extraPaymentCart['paymentCartId']);
                $extraPaymentOrdersCartToStockClothColorSizePrice = WebservicepaymentModel::fetch_PaymentOrderCartToStockClothColorSizePrice_By_PaymentCartId($extraPaymentCart['paymentCartId']);
                $extraPaymentOrdersCartToShopToServiceName = WebservicepaymentModel::fetch_PaymentOrderCartToShopToServiceName_By_PaymentCartId($extraPaymentCart['paymentCartId']);

                if ($extraPaymentOrdersCartToStockPrice != null) {
                    foreach ($extraPaymentOrdersCartToStockPrice as $extraCartToStockPriceOrder) {
                        // search the new order in previous paymentCart
                        $previousPaymentOrderCartToStockPrice = WebservicepaymentModel::fetch_PaymentOrderCartToStockPrice_By_StockPriceIdAndPaymentCartId($extraCartToStockPriceOrder['stockPriceId'], $latestPaymentCart['paymentCartId']);

                        if ($previousPaymentOrderCartToStockPrice == null) {
                            WebservicepaymentModel::insert_PaymentOrderCartToStockPrice($latestPaymentCart['paymentCartId'], $extraCartToStockPriceOrder['stockPriceId'], $extraCartToStockPriceOrder['quantity'], $extraCartToStockPriceOrder['price'], $extraCartToStockPriceOrder['discount']);
                        } else {
                            $quantity = $previousPaymentOrderCartToStockPrice['quantity'] + $extraCartToStockPriceOrder['quantity'];
                            WebservicepaymentModel::update_PaymentOrderCartToStockPrice_Quantity_By_PaymentCartIdAndStockPriceId($latestPaymentCart['paymentCartId'], $extraCartToStockPriceOrder['stockPriceId'], $quantity);
                        }
                    }
                    WebservicepaymentModel::delete_paymentOrderCartToStockPrice_by_paymentCartId($extraPaymentCart['paymentCartId']);
                }

                if ($extraPaymentOrdersCartToStockColorPrice != null) {
                    foreach ($extraPaymentOrdersCartToStockColorPrice as $extraCartToStockColorPriceOrder) {
                        // search the new order in previous paymentCart
                        $previousPaymentOrderCartToStockColorPrice = WebservicepaymentModel::fetch_PaymentOrderCartToStockColorPrice_By_StockPriceIdAndPaymentCartId($extraCartToStockColorPriceOrder['stockColorPriceId'], $latestPaymentCart['paymentCartId']);

                        if ($previousPaymentOrderCartToStockColorPrice == null) {
                            WebservicepaymentModel::insert_PaymentOrderCartToStockColorPrice($latestPaymentCart['paymentCartId'], $extraCartToStockColorPriceOrder['stockColorPriceId'], $extraCartToStockColorPriceOrder['quantity'], $extraCartToStockColorPriceOrder['price'], $extraCartToStockColorPriceOrder['discount']);
                        } else {
                            $quantity = $previousPaymentOrderCartToStockColorPrice['quantity'] + $extraCartToStockColorPriceOrder['quantity'];
                            WebservicepaymentModel::update_PaymentOrderCartToStockColorPrice_Quantity_By_PaymentCartIdAndStockColorPriceId($latestPaymentCart['paymentCartId'], $extraCartToStockColorPriceOrder['stockColorPriceId'], $quantity);
                        }
                    }
                    WebservicepaymentModel::delete_paymentOrderCartToStockColorPrice_by_paymentCartId($extraPaymentCart['paymentCartId']);
                }

                if ($extraPaymentOrdersCartToStockClothColorSizePrice != null) {
                    foreach ($extraPaymentOrdersCartToStockClothColorSizePrice as $extraCartToStockClothColorSizePriceOrder) {
                        // search the new order in previous paymentCart
                        $previousPaymentOrderCartToStockClothColorSizePrice = WebservicepaymentModel::fetch_PaymentOrderCartToStockClothColorSizePrice_By_StockClothColorSizePriceIdAndPaymentCartId($extraCartToStockClothColorSizePriceOrder['stockClothColorSizePriceId'], $latestPaymentCart['paymentCartId']);

                        if ($previousPaymentOrderCartToStockClothColorSizePrice == null) {
                            WebservicepaymentModel::insert_PaymentOrderCartToStockClothColorSizePrice($latestPaymentCart['paymentCartId'], $extraCartToStockClothColorSizePriceOrder['stockClothColorSizePriceId'], $extraCartToStockClothColorSizePriceOrder['quantity'], $extraCartToStockClothColorSizePriceOrder['price'], $extraCartToStockClothColorSizePriceOrder['discount']);
                        } else {
                            $quantity = $previousPaymentOrderCartToStockClothColorSizePrice['quantity'] + $extraCartToStockClothColorSizePriceOrder['quantity'];
                            WebservicepaymentModel::update_PaymentOrderCartToStockClothColorSizePrice_Quantity_By_PaymentCartIdAndStockClothColorSizePriceId($latestPaymentCart['paymentCartId'], $extraCartToStockClothColorSizePriceOrder['stockClothColorSizePriceId'], $quantity);
                        }
                    }
                    WebservicepaymentModel::delete_paymentOrderCartToStockClothColorSizePrice_by_paymentCartId($extraPaymentCart['paymentCartId']);
                }

                if ($extraPaymentOrdersCartToShopToServiceName != null) {
                    foreach ($extraPaymentOrdersCartToShopToServiceName as $extraCartToShopToServiceName) {
                        // search the new order in previous paymentCart
                        $previousPaymentOrderCartToShopToServiceName = WebservicepaymentModel::fetch_PaymentOrderCartToShopToServiceName_By_ShopToServiceNameIdAndPaymentCartId($extraCartToShopToServiceName['shopToServiceNameId'], $latestPaymentCart['paymentCartId']);

                        if ($previousPaymentOrderCartToShopToServiceName == null) {
                            WebservicepaymentModel::insert_PaymentOrderCartToShopToServiceName($latestPaymentCart['paymentCartId'], $extraCartToShopToServiceName['shopToServiceNameId'], $extraCartToShopToServiceName['quantity'], $extraCartToShopToServiceName['price'], $extraCartToShopToServiceName['discount']);
                        } else {
                            $quantity = $previousPaymentOrderCartToShopToServiceName['quantity'] + $extraCartToShopToServiceName['quantity'];
                            WebservicepaymentModel::update_paymentOrderCartToShopToServiceName_quantity_by_paymentCartIdAndShopToServiceNameId($latestPaymentCart['paymentCartId'], $extraCartToShopToServiceName['shopToServiceNameId'], $quantity);
                        }
                    }
                    WebservicepaymentModel::delete_paymentOrderCartShopToServiceName_by_paymentCartId($extraPaymentCart['paymentCartId']);
                }

                $paymentInvoiceId = WebservicepaymentModel::fetch_paymentInvoiceId_by_sessionIdAndUserAccountParentIdIsNull($sessionId);
                WebservicepaymentModel::delete_paymentCart_by_sessionIdAndUserAccountParentIdIsNull($sessionId);
                WebservicepaymentModel::delete_paymentInvoice_by_paymentInvoiceId($paymentInvoiceId);
            }

            // come here when:
            // 1- the user have a not paid paymentCart and right now don't have any paymentCart without login
            // 1- the user have a not paid paymentCart and right now have a paymentCart without login
            WebservicepaymentModel::update_paymentCartSessionId_by_paymentCartId($sessionId, $latestPaymentCart['paymentCartId']);
        } else {
            // come here when:
            // 1- there is no user and work with sessionId
            // 2- we have user but don't have paymentCart that not paid
            $latestPaymentCart = WebservicepaymentModel::fetch_paymentCart_by_sessionIdAndPaid($sessionId, 0);

            if ($latestPaymentCart != null && $userAccountParentId != 0) {
                WebservicepaymentModel::update_paymentInvoiceUserAccountParentId_by_paymentCartId($userAccountParentId, $latestPaymentCart['paymentCartId']);
            }
        }

        return $latestPaymentCart;
    }

    public function getCurrentPaymentCart($userAccountParentId)
    {
        $latestPaymentCart = $this->findLatestPaymentCart($userAccountParentId);
        if ($latestPaymentCart != null) {
            return $latestPaymentCart;
        }

        $sessionId = session_id();

        if ($userAccountParentId == 0) {
            // when user is not login and want to create a new invoice
            $paymentInvoiceId = WebservicepaymentModel::insert_PaymentInvoiceWithoutUserAccountParentId(1, time());
        } else {
            // when user is login and want to create a new invoice
            $paymentInvoiceId = WebservicepaymentModel::insert_PaymentInvoiceWithUserAccountParentId(1, $userAccountParentId, time());
        }
        $paymentCartId = WebservicepaymentModel::insert_PaymentCart($paymentInvoiceId, $sessionId);

        // use bottom code instead of this
        //$paymentCart = WebservicepaymentModel::fetchPaymentCartByPaymentCartId($paymentCartId);

        $paymentCart = array();
        $paymentCart['paymentCartId'] = $paymentCartId;
        $paymentCart['paymentInvoiceId'] = $paymentInvoiceId;
        $paymentCart['sessionId'] = $sessionId;
        return $paymentCart;
    }


    private function calculateAndUpdatePaymentCartPriceWithReturnOrdersCount($paymentCart)
    {
        $paymentCartOrders = WebservicepaymentModel::fetch_paymentCartOrders_by_paymentCartId($paymentCart['paymentCartId']);

        $finalPrice = 0;
        foreach ($paymentCartOrders as $paymentCartOrder) {
            if ($paymentCartOrder['discount'] < 100) {
                $priceWithDiscount = $paymentCartOrder['price'] - $paymentCartOrder['price'] * $paymentCartOrder['discount'] / 100;
            } else {
                $priceWithDiscount = $paymentCartOrder['price'] - $paymentCartOrder['discount'];
            }
            $finalPrice += $paymentCartOrder['quantity'] * $priceWithDiscount;
        }

        WebservicepaymentModel::update_paymentInvoicePrice_by_paymentCartId($finalPrice, $paymentCart['paymentCartId']);

        return count($paymentCartOrders);
    }

    public function addToPaymentCart()
    {
        if (isset($_POST['userAccountParentId']) && isset($_POST['shopId'])) {
            $userAccountParentId = $_POST['userAccountParentId'];
            $shopId = $_POST['shopId'];
        } else {
            WebservicepaymentController::echoError("bad_parameter");
            return;
        }

        $stockPriceId = null;
        $stockColorPriceId = null;
        $stockClothColorSizePriceId = null;
        $shopToServiceNameId = null;
        if (isset($_POST['stockPriceId'])) {
            $stockPriceId = $_POST['stockPriceId'];
        } else if (isset($_POST['stockColorPriceId'])) {
            $stockColorPriceId = $_POST['stockColorPriceId'];
        } else if (isset($_POST['stockClothColorSizePriceId'])) {
            $stockClothColorSizePriceId = $_POST['stockClothColorSizePriceId'];
        } else if (isset($_POST['shopToServiceNameId'])) {
            $shopToServiceNameId = $_POST['shopToServiceNameId'];
        } else {
            WebservicepaymentController::echoError("bad_parameter");
            return;
        }

        $paymentCart = $this->getCurrentPaymentCart($userAccountParentId);
        $errorType = 0;

        $paymentCartShopId = WebservicepaymentModel::fetch_paymentCart_shopId_by_paymentCartId($paymentCart['paymentCartId']);
        if ($paymentCartShopId != null && $shopId != $paymentCartShopId) {
            WebservicepaymentController::echoError("ابتدا سبد خرید فروشگاه قبلی را پرداخت نمایید.");
            return;
        }

        // stockPrice
        if ($stockPriceId != null) {
            $stockPriceQuantityAndMaxOrder = WebservicepaymentModel::fetch_StockPrice_QuantityAndMaxOrder_By_StockPriceId($stockPriceId);

            if ($stockPriceQuantityAndMaxOrder['quantity'] != 0) {
                $currentPaymentOrderCartToStockPrice = WebservicepaymentModel::fetch_PaymentOrderCartToStockPrice_By_PaymentCartIdAndStockPriceId($paymentCart['paymentCartId'], $stockPriceId);

                if ($currentPaymentOrderCartToStockPrice == null) {
                    WebservicepaymentModel::insert_PaymentOrderCartToStockPrice($paymentCart['paymentCartId'], $stockPriceId, 1, $stockPriceQuantityAndMaxOrder['price'], $stockPriceQuantityAndMaxOrder['discount']);
                } else {
                    if ($currentPaymentOrderCartToStockPrice['quantity'] + 1 <= $stockPriceQuantityAndMaxOrder['maxOrder']) {
                        WebservicepaymentModel::update_PaymentOrderCartToStockPrice_Quantity_By_PaymentOrderCartToStockPriceId($currentPaymentOrderCartToStockPrice['paymentOrderCartToStockPriceId'], $currentPaymentOrderCartToStockPrice['quantity'] + 1);
                    } else {
                        $errorType = 2;
                    }
                }
            } else {
                $errorType = 1;
            }

            // stockColorPrice
        } else if ($stockColorPriceId != null) {
            $stockColorPriceQuantityAndMaxOrder = WebservicepaymentModel::fetch_StockColorPrice_QuantityAndMaxOrder_By_StockColorPriceId($stockColorPriceId);

            if ($stockColorPriceQuantityAndMaxOrder['quantity'] != 0) {
                $currentPaymentOrderCartToStockColorPrice = WebservicepaymentModel::fetch_PaymentOrderCartToStockColorPrice_By_PaymentCartIdAndStockColorPriceId($paymentCart['paymentCartId'], $stockColorPriceId);

                if ($currentPaymentOrderCartToStockColorPrice == null) {
                    WebservicepaymentModel::insert_PaymentOrderCartToStockColorPrice($paymentCart['paymentCartId'], $stockColorPriceId, 1, $stockColorPriceQuantityAndMaxOrder['price'], $stockColorPriceQuantityAndMaxOrder['discount']);
                } else {
                    if ($currentPaymentOrderCartToStockColorPrice['quantity'] + 1 <= $stockColorPriceQuantityAndMaxOrder['maxOrder']) {
                        WebservicepaymentModel::update_PaymentOrderCartToStockColorPrice_Quantity_By_PaymentOrderCartToStockColorPriceId($currentPaymentOrderCartToStockColorPrice['paymentOrderCartToStockColorPriceId'], $currentPaymentOrderCartToStockColorPrice['quantity'] + 1);
                    } else {
                        $errorType = 2;
                    }
                }
            } else {
                $errorType = 1;
            }

            // stockClothColorSizePrice
        } else if ($stockClothColorSizePriceId != null) {
            $stockClothColorSizePriceQuantityAndMaxOrder = WebservicepaymentModel::fetch_StockClothColorSizePrice_QuantityAndMaxOrder_By_StockClothColorSizePriceId($stockClothColorSizePriceId);

            if ($stockClothColorSizePriceQuantityAndMaxOrder['quantity'] != 0) {
                $currentPaymentOrderCartToStockClothColorSizePrice = WebservicepaymentModel::fetch_PaymentOrderCartToStockClothColorSizePrice_By_PaymentCartIdAndStockClothColorSizePriceId($paymentCart['paymentCartId'], $stockClothColorSizePriceId);

                if ($currentPaymentOrderCartToStockClothColorSizePrice == null) {
                    WebservicepaymentModel::insert_PaymentOrderCartToStockClothColorSizePrice($paymentCart['paymentCartId'], $stockClothColorSizePriceId, 1, $stockClothColorSizePriceQuantityAndMaxOrder['price'], $stockClothColorSizePriceQuantityAndMaxOrder['discount']);
                } else {
                    if ($currentPaymentOrderCartToStockClothColorSizePrice['quantity'] + 1 <= $stockClothColorSizePriceQuantityAndMaxOrder['maxOrder']) {
                        WebservicepaymentModel::update_PaymentOrderCartToStockClothColorSizePrice_Quantity_By_PaymentOrderCartToStockClothColorSizePriceId($currentPaymentOrderCartToStockClothColorSizePrice['paymentOrderCartToStockClothColorSizePriceId'], $currentPaymentOrderCartToStockClothColorSizePrice['quantity'] + 1);
                    } else {
                        $errorType = 2;
                    }
                }
            } else {
                $errorType = 1;
            }

        } else {
            $ShopToServiceNameQuantityAndMaxOrder = WebservicepaymentModel::fetch_ShopToServiceName_QuantityAndMaxOrder_By_ShopToServiceNameId($shopToServiceNameId);

            if ($ShopToServiceNameQuantityAndMaxOrder['quantity'] != 0) {
                $currentPaymentOrderCartToShopToServiceName = WebservicepaymentModel::fetch_PaymentOrderCartToShopToServiceName_By_ShopToServiceNameIdAndPaymentCartId($paymentCart['paymentCartId'], $shopToServiceNameId);

                if ($currentPaymentOrderCartToShopToServiceName == null) {
                    WebservicepaymentModel::insert_PaymentOrderCartToShopToServiceName($paymentCart['paymentCartId'], $shopToServiceNameId, 1, $ShopToServiceNameQuantityAndMaxOrder['price'], $ShopToServiceNameQuantityAndMaxOrder['discount']);
                } else {
                    if ($currentPaymentOrderCartToShopToServiceName['quantity'] + 1 <= $ShopToServiceNameQuantityAndMaxOrder['maxOrder']) {
                        WebservicepaymentModel::update_PaymentOrderCartToShopToServiceName_Quantity_By_PaymentOrderCartToShopToServiceNameId($currentPaymentOrderCartToShopToServiceName['paymentOrderCartToShopToServiceNameId'], $currentPaymentOrderCartToShopToServiceName['quantity'] + 1);
                    } else {
                        $errorType = 2;
                    }
                }
            } else {
                $errorType = 1;
            }
        }

        $paymentCartOrdersCount = $this->calculateAndUpdatePaymentCartPriceWithReturnOrdersCount($paymentCart);

        $arraySend = array();
        $arraySend['Data'] = $paymentCartOrdersCount;

        if ($errorType != 0) {
            $arraySend['Status'] = "101";
            $arraySend['Status_Str'] = "Error";

            if ($errorType == 1) {
                if ($shopToServiceNameId != null) {
                    $arraySend['MSG'] = "خدمت مورد نظر به اتمام رسیده است.";
                } else {
                    $arraySend['MSG'] = "کالا مورد نظر به اتمام رسیده است.";
                }
            } else {
                if ($shopToServiceNameId != null) {
                    $arraySend['MSG'] = "امکان سفارش بیشتر از این خدمت مقدور نمی باشد.";
                } else {
                    $arraySend['MSG'] = "امکان سفارش بیشتر از این کالا مقدور نمی باشد.";
                }
            }
        } else {
            $arraySend['Status'] = "100";
            $arraySend['Status_Str'] = "OK";

            if ($shopToServiceNameId != null) {
                $arraySend['MSG'] = "خدمت مورد نظر با موفقیت به سبد خرید اضافه شد.";
            } else {
                $arraySend['MSG'] = "کالا مورد نظر با موفقیت به سبد خرید اضافه شد.";
            }
        }

        echo json_encode($arraySend);
    }

    // complete for cloth
    public function fetchPaymentCartOrders()
    {
        if (isset($_GET['userAccountParentId'])) {
            $userAccountParentId = $_GET['userAccountParentId'];
        } else {
            WebservicepaymentController::echoError("bad_parameter");
            return;
        }

        $paymentCart = $this->getCurrentPaymentCart($userAccountParentId);

        $downloadIp = null;
        $downloadFolderProductDefaultLogo = null;
        $downloadFolderServiceDefaultLogo = null;
        $downloadFolderShopProductSpecificLogo = null;
        $downloadFolderShopServiceSpecificLogo = null;
        $settings = WebservicesellersModel::fetchs_settings();
        for ($i = 0; $i < count($settings); $i++) {
            if ($settings[$i]['Name_Sig'] == 'Download_Ip') {
                $downloadIp = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_ProductDefaultLogo') {
                $downloadFolderProductDefaultLogo = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_ServiceDefaultLogo') {
                $downloadFolderServiceDefaultLogo = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_ShopProductSpecificLogo') {
                $downloadFolderShopProductSpecificLogo = $settings[$i]['Value_Sig'];
            } else if ($settings[$i]['Name_Sig'] == 'Download_Folder_ShopServiceSpecificLogo') {
                $downloadFolderShopServiceSpecificLogo = $settings[$i]['Value_Sig'];
            }
        }

        $textStockQuantityIsZero = "stock quantity is 0 and we remove order";
        $textYourQuantityMoreThanStock = "your order quantity is more than stock";

        $paymentOrdersCartToStockPrice = WebservicepaymentModel::fetch_PaymentOrderCartToStockPrice_FullDetails_By_PaymentCartId($paymentCart['paymentCartId'], $textStockQuantityIsZero, $textYourQuantityMoreThanStock);
        $paymentOrdersCartToStockColorPrice = WebservicepaymentModel::fetch_PaymentOrderCartToStockColorPrice_FullDetails_By_PaymentCartId($paymentCart['paymentCartId'], $textStockQuantityIsZero, $textYourQuantityMoreThanStock);
        // cloth must complete
        $paymentOrdersCartToShopToServiceName = WebservicepaymentModel::fetch_PaymentOrderCartToShopToServiceName_FullDetails_By_PaymentCartId($paymentCart['paymentCartId'], $textStockQuantityIsZero, $textYourQuantityMoreThanStock);


        foreach ($paymentOrdersCartToStockPrice as $paymentOrderCartToStockPrice) {
            if ($paymentOrderCartToStockPrice['maxOrder'] == $textStockQuantityIsZero) {
                WebservicepaymentModel::delete_PaymentOrderCartToStockPrice_By_PaymentOrderCartToStockPriceId($paymentOrderCartToStockPrice['paymentOrderCartToStockPriceId']);
            }

            if ($paymentOrderCartToStockPrice['priceChanged'] == 'TRUE' || $paymentOrderCartToStockPrice['discountChanged'] == 'TRUE') {
                WebservicepaymentModel::update_paymentOrderCartToStockPrice_priceAndDiscount_by_paymentCartToStockPriceId($paymentOrderCartToStockPrice['paymentOrderCartToStockPriceId'], $paymentOrderCartToStockPrice['price'], $paymentOrderCartToStockPrice['discount']);
            }
        }

        foreach ($paymentOrdersCartToStockColorPrice as $paymentOrderCartToStockColorPrice) {
            if ($paymentOrderCartToStockColorPrice['maxOrder'] == $textStockQuantityIsZero) {
                WebservicepaymentModel::delete_PaymentOrderCartToStockColorPrice_By_PaymentOrderCartToStockColorPriceId($paymentOrderCartToStockColorPrice['paymentOrderCartToStockColorPriceId']);
            }

            if ($paymentOrderCartToStockColorPrice['priceChanged'] == 'TRUE' || $paymentOrderCartToStockColorPrice['discountChanged'] == 'TRUE') {
                WebservicepaymentModel::update_paymentOrderCartToStockColorPrice_priceAndDiscount_by_paymentCartToStockColorPriceId($paymentOrderCartToStockColorPrice['paymentOrderCartToStockColorPriceId'], $paymentOrderCartToStockColorPrice['price'], $paymentOrderCartToStockColorPrice['discount']);
            }
        }

        foreach ($paymentOrdersCartToShopToServiceName as $paymentOrderCartToShopToServiceName) {
            if ($paymentOrderCartToShopToServiceName['maxOrder'] == $textStockQuantityIsZero) {
                WebservicepaymentModel::delete_PaymentOrderCartToShopToServiceName_By_PaymentOrderCartToShopToServiceNameId($paymentOrderCartToShopToServiceName['paymentOrderCartToShopToServiceNameId']);
            }

            if ($paymentOrderCartToShopToServiceName['priceChanged'] == 'TRUE' || $paymentOrderCartToShopToServiceName['discountChanged'] == 'TRUE') {
                WebservicepaymentModel::update_paymentOrderCartToShopToServiceName_priceAndDiscount_by_paymentOrderCartToShopToServiceNameId($paymentOrderCartToShopToServiceName['paymentOrderCartToShopToServiceNameId'], $paymentOrderCartToShopToServiceName['price'], $paymentOrderCartToShopToServiceName['discount']);
            }
        }

        $arrayData = array();
        $arrayData['paymentOrdersCartToStockPrice'] = $paymentOrdersCartToStockPrice;
        $arrayData['paymentOrdersCartToStockColorPrice'] = $paymentOrdersCartToStockColorPrice;
        $arrayData['paymentOrdersCartToStockClothColorSizePrice'] = "For future";     // must complete
        $arrayData['paymentOrdersCartToServiceName'] = $paymentOrdersCartToShopToServiceName;
        $arrayData['productDefaultLogoUrl'] = $downloadIp . $downloadFolderProductDefaultLogo;
        $arrayData['shopProductSpecificLogoUrl'] = $downloadIp . $downloadFolderShopProductSpecificLogo;
        $arrayData['serviceDefaultLogoUrl'] = $downloadIp . $downloadFolderServiceDefaultLogo;
        $arrayData['shopServiceSpecificLogoUrl'] = $downloadIp . $downloadFolderShopServiceSpecificLogo;

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $arrayData;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    public function countPaymentCartOrderItems()
    {
        if (isset($_GET['userAccountParentId'])) {
            $userAccountParentId = $_GET['userAccountParentId'];
        } else {
            WebservicepaymentController::echoError("bad_parameter");
            return;
        }

        $paymentCart = $this->getCurrentPaymentCart($userAccountParentId);

        $countPaymentCartOrderItems = WebservicepaymentModel::fetch_countPaymentCartOrderItems_by_paymentCartId($paymentCart['paymentCartId']);

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['Data'] = $countPaymentCartOrderItems;
        $arraySend['MSG'] = "OK";

        echo json_encode($arraySend);
    }

    public function removePaymentCartOrder()
    {
        if (isset($_POST['userAccountParentId'])) {
            $userAccountParentId = $_POST['userAccountParentId'];
        } else {
            WebservicepaymentController::echoError("bad_parameter");
            return;
        }

        $paymentOrderCartToStockPriceId = null;
        $paymentOrderCartToStockColorPriceId = null;
        $paymentOrderCartToStockClothColorSizePriceId = null;
        $paymentOrderCartToShopToServiceNameId = null;
        if (isset($_POST['paymentOrderCartToStockPriceId'])) {
            $paymentOrderCartToStockPriceId = $_POST['paymentOrderCartToStockPriceId'];
        } else if (isset($_POST['paymentOrderCartToStockColorPriceId'])) {
            $paymentOrderCartToStockColorPriceId = $_POST['paymentOrderCartToStockColorPriceId'];
        } else if (isset($_POST['paymentOrderCartToStockClothColorSizePriceId'])) {
            $paymentOrderCartToStockClothColorSizePriceId = $_POST['paymentOrderCartToStockClothColorSizePriceId'];
        } else if (isset($_POST['paymentOrderCartToShopToServiceNameId'])) {
            $paymentOrderCartToShopToServiceNameId = $_POST['paymentOrderCartToShopToServiceNameId'];
        } else {
            WebservicepaymentController::echoError("bad_parameter");
            return;
        }

        if ($paymentOrderCartToStockPriceId != null) {
            WebservicepaymentModel::delete_PaymentOrderCartToStockPrice_By_PaymentOrderCartToStockPriceId($paymentOrderCartToStockPriceId);
        } else if ($paymentOrderCartToStockColorPriceId != null) {
            WebservicepaymentModel::delete_PaymentOrderCartToStockColorPrice_By_PaymentOrderCartToStockColorPriceId($paymentOrderCartToStockColorPriceId);
        } else if ($paymentOrderCartToStockClothColorSizePriceId != null) {
            WebservicepaymentModel::delete_PaymentOrderCartToStockClothColorSizePrice_By_PaymentOrderCartToStockClothColorSizePriceId($paymentOrderCartToStockClothColorSizePriceId);
        } else {
            WebservicepaymentModel::delete_PaymentOrderCartToShopToServiceName_By_PaymentOrderCartToShopToServiceNameId($paymentOrderCartToShopToServiceNameId);
        }

        $paymentCart = $this->getCurrentPaymentCart($userAccountParentId);
        $this->calculateAndUpdatePaymentCartPriceWithReturnOrdersCount($paymentCart);

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['MSG'] = "سفارش با موفقیت حذف گردید.";

        echo json_encode($arraySend);
    }

    public function updatePaymentCartOrderQuantity()
    {
        if (isset($_POST['userAccountParentId']) && isset($_POST['orderQuantity'])) {
            $userAccountParentId = $_POST['userAccountParentId'];
            $orderQuantity = $_POST['orderQuantity'];
        } else {
            WebservicepaymentController::echoError("bad_parameter");
            return;
        }

        $paymentOrderCartToStockPriceId = null;
        $paymentOrderCartToStockColorPriceId = null;
        $paymentOrderCartToStockClothColorSizePriceId = null;
        $paymentOrderCartToShopToServiceNameId = null;
        if (isset($_POST['paymentOrderCartToStockPriceId'])) {
            $paymentOrderCartToStockPriceId = $_POST['paymentOrderCartToStockPriceId'];
        } else if (isset($_POST['paymentOrderCartToStockColorPriceId'])) {
            $paymentOrderCartToStockColorPriceId = $_POST['paymentOrderCartToStockColorPriceId'];
        } else if (isset($_POST['paymentOrderCartToStockClothColorSizePriceId'])) {
            $paymentOrderCartToStockClothColorSizePriceId = $_POST['paymentOrderCartToStockClothColorSizePriceId'];
        } else if (isset($_POST['paymentOrderCartToShopToServiceNameId'])) {
            $paymentOrderCartToShopToServiceNameId = $_POST['paymentOrderCartToShopToServiceNameId'];
        } else {
            WebservicepaymentController::echoError("bad_parameter");
            return;
        }

        if ($paymentOrderCartToStockPriceId != null) {
            WebservicepaymentModel::update_PaymentOrderCartToStockPrice_Quantity_By_PaymentOrderCartToStockPriceId($paymentOrderCartToStockPriceId, $orderQuantity);
        } else if ($paymentOrderCartToStockColorPriceId != null) {
            WebservicepaymentModel::update_PaymentOrderCartToStockColorPrice_Quantity_By_PaymentOrderCartToStockColorPriceId($paymentOrderCartToStockColorPriceId, $orderQuantity);
        } else if ($paymentOrderCartToStockClothColorSizePriceId != null) {
            WebservicepaymentModel::update_PaymentOrderCartToStockClothColorSizePrice_Quantity_By_PaymentOrderCartToStockClothColorSizePriceId($paymentOrderCartToStockClothColorSizePriceId, $orderQuantity);
        } else {
            WebservicepaymentModel::update_PaymentOrderCartToShopToServiceName_Quantity_By_PaymentOrderCartToShopToServiceNameId($paymentOrderCartToShopToServiceNameId, $orderQuantity);
        }

        $paymentCart = $this->getCurrentPaymentCart($userAccountParentId);
        $this->calculateAndUpdatePaymentCartPriceWithReturnOrdersCount($paymentCart);

        $arraySend = array();
        $arraySend['Status'] = "100";
        $arraySend['Status_Str'] = "OK";
        $arraySend['MSG'] = "تعداد سفارش با موفقیت تغییر یافت.";

        echo json_encode($arraySend);
    }


    private function checkMaxOrderPriceDiscountErrorForEachOrderItem($orders, $textError1, $textError2)
    {
        $errorHappen = 0;

        foreach ($orders as $order) {
            if ($order['maxOrder'] == $textError1 || $order['maxOrder'] == $textError2) {
                $errorHappen = 1;
                break;
            } else if ($order['priceChanged'] == 'TRUE') {
                $errorHappen = 2;
                break;
            } else if ($order['discountChanged'] == 'TRUE') {
                $errorHappen = 3;
                break;
            }
        }

        return $errorHappen;
    }

    public function payPaymentCart()
    {
        if (isset($_GET['userAccountParentId']) && isset($_GET['bankPaymentGateway'])) {
            $userAccountParentId = $_GET['userAccountParentId'];
            $bankPaymentGateway = $_GET['bankPaymentGateway'];
        } else {
            WebservicepaymentController::echoError("bad_parameter");
            return;
        }

        if ($userAccountParentId == 0) {
            WebservicepaymentController::echoError("لطفا ابتدا وارد حساب کاربری خود شوید.");
            return;
        }

        $paymentCart = $this->findLatestPaymentCart($userAccountParentId);
        if ($paymentCart == null) {
            WebservicepaymentController::echoError("شما سبد خرید پرداخت نشده ای ندارید.");
            return;
        }

        // Check error in payment cart
        $textStockQuantityIsZero = "stock quantity is 0 and must refresh the cart";
        $textYourQuantityMoreThanStock = "your order quantity is more than stock";
        $paymentOrdersCartToStockPrice = WebservicepaymentModel::fetch_PaymentOrderCartToStockPrice_CheckQuantityPriceDiscount_By_PaymentCartId($paymentCart['paymentCartId'], $textStockQuantityIsZero, $textYourQuantityMoreThanStock);
        $paymentOrdersCartToStockColorPrice = WebservicepaymentModel::fetch_PaymentOrderCartToStockColorPrice_CheckQuantityPriceDiscount_By_PaymentCartId($paymentCart['paymentCartId'], $textStockQuantityIsZero, $textYourQuantityMoreThanStock);
        // cloth must complete
        $paymentOrdersCartToShopToServiceName = WebservicepaymentModel::fetch_PaymentOrderCartToShopToServiceName_CheckQuantityPriceDiscount_By_PaymentCartId($paymentCart['paymentCartId'], $textStockQuantityIsZero, $textYourQuantityMoreThanStock);

        $errorHappen = $this->checkMaxOrderPriceDiscountErrorForEachOrderItem($paymentOrdersCartToStockPrice, $textStockQuantityIsZero, $textYourQuantityMoreThanStock);
        if ($errorHappen == 0) {
            $errorHappen = $this->checkMaxOrderPriceDiscountErrorForEachOrderItem($paymentOrdersCartToStockColorPrice, $textStockQuantityIsZero, $textYourQuantityMoreThanStock);
        }
        if ($errorHappen == 0) {
            $errorHappen = $this->checkMaxOrderPriceDiscountErrorForEachOrderItem($paymentOrdersCartToShopToServiceName, $textStockQuantityIsZero, $textYourQuantityMoreThanStock);
        }

        if ($errorHappen == 1) {
            WebservicepaymentController::echoError("خطا در سفارشات سبد خرید. لطفا دوباره به سبد خرید مراجعه نمایید.");
            return;
        } else if ($errorHappen == 1) {
            WebservicepaymentController::echoError("قیمت تعدادی از محصولات تغییر یافته است. جهت اطلاع لطفا دوباره به سبد خرید مراجعه نمایید.");
            return;
        } else if ($errorHappen == 2) {
            WebservicepaymentController::echoError("میزان تخفیف تعدادی از محصولات تغییر یافته است. جهت اطلاع لطفا دوباره به سبد خرید مراجعه نمایید.");
            return;
        }

        $paymentInvoiceWithCustomerAndInvoiceType = WebservicepaymentModel::fetch_PaymentInvoiceWithCustomerAndInvoiceType_By_PaymentCartId($paymentCart['paymentCartId']);

        if ($paymentInvoiceWithCustomerAndInvoiceType['discount'] < 100) {
            $priceWithDiscount = $paymentInvoiceWithCustomerAndInvoiceType['price'] - $paymentInvoiceWithCustomerAndInvoiceType['price'] * $paymentInvoiceWithCustomerAndInvoiceType['discount'] / 100;
        } else {
            $priceWithDiscount = $paymentInvoiceWithCustomerAndInvoiceType['price'] - $paymentInvoiceWithCustomerAndInvoiceType['discount'];
        }

        $info['paymentInvoiceId'] = $paymentInvoiceWithCustomerAndInvoiceType['paymentInvoiceId'];
        $info['userAccountParentId'] = $paymentInvoiceWithCustomerAndInvoiceType['userAccountParentId'];
        $info['price'] = $priceWithDiscount;
        $info['email'] = $paymentInvoiceWithCustomerAndInvoiceType['customerEmail'];
        $info['mobile'] = $paymentInvoiceWithCustomerAndInvoiceType['Mobile_Cmr'];
        $info['title'] = $paymentInvoiceWithCustomerAndInvoiceType['typeName'];

        if ($bankPaymentGateway == 'mellat') {
            // mellat gateway
            $urlOrError = null;
        } else {
            $urlOrError = $this->zarinpalPaymentRequest($info);
        }

        $arraySend = array();
        if (substr($urlOrError, 0, 5) === "https") {
            // reduce form quantity before pay
            WebservicepaymentModel::update_StockPriceQuantity_By_StockPriceIdAndOrderQuantityAndStockQuantity($paymentOrdersCartToStockPrice, false);
            WebservicepaymentModel::update_StockColorPriceQuantity_By_StockColorPriceIdAndOrderQuantityAndStockQuantity($paymentOrdersCartToStockColorPrice, false);
            WebservicepaymentModel::update_ShopToServiceNameQuantity_By_ShopToServiceNameIdAndOrderQuantityAndStockQuantity($paymentOrdersCartToShopToServiceName, false);

            // it's better to redirect the user to bank url, because maybe the user can't get a url and his/her internet fail(and maybe remove the app after that)
            // so we don't know the payment fail and increase the order quantity to stock

            $arraySend['Status'] = "100";
            $arraySend['Status_Str'] = "OK";
            $arraySend['Data'] = $urlOrError;
            $arraySend['MSG'] = "OK";
        } else {
            $arraySend['Status'] = "101";
            $arraySend['Status_Str'] = "Error";
            $arraySend['MSG'] = $urlOrError;
        }

        echo json_encode($arraySend);
    }

    private function increaseQuantityIfTransactionFail($paymentInvoiceId)
    {
        $paymentOrdersCartToStockPrice = WebservicepaymentModel::fetch_paymentOrderCartToStockPrice_quantity_by_paymentInvoiceId($paymentInvoiceId);
        $paymentOrdersCartToStockColorPrice = WebservicepaymentModel::fetch_paymentOrderCartToStockColorPrice_quantity_by_paymentInvoiceId($paymentInvoiceId);
        // cloth must complete
        $paymentOrdersCartToShopToServiceName = WebservicepaymentModel::fetch_paymentOrderCartToShopToServiceName_quantity_by_paymentInvoiceId($paymentInvoiceId);

        WebservicepaymentModel::update_StockPriceQuantity_By_StockPriceIdAndOrderQuantityAndStockQuantity($paymentOrdersCartToStockPrice, true);
        WebservicepaymentModel::update_StockColorPriceQuantity_By_StockColorPriceIdAndOrderQuantityAndStockQuantity($paymentOrdersCartToStockColorPrice, true);
        WebservicepaymentModel::update_ShopToServiceNameQuantity_By_ShopToServiceNameIdAndOrderQuantityAndStockQuantity($paymentOrdersCartToShopToServiceName, true);
    }

    private function zarinpalPaymentRequest($info)
    {
        global $config;
        load_nusoap();

        do {
            $randomString = randomString(15);
            $transactionHash = encryptCharacters($randomString);
            $transactionData = WebservicepaymentModel::fetch_paymentTransaction_by_transactionHash($transactionHash);
        } while ($transactionData != null);

        WebservicepaymentModel::insert_paymentTransaction($info['paymentInvoiceId'], $info['userAccountParentId'], $transactionHash, time());

        //$client = new nusoap_client('https://ir.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
        $client = new nusoap_client('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
        $client->soap_defencoding = 'UTF-8';
        $result = $client->call('PaymentRequest', array(
                array(
                    'MerchantID' => $config['zarinpal']['merchantId'],
                    'Amount' => $info['price'],
                    'Description' => $info['title'],
                    'Email' => $info['email'],
                    'Mobile' => $info['mobile'] == null ? "" : $info['mobile'],
                    'CallbackURL' => fullBaseUrl() . '/webservicepayment/zarinpalVerify/' . $transactionHash,
                )
            )
        );

        WebservicepaymentModel::update_paymentTransaction_authority_by_transactionHash($transactionHash, $result['Authority']);

        if ($result['Status'] == 100) {
            //header('Location: https://ir.zarinpal.com/pg/StartPay/' . $authority);    // for web and android in future
            return 'https://sandbox.zarinpal.com/pg/StartPay/' . $result['Authority'];
        } else {
            return 'فرآیند پرداخت با مشکل مواجه شد. کد خطا: ' . $result['Status'];
        }
    }

    public function zarinpalVerify($transactionHash)
    {
        global $config;
        load_nusoap();

        $transactionWithInvoice = WebservicepaymentModel::fetch_paymentTransaction_withInvoice_by_transactionHash($transactionHash);
        if ($transactionWithInvoice['discount'] < 100) {
            $priceWithDiscount = $transactionWithInvoice['price'] - $transactionWithInvoice['price'] * $transactionWithInvoice['discount'] / 100;
        } else {
            $priceWithDiscount = $transactionWithInvoice['price'] - $transactionWithInvoice['discount'];
        }

        $locationToVerify = "return://fromOnlinePayment/test-zarinpal-payment/";
        if ($_GET['Status'] == 'OK') {
            $authority = $_GET['Authority'];
            //$client = new nusoap_client('https://ir.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
            $client = new nusoap_client('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
            $client->soap_defencoding = 'UTF-8';
            $result = $client->call('PaymentVerification', array(
                    array(
                        'MerchantID' => $config['zarinpal']['merchantId'],
                        'Authority' => $authority,
                        'Amount' => $priceWithDiscount,
                    )
                )
            );

            if ($result['Status'] == 100) {
                WebservicepaymentModel::update_paymentTransaction_reference_by_transactionHash($transactionHash, $result['RefID'], time(), 1);
                WebservicepaymentModel::update_paymentInvoicePaid_by_paymentInvoiceId($transactionWithInvoice['paymentInvoiceId'], 1);

                $this->sendNotificationToSellerApp($transactionWithInvoice['paymentInvoiceId']);

                $message = 'فرآیند پرداخت موفقیت آمیز بود. سند رهگیری: ' . $result['RefID'];
                header('Location: ' . $locationToVerify . $message);
            } else if ($result['Status'] == 101) {
                $message = 'فرآیند پرداخت، قبلا انجام شده و نیاز به تأیید مجدد نیست' . $result['Status'];
                header('Location: ' . $locationToVerify . $message);
            } else {
                $this->increaseQuantityIfTransactionFail($transactionWithInvoice['paymentInvoiceId']);
                $message = 'فرآیند پرداخت با خطا مواجه شد. کد خطا: ' . $result['Status'];
                header('Location: ' . $locationToVerify . $message);
            }
        } else {
            $this->increaseQuantityIfTransactionFail($transactionWithInvoice['paymentInvoiceId']);
            $message = 'فرآیند پرداخت توسط کاربر، لغو شد!';
            header('Location: ' . $locationToVerify . $message);
        }
    }

    private function sendNotificationToSellerApp($paymentInvoice)
    {
        $paymentCartShopId = WebservicepaymentModel::fetch_paymentCart_shopId_by_paymentCartId($paymentInvoice);

        // get Tokens
        $userAccountFCMTokens = WebservicesellersModel::fetch_userAccountFCMToken_shopAdminTokensAndShopName_by_shopId($paymentCartShopId);
        if ($userAccountFCMTokens == null) {
            echo("the account doesn't have any active login.");
            return;
        }

        $tokens = array();
        foreach ($userAccountFCMTokens as $userAccountFCMToken) {
            $tokens[] = $userAccountFCMToken['FCMToken'];
        }

        $notificationMessage = "ثبت سفارشی جدید از فروشگاه '" .$userAccountFCMTokens[0]['Title_Shp']. "'";

        $payload = array();
        $payload['notificationType'] = 'newOrder';
        $payload['paymentInvoice'] = $paymentInvoice;

        $webserviceSellersController = new WebservicesellersController();
        $webserviceSellersController->pushNotificationDataToUserAccountDevices($tokens, 'multiple', 'سفارش جدید', $notificationMessage, $payload, TRUE, '');
    }
}