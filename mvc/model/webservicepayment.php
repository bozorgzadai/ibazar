<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ali_Ai
 * Date: 9/1/2018
 * Time: 8:12 AM
 */

class WebservicepaymentModel
{
    public static function fetch_paymentCart_by_paymentCartId($paymentCartId)
    {
        $db = Db::getInstance();
        $paymentCart = $db->first("SELECT * FROM payment_cart WHERE paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentCart;
    }

    public static function fetch_paymentCart_by_invoiceUserAccountParentIdAndPaid($userAccountParentId, $paid)
    {
        $db = Db::getInstance();
        $paymentCart = $db->first("SELECT payment_cart.*, userAccountParentId, paid FROM payment_cart 
                          INNER JOIN payment_invoice ON payment_cart.paymentInvoiceId = payment_invoice.paymentInvoiceId
                          WHERE payment_invoice.userAccountParentId = :userAccountParentId AND payment_invoice.paid = :paid", array(
            'userAccountParentId' => $userAccountParentId,
            'paid' => $paid,
        ));

        return $paymentCart;
    }

    public static function fetch_paymentCart_by_sessionIdAndPaid($sessionId, $paid)
    {
        $db = Db::getInstance();
        $paymentCart = $db->first("SELECT payment_cart.*, paid FROM payment_cart 
                          INNER JOIN payment_invoice ON payment_cart.paymentInvoiceId = payment_invoice.paymentInvoiceId
                          WHERE payment_cart.sessionId = :sessionId AND payment_invoice.paid = :paid", array(
            'sessionId' => $sessionId,
            'paid' => $paid,
        ));

        return $paymentCart;
    }

    public static function fetch_paymentCart_by_sessionIdAndUserAccountParentIdIsNull($sessionId)
    {
        $db = Db::getInstance();
        $paymentCart = $db->first("SELECT payment_cart.*, userAccountParentId FROM payment_cart 
                          INNER JOIN payment_invoice ON payment_cart.paymentInvoiceId = payment_invoice.paymentInvoiceId
                          WHERE payment_cart.sessionId = :sessionId AND payment_invoice.userAccountParentId IS NULL", array(
            'sessionId' => $sessionId,
        ));

        return $paymentCart;
    }

    public static function fetch_paymentOrderCartToStockPrice_by_paymentCartId($paymentCartId)
    {
        $db = Db::getInstance();
        $paymentOrderCartToStockPrice = $db->query("SELECT * FROM payment_order_cart_to_stock_price WHERE paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentOrderCartToStockPrice;
    }

    public static function fetch_paymentOrderCartToStockClothColorSizePrice_by_paymentCartId($paymentCartId)
    {
        $db = Db::getInstance();
        $paymentOrderCartToStockClothColorSizePrice = $db->query("SELECT * FROM payment_order_cart_to_stock_cloth_color_size_price WHERE paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentOrderCartToStockClothColorSizePrice;
    }

    public static function fetch_paymentOrderCartToStockColorPrice_by_paymentCartId($paymentCartId)
    {
        $db = Db::getInstance();
        $paymentOrderCartToStockColorPrice = $db->query("SELECT * FROM payment_order_cart_to_stock_color_price WHERE paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentOrderCartToStockColorPrice;
    }

    public static function fetch_paymentOrderCartToShopToServiceName_by_paymentCartId($paymentCartId)
    {
        $db = Db::getInstance();
        $paymentOrderCartToShopToService = $db->query("SELECT * FROM payment_order_cart_to_shop_to_service_name WHERE paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentOrderCartToShopToService;
    }

    public static function fetch_paymentOrderCartToStockPrice_by_stockPriceIdAndPaymentCartId($stockPriceId, $paymentCartId)
    {
        $db = Db::getInstance();
        $paymentOrderStockPrice = $db->first("SELECT * FROM payment_order_cart_to_stock_price
                        WHERE stockPriceId=:stockPriceId AND paymentCartId=:paymentCartId", array(
            'stockPriceId' => $stockPriceId,
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentOrderStockPrice;
    }

    public static function fetch_paymentOrderCartToStockColorPrice_by_stockPriceIdAndPaymentCartId($stockColorPriceId, $paymentCartId)
    {
        $db = Db::getInstance();
        $paymentOrderStockColorPrice = $db->first("SELECT * FROM payment_order_cart_to_stock_color_price
                        WHERE stockColorPriceId=:stockColorPriceId AND paymentCartId=:paymentCartId", array(
            'stockColorPriceId' => $stockColorPriceId,
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentOrderStockColorPrice;
    }

    public static function fetch_paymentOrderCartToStockClothColorSizePrice_by_stockClothColorSizePriceIdAndPaymentCartId($stockClothColorSizePriceId, $paymentCartId)
    {
        $db = Db::getInstance();
        $paymentOrderStockClothColorSizePrice = $db->first("SELECT * FROM payment_order_cart_to_stock_cloth_color_size_price
                        WHERE stockClothColorSizePriceId=:stockClothColorSizePriceId AND paymentCartId=:paymentCartId", array(
            'stockClothColorSizePriceId' => $stockClothColorSizePriceId,
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentOrderStockClothColorSizePrice;
    }

    public static function fetch_paymentOrderCartToShopToServiceName_by_shopToServiceNameIdAndPaymentCartId($shopToServiceNameId, $paymentCartId)
    {
        $db = Db::getInstance();
        $paymentOrderCartToShopToServiceName = $db->first("SELECT * FROM payment_order_cart_to_shop_to_service_name
                        WHERE shopToServiceNameId=:shopToServiceNameId AND paymentCartId=:paymentCartId", array(
            'shopToServiceNameId' => $shopToServiceNameId,
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentOrderCartToShopToServiceName;
    }

    public static function insert_paymentCart($paymentInvoiceId, $sessionId)
    {
        $db = Db::getInstance();
        $paymentCartId = $db->insert("INSERT INTO payment_cart (paymentInvoiceId , sessionId) 
                          VALUES (:paymentInvoiceId , :sessionId)", array(
            'paymentInvoiceId' => $paymentInvoiceId,
            'sessionId' => $sessionId,
        ));

        return $paymentCartId;
    }

    public static function insert_paymentOrderCartToStockPrice($paymentCartId, $stockPriceId, $quantity, $price, $discount)
    {
        $db = Db::getInstance();
        $paymentOrderCartToStockPriceId = $db->insert("INSERT INTO payment_order_cart_to_stock_price (paymentCartId , stockPriceId , quantity, price, discount) 
                          VALUES (:paymentCartId , :stockPriceId , :quantity, :price, :discount)", array(
            'paymentCartId' => $paymentCartId,
            'stockPriceId' => $stockPriceId,
            'quantity' => $quantity,
            'price' => $price,
            'discount' => $discount,
        ));

        return $paymentOrderCartToStockPriceId;
    }

    public static function insert_paymentOrderCartToStockColorPrice($paymentCartId, $stockColorPriceId, $quantity, $price, $discount)
    {
        $db = Db::getInstance();
        $paymentOrderCartToStockColorPriceId = $db->insert("INSERT INTO payment_order_cart_to_stock_color_price (paymentCartId , stockColorPriceId , quantity, price, discount) 
                          VALUES (:paymentCartId , :stockColorPriceId , :quantity, :price, :discount)", array(
            'paymentCartId' => $paymentCartId,
            'stockColorPriceId' => $stockColorPriceId,
            'quantity' => $quantity,
            'price' => $price,
            'discount' => $discount,
        ));

        return $paymentOrderCartToStockColorPriceId;
    }

    public static function insert_paymentOrderCartToStockClothColorSizePrice($paymentCartId, $stockClothColorSizePriceId, $quantity, $price, $discount)
    {
        $db = Db::getInstance();
        $paymentOrderCartToStockClothColorSizePriceId = $db->insert("INSERT INTO payment_order_cart_to_stock_cloth_color_size_price (paymentCartId , stockClothColorSizePriceId , quantity, price, discount) 
                          VALUES (:paymentCartId , :stockClothColorSizePriceId , :quantity, :price, :discount)", array(
            'paymentCartId' => $paymentCartId,
            'stockClothColorSizePriceId' => $stockClothColorSizePriceId,
            'quantity' => $quantity,
            'price' => $price,
            'discount' => $discount,
        ));

        return $paymentOrderCartToStockClothColorSizePriceId;
    }

    public static function insert_paymentOrderCartToShopToServiceName($paymentCartId, $ShopToServiceNameId, $quantity, $price, $discount)
    {
        $db = Db::getInstance();
        $paymentOrderCartToShopToServiceNameId = $db->insert("INSERT INTO payment_order_cart_to_shop_to_service_name (paymentCartId , shopToServiceNameId , quantity, price, discount) 
                          VALUES (:paymentCartId , :shopToServiceNameId , :quantity, :price, :discount)", array(
            'paymentCartId' => $paymentCartId,
            'shopToServiceNameId' => $ShopToServiceNameId,
            'quantity' => $quantity,
            'price' => $price,
            'discount' => $discount,
        ));

        return $paymentOrderCartToShopToServiceNameId;
    }

    public static function update_paymentOrderCartToStockPrice_Quantity_by_paymentCartIdAndStockPriceId($paymentCartId, $StockPriceId, $quantity)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_order_cart_to_stock_price SET quantity=:quantity 
                  WHERE stockPriceId=:stockPriceId AND paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
            'stockPriceId' => $StockPriceId,
            'quantity' => $quantity,
        ));
    }

    public static function update_paymentOrderCartToStockColorPrice_Quantity_by_paymentCartIdAndStockColorPriceId($paymentCartId, $stockColorPriceId, $quantity)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_order_cart_to_stock_color_price SET quantity=:quantity 
                  WHERE stockColorPriceId=:stockColorPriceId AND paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
            'stockColorPriceId' => $stockColorPriceId,
            'quantity' => $quantity,
        ));
    }

    public static function update_paymentOrderCartToStockClothColorSizePrice_Quantity_by_paymentCartIdAndStockClothColorSizePriceId($paymentCartId, $stockClothColorSizePriceId, $quantity)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_order_cart_to_stock_cloth_color_size_price SET quantity=:quantity 
                  WHERE stockClothColorSizePriceId=:stockClothColorSizePriceId AND paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
            'stockClothColorSizePriceId' => $stockClothColorSizePriceId,
            'quantity' => $quantity,
        ));
    }

    public static function update_paymentOrderCartToShopToServiceName_quantity_by_paymentCartIdAndShopToServiceNameId($paymentCartId, $ShopToServiceNameId, $quantity)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_order_cart_to_shop_to_service_name SET quantity=:quantity 
                  WHERE shopToServiceNameId=:shopToServiceNameId AND paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
            'shopToServiceNameId' => $ShopToServiceNameId,
            'quantity' => $quantity,
        ));
    }

    public static function delete_paymentOrderCartToStockPrice_by_paymentCartId($paymentCartId)
    {
        $db = Db::getInstance();
        $db->modify("DELETE FROM payment_order_cart_to_stock_price WHERE paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));
    }

    public static function delete_paymentOrderCartToStockColorPrice_by_paymentCartId($paymentCartId)
    {
        $db = Db::getInstance();
        $db->modify("DELETE FROM payment_order_cart_to_stock_color_price WHERE paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));
    }

    public static function delete_paymentOrderCartToStockClothColorSizePrice_by_paymentCartId($paymentCartId)
    {
        $db = Db::getInstance();
        $db->modify("DELETE FROM payment_order_cart_to_stock_cloth_color_size_price WHERE paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));
    }

    public static function delete_paymentOrderCartShopToServiceName_by_paymentCartId($paymentCartId)
    {
        $db = Db::getInstance();
        $db->modify("DELETE FROM payment_order_cart_to_shop_to_service_name WHERE paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));
    }

    public static function delete_paymentCart_by_sessionIdAndUserAccountParentIdIsNull($sessionId)
    {
        $db = Db::getInstance();
        $db->modify("DELETE payment_cart FROM payment_cart
                      INNER JOIN payment_invoice ON payment_cart.paymentInvoiceId = payment_invoice.paymentInvoiceId
                      WHERE sessionId=:sessionId AND payment_invoice.userAccountParentId IS NULL", array(
            'sessionId' => $sessionId,
        ));
    }

    public static function fetch_paymentInvoiceId_by_sessionIdAndUserAccountParentIdIsNull($sessionId)
    {
        $db = Db::getInstance();
        $paymentInvoiceId = $db->first("SELECT payment_invoice.paymentInvoiceId FROM payment_invoice
                      INNER JOIN payment_cart ON payment_invoice.paymentInvoiceId = payment_cart.paymentInvoiceId
                      WHERE sessionId=:sessionId AND payment_invoice.userAccountParentId IS NULL", array(
            'sessionId' => $sessionId,
        ), 'paymentInvoiceId');

        return $paymentInvoiceId;
    }

    public static function delete_paymentInvoice_by_paymentInvoiceId($paymentInvoiceId)
    {
        $db = Db::getInstance();
        $db->modify("DELETE FROM payment_invoice WHERE paymentInvoiceId=:paymentInvoiceId", array(
            'paymentInvoiceId' => $paymentInvoiceId,
        ));
    }

    public static function update_paymentCartSessionId_by_paymentCartId($sessionId, $paymentCartId)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_cart SET sessionId=:sessionId WHERE paymentCartId=:paymentCartId", array(
            'sessionId' => $sessionId,
            'paymentCartId' => $paymentCartId,
        ));
    }

    public static function insert_paymentInvoiceWithoutUserAccountParentId($paymentInvoiceTypeId, $creationDate)
    {
        $db = Db::getInstance();
        $paymentInvoiceId = $db->insert("INSERT INTO payment_invoice (paymentInvoiceTypeId, creationDate) 
                          VALUES (:paymentInvoiceTypeId, :creationDate)", array(
            'paymentInvoiceTypeId' => $paymentInvoiceTypeId,
            'creationDate' => $creationDate,
        ));

        return $paymentInvoiceId;
    }

    public static function insert_paymentInvoiceWithUserAccountParentId($paymentInvoiceTypeId, $userAccountParentId, $creationDate)
    {
        $db = Db::getInstance();
        $paymentInvoiceId = $db->insert("INSERT INTO payment_invoice (paymentInvoiceTypeId, userAccountParentId, creationDate) 
                          VALUES (:paymentInvoiceTypeId, :userAccountParentId, :creationDate)", array(
            'paymentInvoiceTypeId' => $paymentInvoiceTypeId,
            'userAccountParentId' => $userAccountParentId,
            'creationDate' => $creationDate,
        ));

        return $paymentInvoiceId;
    }

    public static function update_paymentInvoiceUserAccountParentId_by_paymentInvoiceId($userAccountParentId, $paymentInvoiceId)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_invoice SET userAccountParentId=:userAccountParentId WHERE paymentInvoiceId=:paymentInvoiceId", array(
            'userAccountParentId' => $userAccountParentId,
            'paymentInvoiceId' => $paymentInvoiceId,
        ));
    }

    public static function update_paymentInvoiceUserAccountParentId_by_paymentCartId($userAccountParentId, $paymentCartId)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_invoice 
                      INNER JOIN payment_cart ON payment_invoice.paymentInvoiceId = payment_cart.paymentInvoiceId
                      SET payment_invoice.userAccountParentId = :userAccountParentId
                      WHERE payment_cart.paymentCartId = :paymentCartId", array(
            'userAccountParentId' => $userAccountParentId,
            'paymentCartId' => $paymentCartId,
        ));
    }

    public static function fetch_paymentOrderCartToStockPrice_by_paymentCartIdAndStockPriceId($paymentCartId, $stockPriceId)

    {
        $db = Db::getInstance();
        $paymentOrderCartToStockPrice = $db->first("SELECT * FROM payment_order_cart_to_stock_price
                        WHERE paymentCartId=:paymentCartId AND stockPriceId=:stockPriceId", array(
            'paymentCartId' => $paymentCartId,
            'stockPriceId' => $stockPriceId,
        ));

        return $paymentOrderCartToStockPrice;
    }

    public static function fetch_paymentOrderCartToStockColorPrice_by_paymentCartIdAndStockColorPriceId($paymentCartId, $stockColorPriceId)
    {
        $db = Db::getInstance();
        $paymentOrderCartToStockColorPrice = $db->first("SELECT * FROM payment_order_cart_to_stock_color_price
                        WHERE paymentCartId=:paymentCartId AND stockColorPriceId=:stockColorPriceId", array(
            'paymentCartId' => $paymentCartId,
            'stockColorPriceId' => $stockColorPriceId,
        ));

        return $paymentOrderCartToStockColorPrice;
    }

    public static function fetch_paymentOrderCartToStockClothColorSizePrice_by_paymentCartIdAndStockClothColorSizePriceId($paymentCartId, $stockClothColorSizePriceId)
    {
        $db = Db::getInstance();
        $paymentOrderCartToStockClothColorSizePrice = $db->first("SELECT * FROM payment_order_cart_to_stock_cloth_color_size_price
                        WHERE paymentCartId=:paymentCartId AND stockClothColorSizePriceId=:stockClothColorSizePriceId", array(
            'paymentCartId' => $paymentCartId,
            'stockClothColorSizePriceId' => $stockClothColorSizePriceId,
        ));

        return $paymentOrderCartToStockClothColorSizePrice;
    }

    public static function update_paymentOrderCartToStockPrice_Quantity_by_paymentOrderCartToStockPriceId($paymentOrderCartToStockPriceId, $quantity)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_order_cart_to_stock_price 
                    SET quantity=:quantity 
                    WHERE paymentOrderCartToStockPriceId=:paymentOrderCartToStockPriceId", array(
            'paymentOrderCartToStockPriceId' => $paymentOrderCartToStockPriceId,
            'quantity' => $quantity,
        ));
    }

    public static function update_paymentOrderCartToStockColorPrice_Quantity_by_paymentOrderCartToStockColorPriceId($paymentOrderCartToStockColorPriceId, $quantity)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_order_cart_to_stock_color_price 
                    SET quantity=:quantity 
                    WHERE paymentOrderCartToStockColorPriceId=:paymentOrderCartToStockColorPriceId", array(
            'paymentOrderCartToStockColorPriceId' => $paymentOrderCartToStockColorPriceId,
            'quantity' => $quantity,
        ));
    }

    public static function update_paymentOrderCartToStockClothColorSizePrice_Quantity_by_paymentOrderCartToStockClothColorSizePriceId($paymentOrderCartToStockClothColorSizePriceId, $quantity)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_order_cart_to_stock_cloth_color_size_price 
                    SET quantity=:quantity 
                    WHERE paymentOrderCartToStockClothColorSizePriceId=:paymentOrderCartToStockClothColorSizePriceId", array(
            'paymentOrderCartToStockClothColorSizePriceId' => $paymentOrderCartToStockClothColorSizePriceId,
            'quantity' => $quantity,
        ));
    }

    public static function update_paymentOrderCartToShopToServiceName_Quantity_by_paymentOrderCartToShopToServiceNameId($paymentOrderCartToShopToServiceNameId, $quantity)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_order_cart_to_shop_to_service_name SET quantity=:quantity WHERE paymentOrderCartToShopToServiceNameId=:paymentOrderCartToShopToServiceNameId", array(
            'paymentOrderCartToShopToServiceNameId' => $paymentOrderCartToShopToServiceNameId,
            'quantity' => $quantity,
        ));
    }

    public static function fetch_paymentCartOrders_by_paymentCartId($paymentCartId)
    {
        $db = Db::getInstance();
        $paymentCartOrders = $db->query("SELECT shop_to_service_name.discount, shop_to_service_name.price, payment_order_cart_to_shop_to_service_name.quantity FROM payment_order_cart_to_shop_to_service_name
                    INNER JOIN shop_to_service_name ON payment_order_cart_to_shop_to_service_name.shopToServiceNameId = shop_to_service_name.shopToServiceNameId
                    WHERE payment_order_cart_to_shop_to_service_name.paymentCartId = :paymentCartId
                    
                    UNION ALL
                    
                    SELECT stock_price.discount, stock_price.price, payment_order_cart_to_stock_price.quantity FROM payment_order_cart_to_stock_price
                    INNER JOIN stock_price ON payment_order_cart_to_stock_price.stockPriceId = stock_price.stockPriceId
                    WHERE payment_order_cart_to_stock_price.paymentCartId = :paymentCartId
                    
                    UNION ALL
                    
                    SELECT stock_color_price.discount, stock_color_price.price, payment_order_cart_to_stock_color_price.quantity FROM payment_order_cart_to_stock_color_price
                    INNER JOIN stock_color_price ON payment_order_cart_to_stock_color_price.stockColorPriceId = stock_color_price.stockColorPriceId
                    WHERE payment_order_cart_to_stock_color_price.paymentCartId = :paymentCartId
                    
                    UNION ALL
                    
                    SELECT stock_cloth_color_size_price.discount, stock_cloth_color_size_price.price, payment_order_cart_to_stock_cloth_color_size_price.quantity FROM payment_order_cart_to_stock_cloth_color_size_price
                    INNER JOIN stock_cloth_color_size_price ON payment_order_cart_to_stock_cloth_color_size_price.stockClothColorSizePriceId = stock_cloth_color_size_price.stockClothColorSizePriceId
                    WHERE payment_order_cart_to_stock_cloth_color_size_price.paymentCartId = :paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentCartOrders;
    }

    public static function fetch_paymentCart_shopId_by_paymentCartId($paymentCartId)
    {
        $db = Db::getInstance();
        $paymentCartShopId = $db->first("SELECT DISTINCT shopId FROM payment_order_cart_to_shop_to_service_name
                    INNER JOIN shop_to_service_name ON payment_order_cart_to_shop_to_service_name.shopToServiceNameId = shop_to_service_name.shopToServiceNameId
                    WHERE payment_order_cart_to_shop_to_service_name.paymentCartId = :paymentCartId
                    
                    UNION ALL
                    
                    SELECT DISTINCT shopId FROM payment_order_cart_to_stock_price
                    INNER JOIN stock_price ON payment_order_cart_to_stock_price.stockPriceId = stock_price.stockPriceId
                    INNER JOIN shop_to_product_model ON stock_price.shopToProductModelId = shop_to_product_model.shopToProductModelId
                    WHERE payment_order_cart_to_stock_price.paymentCartId = :paymentCartId
                    
                    UNION ALL
                    
                    SELECT DISTINCT shopId FROM payment_order_cart_to_stock_color_price
                    INNER JOIN stock_color_price ON payment_order_cart_to_stock_color_price.stockColorPriceId = stock_color_price.stockColorPriceId
                    INNER JOIN shop_to_product_model ON stock_color_price.shopToProductModelId = shop_to_product_model.shopToProductModelId
                    WHERE payment_order_cart_to_stock_color_price.paymentCartId = :paymentCartId
                    
                    UNION ALL
                    
                    SELECT DISTINCT shopId FROM payment_order_cart_to_stock_cloth_color_size_price
                    INNER JOIN stock_cloth_color_size_price ON payment_order_cart_to_stock_cloth_color_size_price.stockClothColorSizePriceId = stock_cloth_color_size_price.stockClothColorSizePriceId
                    INNER JOIN shop_to_product_model ON stock_cloth_color_size_price.shopToProductModelId = shop_to_product_model.shopToProductModelId
                    WHERE payment_order_cart_to_stock_cloth_color_size_price.paymentCartId = :paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ), 'shopId');

        return $paymentCartShopId;
    }

    public static function update_paymentInvoicePrice_by_paymentCartId($price, $paymentCartId)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_invoice 
                      INNER JOIN payment_cart ON payment_invoice.paymentInvoiceId = payment_cart.paymentInvoiceId
                      SET price = :price
                      WHERE payment_cart.paymentCartId = :paymentCartId", array(
            'paymentCartId' => $paymentCartId,
            'price' => $price,
        ));
    }

    public static function fetch_paymentOrderCartToStockPrice_FullDetails_by_paymentCartId($paymentCartId, $textStockQuantityIsZero, $textYourQuantityMoreThanStock)
    {
        $db = Db::getInstance();
        $paymentOrdersCartToStockPrice = $db->query("SELECT paymentOrderCartToStockPriceId, product_model.modelName, productDefaultLogo, shopProductSpecificLogo, stock_price.price, stock_price.discount, stock_price.quantity AS stockQuantity, payment_order_cart_to_stock_price.quantity AS orderQuantity,
                        IF(stock_price.quantity = -1, limitOrderQuantity,
                            IF(stock_price.quantity > limitOrderQuantity, limitOrderQuantity, 
                              	IF(stock_price.quantity >= payment_order_cart_to_stock_price.quantity, stock_price.quantity,
                                  	IF(stock_price.quantity = 0, '$textStockQuantityIsZero', '$textYourQuantityMoreThanStock')
                                  )
                              )
                          ) AS maxOrder,
                        IF(stock_price.price != payment_order_cart_to_stock_price.price, 'TRUE', 'FALSE') AS priceChanged,
                        IF(stock_price.discount != payment_order_cart_to_stock_price.discount, 'TRUE', 'FALSE') AS discountChanged
                          
                        FROM payment_order_cart_to_stock_price
                        INNER JOIN stock_price ON payment_order_cart_to_stock_price.stockPriceId = stock_price.stockPriceId
                        INNER JOIN shop_to_product_model ON stock_price.shopToProductModelId = shop_to_product_model.shopToProductModelId
                        INNER JOIN product_model ON shop_to_product_model.productModelId = product_model.productModelId
                        WHERE paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentOrdersCartToStockPrice;
    }

    public static function fetch_paymentOrderCartToStockColorPrice_FullDetails_by_paymentCartId($paymentCartId, $textStockQuantityIsZero, $textYourQuantityMoreThanStock)
    {
        $db = Db::getInstance();
        $paymentOrdersCartToStockColorPrice = $db->query("SELECT paymentOrderCartToStockColorPriceId, modelName, productDefaultLogo, shopProductSpecificLogo, colorName, colorCodeNumber, stock_color_price.price, stock_color_price.discount, stock_color_price.quantity AS stockQuantity, payment_order_cart_to_stock_color_price.quantity AS orderQuantity,
                        IF(stock_color_price.quantity = -1, limitOrderQuantity,
                            IF(stock_color_price.quantity > limitOrderQuantity, limitOrderQuantity, 
                              	IF(stock_color_price.quantity >= payment_order_cart_to_stock_color_price.quantity, stock_color_price.quantity,
                                  	IF(stock_color_price.quantity = 0, '$textStockQuantityIsZero', '$textYourQuantityMoreThanStock')
                                  )
                              )
                          ) AS maxOrder,
                        IF(stock_color_price.price != payment_order_cart_to_stock_color_price.price, 'TRUE', 'FALSE') AS priceChanged,
                        IF(stock_color_price.discount != payment_order_cart_to_stock_color_price.discount, 'TRUE', 'FALSE') AS discountChanged
                        
                        FROM payment_order_cart_to_stock_color_price
                        INNER JOIN stock_color_price ON payment_order_cart_to_stock_color_price.stockColorPriceId = stock_color_price.stockColorPriceId
                        INNER JOIN shop_to_product_model ON stock_color_price.shopToProductModelId = shop_to_product_model.shopToProductModelId
                        INNER JOIN product_model ON shop_to_product_model.productModelId = product_model.productModelId
                        INNER JOIN product_color ON stock_color_price.productColorId = product_color.productColorId
                        WHERE paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentOrdersCartToStockColorPrice;
    }

    public static function fetch_paymentOrderCartToShopToServiceName_FullDetails_by_paymentCartId($paymentCartId, $textStockQuantityIsZero, $textYourQuantityMoreThanStock)
    {
        $db = Db::getInstance();
        $paymentOrdersCartToShopToServiceName = $db->query("SELECT paymentOrderCartToShopToServiceNameId, service_name.serviceName, serviceDefaultLogo, shopServiceSpecificLogo, shop_to_service_name.price, shop_to_service_name.discount, shop_to_service_name.quantity AS stockQuantity, payment_order_cart_to_shop_to_service_name.quantity As orderQuantity,
                        IF(shop_to_service_name.quantity = -1, limitOrderQuantity,
                            IF(shop_to_service_name.quantity > limitOrderQuantity, limitOrderQuantity, 
                              	IF(shop_to_service_name.quantity >= payment_order_cart_to_shop_to_service_name.quantity, shop_to_service_name.quantity,
                                  	IF(shop_to_service_name.quantity = 0, '$textStockQuantityIsZero', '$textYourQuantityMoreThanStock')
                                  )
                              )
                          ) AS maxOrder,
                        IF(shop_to_service_name.price != payment_order_cart_to_shop_to_service_name.price, 'TRUE', 'FALSE') AS priceChanged,
                        IF(shop_to_service_name.discount != payment_order_cart_to_shop_to_service_name.discount, 'TRUE', 'FALSE') AS discountChanged
                          
                        FROM payment_order_cart_to_shop_to_service_name
                        INNER JOIN shop_to_service_name ON payment_order_cart_to_shop_to_service_name.shopToServiceNameId = shop_to_service_name.shopToServiceNameId
                        INNER JOIN service_name ON shop_to_service_name.serviceNameId = service_name.serviceNameId
                        WHERE paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentOrdersCartToShopToServiceName;
    }

    public static function update_paymentOrderCartToStockPrice_priceAndDiscount_by_paymentCartToStockPriceId($paymentOrderCartToStockPriceId, $price, $discount)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_order_cart_to_stock_price
                      SET price = :price, discount = :discount
                      WHERE paymentOrderCartToStockPriceId = :paymentOrderCartToStockPriceId", array(
            'paymentOrderCartToStockPriceId' => $paymentOrderCartToStockPriceId,
            'price' => $price,
            'discount' => $discount,
        ));
    }

    public static function update_paymentOrderCartToStockColorPrice_priceAndDiscount_by_paymentCartToStockColorPriceId($paymentOrderCartToStockColorPriceId, $price, $discount)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_order_cart_to_stock_color_price
                      SET price = :price, discount = :discount
                      WHERE paymentOrderCartToStockColorPriceId = :paymentOrderCartToStockColorPriceId", array(
            'paymentOrderCartToStockColorPriceId' => $paymentOrderCartToStockColorPriceId,
            'price' => $price,
            'discount' => $discount,
        ));
    }

    public static function update_paymentOrderCartToShopToServiceName_priceAndDiscount_by_paymentOrderCartToShopToServiceNameId($paymentOrderCartToShopToServiceNameId, $price, $discount)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_order_cart_to_shop_to_service_name
                      SET price = :price, discount = :discount
                      WHERE paymentOrderCartToShopToServiceNameId = :paymentOrderCartToShopToServiceNameId", array(
            'paymentOrderCartToShopToServiceNameId' => $paymentOrderCartToShopToServiceNameId,
            'price' => $price,
            'discount' => $discount,
        ));
    }

    public static function fetch_paymentOrderCartToStockPrice_CheckQuantityPriceDiscount_by_paymentCartId($paymentCartId, $textStockQuantityIsZero, $textYourQuantityMoreThanStock)
    {
        $db = Db::getInstance();
        $paymentOrdersCartToStockPrice = $db->query("SELECT paymentOrderCartToStockPriceId, payment_order_cart_to_stock_price.stockPriceId, stock_price.quantity AS stockQuantity, payment_order_cart_to_stock_price.quantity AS orderQuantity,
                        IF(stock_price.quantity = -1, limitOrderQuantity,
                            IF(stock_price.quantity > limitOrderQuantity, limitOrderQuantity, 
                              	IF(stock_price.quantity >= payment_order_cart_to_stock_price.quantity, stock_price.quantity,
                                  	IF(stock_price.quantity = 0, '$textStockQuantityIsZero', '$textYourQuantityMoreThanStock')
                                  )
                              )
                          ) AS maxOrder,
                        IF(stock_price.price != payment_order_cart_to_stock_price.price, 'TRUE', 'FALSE') AS priceChanged,
                        IF(stock_price.discount != payment_order_cart_to_stock_price.discount, 'TRUE', 'FALSE') AS discountChanged
                        
                        FROM payment_order_cart_to_stock_price
                        INNER JOIN stock_price ON payment_order_cart_to_stock_price.stockPriceId = stock_price.stockPriceId
                        WHERE paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentOrdersCartToStockPrice;
    }

    public static function fetch_paymentOrderCartToStockColorPrice_CheckQuantityPriceDiscount_by_paymentCartId($paymentCartId, $textStockQuantityIsZero, $textYourQuantityMoreThanStock)
    {
        $db = Db::getInstance();
        $paymentOrdersCartToStockColorPrice = $db->query("SELECT paymentOrderCartToStockColorPriceId, payment_order_cart_to_stock_color_price.stockColorPriceId, stock_color_price.quantity AS stockQuantity, payment_order_cart_to_stock_color_price.quantity AS orderQuantity,
                        IF(stock_color_price.quantity = -1, limitOrderQuantity,
                            IF(stock_color_price.quantity > limitOrderQuantity, limitOrderQuantity, 
                              	IF(stock_color_price.quantity >= payment_order_cart_to_stock_color_price.quantity, stock_color_price.quantity,
                                  	IF(stock_color_price.quantity = 0, '$textStockQuantityIsZero', '$textYourQuantityMoreThanStock')
                                  )
                              )
                          ) AS maxOrder,
                        IF(stock_color_price.price != payment_order_cart_to_stock_color_price.price, 'TRUE', 'FALSE') AS priceChanged,
                        IF(stock_color_price.discount != payment_order_cart_to_stock_color_price.discount, 'TRUE', 'FALSE') AS discountChanged
                        
                        FROM payment_order_cart_to_stock_color_price
                        INNER JOIN stock_color_price ON payment_order_cart_to_stock_color_price.stockColorPriceId = stock_color_price.stockColorPriceId
                        WHERE paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentOrdersCartToStockColorPrice;
    }

    public static function fetch_paymentOrderCartToShopToServiceName_CheckQuantityPriceDiscount_by_paymentCartId($paymentCartId, $textStockQuantityIsZero, $textYourQuantityMoreThanStock)
    {
        $db = Db::getInstance();
        $paymentOrdersCartToShopToServiceName = $db->query("SELECT paymentOrderCartToShopToServiceNameId, payment_order_cart_to_shop_to_service_name.shopToServiceNameId, shop_to_service_name.quantity AS stockQuantity, payment_order_cart_to_shop_to_service_name.quantity As orderQuantity,
                        IF(shop_to_service_name.quantity = -1, limitOrderQuantity,
                            IF(shop_to_service_name.quantity > limitOrderQuantity, limitOrderQuantity, 
                              	IF(shop_to_service_name.quantity >= payment_order_cart_to_shop_to_service_name.quantity, shop_to_service_name.quantity,
                                  	IF(shop_to_service_name.quantity = 0, '$textStockQuantityIsZero', '$textYourQuantityMoreThanStock')
                                  )
                              )
                          ) AS maxOrder,
                        IF(shop_to_service_name.price != payment_order_cart_to_shop_to_service_name.price, 'TRUE', 'FALSE') AS priceChanged,
                        IF(shop_to_service_name.discount != payment_order_cart_to_shop_to_service_name.discount, 'TRUE', 'FALSE') AS discountChanged
                          
                        FROM payment_order_cart_to_shop_to_service_name
                        INNER JOIN shop_to_service_name ON payment_order_cart_to_shop_to_service_name.shopToServiceNameId = shop_to_service_name.shopToServiceNameId
                        WHERE paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentOrdersCartToShopToServiceName;
    }

    public static function fetch_paymentOrderCartToStockPrice_quantity_by_paymentInvoiceId($paymentInvoiceId)
    {
        $db = Db::getInstance();
        $paymentOrdersCartToStockPrice = $db->query("SELECT payment_order_cart_to_stock_price.stockPriceId, stock_price.quantity AS stockQuantity, payment_order_cart_to_stock_price.quantity AS orderQuantity
                          FROM payment_invoice
                          INNER JOIN payment_cart ON payment_invoice.paymentInvoiceId = payment_cart.paymentInvoiceId
                          INNER JOIN payment_order_cart_to_stock_price ON payment_cart.paymentCartId = payment_order_cart_to_stock_price.paymentCartId
                          INNER JOIN stock_price ON payment_order_cart_to_stock_price.stockPriceId = stock_price.stockPriceId
                          WHERE payment_invoice.paymentInvoiceId=:paymentInvoiceId", array(
            'paymentInvoiceId' => $paymentInvoiceId,
        ));

        return $paymentOrdersCartToStockPrice;
    }

    public static function fetch_paymentOrderCartToStockColorPrice_quantity_by_paymentInvoiceId($paymentInvoiceId)
    {
        $db = Db::getInstance();
        $paymentOrdersCartToStockColorPrice = $db->query("SELECT payment_order_cart_to_stock_color_price.stockColorPriceId, stock_color_price.quantity AS stockQuantity, payment_order_cart_to_stock_color_price.quantity AS orderQuantity
                          FROM payment_invoice
                          INNER JOIN payment_cart ON payment_invoice.paymentInvoiceId = payment_cart.paymentInvoiceId
                          INNER JOIN payment_order_cart_to_stock_color_price ON payment_cart.paymentCartId = payment_order_cart_to_stock_color_price.paymentCartId
                          INNER JOIN stock_color_price ON payment_order_cart_to_stock_color_price.stockColorPriceId = stock_color_price.stockColorPriceId
                          WHERE payment_invoice.paymentInvoiceId=:paymentInvoiceId", array(
            'paymentInvoiceId' => $paymentInvoiceId,
        ));

        return $paymentOrdersCartToStockColorPrice;
    }

    public static function fetch_paymentOrderCartToShopToServiceName_quantity_by_paymentInvoiceId($paymentInvoiceId)
    {
        $db = Db::getInstance();
        $paymentOrdersCartToShopToServiceName = $db->query("SELECT payment_order_cart_to_shop_to_service_name.shopToServiceNameId, shop_to_service_name.quantity AS stockQuantity, payment_order_cart_to_shop_to_service_name.quantity As orderQuantity
                          FROM payment_invoice
                          INNER JOIN payment_cart ON payment_invoice.paymentInvoiceId = payment_cart.paymentInvoiceId
                          INNER JOIN payment_order_cart_to_shop_to_service_name ON payment_cart.paymentCartId = payment_order_cart_to_shop_to_service_name.paymentCartId
                          INNER JOIN shop_to_service_name ON payment_order_cart_to_shop_to_service_name.shopToServiceNameId = shop_to_service_name.shopToServiceNameId
                          WHERE payment_invoice.paymentInvoiceId=:paymentInvoiceId", array(
            'paymentInvoiceId' => $paymentInvoiceId,
        ));

        return $paymentOrdersCartToShopToServiceName;
    }

    public static function fetch_countPaymentCartOrderItems_by_paymentCartId($paymentCartId)
    {
        $db = Db::getInstance();
        $countPaymentCartOrderItems = $db->first("SELECT COUNT(*) AS orderCount
                              FROM
                              (
                               SELECT * FROM payment_order_cart_to_shop_to_service_name
                              	WHERE paymentCartId = :paymentCartId
                              
                              	UNION ALL
                              
                              	SELECT * FROM payment_order_cart_to_stock_price
                              	WHERE paymentCartId = :paymentCartId
                              
                              	UNION ALL
                              
                              	SELECT * FROM payment_order_cart_to_stock_color_price
                              	WHERE paymentCartId = :paymentCartId
                              
                              	UNION ALL
                              
                              	SELECT * FROM payment_order_cart_to_stock_cloth_color_size_price
                              	WHERE paymentCartId = :paymentCartId
                              ) AS orderCountTable", array(
            'paymentCartId' => $paymentCartId,
        ), 'orderCount');

        return $countPaymentCartOrderItems;
    }

    public static function delete_paymentOrderCartToStockPrice_by_paymentOrderCartToStockPriceId($paymentOrderCartToStockPriceId)
    {
        $db = Db::getInstance();
        $db->modify("DELETE FROM payment_order_cart_to_stock_price WHERE paymentOrderCartToStockPriceId=:paymentOrderCartToStockPriceId", array(
            'paymentOrderCartToStockPriceId' => $paymentOrderCartToStockPriceId,
        ));
    }

    public static function delete_paymentOrderCartToStockColorPrice_by_paymentOrderCartToStockColorPriceId($paymentOrderCartToStockColorPriceId)
    {
        $db = Db::getInstance();
        $db->modify("DELETE FROM payment_order_cart_to_stock_color_price WHERE paymentOrderCartToStockColorPriceId=:paymentOrderCartToStockColorPriceId", array(
            'paymentOrderCartToStockColorPriceId' => $paymentOrderCartToStockColorPriceId,
        ));
    }

    public static function delete_paymentOrderCartToStockClothColorSizePrice_by_paymentOrderCartToStockClothColorSizePriceId($paymentOrderCartToStockClothColorSizePriceId)
    {
        $db = Db::getInstance();
        $db->modify("DELETE FROM payment_order_cart_to_stock_cloth_color_size_price 
                      WHERE paymentOrderCartToStockClothColorSizePriceId=:paymentOrderCartToStockClothColorSizePriceId", array(
            'paymentOrderCartToStockClothColorSizePriceId' => $paymentOrderCartToStockClothColorSizePriceId,
        ));
    }

    public static function delete_paymentOrderCartToShopToServiceName_by_paymentOrderCartToShopToServiceNameId($paymentOrderCartToShopToServiceNameId)
    {
        $db = Db::getInstance();
        $db->modify("DELETE FROM payment_order_cart_to_shop_to_service_name WHERE paymentOrderCartToShopToServiceNameId=:paymentOrderCartToShopToServiceNameId", array(
            'paymentOrderCartToShopToServiceNameId' => $paymentOrderCartToShopToServiceNameId,
        ));
    }

    public static function fetch_stockPrice_QuantityAndMaxOrder_by_stockPriceId($stockPriceId)
    {
        $db = Db::getInstance();
        $stockPriceQuantityAndMaxOrder = $db->first("SELECT quantity, price, discount,
                        IF(quantity = -1, limitOrderQuantity,
                            IF(quantity < limitOrderQuantity, quantity, limitOrderQuantity)
                          ) AS maxOrder
                        FROM stock_price
                        WHERE stockPriceId=:stockPriceId", array(
            'stockPriceId' => $stockPriceId,
        ));

        return $stockPriceQuantityAndMaxOrder;
    }

    public static function fetch_stockColorPrice_QuantityAndMaxOrder_by_stockColorPriceId($stockColorPriceId)
    {
        $db = Db::getInstance();
        $stockColorPriceQuantityAndMaxOrder = $db->first("SELECT quantity, price, discount,
                        IF(quantity = -1, limitOrderQuantity,
                            IF(quantity < limitOrderQuantity, quantity, limitOrderQuantity)
                          ) AS maxOrder
                        FROM stock_color_price
                        WHERE stockColorPriceId=:stockColorPriceId", array(
            'stockColorPriceId' => $stockColorPriceId,
        ));

        return $stockColorPriceQuantityAndMaxOrder;
    }

    public static function fetch_stockClothColorSizePrice_QuantityAndMaxOrder_by_stockClothColorSizePriceId($stockClothColorSizePriceId)
    {
        $db = Db::getInstance();
        $stockClothColorSizePriceQuantityAndMaxOrder = $db->first("SELECT quantity, price, discount,
                        IF(quantity = -1, limitOrderQuantity,
                            IF(quantity < limitOrderQuantity, quantity, limitOrderQuantity)
                          ) AS maxOrder
                        FROM stock_cloth_color_size_price
                        WHERE stockClothColorSizePriceId=:stockClothColorSizePriceId", array(
            'stockClothColorSizePriceId' => $stockClothColorSizePriceId,
        ));

        return $stockClothColorSizePriceQuantityAndMaxOrder;
    }

    public static function fetch_shopToServiceName_QuantityAndMaxOrder_by_shopToServiceNameId($shopToServiceNameId)
    {
        $db = Db::getInstance();
        $shopToServiceNameQuantityAndMaxOrder = $db->first("SELECT quantity, price, discount,
                        IF(quantity = -1, limitOrderQuantity,
                            IF(quantity < limitOrderQuantity, quantity, limitOrderQuantity)
                          ) AS maxOrder
                        FROM shop_to_service_name
                        WHERE shopToServiceNameId=:shopToServiceNameId", array(
            'shopToServiceNameId' => $shopToServiceNameId,
        ));

        return $shopToServiceNameQuantityAndMaxOrder;
    }

    public static function update_stockPriceQuantity_by_stockPriceIdAndOrderQuantityAndStockQuantity($paymentOrdersCartToStockPrice, $isIncreaseQuantity)
    {
        $strCase1 = null;
        $strWhere = null;
        $data = array();

        $countMustChange = 0;

        for ($i = 0; $i < count($paymentOrdersCartToStockPrice); $i++) {
            if ($paymentOrdersCartToStockPrice[$i]["stockQuantity"] != -1) {
                $countMustChange++;

                $strCase1 = $strCase1 . "  when stockPriceId=:stockPriceId_" . $i . " then :quantity_" . $i;
                $strWhere = $strWhere . ":stockPriceId_" . $i . ", ";

                $data["stockPriceId_" . $i] = $paymentOrdersCartToStockPrice[$i]["stockPriceId"];

                if ($isIncreaseQuantity == true) {
                    $data["quantity_" . $i] = $paymentOrdersCartToStockPrice[$i]["stockQuantity"] + $paymentOrdersCartToStockPrice[$i]["orderQuantity"];
                } else {
                    $data["quantity_" . $i] = $paymentOrdersCartToStockPrice[$i]["stockQuantity"] - $paymentOrdersCartToStockPrice[$i]["orderQuantity"];
                }
            }
        }

        if ($countMustChange > 0) {
            // The last item has an extra ', '
            $strWhere = substr($strWhere, 0, -2);

            $db = Db::getInstance();
            $db->modify("UPDATE stock_price
                            SET quantity = CASE $strCase1
                                            END
                            WHERE stockPriceId in ($strWhere)", $data);
        }
    }

    public static function update_stockColorPriceQuantity_by_stockColorPriceIdAndOrderQuantityAndStockQuantity($paymentOrdersCartToStockColorPrice, $isIncreaseQuantity)
    {
        $strCase1 = null;
        $strWhere = null;
        $data = array();

        $countMustChange = 0;

        for ($i = 0; $i < count($paymentOrdersCartToStockColorPrice); $i++) {
            if ($paymentOrdersCartToStockColorPrice[$i]["stockQuantity"] != -1) {
                $countMustChange++;

                $strCase1 = $strCase1 . "  when stockColorPriceId=:stockColorPriceId_" . $i . " then :quantity_" . $i;
                $strWhere = $strWhere . ":stockColorPriceId_" . $i . ", ";

                $data["stockColorPriceId_" . $i] = $paymentOrdersCartToStockColorPrice[$i]["stockColorPriceId"];

                if ($isIncreaseQuantity == true) {
                    $data["quantity_" . $i] = $paymentOrdersCartToStockColorPrice[$i]["stockQuantity"] + $paymentOrdersCartToStockColorPrice[$i]["orderQuantity"];
                } else {
                    $data["quantity_" . $i] = $paymentOrdersCartToStockColorPrice[$i]["stockQuantity"] - $paymentOrdersCartToStockColorPrice[$i]["orderQuantity"];
                }
            }
        }

        if ($countMustChange > 0) {
            // The last item has an extra ', '
            $strWhere = substr($strWhere, 0, -2);

            $db = Db::getInstance();
            $db->modify("UPDATE stock_color_price
                            SET quantity = CASE $strCase1
                                            END
                            WHERE stockColorPriceId in ($strWhere)", $data);
        }
    }

    public static function update_shopToServiceNameQuantity_by_shopToServiceNameIdAndOrderQuantityAndStockQuantity($paymentOrdersCartToShopToServiceName, $isIncreaseQuantity)
    {
        $strCase1 = null;
        $strWhere = null;
        $data = array();

        $countMustChange = 0;

        for ($i = 0; $i < count($paymentOrdersCartToShopToServiceName); $i++) {
            if ($paymentOrdersCartToShopToServiceName[$i]["stockQuantity"] != -1) {
                $countMustChange++;

                $strCase1 = $strCase1 . "  when shopToServiceNameId=:shopToServiceNameId_" . $i . " then :quantity_" . $i;
                $strWhere = $strWhere . ":shopToServiceNameId_" . $i . ", ";

                $data["shopToServiceNameId_" . $i] = $paymentOrdersCartToShopToServiceName[$i]["shopToServiceNameId"];

                if ($isIncreaseQuantity == true) {
                    $data["quantity_" . $i] = $paymentOrdersCartToShopToServiceName[$i]["stockQuantity"] + $paymentOrdersCartToShopToServiceName[$i]["orderQuantity"];
                } else {
                    $data["quantity_" . $i] = $paymentOrdersCartToShopToServiceName[$i]["stockQuantity"] - $paymentOrdersCartToShopToServiceName[$i]["orderQuantity"];
                }
            }
        }

        if ($countMustChange > 0) {
            // The last item has an extra ', '
            $strWhere = substr($strWhere, 0, -2);

            $db = Db::getInstance();
            $db->modify("UPDATE shop_to_service_name
                            SET quantity = CASE $strCase1
                                            END
                            WHERE shopToServiceNameId in ($strWhere)", $data);
        }
    }

    public static function fetch_paymentTransaction_by_transactionHash($transactionHash)
    {
        $db = Db::getInstance();
        $transactionData = $db->first("SELECT * FROM payment_transaction WHERE transactionHash=:transactionHash", array(
            'transactionHash' => $transactionHash,
        ));

        return $transactionData;
    }

    public static function fetch_paymentTransaction_withInvoice_by_transactionHash($transactionHash)
    {
        $db = Db::getInstance();
        $transactionWithInvoice = $db->first("SELECT * FROM payment_transaction 
                        INNER JOIN payment_invoice ON payment_transaction.paymentInvoiceId = payment_invoice.paymentInvoiceId
                        WHERE transactionHash=:transactionHash", array(
            'transactionHash' => $transactionHash,
        ));

        return $transactionWithInvoice;
    }

    public static function fetch_paymentInvoiceWithCustomerAndInvoiceType_by_paymentCartId($paymentCartId)
    {
        $db = Db::getInstance();
        $paymentInvoiceWithCustomerAndInvoiceType = $db->first("SELECT payment_invoice.paymentInvoiceId, payment_invoice.userAccountParentId, price, discount, typeName, Mobile_Cmr, customerEmail  FROM payment_cart
                        INNER JOIN payment_invoice ON payment_cart.paymentInvoiceId = payment_invoice.paymentInvoiceId
                        INNER JOIN payment_invoice_type ON payment_invoice.paymentInvoiceTypeId = payment_invoice_type.paymentInvoiceTypeId
                        INNER JOIN user_account_customer ON payment_invoice.userAccountParentId = user_account_customer.userAccountParentId
                        
                        WHERE paymentCartId=:paymentCartId", array(
            'paymentCartId' => $paymentCartId,
        ));

        return $paymentInvoiceWithCustomerAndInvoiceType;
    }

    public static function insert_paymentTransaction($paymentInvoiceId, $userAccountParentIdPaidTransaction, $transactionHash, $creationTime)
    {
        $db = Db::getInstance();
        $paymentTransactionId = $db->insert("INSERT INTO payment_transaction (paymentInvoiceId, userAccountParentIdPaidTransaction, transactionHash, creationTime) 
                          VALUES (:paymentInvoiceId, :userAccountParentIdPaidTransaction, :transactionHash, :creationTime)", array(
            'paymentInvoiceId' => $paymentInvoiceId,
            'userAccountParentIdPaidTransaction' => $userAccountParentIdPaidTransaction,
            'transactionHash' => $transactionHash,
            'creationTime' => $creationTime,
        ));

        return $paymentTransactionId;
    }

    public static function update_paymentTransaction_authority_by_transactionHash($transactionHash, $authority)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_transaction
                      SET authority=:authority
                      WHERE transactionHash=:transactionHash", array(
            'transactionHash' => $transactionHash,
            'authority' => $authority,
        ));
    }

    public static function update_paymentTransaction_reference_by_transactionHash($transactionHash, $reference, $paymentTime, $paid)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_transaction
                      SET reference=:reference, paymentTime=:paymentTime, paid=:paid
                      WHERE transactionHash=:transactionHash", array(
            'transactionHash' => $transactionHash,
            'reference' => $reference,
            'paymentTime' => $paymentTime,
            'paid' => $paid,
        ));
    }

    public static function update_paymentInvoicePaid_by_paymentInvoiceId($paymentInvoiceId, $paid)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE payment_invoice
                      SET paid=:paid
                      WHERE paymentInvoiceId=:paymentInvoiceId", array(
            'paymentInvoiceId' => $paymentInvoiceId,
            'paid' => $paid,
        ));
    }

}