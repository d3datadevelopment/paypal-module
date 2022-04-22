<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

$sLangName = 'Deutsch';

$aLang = [
    'charset'                                     => 'UTF-8',
    'paypal'                                      => 'PayPal',
    'tbclorder_oscpaypal'                         => 'PayPal',
    // PayPal Config
    'OSC_PAYPAL_CONFIG'                           => 'Konfiguration',
    'OSC_PAYPAL_GENERAL'                          => 'Allgemein',
    'OSC_PAYPAL_WEBHOOK_ID'                       => 'Webhook-ID',
    'OSC_PAYPAL_OPMODE'                           => 'Betriebsmodus',
    'OSC_PAYPAL_OPMODE_LIVE'                      => 'Live',
    'OSC_PAYPAL_OPMODE_SANDBOX'                   => 'Sandbox',
    'OSC_PAYPAL_CLIENT_ID'                        => 'Client-ID',
    'OSC_PAYPAL_CLIENT_SECRET'                    => 'Passwort',
    'OSC_PAYPAL_CREDENTIALS'                      => 'API-Anmeldeinformationen',
    'OSC_PAYPAL_LIVE_CREDENTIALS'                 => 'API-Anmeldeinformationen (Live)',
    'OSC_PAYPAL_SANDBOX_CREDENTIALS'              => 'API-Anmeldeinformationen (Sandbox)',
    'OSC_PAYPAL_LIVE_BUTTON_CREDENTIALS'          => 'Anmeldung Händler PayPal-Integration (Live)',
    'OSC_PAYPAL_LIVE_BUTTON_CREDENTIALS_INIT'     => 'Händler PayPal-Integration (Live) im neuen Fenster starten ...',
    'OSC_PAYPAL_SANDBOX_BUTTON_CREDENTIALS'       => 'Anmeldung Händler PayPal-Integration (Sandbox)',
    'OSC_PAYPAL_SANDBOX_BUTTON_CREDENTIALS_INIT'  => 'Händler PayPal-Integration (Sandbox) im neuen Fenster starten ...',
    'OSC_PAYPAL_ONBOARD_CLICK_HELP'               => 'Bitte schließen Sie diese Seite, wenn Sie die PayPal-Integration abbrechen wollen ...',
    'OSC_PAYPAL_ONBOARD_CLOSE_HELP'               => 'Onboarding erfolgreich. Sie können das Fenster jetzt schließen.',
    'OSC_PAYPAL_ERR_CONF_INVALID'                 =>
        'Ein oder mehrere Konfigurationswerte sind entweder nicht festgelegt oder falsch.
        Bitte überprüfen Sie sie noch einmal.<br>
        <b>Modul inaktiv.</b>',
    'OSC_PAYPAL_CONF_VALID'                       => 'Konfigurationswerte OK.<br><b>Modul ist aktiv</b>',
    'OSC_PAYPAL_BUTTON_PLACEMEMT_TITLE'           => 'Einstellungen für die Buttonplatzierung',
    'OSC_PAYPAL_PRODUCT_DETAILS_BUTTON_PLACEMENT' => 'Produktdetailseite',
    'OSC_PAYPAL_BASKET_BUTTON_PLACEMENT'          => 'Warenkorb',
    'HELP_OSC_PAYPAL_BUTTON_PLACEMEMT'            => 'Schalten Sie die Anzeige der PayPal-Schaltflächen um',
    'OSC_SHOW_PAYPAL_PAYLATER_BUTTON'             => '"Später Bezahlen"-Button anzeigen?',
    'HELP_OSC_SHOW_PAYPAL_PAYLATER_BUTTON'        => 'Neben den klassischen PayPal-Button gibt es ein "Später Bezahlen"-Button, der unter dem Standardbutton angezeigt werden kann. Ist der aktiviert, bekommt der Kunde direkt die Möglichkeit, die Ware später zu zahlen.',

    'OSC_PAYPAL_EXPRESS_LOGIN_TITLE'              => 'Login mit PayPal',
    'OSC_PAYPAL_LOGIN_WITH_PAYPAL_EMAIL'          => 'Im Shop beim Kauf automatisch einloggen',
    'HELP_OSC_PAYPAL_EXPRESS_LOGIN'               => 'Wenn die Shop-Kundenkonto-EMail-Adresse gleich der PayPal-EMail-Adresse ist,
        besteht die Möglichkeit, sich durch ein Login bei PayPal auch automatisch im Shop anzumelden. Möglicherweise ist dieses Verhalten nicht im
        Sicherheitsinteresse Ihrer Kunden',

    'HELP_OSC_PAYPAL_CREDENTIALS'                 =>
        'Wenn Sie die API-Anmeldeinformationen (Client-ID, Client Passwort, Webhook-ID) bereits vorliegen haben, können Sie sie direkt eingeben.<br>
        Sollten Sie noch keine API-Daten haben und die Eingafelder noch leer sein, können Sie auch den eingeblendeten
        Button für eine komfortable Verknüpfung nutzen.',
    'HELP_OSC_PAYPAL_CLIENT_ID'                   => 'Client-ID des Live-Account für Live-Modus',
    'HELP_OSC_PAYPAL_CLIENT_SECRET'               => 'Secret des Live-Account für Live-Modus',
    'HELP_OSC_PAYPAL_SANDBOX_CLIENT_ID'           => 'Client-ID des Sandbox-Account für Sandbox-Modus',
    'HELP_OSC_PAYPAL_SANDBOX_CLIENT_SECRET'       => 'Secret des Sandbox-Account für Sandbox-Modus. Bitte geben Sie das Passwort zweimal ein.',
    'HELP_OSC_PAYPAL_SANDBOX_WEBHOOK_ID'          =>
        'Die ID des Sandbox-Webhooks, wie in Ihrem Developer Portal-Konto konfiguriert',
    'HELP_OSC_PAYPAL_OPMODE'                      =>
        'Verwenden Sie Sandbox (Test), um PayPal zu konfigurieren und zu testen. Wenn Sie bereit sind,
        echte Transaktionen zu empfangen, wechseln Sie zu Produktion (live).',
    'HELP_OSC_PAYPAL_WEBHOOK_ID'                  =>
        'Die ID des Webhooks, wie in Ihrem Developer Portal-Konto konfiguriert',
    'OSC_PAYPAL_SPECIAL_PAYMENTS'                 => 'Freischaltung für besondere Zahlarten erfolgt',
    'OSC_PAYPAL_SPECIAL_PAYMENTS_PUI'             => 'Rechnungskauf',
    'OSC_PAYPAL_SPECIAL_PAYMENTS_ACDC'            => 'Kreditkarte',

    // PayPal ORDER
    'OSC_PAYPAL_AMOUNT'                           => 'Betrag',
    'OSC_PAYPAL_SHOP_PAYMENT_STATUS'              => 'Shop-Zahlungsstatus',
    'OSC_PAYPAL_ORDER_PRICE'                      => 'Bestellpreis gesamt',
    'OSC_PAYPAL_ORDER_PRODUCTS'                   => 'Bestellte Artikel',
    'OSC_PAYPAL_CAPTURED'                         => 'Eingezogen',
    'OSC_PAYPAL_REFUNDED'                         => 'Erstattet',
    'OSC_PAYPAL_CAPTURED_NET'                     => 'Resultierender Zahlungsbetrag',
    'OSC_PAYPAL_CAPTURED_AMOUNT'                  => 'Eingezogener Betrag',
    'OSC_PAYPAL_REFUNDED_AMOUNT'                  => 'Erstatteter Betrag',
    'OSC_PAYPAL_MONEY_CAPTURE'                    => 'Geldeinzug',
    'OSC_PAYPAL_MONEY_REFUND'                     => 'Gelderstattung',
    'OSC_PAYPAL_CAPTURE'                          => 'Einziehen',
    'OSC_PAYPAL_REFUND'                           => 'Erstatten',
    'OSC_PAYPAL_DETAILS'                          => 'Details',
    'OSC_PAYPAL_AUTHORIZATION'                    => 'Autorisierung',
    'OSC_PAYPAL_CANCEL_AUTHORIZATION'             => 'Stornieren',
    'OSC_PAYPAL_PAYMENT_HISTORY'                  => 'PayPal-Historie',
    'OSC_PAYPAL_HISTORY_DATE'                     => 'Datum',
    'OSC_PAYPAL_HISTORY_ACTION'                   => 'Aktion',
    'OSC_PAYPAL_HISTORY_PAYPAL_STATUS'            => 'PayPal-Status',
    'OSC_PAYPAL_HISTORY_PAYPAL_STATUS_HELP'       =>
        'Von PayPal zurückgegebener Zahlungsstatus. Für mehr Details siehe (nur Englisch):
        <a href="https://www.paypal.com/webapps/helpcenter/article/?articleID=94021&m=SRE" target="_blank">
            PayPal Zahlungsstatus
        </a>',
    'OSC_PAYPAL_HISTORY_COMMENT'                  => 'Kommentar',
    'OSC_PAYPAL_HISTORY_NOTICE'                   => 'Hinweis',
    'OSC_PAYPAL_MONEY_ACTION_FULL'                => 'vollständig',
    'OSC_PAYPAL_MONEY_ACTION_PARTIAL'             => 'teilweise',
    'OSC_PAYPAL_LIST_STATUS_ALL'                  => 'Alle',
    'OSC_PAYPAL_STATUS_APPROVED'                  => 'genehmigt',
    'OSC_PAYPAL_STATUS_COMPLETED'                 => 'abgeschlossen',
    'OSC_PAYPAL_STATUS_DECLINED'                  => 'abgelehnt',
    'OSC_PAYPAL_STATUS_PARTIALLY_REFUNDED'        => 'Teilweise erstattet',
    'OSC_PAYPAL_STATUS_PENDING'                   => 'steht aus',
    'OSC_PAYPAL_STATUS_REFUNDED'                  => 'Erstattet',
    'OSC_PAYPAL_PAYMENT_METHOD'                   => 'Zahlungsart',
    'OSC_PAYPAL_CLOSE'                            => 'Schließen',
    'OSC_PAYPAL_COMMENT'                          => 'Kommentar',
    'OSC_PAYPAL_RESPONSE_FROM_PAYPAL'             => 'Fehlermeldung von PayPal: ',
    'OSC_PAYPAL_AUTHORIZATIONID'                  => 'Autorisierungs-ID',
    'OSC_PAYPAL_TRANSACTIONID'                    => 'Transaktions-ID',
    'OSC_PAYPAL_REFUND_AMOUNT'                    => 'Rückerstattungsbetrag',
    'OSC_PAYPAL_INVOICE_ID'                       => 'Rechnungs-Nr',
    'OSC_PAYPAL_NOTE_TO_BUYER'                    => 'Hinweis für Käufer',
    'OSC_PAYPAL_REFUND_ALL'                       => 'Alle erstatten',
    'OSC_PAYPAL_FIRST_NAME'                       => 'Vorname',
    'OSC_PAYPAL_LAST_NAME'                        => 'Nachname',
    'OSC_PAYPAL_FULL_NAME'                        => 'Vollständiger Name',
    'OSC_PAYPAL_EMAIL'                            => 'Email',
    'OSC_PAYPAL_ADDRESS_LINE_1'                   => 'Adresse Zeile 1',
    'OSC_PAYPAL_ADDRESS_LINE_2'                   => 'Adresse Zeile 2',
    'OSC_PAYPAL_ADDRESS_LINE_3'                   => 'Adresse Zeile 3',
    'OSC_PAYPAL_ADMIN_AREA_1'                     => 'Provinz, Bundesland oder ISO-Unterteilung',
    'OSC_PAYPAL_ADMIN_AREA_2'                     => 'Stadt',
    'OSC_PAYPAL_ADMIN_AREA_3'                     => 'Ortsteil, Vorort, Bezirk',
    'OSC_PAYPAL_ADMIN_AREA_4'                     => 'Nachbarschaft, Gemeinde',
    'OSC_PAYPAL_POSTAL_CODE'                      => 'Postleitzahl',
    'OSC_PAYPAL_COUNTRY_CODE'                     => 'Ländercode',
    'OSC_PAYPAL_SHIPPING'                         => 'Versand',
    'OSC_PAYPAL_BILLING'                          => 'Abrechnung',

    // PSPAYPAL-491 -->
    'OSC_PAYPAL_BANNER_CREDENTIALS'                => 'Banner-Einstellungen',
    'OSC_PAYPAL_BANNER_INFOTEXT'                   => 'Bieten Sie Ihren Kunden PayPal Ratenzahlung mit 0% effektiven Jahreszins an. <a href="https://www.paypal.com/de/webapps/mpp/installments" target="_blank">Erfahren Sie hier mehr</a>.',
    'OSC_PAYPAL_BANNER_SHOW_ALL'                   => 'Ratenzahlung-Banner aktivieren',
    'HELP_OSC_PAYPAL_BANNER_SHOP_MODULE_SHOW_ALL'  => 'Aktivieren Sie diese Einstellung, um die Bannerfunktion zuzulassen.',
    'OSC_PAYPAL_BANNER_STARTPAGE'                   => 'Ratenzahlung-Banner auf Startseite anzeigen',
    'OSC_PAYPAL_BANNER_STARTPAGESELECTOR'           => 'CSS-Selektor der Startseite hinter dem das Banner angezeigt wird.',
    'HELP_OSC_PAYPAL_BANNER_STARTPAGESELECTOR'      => 'Standardwerte für die Themes "Flow" und "Wave": \'#wrapper .row\' bzw. \'#wrapper .container\'. Nach diesen CSS-Selektoren wird das Banner angezeigt.',
    'OSC_PAYPAL_BANNER_CATEGORYPAGE'                => 'Ratenzahlung-Banner auf Kategorieseiten anzeigen',
    'OSC_PAYPAL_BANNER_CATEGORYPAGESELECTOR'        => 'CSS-Selektor der Kategorieseiten hinter dem das Banner angezeigt wird.',
    'HELP_OSC_PAYPAL_BANNER_CATEGORYPAGESELECTOR'   => 'Standardwerte für die Themes "Flow" und "Wave": \'.page-header\' bzw. \'.page-header\'. Nach diesen CSS-Selektoren wird das Banner angezeigt.',
    'OSC_PAYPAL_BANNER_SEARCHRESULTSPAGE'           => 'Ratenzahlung-Banner bei Suchergebnissen anzeigen',
    'OSC_PAYPAL_BANNER_SEARCHRESULTSPAGESELECTOR'   => 'CSS-Selektor der Suchergebnisse hinter dem das Banner angezeigt wird.',
    'HELP_OSC_PAYPAL_BANNER_SEARCHRESULTSPAGESELECTOR' => 'Standardwerte für die Themes "Flow" und "Wave": \'#content .page-header .clearfix\' bzw. \'.page-header\'. Nach diesen CSS-Selektoren wird das Banner angezeigt.',
    'OSC_PAYPAL_BANNER_DETAILSPAGE'                 => 'Ratenzahlung-Banner auf Detailseiten anzeigen',
    'OSC_PAYPAL_BANNER_DETAILSPAGESELECTOR'         => 'CSS-Selektor der Detailseiten hinter dem das Banner angezeigt wird.',
    'HELP_OSC_PAYPAL_BANNER_DETAILSPAGESELECTOR'    => 'Standardwerte für die Themes "Flow" und "Wave": \'.detailsParams\' bzw. \'#detailsItemsPager\'. Nach diesen CSS-Selektoren wird das Banner angezeigt.',
    'OSC_PAYPAL_BANNER_CHECKOUTPAGE'                => 'Ratenzahlung-Banner im Warenkorb anzeigen',
    'OSC_PAYPAL_BANNER_CARTPAGESELECTOR'            => 'CSS-Selektor der Warenkorbübersicht (Bestellschritt 1) hinter dem das Banner angezeigt wird.',
    'HELP_OSC_PAYPAL_BANNER_CARTPAGESELECTOR'       => 'Standardwerte für die Themes "Flow" und "Wave": \'.cart-buttons\' und \'.cart-buttons\'. Nach diesen CSS-Selektoren wird das Banner angezeigt.',
    'OSC_PAYPAL_BANNER_PAYMENTPAGESELECTOR'         => 'CSS-Selektor der Seite "Versand & Zahlungsart" (Bestellschritt 3) hinter dem das Banner angezeigt wird.',
    'HELP_OSC_PAYPAL_BANNER_PAYMENTPAGESELECTOR'    => 'Standardwerte für die Themes "Flow" und "Wave": \'.checkoutSteps ~ .spacer\' bzw. \'.checkout-steps\'. Nach diesen CSS-Selektoren wird das Banner angezeigt.',
    'OSC_PAYPAL_BANNER_COLORSCHEME'                 => 'Farbe des Ratenzahlung-Banners auswählen',
    'OSC_PAYPAL_BANNER_COLORSCHEMEBLUE'             => 'blau',
    'OSC_PAYPAL_BANNER_COLORSCHEMEBLACK'            => 'schwarz',
    'OSC_PAYPAL_BANNER_COLORSCHEMEWHITE'            => 'weiß',
    'OSC_PAYPAL_BANNER_COLORSCHEMEWHITENOBORDER'    => 'weiß, ohne Rand',
    // <-- PSPAYPAL-491
];
