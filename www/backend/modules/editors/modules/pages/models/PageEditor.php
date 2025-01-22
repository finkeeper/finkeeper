<?php
namespace backend\modules\editors\modules\pages\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use common\models\Page;
//use yii\web\UploadedFile;
//use backend\components\GetFile;
use yii\data\ActiveDataProvider;
use yii\base\InvalidParamException;
use backend\modules\editors\modules\pages\PagesModule;

/**
 * Login form
 */
class PageEditor extends Model
{
	public $id_page;
	public $id_header;
	public $id_footer;
	public $template;
	public $deleted;
	public $deleted_date;
	public $creation_date;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		
			[['id_page', 'id_header', 'id_footer', 'deleted'], 'integer'],
			[['creation_date', 'deleted_date'], 'string', 'max' => 60],
			[['template'], 'string'],
        ];
    }
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'template' => Yii::t('Page', 'Template'),
			'id_page' => Yii::t('Frontend', 'ID Page'),
			'id_header' => Yii::t('Frontend', 'ID Header'),
			'id_footer' => Yii::t('Frontend', 'ID Footer'),
			'deleted' => Yii::t('Frontend', 'Deleted'),
			'creation_date' => Yii::t('Frontend', 'Creation Date'),
			'deleted_date' => Yii::t('Frontend', 'Deleted Date'),
        ];
    }
	
	/**
	 * search()
	 */
	public function search()
	{
		$query = Page::findPagesProvider();
		   
		return new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => Yii::$app->params['pagination'],
			],
			'sort' => [
				'defaultOrder' => [
					'id_page' => SORT_ASC,
				]
			],
		]);
	}

	 /**
     * update
     */
    public function update()
    {
		if (!$this->validate()) {
            return null;
        }
		
		$page = self::findPage($this->id_page);
		$page->setAttributes($this->attributes, false);

		if ($page->save()) {
			return true;
		}

        return false;
    }

	/**
	 * findPages()
	 */
	public static function findPage($id)
	{
		return Page::findPage($id);
	}
	
	/**
	 * findPages()
	 */
	public static function findPages()
	{
		return Page::find()->where(['deleted'=>0]);
	}
}
