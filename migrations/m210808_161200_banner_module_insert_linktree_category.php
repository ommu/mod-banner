<?php
/**
 * m210808_161200_banner_module_insert_linktree_category
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 8 August 2021, 16:12 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;
use app\models\SourceMessage;

class m210808_161200_banner_module_insert_linktree_category extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_banner_category';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['publish', 'type', 'name', 'desc', 'code', 'creation_id'], [
				['1', 'linktree', SourceMessage::setMessage('Linktree', 'banner category title'), SourceMessage::setMessage('Linktree', 'banner category description'), 'linktree', Yii::$app->user->id],
			]);
		}
	}
}
