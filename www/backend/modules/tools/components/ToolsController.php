<?php

namespace app\modules\tools\components;

use Yii;
use yii\web\Controller;

/**
 * Default controller for the `service` module
 */
class ToolsController extends Controller
{
	/**
     * @var \app\modules\service\Module
     */
    private $_module;

    public $module = 'tools';
	
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
