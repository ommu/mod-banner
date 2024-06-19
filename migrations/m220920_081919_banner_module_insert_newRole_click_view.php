<?php
/**
 * m220920_081919_banner_module_insert_newRole_click_view
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 18 March 2022, 08:20 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m220920_081919_banner_module_insert_newRole_click_view extends \yii\db\Migration
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }

        return $authManager;
    }

	public function up()
	{
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;
        $schema = $this->db->getSchema()->defaultSchema;

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'type', 'data', 'created_at'], [
				['/banner/click/admin/*', '2', '', time()],
				['/banner/click/history/*', '2', '', time()],
				['/banner/view/admin/*', '2', '', time()],
				['/banner/view/history/*', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['parent', 'child'], [
				['bannerModLevelModerator', '/banner/click/admin/*'],
				['bannerModLevelModerator', '/banner/click/history/*'],
				['bannerModLevelModerator', '/banner/view/admin/*'],
				['bannerModLevelModerator', '/banner/view/history/*'],
			]);
		}
	}
}
