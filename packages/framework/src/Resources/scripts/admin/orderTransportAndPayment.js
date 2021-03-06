(function ($) {

    Shopsys = window.Shopsys || {};
    Shopsys.order = Shopsys.order || {};
    Shopsys.order.transportAndPayment = Shopsys.order.transportAndPayment || {};

    Shopsys.order.transportAndPayment.Prefiller = function () {
        var $transportSelect = $('#order_form_orderItems_orderTransport_transport');
        var transportPricesWithVatByTransportId = $transportSelect.closest('.js-order-transport-row').data('transport-prices-with-vat-by-transport-id');
        var transportVatPercentsByTransportId = $transportSelect.closest('.js-order-transport-row').data('transport-vat-percents-by-transport-id');

        var $paymentSelect = $('#order_form_orderItems_orderPayment_payment');
        var paymentPricesWithVatByPaymentId = $paymentSelect.closest('.js-order-payment-row').data('payment-prices-with-vat-by-payment-id');
        var paymentVatPercentsByPaymentId = $paymentSelect.closest('.js-order-payment-row').data('payment-vat-percents-by-payment-id');

        this.init = function () {
            $transportSelect.on('change', onOrderTransportChange);
            $paymentSelect.on('change', onOrderPaymentChange);
        };

        var onOrderTransportChange = function () {
            var selectedTransportId = $transportSelect.val();
            $('#order_form_orderItems_orderTransport_priceWithVat').val(transportPricesWithVatByTransportId[selectedTransportId].amount);
            $('#order_form_orderItems_orderTransport_vatPercent').val(transportVatPercentsByTransportId[selectedTransportId]);
            $('#order_form_orderItems_orderTransport_usePriceCalculation').prop('checked', true).change();
            $('#order_form_orderItems_orderTransport_priceWithoutVat').val('');
        };

        var onOrderPaymentChange = function () {
            var selectedPaymentId = $paymentSelect.val();
            $('#order_form_orderItems_orderPayment_priceWithVat').val(paymentPricesWithVatByPaymentId[selectedPaymentId].amount);
            $('#order_form_orderItems_orderPayment_vatPercent').val(paymentVatPercentsByPaymentId[selectedPaymentId]);
            $('#order_form_orderItems_orderPayment_usePriceCalculation').prop('checked', true).change();
            $('#order_form_orderItems_orderPayment_priceWithoutVat').val('');
        };
    };

    $(document).ready(function () {
        var instance = new Shopsys.order.transportAndPayment.Prefiller();
        instance.init();
    });

})(jQuery);
