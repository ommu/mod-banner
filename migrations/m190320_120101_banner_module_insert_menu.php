<?php
/**
 * m190320_120101_banner_module_insert_menu
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;
use app\models\Menu;
use mdm\admin\components\Configs;

class m190320_120101_banner_module_insert_menu extends \yii\db\Migration
{
	public function up()
	{
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Banners', 'banner', null, Menu::getParentId('Publications#rbac'), '/banner/admin/index', null, null],
				['Banner Settings', 'banner', null, Menu::getParentId('Settings#rbac'), '/banner/setting/admin/index', null, null],
			]);
		}
	}
}
