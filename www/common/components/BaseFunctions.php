<?php
namespace common\components;

/**
 * BaseFunctions
 */
 
Class BaseFunctions
{
	public static function getDateFormatTZ($timezone='utc', $desimals=0)
	{
		if (empty($timezone)) {
			$timezone='utc';
		}
		
		$timezone = strtoupper($timezone);
		date_default_timezone_set($timezone);
		
		if (!empty($desimals)) {
			
			$milliseconds = explode('.', number_format(microtime(true), $desimals, '.', ''));
			return date('Y-m-d\TH:i:s').'.'.$milliseconds[1].'Z';	
		}
		
		return date('Y-m-d\TH:i:s').'Z';	
	}

	/**
     * getDecimalsNumber($desimals=0)
	 */
	public static function getDecimalsNumber($num=0)
	{	
		if (empty($num)) {
			return $number;
		}
		
		for ($i=1; $i<=$num; $i++) {
			if (empty($decimal)) {
				$decimal = 10;
			} else {
				$decimal = $decimal*10;
			}
		}
		
		return $decimal;
	}
 }