<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace api\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\Expression;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\caching\Cache;
use yii\db\Connection;
use yii\db\Query;
use yii\i18n\DbMessageSource;

/**
 * DbMessageSource extends [[MessageSource]] and represents a message source that stores translated
 * messages in database.
 *
 * The database must contain the following two tables: source_message and message.
 *
 * The `source_message` table stores the messages to be translated, and the `message` table stores
 * the translated messages. The name of these two tables can be customized by setting [[sourceMessageTable]]
 * and [[messageTable]], respectively.
 *
 * The database connection is specified by [[db]]. Database schema could be initialized by applying migration:
 *
 * ```
 * yii migrate --migrationPath=@yii/i18n/migrations/
 * ```
 *
 * If you don't want to use migration and need SQL instead, files for all databases are in migrations directory.
 *
 * @author resurtm <resurtm@gmail.com>
 * @since 2.0
 */
class newDbMessageSource extends DbMessageSource
{
    /**
     * Loads the messages from database.
     * You may override this method to customize the message storage in the database.
     * @param string $category the message category.
     * @param string $language the target language.
     * @return array the messages loaded from database.
     */
    protected function loadMessagesFromDb($category, $language)
    {
        $mainQuery = (new Query())->select(['message' => 't1.message', 'translation' => 't2.translation'])
            ->from(['t1' => $this->sourceMessageTable, 't2' => $this->messageTable])
            ->where([
                't1.id' => new Expression('[[t2.id]]'),
                't1.category' => $category,
                't2.language' => $language,
				't1.deleted' => 0,
				't2.deleted' => 0,
            ]);

        $fallbackLanguage = substr($language, 0, 2);
        $fallbackSourceLanguage = substr($this->sourceLanguage, 0, 2);

        if ($fallbackLanguage !== $language) {
            $mainQuery->union($this->createFallbackQuery($category, $language, $fallbackLanguage), true);
        } elseif ($language === $fallbackSourceLanguage) {
            $mainQuery->union($this->createFallbackQuery($category, $language, $fallbackSourceLanguage), true);
        }

        $messages = $mainQuery->createCommand($this->db)->queryAll();

        return ArrayHelper::map($messages, 'message', 'translation');
    }

    /**
     * The method builds the [[Query]] object for the fallback language messages search.
     * Normally is called from [[loadMessagesFromDb]].
     *
     * @param string $category the message category
     * @param string $language the originally requested language
     * @param string $fallbackLanguage the target fallback language
     * @return Query
     * @see loadMessagesFromDb
     * @since 2.0.7
     */
    protected function createFallbackQuery($category, $language, $fallbackLanguage)
    {
        return (new Query())->select(['message' => 't1.message', 'translation' => 't2.translation'])
            ->from(['t1' => $this->sourceMessageTable, 't2' => $this->messageTable])
            ->where([
                't1.id' => new Expression('[[t2.id]]'),
                't1.category' => $category,
                't2.language' => $fallbackLanguage,
				't1.deleted' => 0,
				't2.deleted' => 0,
            ])->andWhere([
                'NOT IN', 't2.id', (new Query())->select('[[id]]')->from($this->messageTable)->where(['language' => $language])
            ]);
    }
}
