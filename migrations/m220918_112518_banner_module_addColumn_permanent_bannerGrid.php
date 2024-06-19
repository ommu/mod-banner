<?php
/**
 * m220918_112518_banner_module_addColumn_permanent_bannerGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 18 September 2022, 11:26 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;
use yii\db\Schema;

class m220918_112518_banner_module_addColumn_permanent_bannerGrid extends \yii\db\Migration
{
	public $tableName = 'ommu_banner_grid';

	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . $this->tableName;
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'permanent',
				$this->boolean()->notNull()->defaultValue(0)->after('id'),
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . $this->tableName;
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->dropColumn($tableName, 'permanent');
		}
	}
}
