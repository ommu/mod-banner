<?php
/**
 * m220916_214105_banner_module_dropColumn_slug_banners
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 16 September 2022, 21:42 WIB
 * @link https://www.ommu.id
 *
 */

use Yii;
use yii\db\Schema;

class m220916_214105_banner_module_dropColumn_slug_banners extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_banners';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->dropColumn(
				$tableName,
				'slug'
			);
		}
	}
}
