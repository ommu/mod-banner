<?php
/**
 * m210806_142308_banner_module_create_table_category
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 6 August 2021, 14:23 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;
use yii\db\Schema;

class m210806_142308_banner_module_create_table_category extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_banner_category';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'cat_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\' COMMENT \'Enable,Disable\'',
				'name' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL COMMENT \'trigger[delete]\'',
				'desc' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL COMMENT \'trigger[delete],text\'',
				'cat_code' => Schema::TYPE_STRING . '(32) NOT NULL',
				'banner_size' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'serialize\'',
				'banner_limit' => Schema::TYPE_TINYINT . '(2) NOT NULL',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'slug' => Schema::TYPE_STRING . '(32) NOT NULL',
				'PRIMARY KEY ([[cat_id]])',
			], $tableOptions);

			$this->createIndex(
				'publishWithName',
				$tableName,
				['publish', 'name']
			);

			$this->createIndex(
				'name',
				$tableName,
				'name'
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_banner_category';
		$this->dropTable($tableName);
	}
}