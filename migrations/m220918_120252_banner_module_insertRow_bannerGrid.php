<?php
/**
 * m220918_120252_banner_module_insertRow_bannerGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 18 September 2022, 12:03 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;
use yii\db\Schema;

class m220918_120252_banner_module_insertRow_bannerGrid extends \yii\db\Migration
{
	public function up()
	{
		$inserRowBannerGrid = <<< SQL
INSERT INTO `ommu_banner_grid` (`id`, `permanent`, `click`, `view`) 

SELECT 
	a.banner_id AS id,
	case when a.permanent is null then 0 else a.permanent end AS `permanent`,
	case when a.clicks is null then 0 else a.clicks end AS `clicks`,
	case when a.views is null then 0 else a.views end AS `views`
FROM _banners AS a
LEFT JOIN ommu_banner_grid AS b
	ON b.id = a.banner_id
WHERE
	b.id IS NULL;
SQL;
		$this->execute($inserRowBannerGrid);
	}
}
