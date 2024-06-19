<?php
/**
 * m210807_195128_banner_module_addColumn_isBanner_banners
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 19:51 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;
use yii\db\Schema;

class m210807_195128_banner_module_addColumn_isBanner_banners extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_banners';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'is_banner',
				$this->boolean()->notNull()->defaultValue(1)->after('cat_id'),
			);
		}
	}
}
