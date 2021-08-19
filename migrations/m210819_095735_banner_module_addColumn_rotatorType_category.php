<?php
/**
 * m210819_095735_banner_module_addColumn_rotatorType_category
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 19 August 2021, 16:05 WIB
 * @link https://www.ommu.id
 *
 */

use Yii;

class m210819_095735_banner_module_addColumn_rotatorType_category extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_banner_category';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'rotator_type',
				'enum(\'url\',\'wa\') NOT NULL DEFAULT \'url\' AFTER type',
			);
		}
	}
}
