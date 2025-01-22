<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Page;
use yii\web\HttpException;

/**
 * ContactForm is the model behind the contact form.
 */
class LoadPage extends Model
{
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_header', 'id_footer', 'deleted'], 'integer'],
			['creation_date', 'deleted_date', 'string', 'min' => 60, 'max' => 60],
			['template', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id_page' => 'id_page',
			'id_header' => 'id_header',
			'id_footer' => 'id_footer',
			'template' => 'template',
            'deleted' => 'deleted',
			'deleted_date' => 'deleted_date',
			'creation_date' => 'creation_date',
        ];
    }
	
	/**
	 * getPage($id)
	 */
	public static function getPage($id)
	{	
		$modelPage = Page::findPage($id);

		if (empty($modelPage)) {
			return false;
		}

		return $modelPage;
	}
	
	/**
	 * getPages()
	 */
	public static function getPages()
	{	
		$modelPages = Page::findPageAll();
		
		if (empty($modelPages) || !is_array($modelPages)) {
			return false;
		}

		return $modelPages;
	}	
}
