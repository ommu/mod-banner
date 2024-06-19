<?php
/**
 * m220919_141229_banner_module_alterTrigger_all_updateBannerGrid
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 19 September 2022, 14:13 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;
use yii\db\Schema;

class m220919_141229_banner_module_alterTrigger_all_updateBannerGrid extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterInsertClicks`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterUpdateClicks`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterInsertViews`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterUpdateViews`');

		// alter trigger bannerAfterInsertClicks
		$alterTriggerBannerAfterInsertClicks = <<< SQL
CREATE
    TRIGGER `bannerAfterInsertClicks` AFTER INSERT ON `ommu_banner_clicks` 
    FOR EACH ROW BEGIN
	INSERT `ommu_banner_click_history` (`click_id`, `click_date`,  `click_ip`)
	VALUE (NEW.click_id, NEW.click_date, NEW.click_ip);

	UPDATE `ommu_banner_grid` SET `click` = `click` + 1 WHERE `id` = NEW.banner_id;
    END;
SQL;
		$this->execute($alterTriggerBannerAfterInsertClicks);

		// alter trigger bannerAfterUpdateClicks
		$alterTriggerBannerAfterUpdateClicks = <<< SQL
CREATE
    TRIGGER `bannerAfterUpdateClicks` AFTER UPDATE ON `ommu_banner_clicks` 
    FOR EACH ROW BEGIN
	IF (NEW.click_date <> OLD.click_date) THEN
		INSERT `ommu_banner_click_history` (`click_id`, `click_date`,  `click_ip`)
		VALUE (NEW.click_id, NEW.click_date, NEW.click_ip);

		UPDATE `ommu_banner_grid` SET `click` = `click` + 1 WHERE `id` = NEW.banner_id;
	END IF;
    END;
SQL;
		$this->execute($alterTriggerBannerAfterUpdateClicks);

		// alter trigger bannerAfterInsertViews
		$alterTriggerBannerAfterInsertViews = <<< SQL
CREATE
    TRIGGER `bannerAfterInsertViews` AFTER INSERT ON `ommu_banner_views` 
    FOR EACH ROW BEGIN
	INSERT `ommu_banner_view_history` (`view_id`, `view_date`, `view_ip`)
	VALUE (NEW.view_id, NEW.view_date, NEW.view_ip);

	UPDATE `ommu_banner_grid` SET `view` = `view` + 1 WHERE `id` = NEW.banner_id;
    END;
SQL;
		$this->execute($alterTriggerBannerAfterInsertViews);

		// alter trigger bannerAfterUpdateViews
		$alterTriggerBannerAfterUpdateViews = <<< SQL
CREATE
    TRIGGER `bannerAfterUpdateViews` AFTER UPDATE ON `ommu_banner_views` 
    FOR EACH ROW BEGIN
	IF (NEW.view_date <> OLD.view_date) THEN
		INSERT `ommu_banner_view_history` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.view_id, NEW.view_date, NEW.view_ip);

		UPDATE `ommu_banner_grid` SET `view` = `view` + 1 WHERE `id` = NEW.banner_id;
	END IF;
    END;
SQL;
		$this->execute($alterTriggerBannerAfterUpdateViews);
	}

	public function down()
	{
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterInsertClicks`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterUpdateClicks`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterInsertViews`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterUpdateViews`');

		// alter trigger bannerAfterInsertClicks
		$alterTriggerBannerAfterInsertClicks = <<< SQL
CREATE
    TRIGGER `bannerAfterInsertClicks` AFTER INSERT ON `ommu_banner_clicks` 
    FOR EACH ROW BEGIN
	INSERT `ommu_banner_click_history` (`click_id`, `click_date`,  `click_ip`)
	VALUE (NEW.click_id, NEW.click_date, NEW.click_ip);
    END;
SQL;
		$this->execute($alterTriggerBannerAfterInsertClicks);

		// alter trigger bannerAfterUpdateClicks
		$alterTriggerBannerAfterUpdateClicks = <<< SQL
CREATE
    TRIGGER `bannerAfterUpdateClicks` AFTER UPDATE ON `ommu_banner_clicks` 
    FOR EACH ROW BEGIN
	IF (NEW.click_date <> OLD.click_date) THEN
		INSERT `ommu_banner_click_history` (`click_id`, `click_date`,  `click_ip`)
		VALUE (NEW.click_id, NEW.click_date, NEW.click_ip);
	END IF;
    END;
SQL;
		$this->execute($alterTriggerBannerAfterUpdateClicks);

		// alter trigger bannerAfterInsertViews
		$alterTriggerBannerAfterInsertViews = <<< SQL
CREATE
    TRIGGER `bannerAfterInsertViews` AFTER INSERT ON `ommu_banner_views` 
    FOR EACH ROW BEGIN
	INSERT `ommu_banner_view_history` (`view_id`, `view_date`, `view_ip`)
	VALUE (NEW.view_id, NEW.view_date, NEW.view_ip);
    END;
SQL;
		$this->execute($alterTriggerBannerAfterInsertViews);

		// alter trigger bannerAfterUpdateViews
		$alterTriggerBannerAfterUpdateViews = <<< SQL
CREATE
    TRIGGER `bannerAfterUpdateViews` AFTER UPDATE ON `ommu_banner_views` 
    FOR EACH ROW BEGIN
	IF (NEW.view_date <> OLD.view_date) THEN
		INSERT `ommu_banner_view_history` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.view_id, NEW.view_date, NEW.view_ip);
	END IF;
    END;
SQL;
		$this->execute($alterTriggerBannerAfterUpdateViews);
	}
}
