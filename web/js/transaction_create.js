document.addEventListener("DOMContentLoaded", function () {
    $.getScript('/js/params.js');
    var $stock = $('#stock_id'), $unit_cost = $('#unit_cost'), $link = $('#link');
    var last_sale_api = null, symbol = null, last_sale = null;
    $stock.on('change', function () {
        //get from API
        $.getJSON(API_URL + 'stock/' + $stock.val(),
            function (stock_attr) {
                console.info(stock_attr);
                if (_.isObject(stock_attr) && stock_attr.hasOwnProperty('last_sale')) {
                    last_sale_api = (stock_attr.last_sale);
                }
                if (_.isObject(stock_attr) && stock_attr.hasOwnProperty('symbol')) {
                    symbol = (stock_attr.symbol);
                }
                if (!_.isNull(symbol)) {
                    $link.attr('href', "https://finance.yahoo.com/quote/" + symbol + "?p=" + symbol + "&output=embed").show();
                    $.get('http://query.yahooapis.com/v1/public/yql?q=select * from yahoo.finance.quotes where symbol in ("' + symbol + '")&format=json&env=store://datatables.org/alltableswithkeys',
                        function (result) {
                            if (_.isObject(result) && result.hasOwnProperty('query') && result.query.hasOwnProperty("results") && result.query.results.hasOwnProperty("quote") && result.query.results.quote.hasOwnProperty("symbol")) {
                                var quote_detail = result.query.results.quote;
                                if (quote_detail.symbol !== symbol) {
                                    $unit_cost.val(last_sale_api);
                                    $('#note').html('Note: stock value is pulled from NASDAQ');
                                } else {
                                    $unit_cost.val(parseFloat(quote_detail.Ask));
                                    $('#note').html('Note: stock value is pulled from Yahoo Finance');
                                }
                            }
                        });
                }
            });
    });
})
;