(function ($) {

    Shopsys = window.Shopsys || {};
    Shopsys.cartBox = Shopsys.cartBox || {};

    Shopsys.cartBox.init = function ($container) {
        $container.filterAllNodes('#js-cart-box').bind('reload', Shopsys.cartBox.reload);
        $container.filterAllNodes('.js-cart-box-toggle').on('click', Shopsys.cartBox.reloadItems);
    };

    Shopsys.cartBox.reload = function (event) {

        Shopsys.ajax({
            loaderElement: '#js-cart-box',
            url: $(this).data('reload-url'),
            type: 'get',
            success: function (data) {
                $('#js-cart-box').replaceWith(data);

                Shopsys.register.registerNewContent($('#js-cart-box').parent());
            }
        });

        event.preventDefault();
    };

    Shopsys.cartBox.reloadItems = function (event) {
        var $cartBoxContent = $(this).parent().filterAllNodes('#js-cart-box-content');

        if ($cartBoxContent.children().length === 0) {
            Shopsys.ajax({
                loaderElement: '#js-cart-box-content',
                url: $cartBoxContent.data('reload-url'),
                type: 'get',
                success: function (data) {
                    var $cartBoxContent = $('#js-cart-box-content');
                    $cartBoxContent.html(data);

                    Shopsys.register.registerNewContent($cartBoxContent.parent());
                }
            });
            event.preventDefault();
        }
    };

    Shopsys.register.registerCallback(Shopsys.cartBox.init);

})(jQuery);
