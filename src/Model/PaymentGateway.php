<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidSolutionCatalysts\PayPal\Model;

use Exception;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsObject;
use OxidSolutionCatalysts\PayPalApi\Model\Orders\OrderCaptureRequest;
use OxidSolutionCatalysts\PayPalApi\Model\Orders\OrderRequest;
use OxidSolutionCatalysts\PayPalApi\Model\Orders\Patch;
use OxidSolutionCatalysts\PayPal\Core\PatchRequestFactory;
use OxidSolutionCatalysts\PayPal\Core\PayPalSession;
use OxidSolutionCatalysts\PayPal\Core\ServiceFactory;

/**
 * Class PaymentGateway
 * @package OxidSolutionCatalysts\PayPal\Model
 *
 * @mixin \OxidEsales\Eshop\Application\Model\PaymentGateway
 */
class PaymentGateway extends PaymentGateway_parent
{
     /**
     * Executes payment, returns true on success.
     *
     * @param double                               $amount Goods amount
     * @param \OxidEsales\PayPalModule\Model\Order $order  User ordering object
     *
     * @return bool
     */
    public function executePayment($amount, &$order)
    {
        $success = parent::executePayment($amount, $order);
        $session = $this->getSession();

        if ($session->getVariable('isSubscriptionCheckout')) {
            $this->getSession()->deleteVariable('isSubscriptionCheckout');
            return true;
        }

        if ($this->getSessionPaymentId() === 'oxidpaypal') {
            $success = $this->doAuthorizePayPalPayment($order);
        }
/*
        if (
            ($session->getVariable('paymentid') == 'oxidpaypal')
             || ($session->getBasket()->getPaymentId() == 'oxidpaypal')
        ) {
            $success = $this->doAuthorizePayment($order);
        } */

        return $success;
    }

     /**
     * Return the PaymentId from Session
     */
    public function getSessionPaymentId()
    {
        $session = $this->getSession();
        return $session->getVariable('paymentid') ?? $session->getBasket()->getPaymentId();
    }

    /**
     * Executes Authorize to PayPal
     *
     * @param Order $order  User ordering object
     *
     * @return bool
     */
    public function doAuthorizePayment(&$order)
    {
        $success = false;

        try {
            // updating order state
            if ($order) {
                if ($checkoutOrderId = PayPalSession::getcheckoutOrderId()) {

                    /** @var ServiceFactory $serviceFactory */
                    $serviceFactory = Registry::get(ServiceFactory::class);
                    $service = $serviceFactory->getOrderService();

                    // Capture Order
                    try {
                        $request = new OrderCaptureRequest();
                        $response = $service->capturePaymentForOrder('', $checkoutOrderId, $request, '');
                    } catch (Exception $exception) {
                        Registry::getLogger()->error("Error on order capture call.", [$exception]);
                        $exception = oxNew(StandardException::class, 'OSC_PAYPAL_ORDEREXECUTION_ERROR');
                        throw $exception;
                    }

                    $sql = 'INSERT INTO osc_paypal_order (';
                    $sql .= 'OXID, OXSHOPID, OXORDERID, ';
                    $sql .= 'OXPAYPALORDERID) VALUES(?,?,?,?)';

                    DatabaseProvider::getDb()->execute($sql, [
                        UtilsObject::getInstance()->generateUId(),
                        Registry::getConfig()->getShopId(),
                        $order->getId(),
                        $checkoutOrderId
                    ]);

                    $success = true;
                }
            } else {
                $exception = oxNew(StandardException::class, 'OSC_PAYPAL_ORDEREXECUTION_ERROR');
                throw $exception;
            }
        } catch (Exception $exception) {
            $this->_iLastErrorNo = \OxidEsales\Eshop\Application\Model\Order::ORDER_STATE_PAYMENTERROR;
            Registry::getLogger()->error("Error on doAuthorizePayment call.", [$exception]);

            Registry::getUtilsView()->addErrorToDisplay($exception);
        }

        // destroy PayPal-Session
        PayPalSession::storePayPalOrderId('');

        return $success;
    }

    /**
     * Executes Authorize to PayPal
     *
     * @param Order $order  User ordering object
     *
     * @return bool
     */
    public function doAuthorizePayPalPayment(&$order)
    {
        $success = false;

        try {
            // updating order state
            if ($order) {
                if ($checkoutOrderId = PayPalSession::getcheckoutOrderId()) {

                    /** @var ServiceFactory $serviceFactory */
                    $serviceFactory = Registry::get(ServiceFactory::class);
                    $service = $serviceFactory->getOrderService();

                    /** @var PatchRequestFactory $requestFactory */
                    $requestFactory = Registry::get(PatchRequestFactory::class);
                    $request = $requestFactory->getRequest(
                        Registry::getSession()->getBasket()
                    );

                    // Update Order
                    try {
                        $response = $service->updateOrder($checkoutOrderId, $request);
                    } catch (Exception $exception) {
                        Registry::getLogger()->error("Error on order patch call.", [$exception]);
                    }

                    // Capture Order
                    try {
                        $request = new OrderCaptureRequest();
                        $service->capturePaymentForOrder('', $checkoutOrderId, $request, '');
                    } catch (Exception $exception) {
                        Registry::getLogger()->error("Error on order capture call.", [$exception]);
                        throw oxNew(StandardException::class, 'OXPS_PAYPAL_ORDEREXECUTION_ERROR');
                    }

                    $sql = 'INSERT INTO osc_paypal_order (';
                    $sql .= 'OXID, OXSHOPID, OXORDERID, ';
                    $sql .= 'OXPAYPALORDERID) VALUES(?,?,?,?)';

                    DatabaseProvider::getDb()->execute($sql, [
                        UtilsObject::getInstance()->generateUId(),
                        Registry::getConfig()->getShopId(),
                        $order->getId(),
                        $checkoutOrderId
                    ]);

                    $success = true;
                }
            } else {
                $exception = oxNew(StandardException::class, 'OXPS_PAYPAL_ORDEREXECUTION_ERROR');
                throw $exception;
            }
        } catch (Exception $exception) {
            Registry::getLogger()->error("Error on doAuthorizePayPalPayment call.", [$exception]);

            Registry::getUtilsView()->addErrorToDisplay($exception);
        }

        // destroy PayPal-Session
        PayPalSession::storePayPalOrderId('');

        return $success;
    }
}
