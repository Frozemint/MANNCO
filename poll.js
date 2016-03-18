var url = 'https://steamcommunity.com/market/priceoverview/?currency=USD&appid=440&market_hash_name=Mann%20Co.%20Supply%20Crate%20Key';

function fetchData() {
  $.ajax({
    url: url,
    method: 'GET',
    dataType: 'json',
    cache: false,
    success: function (data) {
        //parseData(data);
        console.log("t");
        console.log(data);
    },
    error: function (data) {
    	console.log("Failed to fetch!");
        setTimeout(function () { fetchData() }, 5000);
    }

 });

}