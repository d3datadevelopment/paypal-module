<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidSolutionCatalysts\PayPal\Core;

use OxidEsales\Eshop\Core\Registry;
use OxidSolutionCatalysts\PayPal\Traits\ServiceContainer;
use OxidSolutionCatalysts\PayPal\Service\ModuleSettings;

/**
 * @mixin \OxidEsales\Eshop\Core\ViewConfig
 */
class ViewConfig extends ViewConfig_parent
{
    use ServiceContainer;

    /**
     * @return bool
     */
    public function isPayPalActive(): bool
    {
        return $this->getPayPalConfig()->isActive();
    }

    /**
     * @return bool
     */
    public function isPayPalSessionActive(): bool
    {
        return PayPalSession::isPayPalOrderActive();
    }

    /**
     * @return Config
     */
    public function getPayPalConfig(): Config
    {
        return oxNew(Config::class);
    }

    /**
     * @return Bool
     */
    public function showOverlay(): bool
    {
        return PayPalSession::isSubscriptionProcessing();
    }


    /**
     * @return array
     */
    public function getPayPalCurrencyCodes(): array
    {
        return Currency::getCurrencyCodes();
    }

    /**
     * @return null or string
     */
    public function getcheckoutOrderId(): ?string
    {
        return PayPalSession::getcheckoutOrderId();
    }

    /**
     * get CancelPayPalPayment-Url
     *
     * @return string
     */
    public function getCancelPayPalPaymentUrl(): string
    {
        return $this->getSelfLink() . 'cl=PayPalProxyController&fnc=cancelPayPalPayment';
    }

    /**
     * Gets PayPal JS SDK url
     *
     * @param bool $subscribe is it a PayPal Subscription
     *
     * @return string
     */
    public function getPayPalJsSdkUrl($subscribe = false): string
    {
        $payPalConfig = $this->getPayPalConfig();
        $config = Registry::getConfig();

        $params = [];

        $params['client-id'] = $payPalConfig->getClientId();

        if ($subscribe) {
            $params['vault'] = 'true';
            $params['intent'] = 'subscription';
            $params['locale'] = 'de_DE';
        } else {
            $params['integration-date'] = Constants::PAYPAL_INTEGRATION_DATE;
            $params['intent'] = strtolower(Constants::PAYPAL_ORDER_INTENT_CAPTURE);
            $params['commit'] = 'false';
        }

        if ($currency = $config->getActShopCurrencyObject()) {
            $params['currency'] = strtoupper($currency->name);
        }

        // Available components: enable messages+buttons for PDP
        if ($this->getActiveClassName('details')) {
            $params['components'] = 'messages,buttons';
        }

        return Constants::PAYPAL_JS_SDK_URL . '?' . http_build_query($params);
    }

    /**
     * PSPAYPAL-491 -->
     * Returns whether PayPal banners should be shown on the start page
     */
    public function enablePayPalBanners(): bool
    {
        //TODO: refactor all similar settings fetching places
        return $this->getServiceFromContainer(ModuleSettings::class)->showAllPayPalBanners();
    }

    /**
     * Client ID getter for use with the installment banner feature.
     */
    public function getPayPalClientId(): string
    {
        return $this->getPayPalConfig()->getClientId();
    }

    /**
     * API URL getter for use with the installment banner feature
     */
    public function getPayPalApiBannerUrl(): string
    {
        $params['client-id'] = $this->getPayPalClientId();

        $components = 'messages';
        // enable buttons for PDP
        if ($this->getActiveClassName('details')) {
            $components .= ',buttons';
        }

        $params['components'] = $components;

        return Constants::PAYPAL_JS_SDK_URL . '?' . http_build_query($params);
    }

    /**
     * Returns whether PayPal banners should be shown on the start page
     */
    public function showPayPalBannerOnStartPage(): bool
    {
        $config = Registry::getConfig();
        $settings = $this->getServiceFromContainer(ModuleSettings::class);

        return (
            $settings->showAllPayPalBanners() &&
            $settings->showBannersOnStartPage() &&
            $settings->getStartPageBannerSelector() &&
            $config->getConfigParam('bl_perfLoadPrice')
        );
    }

    /**
     * Returns PayPal banners selector for the start page
     */
    public function getPayPalBannerStartPageSelector(): string
    {
        return $this->getServiceFromContainer(ModuleSettings::class)->getStartPageBannerSelector();
    }

    /**
     * Returns whether PayPal banners should be shown on the category page
     */
    public function showPayPalBannerOnCategoryPage(): bool
    {
        $config = Registry::getConfig();
        $settings = $this->getServiceFromContainer(ModuleSettings::class);

        return (
            $settings->showAllPayPalBanners() &&
            $settings->showBannersOnCategoryPage() &&
            $settings->getCategoryPageBannerSelector() &&
            $config->getConfigParam('bl_perfLoadPrice')
        );
    }

    /**
     * Returns PayPal banners selector for the category page
     */
    public function getPayPalBannerCategoryPageSelector(): string
    {
        return $this->getServiceFromContainer(ModuleSettings::class)->getCategoryPageBannerSelector();
    }

    /**
     * Returns whether PayPal banners should be shown on the search results page
     */
    public function showPayPalBannerOnSearchResultsPage(): bool
    {
        $config = Registry::getConfig();
        $settings = $this->getServiceFromContainer(ModuleSettings::class);

        return (
            $settings->showAllPayPalBanners() &&
            $settings->showBannersOnSearchPage() &&
            $settings->getSearchPageBannerSelector() &&
            $config->getConfigParam('bl_perfLoadPrice')
        );
    }

    /**
     * Returns PayPal banners selector for the search page
     */
    public function getPayPalBannerSearchPageSelector(): string
    {
        return $this->getServiceFromContainer(ModuleSettings::class)->getSearchPageBannerSelector();
    }

    /**
     * Returns whether PayPal banners should be shown on the product details page
     */
    public function showPayPalBannerOnProductDetailsPage(): bool
    {
        $config = Registry::getConfig();
        $settings = $this->getServiceFromContainer(ModuleSettings::class);

        return (
            $settings->showAllPayPalBanners() &&
            $settings->showBannersOnProductDetailsPage() &&
            $settings->getProductDetailsPageBannerSelector() &&
            $config->getConfigParam('bl_perfLoadPrice')
        );
    }

    /**
     * Returns PayPal banners selector for the product detail page
     */
    public function getPayPalBannerProductDetailsPageSelector(): string
    {
        return $this->getServiceFromContainer(ModuleSettings::class)->getProductDetailsPageBannerSelector();
    }

    /**
     * Returns whether PayPal banners should be shown on the checkout page
     */
    public function showPayPalBannerOnCheckoutPage(): bool
    {
        //TODO
        $showBanner = false;
        $actionClassName = $this->getActionClassName();
        $config = Registry::getConfig();
        $settings = $this->getServiceFromContainer(ModuleSettings::class);

        if ($actionClassName === 'basket') {
            $showBanner = (
                $settings->showAllPayPalBanners() &&
                $settings->showBannersOnCheckoutPage() &&
                $settings->getPayPalBannerCartPageSelector() &&
                $config->getConfigParam('bl_perfLoadPrice')
            );
        } elseif ($actionClassName === 'payment') {
            $showBanner = (
                $settings->showAllPayPalBanners() &&
                $settings->showBannersOnCheckoutPage() &&
                $settings->getPayPalBannerPaymentPageSelector() &&
                $config->getConfigParam('bl_perfLoadPrice')
            );
        }

        return $showBanner;
    }

    /**
     * Returns PayPal banners selector for the cart page
     */
    public function getPayPalBannerCartPageSelector(): string
    {
        return $this->getServiceFromContainer(ModuleSettings::class)->getPayPalBannerCartPageSelector();
    }

    /**
     * Returns PayPal banners selector for the payment page
     */
    public function getPayPalBannerPaymentPageSelector(): string
    {
        return $this->getServiceFromContainer(ModuleSettings::class)->getPayPalBannerPaymentPageSelector();
    }

    /**
     * Returns the PayPal banners colour scheme
     */
    public function getPayPalBannersColorScheme(): string
    {
        return Registry::getConfig()->getConfigParam('oePayPalBannersColorScheme');
    }

    // <-- PSPAYPAL-491
}