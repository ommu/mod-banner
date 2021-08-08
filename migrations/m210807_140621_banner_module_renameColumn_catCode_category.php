<?php
/**
 * m210807_140621_banner_module_renameColumn_catCode_category
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 7 August 2021, 14:09 WIB
 * @link https://www.ommu.id
 *
 */

use Yii;
use yii\db\Schema;

class m210807_140621_banner_module_renameColumn_catCode_category extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_banner_category';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->renameColumn(
                $tableName,
                'cat_code',
                'code',
            );
		}
	}
}
