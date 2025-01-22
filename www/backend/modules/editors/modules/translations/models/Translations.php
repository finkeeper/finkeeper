<?php
namespace backend\modules\editors\modules\translations\models;

use Yii;
use yii\data\Sort;
use yii\base\Model;
use yii\helpers\Html;
use common\models\Message;
use common\models\SourceMessage;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\base\InvalidParamException;
use backend\modules\settings\modules\translations\TranslationsModule;

/**
 * Login form
 */
class Translations extends Model
{
	public $id;
	public $language;
	public $translation;
	public $translation_arr;
	public $category;
	public $message;
	public $deleted;
	public $date_create;
	public $date_deleted;
	public $translation_search;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		
			[['deleted', 'id'], 'integer'],
			
			['category', 'string', 'max' => 255],
			['category', 'required'],
			
			[['date_create', 'date_deleted'], 'string', 'max' => 60],
			
			['language', 'string', 'max' => 8],
			['language', 'required'],
			
			[['translation', 'translation_search', 'message'], 'string'],
			['message', 'required'],
			
			['translation_arr', 'safe'],
			['translation_arr', 'required'],
        ];
    }
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'language' => Yii::t('EditorsTranslations', 'Language'),
			'translation' => Yii::t('EditorsTranslations', 'Translation'),
			'category' => Yii::t('EditorsTranslations', 'Category'),
			'message' => Yii::t('EditorsTranslations', 'Message'),
			'translation_search' => Yii::t('EditorsTranslations', 'Search Message'),
			'translation_arr' => Yii::t('EditorsTranslations', 'Translation'),
        ];
    }
	
	/**
	 * searchCategory()
	 */
	public function searchCategory()
	{
		$query = $this->findMeccagesCategoryAll();
		if (empty($query) || !is_array($query)) {
			$query = [];
		}
		
		$sort = new Sort([
			'attributes' => [
				'category' => [
					'default' => SORT_ASC,
				],
			],
		]);
		   
		return new ArrayDataProvider([
			'allModels' => $query,
			'sort' => $sort,
			'pagination' => [
				'pageSize' => Yii::$app->params['pagination'],
			],
		]);
	}
	
	/**
	 * searchCategory()
	 */
	public function getCategory()
	{
		$array = [];
		$results = $this->findMeccagesCategoryAll();
		if (empty($results) || !is_array($results)) {
			return $array;
		}
		
		foreach ($results as $value) {
			$array[$value->category] = $value->category;
		}
		   
		return $array;
	}
	
	/**
     * @inheritdoc
     */
    public function findMeccagesCategoryAll()
    {
		return SourceMessage::find()
			->select(['category'])
			->where([
				'deleted'=>SourceMessage::STATUS_NOT_DELETED,
			])
			->groupBy('category')
			->all();
    }
	
	/**
	 * searchCategory()
	 */
	public function searchMessages()
	{
		$query = $this->findMeccagesCategoryJoin();
		if (empty($query) || !is_array($query)) {
			$query = [];
		}
		
		$sort = new Sort([
			'attributes' => [
				'message' => [
					'default' => SORT_ASC,
				],
				'category',
			],
		]);
		   
		return new ArrayDataProvider([
			'allModels' => $query,
			'sort' => $sort,
			'pagination' => [
				'pageSize' => Yii::$app->params['pagination'],
			],
		]);
	}
	
	/**
     * @inheritdoc
     */
	 
    public function findMeccagesCategoryJoin()
    {
		$select = [
			'{{%source_message}}.id', 
			'{{%source_message}}.category', 
			'{{%source_message}}.message', 
			'{{%message}}.language', 
			'{{%message}}.translation',
		];
		
		$join = '{{%message}}.`id` = {{%source_message}}.`id`';
		
		$where = [
			'{{%message}}.deleted'=>Message::STATUS_NOT_DELETED,
			'{{%source_message}}.deleted'=>SourceMessage::STATUS_NOT_DELETED,
		];
		
		$addWhere = [];
		
		if (!empty($this->language)) {
			$lang = strtolower($this->language).'-'.strtoupper($this->language);
			$where['{{%message}}.language'] = $lang;
		}
		
		if (!empty($this->category)) {
			$where['{{%source_message}}.category'] = $this->category;
		}

		if (!empty($this->translation_search)) {
			$addWhere = ['like', 'translation', '%'.$this->translation_search.'%', false];
		}
		
		return SourceMessage::find()
			->select($select)
			->leftJoin('{{%message}}', $join)
			->where($where)
			->andWhere($addWhere)
			->all();
    }

	/**
     * loadModel
     */
    public function loadModel()
    {
		$results = $this->findMessageFetch();
		if (empty($results)) {
			return $this;
		}
		
		$this->setAttributes($results);
		
		if (!empty($results['message']) && is_array($results['message'])) {
			foreach ($results['message'] as $message) {
				$this->translation_arr[$message['language']] = $message['translation'];
			}
		}
		
		$this->message = $results['key'];
		$this->language = '';
		$this->translation = '';
		
		return $this;
	}

	/**
     * @findMessageFetch()
     */
    public function findMessageFetch()
    {
		if (empty($this->id)) {
			return false;
		}

		$select = [
			'{{%source_message}}.id', 
			'{{%source_message}}.category', 
			'{{%source_message}}.message as key', 
			'{{%message}}.language', 
			'{{%message}}.translation',
		];
		
		$join = '{{%message}}.`id` = {{%source_message}}.`id`';

		$where = [
			'{{%message}}.deleted'=>Message::STATUS_NOT_DELETED,
			'{{%source_message}}.deleted'=>SourceMessage::STATUS_NOT_DELETED,
			'{{%source_message}}.id'=>$this->id,
		];
			
		return SourceMessage::find()
			->select($select)
			->leftJoin('{{%message}}', $join)
			->where($where)
			->with('message')
			->asArray()
			->one();
    }
	
	/**
     * save
     */
    public function save()
    {
		if(!$this->validate(['category'])) {
			$this->addError('category', Yii::t('EditorsTranslations', 'Incorrect Category'));
			return false;
		}
		
		if(!$this->validate(['message'])) {
			$this->addError('message', Yii::t('EditorsTranslations', 'Incorrect Message'));
			return false;
		}
		
		if (empty($this->id)) {
			
			$modelSourceMessage = new SourceMessage;
			
		} else {
			
			$modelSourceMessage = $this->findMessageSource();
			if (empty($modelSourceMessage)) {
				$this->addError('Error', Yii::t('EditorsTranslations', 'Missing Message Source'));
				return false;
			}
		}
		
		$modelSourceMessage->setAttributes([
			'category' => $this->category,
			'message' => $this->message,
		], false);

		if(!$modelSourceMessage->save()) {
			return false;
		}
		
		if (!empty($this->translation_arr) && is_array($this->translation_arr)) {
			foreach ($this->translation_arr as $lang => $translation) {
				
				$this->language = $lang;
				$this->translation = $translation;
				
				if(!$this->validate(['language'])) {
					$this->addError('language', Yii::t('EditorsTranslations', 'Incorrect Language'));
					return false;
				}
				
				if(!$this->validate(['translation'])) {
					$this->addError('translation', Yii::t('EditorsTranslations', 'Incorrect Translation'));
					return false;
				}
				
				if (empty($this->id)) {
			
					$modelMessage = new Message;
					$modelMessage->id = $modelSourceMessage->id;
					
				} else {
				
					$modelMessage = $this->findMessage();
					if (empty($modelMessage)) {
						$this->addError('Error', Yii::t('EditorsTranslations', 'Missing Message'));
						return false;
					}
				}
				
				$modelMessage->setAttributes([
					'language' => $this->language,
					'translation' => $this->translation,
				], false);
				
				
				if(!$modelMessage->save()) {
					return false;
				}
			}
			
			$this->id = $modelSourceMessage->id;
			
			return true;
		}
	
        return false;
    }
	
	/**
     * deleted
     */
    public function deleted()
    {
		if (empty($this->id)) {
			Yii::$app->session->setFlash('error', Yii::t('EditorsTranslations', 'Missing ID Translation'));
			return false;
		} 
		
		$modelSourceMessage = $this->findMessageSource();
		if (empty($modelSourceMessage)) {
			Yii::$app->session->setFlash('error', Yii::t('EditorsTranslations', 'Missing Message Source'));
			return false;
		}

		$modelSourceMessage->setAttributes([
			'deleted' => $this->deleted,
			'date_deleted' => $this->date_deleted,
		], false);

		if(!$modelSourceMessage->save()) {
			return false;
		}
		
		foreach (Yii::$app->params['supported_lang'] as $lang) {
				
			$lang = strtolower($lang).'-'.strtoupper($lang);
			
			$this->language = $lang;
			$modelMessage = $this->findMessage();
			if (empty($modelMessage)) {
				Yii::$app->session->setFlash('error', Yii::t('EditorsTranslations', 'Missing Message'));
				return false;
			}

			$modelMessage->setAttributes([
				'deleted' => $this->deleted,
				'date_deleted' => $this->date_deleted,
			], false);

			if(!$modelMessage->save()) {
				return false;
			}
		}
	
        return true;
    }
	
	/**
     * findMessageSource()
     */
    public function findMessageSource()
    {
        if (empty($this->id)) {
			return false;
		}
		
		return SourceMessage::findOne([
			'id' => $this->id, 
			'deleted' => SourceMessage::STATUS_NOT_DELETED,
		]);
    }
	
	/**
     * findMessage()
     */
    public function findMessage()
    {
        if (empty($this->id) || empty($this->language)) {
			return false;
		}
		
		return Message::findOne([
			'id' => $this->id, 
			'language' => $this->language,
			'deleted' => Message::STATUS_NOT_DELETED,
		]);
    }
}
