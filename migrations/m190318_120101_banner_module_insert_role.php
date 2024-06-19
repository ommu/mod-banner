<?php
/**
 * m190318_120101_banner_module_insert_role
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m190318_120101_banner_module_insert_role extends \yii\db\Migration
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
				['bannerModLevelAdmin', '2', '', time()],
				['bannerModLevelModerator', '2', '', time()],
				['/banner/admin/*', '2', '', time()],
				['/banner/admin/index', '2', '', time()],
				['/banner/o/click/*', '2', '', time()],
				['/banner/history/click/*', '2', '', time()],
				['/banner/o/view/*', '2', '', time()],
				['/banner/history/view/*', '2', '', time()],
				['/banner/setting/admin/index', '2', '', time()],
				['/banner/setting/admin/update', '2', '', time()],
				['/banner/setting/admin/delete', '2', '', time()],
				['/banner/setting/category/*', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['parent', 'child'], [
				['userAdmin', 'bannerModLevelAdmin'],
				['userModerator', 'bannerModLevelModerator'],
				['bannerModLevelAdmin', 'bannerModLevelModerator'],
				['bannerModLevelAdmin', '/banner/setting/admin/update'],
				['bannerModLevelAdmin', '/banner/setting/admin/delete'],
				['bannerModLevelAdmin', '/banner/setting/category/*'],
				['bannerModLevelModerator', '/banner/setting/admin/index'],
				['bannerModLevelModerator', '/banner/admin/*'],
				['bannerModLevelModerator', '/banner/o/click/*'],
				['bannerModLevelModerator', '/banner/history/click/*'],
				['bannerModLevelModerator', '/banner/o/view/*'],
				['bannerModLevelModerator', '/banner/history/view/*'],
			]);
		}
	}
}
