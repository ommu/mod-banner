<?php
/**
 * m220919_140538_banner_module_alterTrigger_all_insertBannerGrid
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 19 September 2022, 14:06 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;
use yii\db\Schema;

class m220919_140538_banner_module_alterTrigger_all_insertBannerGrid extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterInsert`');

		// alter trigger bannerAfterInsert
		$bannerAfterInsert = <<< SQL
CREATE
    TRIGGER `bannerAfterInsert` AFTER INSERT ON `ommu_banners` 
    FOR EACH ROW BEGIN
	INSERT `ommu_banner_grid` (`id`, `permanent`, `click`, `view`) 
	VALUE (NEW.banner_id, 0, 0, 0);
    END;
SQL;
		$this->execute($bannerAfterInsert);
	}

	public function down()
	{
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterInsert`');
	}
}
