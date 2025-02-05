<?php
require dirname(__DIR__) . '/cron/DB.php';
$DB = new DB;

$res = $DB->getConfig();
if (empty($res) || empty($res['completed']) || !is_array($res['completed'])) {
	return false;
}

$config = require dirname(__DIR__) . '/config/exchange.php';
if (
	empty($config) || 
	!is_array($config)
) {
	return false;
}

$config['rates'] = $res['completed'];

// Create Exchange Rates
$cryptorank_top = true;
$cryptorank_api = false;
$cryptoprices_api = true;
$coingecko_api = true;

if (
	empty($config) || 
	!is_array($config) || 
	empty($config['rates']) || 
	!is_array($config['rates'])
) {
	return false;
}

$data = [
	'rates' => [],
	'status' => false,
];

foreach ($config['rates'] as $conf) {
	
	$identify = strtolower($conf['symbol']);
	
	$data['rates'][$identify] = [
		'rank' => 0,
		'slug' => '',
		'name' => '',
		'type' => '',
		'nominal' => 1,
		'symbol' => $identify,
		'currency' => $config['currency'],
		'value' => 0,
		'image' => $conf['logo'],
		'filled' => false,
		'id_config' => $conf['id'],
		'type' => 1,
	];
}

// Coingecko
if (!empty($coingecko_api) && empty($data['status'])) {		
	require dirname(__DIR__) . '/cron/adapters/Coingecko.php';
	
	$Coingecko  = new Coingecko($config);
	$data = $Coingecko->getAllData($data);
}

// Cryptoprices
if (!empty($cryptoprices_api) && empty($data['status'])) {		
	require dirname(__DIR__) . '/cron/adapters/Cryptoprices.php';
	
	$Cryptoprices  = new Cryptoprices($config);
	$data = $Cryptoprices->getData($data);
}

// Cryptorank
if ((!empty($cryptorank_top) || !empty($cryptorank_api)) && empty($data['status'])) {
	require dirname(__DIR__) . '/cron/adapters/Cryptorank.php';
	
	$Cryptorank  = new Cryptorank($config);
	
	if (!empty($cryptorank_top)) {
		$data = $Cryptorank->getTopData($data);
	}
	
	if (!empty($cryptorank_api)) {
		$data = $Cryptorank->getData($data);
	}
}

// Cryptoprices
if (!empty($cryptoprices_api) && empty($data['status'])) {		
	require dirname(__DIR__) . '/cron/adapters/Cryptoprices.php';
	
	$Cryptoprices  = new Cryptoprices($config);
	$data = $Cryptoprices->getData($data);
}

// Save DB
$DB->saveData($data);






