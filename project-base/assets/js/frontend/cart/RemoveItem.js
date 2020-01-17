import Register from 'framework/common/utils/register';
import Ajax from 'framework/common/utils/ajax';
import Window from '../utils/window';

export default class RemoveItem {

    static init() {
        document.getElementsByClassName('js-cart-item-remove-button').forEach(element => {
            element.addEventListener('click', event => {
                event.preventDefault();

                Ajax.ajax({
                    loaderElement: element,
                    url: element.getAttribute('href'),
                    type: 'post',
                    success: function (data) {
                        if (data.success === true) {
                            $('#js-cart-box').trigger('reload');
                        } else {
                            // eslint-disable-next-line no-new
                            new Window({
                                data: data.errorMessage
                            });
                        }
                    }
                });
            });
        });
    }
}

(new Register()).registerCallback(RemoveItem.init);
