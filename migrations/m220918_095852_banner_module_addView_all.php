<?php
/**
 * m220918_095852_banner_module_addView_all
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 18 September 2022, 10:00 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;
use yii\db\Schema;

class m220918_095852_banner_module_addView_all extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_banner_category`');
		$this->execute('DROP VIEW IF EXISTS `_banner_statistic_clicks`');
		$this->execute('DROP VIEW IF EXISTS `_banner_statistic_views`');
		$this->execute('DROP VIEW IF EXISTS `_banners`');

		// alter view _banner_category
		$alterViewBannerCategory = <<< SQL
CREATE VIEW `_banner_category` AS
select
  `a`.`cat_id` AS `cat_id`,
  sum(case when `b`.`publish` = '1' and `b`.`published_date` <= curdate() and (`b`.`expired_date` not in ('0000-00-00','1970-01-01','0002-12-02') or `b`.`expired_date` >= curdate()) then 1 else 0 end) AS `publish`,
  sum(case when `b`.`publish` = '1' and `b`.`expired_date` in ('0000-00-00','1970-01-01','0002-12-02') and `b`.`published_date` <= curdate() then 1 else 0 end) AS `permanent`,
  sum(case when `b`.`publish` = '1' and `b`.`published_date` > curdate() then 1 else 0 end) AS `pending`,
  sum(case when `b`.`publish` = '1' and `b`.`expired_date` < curdate() then 1 else 0 end) AS `expired`,
  sum(case when `b`.`publish` = '0' then 1 else 0 end) AS `unpublish`,
  count(`b`.`cat_id`) AS `all`
from (`ommu_banner_category` `a`
   left join `ommu_banners` `b`
     on (`a`.`cat_id` = `b`.`cat_id`))
group by `a`.`cat_id`;
SQL;
		$this->execute($alterViewBannerCategory);

		// alter view _banner_statistic_clicks
		$alterViewBannerStatisticClicks = <<< SQL
CREATE VIEW `_banner_statistic_clicks` AS
select
  `a`.`banner_id` AS `banner_id`,
  sum(`a`.`clicks`) AS `clicks`
from `ommu_banner_clicks` `a`
group by `a`.`banner_id`;
SQL;
		$this->execute($alterViewBannerStatisticClicks);

		// alter view _banner_statistic_views
		$alterViewBannerStatisticViews = <<< SQL
CREATE VIEW `_banner_statistic_views` AS
select
  `a`.`banner_id` AS `banner_id`,
  sum(`a`.`views`) AS `views`
from `ommu_banner_views` `a`
group by `a`.`banner_id`;
SQL;
		$this->execute($alterViewBannerStatisticViews);

		// alter view _banners
		$alterViewBanners = <<< SQL
CREATE VIEW `_banners` AS
select
  `a`.`banner_id` AS `banner_id`,
  case when `a`.`publish` = '1' and `a`.`expired_date` in ('0000-00-00','1970-01-01','0002-12-02') and `a`.`published_date` <= CURDATE() then 1 else 0 end AS `permanent`,
  `b`.`views` AS `views`,
  `c`.`clicks` AS `clicks`
from ((`ommu_banners` `a`
left join `_banner_statistic_views` `b`
    on (`a`.`banner_id` = `b`.`banner_id`))
left join `_banner_statistic_clicks` `c`
    on (`a`.`banner_id` = `c`.`banner_id`))
group by `a`.`banner_id`;
SQL;
		$this->execute($alterViewBanners);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_banner_category`');
		$this->execute('DROP VIEW IF EXISTS `_banner_statistic_clicks`');
		$this->execute('DROP VIEW IF EXISTS `_banner_statistic_views`');
		$this->execute('DROP VIEW IF EXISTS `_banners`');
	}
}
