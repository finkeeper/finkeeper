<?php

namespace backend\modules\statistics\modules\yandex\components;

use Yii;
use yii\web\Controller;

/**
 * Default controller for the `service` module
 */
class YandexController extends Controller
{
	/**
     * @var \app\modules\service\Module
     */
    private $_module;

    public $module = 'yandex';
	
	/**
     * Service constructor.
     * @param array $config
     */
	public function __construct($id, $module=null)
	{
		parent::__construct($id, $module);
	}

	/**
     * beforeAction
     */
    public function beforeAction($action)
    {
		return true;
	}
}
