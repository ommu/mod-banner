<?php
/**
 * m210807_222633_banner_module_createIndex_isBanner_banners
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 22:26 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;
use yii\db\Schema;

class m210807_222633_banner_module_createIndex_isBanner_banners extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_banners';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createIndex(
				'controllerFindModel',
				$tableName,
				['banner_id', 'is_banner'],
			);
		}
	}
}
