<?php
/**
 * m210806_194216_banner_module_create_table_banners
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 6 August 2021, 19:42 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;
use yii\db\Schema;

class m210806_194216_banner_module_create_table_banners extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_banners';
		if(!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'banner_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'cat_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED NOT NULL',
				'title' => Schema::TYPE_STRING . '(64) NOT NULL',
				'url' => Schema::TYPE_TEXT . ' NOT NULL',
				'banner_filename' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'file\'',
				'banner_desc' => Schema::TYPE_TEXT . ' NOT NULL',
				'published_date' => Schema::TYPE_DATE . ' NOT NULL',
				'expired_date' => Schema::TYPE_DATE . ' NOT NULL',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'slug' => Schema::TYPE_STRING . '(64) NOT NULL',
				'PRIMARY KEY ([[banner_id]])',
				'FOREIGN KEY ([[cat_id]]) REFERENCES ommu_banner_category ([[cat_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}

        $this->createIndex(
            'publishWithCategory',
            $tableName,
            ['publish', 'cat_id']
        );
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_banners';
		$this->dropTable($tableName);
	}
}
