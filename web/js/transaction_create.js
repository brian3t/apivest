document.addEventListener("DOMContentLoaded", function () {
    $.getScript('/js/params.js');
    var $stock = $('#stock_id'), $unit_cost = $('#unit_cost');
    $stock.on('change', function () {
        $.getJSON(API_URL + 'stock/' + $stock.val(),
            function (stock_attr) {
                console.info(stock_attr);
                if (_.isObject(stock_attr) && stock_attr.hasOwnProperty('last_sale')){
                    $unit_cost.val(stock_attr.last_sale);
                }
            });
    });
});