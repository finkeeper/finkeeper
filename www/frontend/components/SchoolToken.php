<?php

namespace frontend\components;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;


use app\modules\profile\models\Profile;

class SchoolToken
{
    const TESTNET_BSCSCAN_ADDRESS = "https://testnet.bscscan.com/token/";
    const MAINNET_BSCSCAN_ADDRESS = "https://bscscan.com/token/";

    const TESTNET_BSCSCAN_OWNER_ADDRESS = "https://testnet.bscscan.com/address/";
    const MAINNET_BSCSCAN_OWNER_ADDRESS = "https://bscscan.com/address/";

	const BNB_TESTNET_CHAIN_ID = 97;
	const BNB_MAINNET_CHAIN_ID = 56;

    const JS_ADMIN_OBJECT_TESTNET = "adminCtfnTestnet";
    const JS_ADMIN_OBJECT_MAINNET = "adminCtfnMainnet";

	const NETWORK_TYPE_MAINNET = 'mainnet';
	const NETWORK_TYPE_TESTNET = 'testnet';

	const OPENSEA_API_KEY = ''; // until 2024 | np@sheremetev.info
	
	const OPENSEA_API_URI_MAINNET = 'https://api.opensea.io/api/v1/';
	const OPENSEA_API_URI_TESTNET = 'https://testnets-api.opensea.io/api/v1/';

    public static function clearText($text = null) {
		
		if (empty($text)) { 
			return '';
		}

		$text = (string)$text;
		$text = strip_tags($text);
		$text = htmlspecialchars($text);
		$text = htmlentities($text);
		
		return $text;
	}


	public static function getMetaData($url) {

		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 5,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",			
			CURLOPT_HTTPHEADER => [
				"accept: application/json"
			],			
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);
		
		curl_close($curl);

		if ($err) {
			return ['error'=> 1, 'message' => "cURL Error #:" . $err];
		}

		return ['error'=> 0,'message' => 'Found', 'data' => json_decode($response, true) ];
	}
}