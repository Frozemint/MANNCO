<?php 
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

	ob_flush();
	flush();

	$keyData = json_decode(file_get_contents('https://steamcommunity.com/market/priceoverview/?currency=USD&appid=440&market_hash_name=Mann%20Co.%20Supply%20Crate%20Key'), true) or die("Failed to fetch price!");
	$ticketData = json_decode(file_get_contents('https://steamcommunity.com/market/priceoverview/?currency=USD&appid=440&market_hash_name=Tour%20of%20Duty%20Ticket'), true) or die("Failed to fetch price!");
	$billsData = json_decode(file_get_contents('https://steamcommunity.com/market/priceoverview/?currency=USD&appid=440&market_hash_name=Bill%27s%20Hat'), true) or die("Failed to fetch price!");
	$budsData = json_decode(file_get_contents('https://steamcommunity.com/market/priceoverview/?currency=USD&appid=440&market_hash_name=Earbuds'), true) or die("Failed to fetch price!");
	$nameData = json_decode(file_get_contents('https://steamcommunity.com/market/priceoverview/?currency=USD&appid=440&market_hash_name=Name%20Tag'), true) or die("Failed to fetch price!");
	$descData = json_decode(file_get_contents('https://steamcommunity.com/market/priceoverview/?currency=USD&appid=440&market_hash_name=Description%20Tag'), true) or die("Failed to fetch price!");

	$keyPrice = substr($keyData['lowest_price'], 1);
	$ticketPrice = substr($ticketData['lowest_price'], 1);
	$billsPrice = substr($billsData['lowest_price'], 1);
	$namePrice = substr($nameData['lowest_price'], 1);
	$descPrice = substr($descData['lowest_price'], 1);
	$budsPrice = substr($budsData['lowest_price'], 1);


	$ticketDelta =  number_format(($ticketPrice - 0.99)/0.99, 4);
	$keyDelta = number_format(($keyPrice - 2.49)/2.49, 4);
	$billsDelta = number_format(($billsPrice - 5.7)/5.7, 4);
	$nameDelta = number_format(($namePrice - 0.99)/0.99, 4);
	$descDelta = number_format(($descPrice - 0.53)/0.53, 4);
	$budsDelta = number_format(($budsPrice - 7)/7, 4);

	$indexPoints = number_format((5 * (1 + $keyDelta)) + (5 * (1 + $ticketDelta)) + (5 *  (1 + $billsDelta)) + (5 * (1 + $nameDelta)) + (5 * (1 + $descDelta)), 2);
	$indexDelta = number_format(($indexPoints - 25)/25, 4);

	
	$currentTime = gmdate("M d H:i:s ");

	echo 'data: {"ticket": "' . $ticketPrice . '",';
	echo '"key": "' . $keyPrice . '",';
	echo '"bills": "' . $billsPrice . '",';
	echo '"buds": "' . $budsPrice . '",';
	echo '"names": "' . $namePrice . '",';

	echo '"gmtTime": "' . 'Updated at (GMT): ' .$currentTime . '",';

	echo '"indexP": "' . $indexPoints . '",';
	echo '"ticketD": "' . number_format($ticketDelta * 100, 2) .'",';
	echo '"keyD": "' . number_format($keyDelta * 100, 2) . '",';
	echo '"billsD": "' . number_format($billsDelta * 100, 2) . '",';
	echo '"budsD": "' . number_format($budsDelta * 100, 2) . '",';
	echo '"namesD": "' . number_format($nameDelta * 100, 2) . '",';
	echo '"descD": "' . number_format($descDelta * 100, 2) . '",';

	echo '"indexD": "' . number_format($indexDelta * 100, 2) . '",';
	
	echo '"desc": "' . $descPrice . '"';
	echo "}";
	echo "\n\n";

	flush();

	$dataData = $ticketPrice.PHP_EOL.$keyPrice.PHP_EOL.$billsPrice.PHP_EOL.$namePrice.PHP_EOL.$descPrice.PHP_EOL.$budsPrice.PHP_EOL.$ticketDelta.PHP_EOL.$keyDelta.PHP_EOL.$billsDelta.PHP_EOL.$nameDelta.PHP_EOL.$descDelta.PHP_EOL.$budsDelta.PHP_EOL.PHP_EOL.$indexPoints.PHP_EOL.$indexDelta;

	file_put_contents('data.txt', $dataData);

	$historyData = "history.txt";

	$lines = file($historyData) or die("Failed to read!");

	$historyHigh = $lines[0];
	$historyHighTime = $line[1];
	$historyLow = $lines[2];

	if ($indexPoints > $historyHigh){
		$history = "Lifetime high: ".$indexPoints.PHP_EOL."At: ".$currentTime;
		file_put_contents($historyData, $history);
	}

	if ($indexPoints > $historyLow){
		$history = "Lifetime high: ".$historyHigh.PHP_EOL."At: ".$historyHighTime.PHP_EOL."Lifetime low: ".$indexPoints.PHP_EOL."At: ".$currentTime;
		file_put_contents($historyData, $history);
	}

?>