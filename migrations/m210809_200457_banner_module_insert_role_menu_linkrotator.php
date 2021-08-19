<?php
/**
 * m210809_200457_banner_module_insert_role_menu_linkrotator
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 9 August 2021, 20:04 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;
use app\models\Menu;
use mdm\admin\components\Configs;

class m210809_200457_banner_module_insert_role_menu_linkrotator extends \yii\db\Migration
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
				['/banner/rotator/admin/*', '2', '', time()],
				['/banner/rotator/admin/index', '2', '', time()],
				['/banner/rotator/item/*', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['parent', 'child'], [
				['bannerModLevelModerator', '/banner/rotator/admin/*'],
				['bannerModLevelModerator', '/banner/rotator/item/*'],
			]);
		}

        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['URL/WA Rotator', 'banner', null, Menu::getParentId('Publications#rbac'), '/banner/rotator/admin/index', null, null],
			]);
		}
	}
}