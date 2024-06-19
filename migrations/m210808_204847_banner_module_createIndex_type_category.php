<?php
/**
 * m210808_204847_banner_module_createIndex_type_category
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 8 August 2021, 20:48 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;
use yii\db\Schema;

class m210808_204847_banner_module_createIndex_type_category extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_banner_category';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createIndex(
				'publishWithType',
				$tableName,
				['publish', 'type'],
			);

			$this->createIndex(
				'type',
				$tableName,
				'type',
			);
		}
	}
}
