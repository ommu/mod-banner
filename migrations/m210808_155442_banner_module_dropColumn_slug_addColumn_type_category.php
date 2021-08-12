<?php
/**
 * m210808_155442_banner_module_dropColumn_slug_addColumn_type_category
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 8 August 2021, 15:54 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;

class m210808_155442_banner_module_dropColumn_slug_addColumn_type_category extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_banner_category';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->dropColumn(
				$tableName,
				'slug'
			);

			$this->addColumn(
				$tableName,
				'type',
				'enum(\'banner\',\'linktree\',\'rotator\') NOT NULL DEFAULT \'banner\' AFTER publish',
			);
		}
	}
}
