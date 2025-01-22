<?php

namespace frontend\components;

class TranslateHelper
{
    public $translateArray = [];

	public function setTranslate($category, $source, $message_en, $message_ru) {
		if (empty($category) || empty($source)) {
			return false;
		}

		$this->translateArray[$category][$source] = [
			'en' => $message_en,
			'ru' => $message_ru,
		];

		return true;
	}

	public function translate() {
		
		if (empty($this->translateArray)) {
			return false;
		}

		$query = new \yii\db\Query();
		$command = \Yii::$app->db->createCommand();

		foreach($this->translateArray as $category => $source) {
				
			$date_create = date("Y-m-d H:i:s");
			$source_keys = array_keys($source);

			foreach($source_keys as $source_key) {

				$source_row = null;
				$source_row = $query->select(['id'])
					->from('{{%source_message}}')
					->where('category = :category AND message = :message', ['category' => $category, 'message' => $source_key])
					->one();

				$translate_en = trim($this->translateArray[$category][$source_key]['en']);
				$translate_ru = trim($this->translateArray[$category][$source_key]['ru']);

				if (!empty($source_row['id'])) {
					
					if (!empty($translate_en)) {
						$command->update('{{%message}}', ['translation' => $translate_en], 'language = :language AND id = :id', [':language' => 'en-EN', ':id' => $source_row['id'] ])->execute();
					}
					
					if (!empty($translate_ru)) {
						$command->update('{{%message}}', ['translation' => $translate_ru], 'language = :language AND id = :id', [':language' => 'ru-RU', ':id' => $source_row['id'] ])->execute();
					}
				
				} else {

					$command->insert('{{%source_message}}', [
							'category' => $category,
							'message' => $source_key,
							'date_create' => $date_create
						])->execute();

					$source_row = $query->select(['id'])
						->from('{{%source_message}}')
						->where('category = :category AND message = :message', ['category' => $category, 'message' => $source_key])
						->one();

					if (!empty($source_row['id'])) {

						if (!empty($translate_en)) {

							$command->insert('{{%message}}', [
								'id' => $source_row['id'],
								'language' => 'en-EN',
								'translation' => $translate_en,
								'date_create' => $date_create
							])->execute();

						}
							
						if (!empty($translate_ru)) {

							$command->insert('{{%message}}', [
								'id' => $source_row['id'],
								'language' => 'ru-RU',
								'translation' => $translate_ru,
								'date_create' => $date_create
							])->execute();

						}

					}
				}
			}
		}
		return true;
	}
}