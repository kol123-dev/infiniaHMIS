'use strict';

window.setPrice = function (selector, price) {
    if (price != '' || price > 0) {
        if (typeof price !== 'number') {
            price = price.replace(/,/g, '');
        }
        let formattedPrice = addCommas(price);
        $(selector).val(formattedPrice);
    }
};

window.addCommas = function (nStr) {
    nStr += '';
    let x = nStr.split('.');
    let x1 = x[0];
    let x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
};

window.getFormattedPrice = function(price) {
    if(price != '' || price > 0) {
        if(typeof price !== 'number') {
            price = price.replace(/,/g, '');
        }
        return addCommas(price);
    }
}

window.priceFormatSelector = function (selector) {
    listen('input keyup keydown keypress', selector, function (event) {
        let price = $(this).val();
        if (price === '') {
            $(this).val('');
        } else {
            price = price.replace(/,/g, '');
            price = price.replace(/^(\d+\.?\d{0,2}).*/, '$1');
            if (/^\d*\.?\d{0,2}$/.test(price)) {
                $(this).val(getFormattedPrice(price));
            } else {
                $(this).val(price.replace(/[^0-9.]/g, ''));
            }
        }
    });
};


window.removeCommas = function (str) {
    return str.replace(/,/g, '');
};

priceFormatSelector('.price-input');
