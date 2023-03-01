[{block name="oscpaypal_paymentbuttons"}]
    <div id="[{$buttonId}]" class="paypal-button-container [{$buttonClass}]"></div>
    [{if $phpStorm}]<script>[{/if}]
    [{capture assign="paypal_init"}]
        [{if !$aid}]
            [{assign var="aid" value=""}]
        [{/if}]
        [{assign var="sToken" value=$oViewConf->getSessionChallengeToken()}]
        [{assign var="sSelfLink" value=$oViewConf->getSslSelfLink()|replace:"&amp;":"&"}]
        [{if $buttonId == 'PayPalButtonSepa'}]
            var FUNDING_SOURCES = [
                paypal.FUNDING.SEPA
            ];
            // Loop over each funding source/payment method
            FUNDING_SOURCES.forEach(function (fundingSource) {
                // Initialize the buttons
                var button = paypal.Buttons({
                    fundingSource: fundingSource,
                })
                // Check if the button is eligible
                if (button.isEligible()) {
                // Render the standalone button for that funding source
                    button.render('#[{$buttonId}]')
                }
            });
        [{else}]
            button = paypal.Buttons({
                [{if $oViewConf->getCountryRestrictionForPayPalExpress()}]
                onShippingChange: function (data, actions) {
                    if (!countryRestriction.includes(data.shipping_address.country_code)) {
                        return actions.reject();
                    }
                    return actions.resolve();
                },
                [{/if}]
                createOrder: function (data, actions) {
                    return fetch('[{$sSelfLink|cat:"cl=oscpaypalproxy&fnc=createOrder&context=continue"|cat:"&aid="|cat:$aid|cat:"&stoken="|cat:$sToken}]', {
                        method: 'post',
                        headers: {
                            'content-type': 'application/json'
                        }
                    }).then(function (res) {
                        return res.json();
                    }).then(function (data) {
                        return data.id;
                    })
                },
                onApprove: function (data, actions) {
                    captureData = new FormData();
                    captureData.append('orderID', data.orderID);
                    return fetch('[{$sSelfLink|cat:"cl=oscpaypalproxy&fnc=approveOrder&context=continue"|cat:"&aid="|cat:$aid|cat:"&stoken="|cat:$sToken}]', {
                        method: 'post',
                        body: captureData
                    }).then(function (res) {
                        return res.json();
                    }).then(function (data) {
                        if (data.status == "ERROR") {
                            location.reload();
                        } else if (data.id && data.status == "APPROVED") {
                            location.replace('[{$sSelfLink|cat:"cl=order"}]');
                        }
                    })
                },
                onCancel: function (data, actions) {
                    fetch('[{$sSelfLink|cat:"cl=oscpaypalproxy&fnc=cancelPayPalPayment"}]');
                },
                onError: function (data) {
                    fetch('[{$sSelfLink|cat:"cl=oscpaypalproxy&fnc=cancelPayPalPayment"}]');
                }
            })
            if (button.isEligible()) {
                button.render('#[{$buttonId}]');
            }
        [{/if}];
    [{/capture}]
    [{if $phpStorm}]</script>[{/if}]
    [{oxscript add=$paypal_init}]
[{/block}]