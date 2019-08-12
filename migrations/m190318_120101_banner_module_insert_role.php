<?php
/**
 * m190318_120101_banner_module_insert_role
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;

class m190318_120101_banner_module_insert_role extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_auth_item';
		if(Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_auth_item', ['name', 'type', 'data', 'created_at'], [
				['bannerModLevelAdmin', '2', '', time()],
				['bannerModLevelModerator', '2', '', time()],
				['/banner/admin/*', '2', '', time()],
				['/banner/admin/index', '2', '', time()],
				['/banner/history/click/*', '2', '', time()],
				['/banner/history/click-detail/*', '2', '', time()],
				['/banner/history/view/*', '2', '', time()],
				['/banner/history/view-detail/*', '2', '', time()],
				['/banner/setting/admin/index', '2', '', time()],
				['/banner/setting/admin/update', '2', '', time()],
				['/banner/setting/admin/delete', '2', '', time()],
				['/banner/setting/category/*', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_auth_item_child';
		if(Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_auth_item_child', ['parent', 'child'], [
				['userAdmin', 'bannerModLevelAdmin'],
				['userModerator', 'bannerModLevelModerator'],
				['bannerModLevelAdmin', 'bannerModLevelModerator'],
				['bannerModLevelAdmin', '/banner/setting/admin/update'],
				['bannerModLevelAdmin', '/banner/setting/admin/delete'],
				['bannerModLevelAdmin', '/banner/setting/category/*'],
				['bannerModLevelModerator', '/banner/setting/admin/index'],
				['bannerModLevelModerator', '/banner/admin/*'],
				['bannerModLevelModerator', '/banner/history/click/*'],
				['bannerModLevelModerator', '/banner/history/click-detail/*'],
				['bannerModLevelModerator', '/banner/history/view/*'],
				['bannerModLevelModerator', '/banner/history/view-detail/*'],
			]);
		}
	}
}
