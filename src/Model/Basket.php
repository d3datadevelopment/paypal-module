<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidSolutionCatalysts\PayPal\Model;

use OxidEsales\Eshop\Core\Price;
use OxidEsales\Eshop\Core\Registry;

/**
 * PayPal basket class
 *
 * @mixin \OxidEsales\Eshop\Application\Model\Basket
 */
class Basket extends Basket_parent
{
    /**
     * Checks if products in basket ar virtual and does not require real delivery.
     * Returns TRUE if virtual
     *
     * @return bool
     */
    public function isVirtualPayPalBasket()
    {
        $isVirtual = true;

        $products = $this->getBasketArticles();
        foreach ($products as $product) {
            if (!$product->isVirtualPayPalArticle()) {
                $isVirtual = false;
                break;
            }
        }

        return $isVirtual;
    }

    /**
     * Checks if fraction quantity items (with 1.3 amount) exists in basket.
     *
     * @return bool
     */
    public function isFractionQuantityItemsPresent()
    {
        $fractionItemsPresent = false;

        foreach ($this->getContents() as $basketItem) {
            $amount = $basketItem->getAmount();
            if ((int) $amount != $amount) {
                $fractionItemsPresent = true;
                break;
            }
        }

        return $fractionItemsPresent;
    }

    /**
     * Returns wrapping cost value
     *
     * @return double
     */
    public function getPayPalCheckoutWrappingCosts()
    {
        $amount = 0.0;

        $wrappingCost = $this->getCosts('oxwrapping');
        if ($wrappingCost) {
            $amount = $this->isCalculationModeNetto() ?
                $wrappingCost->getNettoPrice() :
                $wrappingCost->getBruttoPrice();
        }

        return $amount;
    }

    /**
     * Returns wrapping Vat
     *
     * @return double
     */
    public function getPayPalCheckoutWrappingVat()
    {
        $amount = 0.0;

        $wrappingCost = $this->getCosts('oxwrapping');
        if ($wrappingCost) {
            $amount = $wrappingCost->getVat();
        }

        return $amount;
    }

    /**
     * Returns greeting card cost value
     *
     * @return double
     */
    public function getPayPalCheckoutGiftCardCosts()
    {
        $amount = 0.0;

        $giftCardCost = $this->getCosts('oxgiftcard');
        if ($giftCardCost) {
            $amount = $this->isCalculationModeNetto() ?
                $giftCardCost->getNettoPrice() : $giftCardCost->getBruttoPrice();
        }

        return $amount;
    }

    /**
     * Returns greeting card vat value
     *
     * @return double
     */
    public function getPayPalCheckoutGiftCardVat()
    {
        $amount = 0.0;

        $giftCardCost = $this->getCosts('oxgiftcard');
        if ($giftCardCost) {
            $amount = $giftCardCost->getVat();
        }

        return $amount;
    }

    /**
     * Returns payment costs netto or brutto value.
     *
     * @return double
     */
    public function getPayPalCheckoutPaymentCosts()
    {
        $amount = 0.0;

        $paymentCost = $this->getCosts('oxpayment');
        if ($paymentCost) {
            $amount = $this->isCalculationModeNetto() ?
                $paymentCost->getNettoPrice() : $paymentCost->getBruttoPrice();
        }

        return $amount;
    }

    /**
     * Returns payment costs netto or brutto value.
     *
     * @return double
     */
    public function getPayPalCheckoutPaymentVat()
    {
        $amount = 0.0;

        $paymentCost = $this->getCosts('oxpayment');
        if ($paymentCost) {
            $amount = $paymentCost->getVat();
        }

        return $amount;
    }

    /**
     * Collects all basket discounts (basket, payment and vouchers)
     * and returns sum of collected discounts.
     *
     * @return double
     */
    public function getDiscountSumPayPalBasket()
    {
        // collect discounts
        $discount = 0.0;

        $totalDiscount = $this->getTotalDiscount();

        if ($totalDiscount) {
            $discount += $totalDiscount->getBruttoPrice();
        }

        //if payment costs are negative, adding them to discount
        if (($costs = $this->getPaymentCosts()) < 0) {
            $discount += ($costs * -1);
        }

        // vouchers..
        $vouchers = (array) $this->getVouchers();
        foreach ($vouchers as $voucher) {
            $discount += round($voucher->dVoucherdiscount, 2);
        }

        return $discount;
    }

    /**
     * Calculates basket costs (payment, GiftCard and gift card)
     * and returns sum of all costs.
     *
     * @return double
     */
    public function getSumOfCostOfAllItemsPayPalBasket()
    {
        // basket items sum
        $allCosts = $this->getProductsPrice()->getSum($this->isCalculationModeNetto());

        //adding to additional costs only if payment is > 0
        if (($costs = $this->getPayPalCheckoutPaymentCosts()) > 0) {
            $allCosts += $costs;
        }

        // wrapping costs
        $allCosts += $this->getPayPalCheckoutWrappingCosts();

        // greeting card costs
        $allCosts += $this->getPayPalCheckoutGiftCardCosts();

        return $allCosts;
    }

    /**
     * collect the netto-sum of all articles in Basket
     * and returns sum of all costs.
     *
     * Normally we could use the method: $this->getProductsPrice()->getSum(true)
     * to calculate the total net amount. However, since Paypal calculates the
     * sum of the items on the basis of the rounded net prices, rounding errors
     * can occur in the total. Therefore we calculate the sum over the following
     * iteration.
     *
     * @return double
     */
    public function getPayPalProductNetto() {
        $result = 0;
        foreach ($this->getContents() as $basketItem) {
            $itemUnitPrice = $basketItem->getUnitPrice();
            $result += $itemUnitPrice->getNettoPrice() * $basketItem->getAmount();
        }
        return $result;
    }

    /**
     * Returns absolute VAT value.
     *
     * @return float
     */
    public function getPayPalBasketVatValue()
    {
        $basketVatValue = 0;
        $basketVatValue += $this->getPayPalProductVatValue();
        $basketVatValue += $this->getPayPalCheckoutWrappingVatValue();
        $basketVatValue += $this->getPayPalCheckoutGiftCardVatValue();
        $basketVatValue += $this->getPayPalCheckoutPaymentVatValue();

        if ($this->getDeliveryCosts() < round($this->getDeliveryCosts(), 2)) {
            return floor($basketVatValue * 100) / 100;
        }

        return $basketVatValue;
    }

    /**
     * Return products VAT.
     *
     * Normally we could use the method: $this->getProductVats(false)
     * to calculate the total net amount. However, since Paypal calculates the
     * sum of the items on the basis of the rounded net prices, rounding errors
     * can occur in the total. Therefore we calculate the sum over the following
     * iteration.
     *
     * @return double
     */
    public function getPayPalProductVatValue()
    {
        $result = 0;
        foreach ($this->getContents() as $basketItem) {
            $itemUnitPrice = $basketItem->getUnitPrice();
            $result += $itemUnitPrice->getVatValue() * $basketItem->getAmount();
        }
        return $result;
    }

    /**
     * Return wrapping VAT.
     *
     * @return double
     */
    public function getPayPalCheckoutWrappingVatValue()
    {
        $wrappingVat = 0.0;

        $wrapping = $this->getCosts('oxwrapping');
        if ($wrapping && $wrapping->getVatValue()) {
            $wrappingVat = $wrapping->getVatValue();
        }

        return $wrappingVat;
    }

    /**
     * Return gift card VAT.
     *
     * @return double
     */
    public function getPayPalCheckoutGiftCardVatValue()
    {
        $giftCardVat = 0.0;

        $giftCard = $this->getCosts('oxgiftcard');
        if ($giftCard && $giftCard->getVatValue()) {
            $giftCardVat = $giftCard->getVatValue();
        }

        return $giftCardVat;
    }

    /**
     * Return payment VAT.
     *
     * @return double
     */
    public function getPayPalCheckoutPaymentVatValue()
    {
        $paymentVAT = 0.0;

        $paymentCost = $this->getCosts('oxpayment');
        if ($paymentCost && $paymentCost->getVatValue()) {
            $paymentVAT = $paymentCost->getVatValue();
        }

        return $paymentVAT;
    }

    /**
     * Check if variants of the given product are already in the basket
     * @param \OxidEsales\Eshop\Application\Model\Article $product
     * @return bool
     */
    public function hasProductVariantInBasket(\OxidEsales\Eshop\Application\Model\Article $product)
    {
        $return = false;

        $variantIds = $product->getVariantIds();
        foreach ($variantIds as $id) {
            if ($this->getArtStockInBasket($id)) {
                $return = true;
                break;
            }
        }

        return $return;
    }
}
