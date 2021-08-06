<?php
/**
 * m210806_195351_banner_module_create_table_views
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 6 August 2021, 19:54 WIB
 * @link https://www.ommu.id
 *
 */

use Yii;
use yii\db\Schema;

class m210806_195351_banner_module_create_table_views extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_banner_views';
		if(!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'view_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'banner_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'views' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT \'1\'',
				'view_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'view_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'PRIMARY KEY ([[view_id]])',
				'FOREIGN KEY ([[banner_id]]) REFERENCES ommu_banners ([[banner_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

			$this->createIndex(
				'user_id',
				$tableName,
				'user_id'
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_banner_views';
		$this->dropTable($tableName);
	}
}
