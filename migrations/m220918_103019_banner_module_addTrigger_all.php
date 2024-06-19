<?php
/**
 * m220918_103019_banner_module_addTrigger_all
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 18 September 2022, 10:31 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

use Yii;
use yii\db\Schema;

class m220918_103019_banner_module_addTrigger_all extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterDeleteCategory`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerBeforeUpdateCategory`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerBeforeUpdate`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterInsertClicks`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerBeforeUpdateClicks`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterUpdateClicks`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterInsertViews`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerBeforeUpdateViews`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterUpdateViews`');

		// alter trigger bannerAfterDeleteCategory
		$alterTriggerBannerAfterDeleteCategory = <<< SQL
CREATE
    TRIGGER `bannerAfterDeleteCategory` AFTER DELETE ON `ommu_banner_category` 
    FOR EACH ROW BEGIN
	/*
	DELETE FROM `source_message` WHERE `id`=OLD.name;
	DELETE FROM `source_message` WHERE `id`=OLD.desc;
	*/
	UPDATE `source_message` SET `message`=CONCAT(message,'_DELETED') WHERE `id`=OLD.name;
	UPDATE `source_message` SET `message`=CONCAT(message,'_DELETED') WHERE `id`=OLD.desc;
    END;
SQL;
		$this->execute($alterTriggerBannerAfterDeleteCategory);

		// alter trigger bannerBeforeUpdateCategory
		$alterTriggerBannerBeforeUpdateCategory = <<< SQL
CREATE
    TRIGGER `bannerBeforeUpdateCategory` BEFORE UPDATE ON `ommu_banner_category` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
		$this->execute($alterTriggerBannerBeforeUpdateCategory);

		// alter trigger bannerBeforeUpdate
		$alterTriggerBannerBeforeUpdate = <<< SQL
CREATE
    TRIGGER `bannerBeforeUpdate` BEFORE UPDATE ON `ommu_banners` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
		$this->execute($alterTriggerBannerBeforeUpdate);

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

		// alter trigger bannerBeforeUpdateClicks
		$alterTriggerBannerBeforeUpdateClicks = <<< SQL
CREATE
    TRIGGER `bannerBeforeUpdateClicks` BEFORE UPDATE ON `ommu_banner_clicks` 
    FOR EACH ROW BEGIN
	IF (NEW.clicks <> OLD.clicks AND NEW.clicks > OLD.clicks) THEN
		SET NEW.click_date = NOW();
	END IF;
    END;
SQL;
		$this->execute($alterTriggerBannerBeforeUpdateClicks);

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

		// alter trigger bannerBeforeUpdateViews
		$alterTriggerBannerBeforeUpdateViews = <<< SQL
CREATE
    TRIGGER `bannerBeforeUpdateViews` BEFORE UPDATE ON `ommu_banner_views` 
    FOR EACH ROW BEGIN
	IF (NEW.views <> OLD.views AND NEW.views > OLD.views) THEN
		SET NEW.view_date = NOW();
	END IF;
    END;
SQL;
		$this->execute($alterTriggerBannerBeforeUpdateViews);

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

	public function down()
	{
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterDeleteCategory`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerBeforeUpdateCategory`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerBeforeUpdate`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterInsertClicks`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerBeforeUpdateClicks`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterUpdateClicks`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterInsertViews`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerBeforeUpdateViews`');
		$this->execute('DROP TRIGGER IF EXISTS `bannerAfterUpdateViews`');
	}
}
