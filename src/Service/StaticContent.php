<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidSolutionCatalysts\PayPal\Service;

use OxidEsales\Eshop\Core\Registry as EshopRegistry;
use OxidSolutionCatalysts\PayPal\Core\PayPalDefinitions;
use OxidEsales\Eshop\Application\Model\Content as EshopModelContent;
use OxidEsales\Eshop\Application\Model\Payment as EshopModelPayment;
use OxidEsales\Eshop\Core\Model\BaseModel as EshopBaseModel;
use OxidEsales\Eshop\Core\Config as EshopCoreConfig;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Database\Adapter\Doctrine\Database;

//NOTE: later we will do this on module installation, for now on first activation
class StaticContent
{
    /** @var DatabaseProvider */
    private $db;

    /** @var EshopCoreConfig */
    private $config;

    public function __construct(
        EshopCoreConfig $config,
        Database $db
    ) {
        $this->config = $config;
        $this->db = $db;
    }

    public function ensurePayPalPaymentMethods(): void
    {
        foreach (PayPalDefinitions::getPayPalDefinitions() as $paymentId => $paymentDefinitions) {
            $paymentMethod = oxNew(EshopModelPayment::class);
            if ($paymentMethod->load($paymentId)) {
                $this->reActivatePaymentMethod($paymentId, $paymentDefinitions);
                continue;
            }
            $this->createPaymentMethod($paymentId, $paymentDefinitions);
            $this->assignPaymentToCountries($paymentId, $paymentDefinitions['countries']);
            $this->assignPaymentToActiveDeliverySets($paymentId);
        }
    }

    public function deactivatePayPalPaymentMethods(): void
    {
        foreach (PayPalDefinitions::getPayPalDefinitions() as $paymentId => $paymentDefinitions) {
            $paymentMethod = oxNew(EshopModelPayment::class);
            if ($paymentMethod->load($paymentId)) {
                $paymentMethod->oxpayments__oxactive = new Field(false);
                $paymentMethod->save();
            }
        }
    }

    protected function assignPaymentToActiveDeliverySets(string $paymentId): void
    {
        $deliverySetIds = $this->getActiveDeliverySetIds();
        foreach ($deliverySetIds as $deliverySetId) {
            $this->assignPaymentToDelivery($paymentId, $deliverySetId);
        }
    }

    protected function assignPaymentToCountries(string $paymentId, array $countries): void
    {
        $activeCountriesIso2Id = array_flip($this->getActiveCountries());
        $assignToCountries = [];
        foreach ($countries as $countryIsoAlpha2) {
            if (isset($activeCountriesIso2Id[strtoupper($countryIsoAlpha2)])) {
                $assignToCountries[] = $activeCountriesIso2Id[strtoupper($countryIsoAlpha2)];
            }
        }
        $assignToCountries = empty($assignToCountries) ? $activeCountriesIso2Id : $assignToCountries;

        foreach ($assignToCountries as $countryId) {
            $this->assignPaymentToCountry($paymentId, $countryId);
        }
    }

    protected function assignPaymentToCountry(string $paymentId, string $countryId): void
    {
        $object2Paymentent = oxNew(EshopBaseModel::class);
        $object2Paymentent->init('oxobject2payment');
        $object2Paymentent->assign(
            [
                'oxpaymentid' => $paymentId,
                'oxobjectid'  => $countryId,
                'oxtype'      => 'oxcountry'
            ]
        );
        $object2Paymentent->save();
    }

    protected function assignPaymentToDelivery(string $paymentId, string $deliverySetId): void
    {
        $object2Paymentent = oxNew(EshopBaseModel::class);
        $object2Paymentent->init('oxobject2payment');
        $object2Paymentent->assign(
            [
                'oxpaymentid' => $paymentId,
                'oxobjectid'  => $deliverySetId,
                'oxtype'      => 'oxdelset'
            ]
        );
        $object2Paymentent->save();
    }

    protected function createPaymentMethod(string $paymentId, array $definitions): void
    {
        /** @var EshopModelPayment $paymentModel */
        $paymentModel = oxNew(EshopModelPayment::class);
        $paymentModel->setId($paymentId);

        $activeCountries = $this->getActiveCountries();
        $iso2LanguageId = array_flip($this->getLanguageIds());

        $active = empty($definitions['countries']) ||
            0 < count(array_intersect($definitions['countries'], $activeCountries));
        $paymentModel->assign(
            [
               'oxactive' => (int) $active,
               'oxfromamount' => (int) $definitions['constraints']['oxfromamount'],
               'oxtoamount' => (int) $definitions['constraints']['oxtoamount'],
               'oxaddsumtype' => (string) $definitions['constraints']['oxaddsumtype']
            ]
        );
        $paymentModel->save();

        foreach ($definitions['descriptions'] as $langAbbr => $data) {
            if (!isset($iso2LanguageId[$langAbbr])) {
                continue;
            }
            $paymentModel->loadInLang($iso2LanguageId[$langAbbr], $paymentModel->getId());
            $paymentModel->assign(
                [
                    'oxdesc' => $data['desc'],
                    'oxlongdesc' => $data['longdesc']
                ]
            );
            $paymentModel->save();
        }
    }

    protected function reActivatePaymentMethod(string $paymentId, array $definitions): void
    {
        /** @var EshopModelPayment $paymentModel */
        $paymentModel = oxNew(EshopModelPayment::class);
        $paymentModel->load($paymentId);

        $activeCountries = $this->getActiveCountries();

        $paymentModel->oxpayments__oxactive = new Field(empty($definitions['countries']) ||
            0 < count(array_intersect($definitions['countries'], $activeCountries)));

        $paymentModel->save();
    }

    public function ensureStaticContents(): void
    {
        foreach (PayPalDefinitions::getPayPalStaticContents() as $content) {
            $loadId = $content['oxloadid'];
            if (!$this->needToAddContent($loadId)) {
                continue;
            }

            foreach ($this->getLanguageIds() as $langId => $langAbbr) {
                $contentModel = $this->getContentModel($loadId, $langId);
                $contentModel->assign(
                    [
                        'oxloadid'  => $loadId,
                        'oxactive'  => $content['oxactive'],
                        'oxtitle'   => $content['oxtitle_' . $langAbbr] ?? '',
                        'oxcontent' => $content['oxcontent_' . $langAbbr] ?? '',
                    ]
                );
                $contentModel->save();
            }
        }
    }

    protected function needToAddContent(string $ident): bool
    {
        $content = oxNew(EshopModelContent::class);
        if ($content->loadByIdent($ident)) {
            return false;
        }
        return true;
    }

    protected function getContentModel(string $ident, int $languageId): EshopModelContent
    {
        $content = oxNew(EshopModelContent::class);
        if ($content->loadByIdent($ident)) {
            $content->loadInLang($languageId, $content->getId());
        }

        return $content;
    }

    protected function getActiveDeliverySetIds(): array
    {
        $fromDb = $this->db->getAll(
            'SELECT `oxid` FROM `oxdeliveryset` WHERE oxactive = 1'
        );

        foreach ($fromDb as $row) {
            $id = $row['oxid'] ?? $row[0];
            $result[$id] =$id;
        }

        return $result;
    }

    /**
     * get the language-IDs
     */
    protected function getLanguageIds(): array
    {
        return EshopRegistry::getLang()->getLanguageIds();
    }

    protected function getActiveCountries(): array
    {
        $result = [];

        $fromDb = $this->db->getAll(
            'SELECT `oxid`, oxisoalpha2 FROM `oxcountry` WHERE oxactive = 1'
        );

        foreach ($fromDb as $row) {
            $id = $row['oxid'] ?? $row[0];
            $iso = $row['oxisoalpha2'] ?? $row[1];
            $result[$id] = $iso;
        }

        return $result;
    }
}
