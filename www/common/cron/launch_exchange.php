<?php
require dirname(__DIR__) . '/cron/DB.php';
$DB = new DB;

$exchange_rates = $DB->getConfigExchange();
if (
	empty($exchange_rates) || 
	empty($exchange_rates['completed']) || 
	!is_array($exchange_rates['completed'])
) {
	return false;
}

$currency_rates = $DB->getConfigCurrency();
if (
	empty($currency_rates) || 
	empty($currency_rates['completed']) || 
	!is_array($currency_rates['completed'])
) {
	return false;
}

$config = require dirname(__DIR__) . '/config/exchange.php';
if (
	empty($config) || 
	!is_array($config)
) {
	return false;
}

$config['exchange_rates'] = $exchange_rates['completed'];
$config['currency_rates'] = $currency_rates['completed'];

// Create Exchange Rates
$cryptorank_top = true;
$cryptorank_api = false;
$cryptoprices_api = true;
$coingecko_api = true;
$currency_api = true;

if (
	empty($config) || 
	!is_array($config) || 
	empty($config['exchange_rates']) || 
	!is_array($config['exchange_rates']) ||
	empty($config['currency_rates']) || 
	!is_array($config['currency_rates'])
) {
	return false;
}

$data = [
	'rates' => [],
	'exchange_status' => false,
	'crrency_status' => false,
];

require dirname(__DIR__) . '/cron/adapters/Coingecko.php';
require dirname(__DIR__) . '/cron/adapters/Cryptoprices.php';
require dirname(__DIR__) . '/cron/adapters/Cryptorank.php';
require dirname(__DIR__) . '/cron/adapters/Currency.php';

foreach ($config['exchange_rates'] as $conf) {
	
	$identify = strtolower($conf['symbol']);
	
	$data['rates'][$identify] = [
		'rank' => 0,
		'slug' => '',
		'name' => $conf['name'],
		'type' => '',
		'nominal' => 1,
		'symbol' => $identify,
		'currency' => $config['currency'],
		'value' => 0,
		'image' => $conf['logo'],
		'filled' => false,
		'id_config' => $conf['id'],
		'id' => '',
		'currency_type' => 0,
	];
}

foreach ($config['currency_rates'] as $conf) {
	
	$identify = strtolower($conf['symbol']);
	
	$data['rates'][$identify] = [
		'rank' => 0,
		'slug' => '',
		'name' => $conf['name'],
		'type' => '',
		'nominal' => 1,
		'symbol' => $identify,
		'currency' => $config['currency'],
		'value' => 0,
		'image' => $conf['logo'],
		'filled' => false,
		'id_config' => $conf['id'],
		'id' => '',
		'currency_type' => 0,
	];
}

// Coingecko
if (!empty($coingecko_api) && empty($data['exchange_status'])) {		

	$Coingecko  = new Coingecko($config);
	$data = $Coingecko->getAllData($data);
}

// Cryptoprices
if (!empty($cryptoprices_api) && empty($data['exchange_status'])) {		

	$Cryptoprices  = new Cryptoprices($config);
	$data = $Cryptoprices->getData($data);
}

// Cryptorank
if ((!empty($cryptorank_top) || !empty($cryptorank_api)) && empty($data['exchange_status'])) {

	$Cryptorank  = new Cryptorank($config);
	
	if (!empty($cryptorank_top)) {
		$data = $Cryptorank->getTopData($data);
	}
	
	if (!empty($cryptorank_api)) {
		$data = $Cryptorank->getData($data);
	}
}

// Cryptoprices
if (!empty($cryptoprices_api) && empty($data['exchange_status'])) {		
	
	$Cryptoprices  = new Cryptoprices($config);
	$data = $Cryptoprices->getData($data);
}

// Currency
if (!empty($currency_api) && empty($data['crrency_status'])) {		
	
	$Currency  = new Currency($config);
	$data = $Currency->getAllData($data);
}

// Save DB
$DB->saveData($data);






