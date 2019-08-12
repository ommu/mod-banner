<?php
/**
 * BannerSetting
 *
 * This is the ActiveQuery class for [[\ommu\banner\models\BannerSetting]].
 * @see \ommu\banner\models\BannerSetting
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 19 January 2019, 06:54 WIB
 * @link https://github.com/ommu/mod-banner
 *
 */

namespace ommu\banner\models\query;

class BannerSetting extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\BannerSetting[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\banner\models\BannerSetting|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
